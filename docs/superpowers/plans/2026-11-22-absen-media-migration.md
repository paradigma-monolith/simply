# Absen Media Migration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Migrate all base64 photos and signatures out of `bkkr3927_simply.absen` (`11.19 GB`) into private `writable/absen_media/` storage with SHA-256 deduplication against existing `dist/foto_absen/` files, archive & drop unused `absen_backup` (`217.6 MB`), and reclaim InnoDB space so total server databases drop from `12.28 GB` to `< 1 GB` (`~0.92 GB`).

**Architecture:** Introduce `App\Libraries\AbsenMediaStorage` as the single storage/resolution boundary supporting `media:...` private paths, legacy base64 data URIs, and transitional `dist/foto_absen/` lookups. Serve all attendance photos and `rekam_absen` frames strictly behind `AuthFilter` using extensionless routes (`/absen/foto/{id}/{jenis}` and `/absen/rekam-foto/{id}/{index}`), migrate historical rows via a resumable, purge-lag-throttled CLI engine (`App\Libraries\AbsenMediaMigrator` + `php spark absen:migrate-media`), and reclaim InnoDB space online with `ALTER TABLE ... FORCE, ALGORITHM=INPLACE, LOCK=NONE`.

**Tech Stack:** PHP 8.2 (local AMPPS `C:\Program Files\Ampps\php82\php.exe`) / PHP 8.3 (production `ea-php83-php-fpm`), CodeIgniter 4, PHPUnit 9.6.3 (`vendor\bin\phpunit`), MariaDB `10.11.19`, PhpSpreadsheet, Dompdf.

**Spec:** [docs/superpowers/specs/2026-11-22-absen-media-migration-design.md](../specs/2026-11-22-absen-media-migration-design.md)

## Global Constraints

- Production PHP SAPI is `ea-php83-php-fpm`; reload via `systemctl reload ea-php83-php-fpm` after deploy and before moving `dist/foto_absen/`.
- MariaDB is `10.11.19` with `log_bin = OFF`, `innodb_file_per_table = ON`, `innodb_undo_tablespaces = 0`, `ibdata1 = 76M`, 0 triggers, 0 `FULLTEXT` indexes, and `updated_at` without `ON UPDATE CURRENT_TIMESTAMP`.
- Canonical DB reference format for migrated/new media is `media:legacy/<file>`, `media:foto/YYYY/MM/<file>`, or `media:ttd/YYYY/MM/<file>`.
- Private `rekam_absen` route MUST be extensionless (`GET /absen/rekam-foto/(:num)/(:num)`) because `.htaccess` sets `Cache-Control: public, max-age=31536000, immutable` on `\.(jpg|jpeg|png|gif|webp|...)$`.
- On live filesystem write failure during `clockIn`, `clockOut`, or `simpanTandaTangan`, degrade gracefully to storing the original base64 in DB and logging via `log_message('error', ...)` — never block employee attendance.
- Backfill updates MUST preserve historical `updated_at` (use Query Builder `$db->table('absen')->where('id_absen', $id)->update($patch)`, never `AbsenModel::$useTimestamps`), verify `is_file && filesize > 0` before updating DB, and throttle when InnoDB `History list length > 200`.
- Do NOT touch Docker, email services, `rekam_absen` JSON schema, or `absen` column types.

## Review Focus

- **Truncated July 2024 `65,535`-byte base64 payloads** (legacy `TEXT` truncation breaking strict `base64_decode` padding): must decode tolerantly without crashing, write valid bytes (`> 32` bytes), and leave undecodable/corrupt values untouched with a logged warning (pinned in Task 1 & Task 5 tests).
- **Empty canvas `"data:,"` and `""` values** (verified on historical rows like `id_absen=32, 96, 100, 477, 742, 1210`): must be treated as empty/no-image by `AbsenMediaStorage`, `Ajax::getImage`/`getSecondImage`, `Excel`/`Pdf` exports, and skipped untouched by `AbsenMediaMigrator` (pinned in Task 1, Task 3, Task 4, Task 5 tests).
- **Path traversal & symlink escape attempts** (`media:../../.env`, `..\app\Config\Database.php`, null bytes, or malicious `rekam_absen['foto']` entries): must be rejected (`null` / HTTP `404`) by `realpath` containment checks (pinned in Task 1 & Task 2 tests).
- **Split-directory `FCPATH` vs `WRITEPATH` in CLI (`spark`)**: `spark` defines `FCPATH` as `masterkkp_ci4/public/`, not `public_html/simply.bkkbandung.net/`; `AbsenMediaStorage` and `MigrateAbsenMedia` must read from `WRITEPATH . 'absen_media/'` and accept an explicit legacy directory override (pinned in Task 1 & Task 5 tests).
- **Filesystem write failure during live attendance**: when `absen_media` directory is unwritable, `saveBase64()` returns `null` without throwing, allowing `Crud` to log an error and preserve base64 in DB rather than failing attendance (pinned in Task 1 & Task 3 tests).

---

### Task 1: Core Storage Library (`AbsenMediaStorage`) & `.gitignore`

**Files:**
- Create: `app/Libraries/AbsenMediaStorage.php`
- Test: `tests/unit/AbsenMediaStorageTest.php`
- Modify: `.gitignore:135-149`

**Interfaces:**
- Consumes: CI4 `WRITEPATH`, `FCPATH`, `CodeIgniter\HTTP\Files\UploadedFile`
- Produces:
  - `AbsenMediaStorage::__construct(?string $baseDir = null, ?string $legacyPublicDir = null)`
  - `AbsenMediaStorage::isEmptyValue(?string $raw): bool`
  - `AbsenMediaStorage::isMediaRef(?string $raw): bool`
  - `AbsenMediaStorage::decodeBase64Tolerant(string $raw): ?array` (`['binary' => string, 'mime' => string, 'ext' => string, 'truncated' => bool]`)
  - `AbsenMediaStorage::saveBase64(string $dataUri, string $category, ?string $dateStr = null, ?string $prefix = null): ?array` (`['relPath' => string, 'mediaRef' => string, 'fullPath' => string, 'mime' => string, 'size' => int]`)
  - `AbsenMediaStorage::saveBinary(string $binary, string $ext, string $category, ?string $dateStr = null, ?string $filename = null): ?array`
  - `AbsenMediaStorage::saveUploadedFile(UploadedFile $file, string $category = 'foto', ?string $dateStr = null): ?array`
  - `AbsenMediaStorage::resolveToFile(?string $raw): ?array` (`['path' => string, 'mime' => string]`)
  - `AbsenMediaStorage::resolveToBinary(?string $raw): ?array` (`['binary' => string, 'mime' => string]`)
  - `AbsenMediaStorage::resolveToDataUri(?string $raw): ?string`
  - `AbsenMediaStorage::resolveRekamFotoPath(string $fotoRef): ?array` (`['path' => string, 'mime' => string, 'mediaRef' => string]`)
  - `AbsenMediaStorage::canonicalFromRekamFoto(string $fotoRef): ?string`
  - `AbsenMediaStorage::buildRekamHashMap(?string $rekamJson): array` (`array<sha256, mediaRef>`)

- [ ] **Step 1: Write failing unit tests in `tests/unit/AbsenMediaStorageTest.php`**

Cover all public methods and Review Focus cases using a temporary directory per test (cleaned in `tearDown()`):
- `testSaveBase64CreatesAtomicFileInYearMonthSubdirAndResolvesBack`: saves a valid JPEG data URI with `category='foto'`, `dateStr='2026-11-22'`, asserts `mediaRef` matches `^media:foto/2026/11/abs_.+\.jpg$`, `is_file($res['fullPath'])`, `filesize > 0`, `resolveToFile()` returns path + `image/jpeg`, `resolveToBinary()` returns exact original bytes, and `resolveToDataUri()` returns valid `data:image/jpeg;base64,...`.
- `testSaveBase64SignatureUsesTtdFolderAndPngExtension`: saves PNG data URI with `category='ttd'`, asserts `media:ttd/YYYY/MM/...png`.
- `testIsEmptyValueHandlesEmptyNullAndEmptyCanvas`: asserts `isEmptyValue('')`, `isEmptyValue(null)`, `isEmptyValue('data:,')` are `true`, and valid `media:` / `data:image/jpeg;base64,...` are `false`.
- `testDecodeBase64TolerantRecoversTruncatedPayloadAndRejectsGarbage`: builds a valid JPEG base64 string truncated mid-quantum (not a multiple of 4, simulating 65535-byte `TEXT` cut), asserts `decodeBase64Tolerant()` returns `truncated === true` and `strlen($res['binary']) > 32`; asserts `'data:,'`, `'not-valid-base64!!!'`, and tiny `< 32`-byte payloads return `null`.
- `testPathTraversalAndEscapeAttemptsAreRejected`: asserts `resolveToFile('media:../../.env')`, `resolveToFile("media:foto/2026/11/a.jpg\0.png")`, `resolveToFile('media:..\\..\\app\\Config\\Database.php')`, and `resolveRekamFotoPath('../../.env')` all return `null`.
- `testLegacyFallbackResolvesFromLegacyDirBeforeMoveAndFromWritableLegacyAfterMove`: places `old.jpg` in `$legacyPublicDir`, asserts `resolveToFile('media:legacy/old.jpg')` and `resolveRekamFotoPath('old.jpg')` find it; then moves it to `$baseDir/legacy/old.jpg` and asserts both resolve to `$baseDir/legacy/old.jpg` with `mediaRef === 'media:legacy/old.jpg'`.
- `testBuildRekamHashMapMatchesSha256ToCanonicalMediaRef`: writes a JPEG to `$baseDir/legacy/abc.jpg`, passes `rekam_absen` JSON `[{"foto":"abc.jpg"}]`, asserts `buildRekamHashMap()` maps `hash('sha256', $bytes) => 'media:legacy/abc.jpg'`.
- `testSaveBinaryReturnsNullGracefullyWhenBaseDirIsUnwritable`: passes a regular file path as `$baseDir` (so subdirectory creation fails cleanly on both Windows and Linux), asserts `saveBase64()` returns `null` without throwing.

- [ ] **Step 2: Run PHPUnit to verify tests fail**

Run: `& "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaStorageTest.php`
Expected: FAIL (`Class "App\Libraries\AbsenMediaStorage" not found`).

- [ ] **Step 3: Implement `App\Libraries\AbsenMediaStorage` and update `.gitignore`**

Implement `app/Libraries/AbsenMediaStorage.php` matching the Interfaces block:
- Default `$baseDir` to `rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'absen_media'` and `$legacyPublicDir` to `env('ABSEN_LEGACY_FOTO_DIR') ?: (rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'foto_absen')`.
- Enforce strict `realpath` jail containment inside `$baseDir` (or `$legacyPublicDir` / `FCPATH . 'dist'` for fallbacks); reject null bytes, `..`, and non-allowed extensions.
- Write atomically via temp file in destination directory + `rename()` + `is_file() && filesize() > 0` check; clean up temp file on error and return `null` instead of throwing.
- On `decodeBase64Tolerant()`: try strict `base64_decode($payload, true)`; if it fails, strip whitespace, trim dangling `len % 4 == 1` char, pad with `=` to multiple of 4, decode non-strictly (`base64_decode($fixed, false)`), require `strlen($bin) >= 32` and valid image magic header (`\xFF\xD8\xFF` JPEG, `\x89PNG` PNG, `GIF8` GIF, `RIFF` WebP, `BM` BMP) or JPEG SOI marker.
- Append `writable/absen_media/` explicitly in `.gitignore` under the `writable/*` block.

- [ ] **Step 4: Run PHP lint and unit tests to verify pass**

Run: `& "C:\Program Files\Ampps\php82\php.exe" -l app\Libraries\AbsenMediaStorage.php && & "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaStorageTest.php`
Expected: `No syntax errors detected` and all 8 tests PASS.

- [ ] **Step 5: Commit**

```bash
git add .gitignore app/Libraries/AbsenMediaStorage.php tests/unit/AbsenMediaStorageTest.php
git commit -m "feat(absen): add AbsenMediaStorage private media library with unit tests"
```

---

### Task 2: Authenticated Extensionless Photo Streaming & Views

**Files:**
- Modify: `app/Config/Routes.php:775-778`
- Modify: `app/Controllers/Absen/Foto.php:31-148`
- Modify: `app/Views/absen/rekam_absen.php:1-19`
- Modify: `app/Views/absen/get_absen_harian.php:125-175`
- Test: `tests/unit/AbsenFotoControllerTest.php`

**Interfaces:**
- Consumes: `AbsenMediaStorage::resolveToBinary()`, `AbsenMediaStorage::resolveRekamFotoPath()`, `AbsenMediaStorage::isEmptyValue()`
- Produces:
  - Route `GET /absen/rekam-foto/(:num)/(:num)` → `Absen\Foto::rekamFoto/$1/$2` (under `AuthFilter` via `absen/*`)
  - `Absen\Foto::show($idAbsen, $jenis = 'masuk')` (updated to use `AbsenMediaStorage` + `ETag` + `Cache-Control: private, max-age=86400`)
  - `Absen\Foto::rekamFoto($idAbsen, $index)` (resolves item `$index` from `absen.rekam_absen` JSON and streams binary with `ETag` + `Cache-Control: private, max-age=86400`)
  - `Absen\Foto::extractRekamFotoRef(?string $rekamJson, int $index): ?string` (public static helper for clean testability and reuse by `Crud::setNewAbsen()`)

- [ ] **Step 1: Write failing unit tests in `tests/unit/AbsenFotoControllerTest.php`**

Test `Absen\Foto::extractRekamFotoRef()` and `rekam_absen.php` rendered markup:
- `testExtractRekamFotoRefReturnsExactFilenameForValidIndex`: JSON with 2 entries returns item `0` and item `1`'s `'foto'` value.
- `testExtractRekamFotoRefReturnsNullForInvalidJsonOrOutOfBoundsOrNegativeIndex`: asserts `null` for `index = -1`, `index = 5`, invalid JSON, empty string, or entry missing `'foto'`.
- `testRekamAbsenViewRendersExtensionlessAuthenticatedUrl`: renders `app/Views/absen/rekam_absen.php` with `$id_absen = 50731`, `$superUser = true`, `$getData = [['foto' => 'abs_123.jpg', 'tombol' => 'Rekam Masuk', 'alamat_terdekat' => 'Kantor', 'longitude' => '107', 'latitude' => '-6', 'jam_absen' => '2026-11-22 07:30:00']]`; asserts HTML contains `src="/absen/rekam-foto/50731/0"`, `data-src="/absen/rekam-foto/50731/0"`, and does NOT contain `/dist/foto_absen/` or `.jpg`.

- [ ] **Step 2: Run PHPUnit to verify tests fail**

Run: `& "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenFotoControllerTest.php`
Expected: FAIL (`Call to undefined method App\Controllers\Absen\Foto::extractRekamFotoRef()`).

- [ ] **Step 3: Implement route, `Foto.php` updates, and view updates**

- In `app/Config/Routes.php` right after line 777: add `$routes->get('/absen/rekam-foto/(:num)/(:num)', 'Absen\Foto::rekamFoto/$1/$2');`.
- In `app/Controllers/Absen/Foto.php`:
  - Replace inline decoding/file reading in `show()` (lines 76–106) with `(new AbsenMediaStorage())->resolveToBinary($raw)`. Return `404` when `isEmptyValue($raw)` or `resolveToBinary()` returns `null`.
  - Add `public static function extractRekamFotoRef(?string $rekamJson, int $index): ?string`.
  - Add `public function rekamFoto($idAbsen, $index)`: validate `$id > 0 && $idx >= 0`, query `SELECT rekam_absen, updated_at FROM absen WHERE id_absen = ? LIMIT 1`, extract `$fotoRef` via `self::extractRekamFotoRef()`, resolve via `(new AbsenMediaStorage())->resolveRekamFotoPath($fotoRef)`, clear PHP session cache headers, compute `ETag` (`md5("rekam|{$id}|{$idx}|{$fotoRef}|" . filemtime($path))`), support `304 Not Modified`, and stream with `Content-Type`, `Content-Length`, `Cache-Control: private, max-age=86400`, `ETag`.
  - In `checkSecond()`, also exclude `'data:,'` (`AND {$column} != 'data:,'`).
- In `app/Views/absen/rekam_absen.php`: iterate `foreach ($getData as $idx => $data)` and render `src="/absen/rekam-foto/<?= (int) $id_absen; ?>/<?= (int) $idx; ?>"` and `data-src="/absen/rekam-foto/<?= (int) $id_absen; ?>/<?= (int) $idx; ?>"`.
- In `app/Views/absen/get_absen_harian.php` lines 126–128 and 171–173: check `!empty($data['image_masuk_1']) && $data['image_masuk_1'] !== 'data:,'` and set `data-src="/absen/foto/<?= (int) $data['id_absen']; ?>/masuk"` (and `/pulang` for `image_pulang_1`).

- [ ] **Step 4: Run PHP lint and unit tests to verify pass**

Run: `& "C:\Program Files\Ampps\php82\php.exe" -l app\Config\Routes.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Controllers\Absen\Foto.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Views\absen\rekam_absen.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Views\absen\get_absen_harian.php && & "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenFotoControllerTest.php`
Expected: `No syntax errors detected` for all 4 files and all 3 tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Config/Routes.php app/Controllers/Absen/Foto.php app/Views/absen/rekam_absen.php app/Views/absen/get_absen_harian.php tests/unit/AbsenFotoControllerTest.php
git commit -m "feat(absen): serve private absen and rekam photos via extensionless auth routes"
```

---

### Task 3: Write Paths (`Crud.php`) & Read/Download AJAX Paths (`Ajax.php`)

**Files:**
- Modify: `app/Controllers/Absen/Crud.php:170-223,308-366,455-488,1181-1192,1291-1300,1383-1416,1655-1673`
- Modify: `app/Controllers/Absen/Ajax.php:54-156,597-615,1047-1064`
- Test: `tests/unit/AbsenMediaHandlersTest.php`

**Interfaces:**
- Consumes: `AbsenMediaStorage` (Task 1), `Absen\Foto::extractRekamFotoRef()` (Task 2)
- Produces:
  - `Absen\Crud::resolveRekamImageInput(string $imageInput, ?string $rekamJson, ?AbsenMediaStorage $storage = null): ?string` (resolves `/absen/rekam-foto/{id}/{index}` or legacy `/dist/foto_absen/{file}` into a validated `media:...` ref)
  - `Absen\Ajax::mapImageColumnsToUrls(int $idAbsen, array $row, array $columnToJenis): array` (maps non-empty/non-`data:,` DB columns to `/absen/foto/{id}/{jenis}` URLs)

- [ ] **Step 1: Write failing unit tests in `tests/unit/AbsenMediaHandlersTest.php`**

- `testResolveRekamImageInputFromExtensionlessRouteAndLegacyPath`:
  - Given temp storage with `foto/2026/11/a.jpg` and `legacy/old.jpg`, and `$rekamJson = '[{"foto":"foto/2026/11/a.jpg"},{"foto":"old.jpg"}]'`:
    - `Crud::resolveRekamImageInput('/absen/rekam-foto/50/0', $rekamJson, $storage)` returns `'media:foto/2026/11/a.jpg'`.
    - `Crud::resolveRekamImageInput('/absen/rekam-foto/50/1', $rekamJson, $storage)` returns `'media:legacy/old.jpg'`.
    - `Crud::resolveRekamImageInput('/dist/foto_absen/old.jpg', $rekamJson, $storage)` returns `'media:legacy/old.jpg'`.
    - `Crud::resolveRekamImageInput('/absen/rekam-foto/50/99', $rekamJson, $storage)` and `Crud::resolveRekamImageInput('/dist/foto_absen/../../.env', $rekamJson, $storage)` return `null`.
- `testMapImageColumnsToUrlsIgnoresEmptyAndEmptyCanvas`:
  - Given `$row = ['id_absen' => 12, 'image_masuk_1' => 'media:foto/2026/11/x.jpg', 'image_pulang_1' => 'data:,']`, `Ajax::mapImageColumnsToUrls(12, $row, ['image_masuk_1' => 'masuk', 'image_pulang_1' => 'pulang'])` returns `image_masuk_1 === '/absen/foto/12/masuk'` and `image_pulang_1 === ''`.

- [ ] **Step 2: Run PHPUnit to verify tests fail**

Run: `& "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaHandlersTest.php`
Expected: FAIL (`Call to undefined method ...`).

- [ ] **Step 3: Implement `Crud.php` and `Ajax.php` updates**

- In `app/Controllers/Absen/Crud.php`:
  - Update `parseAndSaveFotoAbsen(?string $imageData): array`: keep black-image & base64 validation (`>= 256` bytes), then delegate saving to `(new AbsenMediaStorage())->saveBase64($img, 'foto')`. Return `['valid' => true, 'filename' => $saved['relPath'] ?? null, 'mediaRef' => $saved['mediaRef'] ?? null]`. If `$saved === null`, call `log_message('error', 'AbsenMediaStorage: gagal menyimpan foto absen ke disk, fallback ke DB base64')`.
  - In `clockIn()` (`L311, L344`) and `clockOut()` (`L457, L463`): compute `$storedFoto = !empty($fotoCheck['mediaRef']) ? $fotoCheck['mediaRef'] : $imageData;` and write `$storedFoto` to `image_masuk_1`, `image_masuk_2`, `image_pulang_1`, `image_pulang_2`. Keep `$fotoCheck['filename']` (`foto/YYYY/MM/...`) in `rekam_absen`.
  - In `updateDataAbsenAll()` (`L1184, L1189`) and `addDataAbsen()` (`L1295`): save uploaded files via `(new AbsenMediaStorage())->saveUploadedFile($file, 'foto')`; if `$saved` is valid use `$saved['mediaRef']`, otherwise log error and fall back to `generate_base64($file->getTempName())`.
  - Add `public static function resolveRekamImageInput(string $imageInput, ?string $rekamJson, ?AbsenMediaStorage $storage = null): ?string`, and in `setNewAbsen()` (`L1383–1416`): fetch `rekam_absen` for `(int) $id_absen`, resolve `$mediaRef = self::resolveRekamImageInput((string) $image, $row['rekam_absen'] ?? null)`; if `$mediaRef !== null`, set `"image_{$var}_1" => $mediaRef` (never call `generate_base64()`!).
  - In `simpanTandaTangan()` (`L1658–1663`): save `$signature` via `(new AbsenMediaStorage())->saveBase64((string) $signature, 'ttd')`; store `$saved['mediaRef']` if non-null, else `log_message('error', ...)` and fall back to `$signature`.
- In `app/Controllers/Absen/Ajax.php`:
  - Add `public static function mapImageColumnsToUrls(int $idAbsen, array $row, array $columnToJenis): array`.
  - Update `getImage()` (`L54–67`) and `getSecondImage()` (`L597–615`) to transform the selected columns via `self::mapImageColumnsToUrls()` before returning JSON.
  - Update `serveFoto()` (`L117–148`) to delegate binary resolution to `(new AbsenMediaStorage())->resolveToBinary($raw)`.
  - Update `getLatestDLPhoto()` (`L1047–1063`) to resolve the selected column value (`image_pulang_1` / `image_masuk_1` / `image_masuk_2`) via `(new AbsenMediaStorage())->resolveToDataUri($rawPhoto)` before returning `'image' => $photoBase64`.

- [ ] **Step 4: Run PHP lint and unit tests to verify pass**

Run: `& "C:\Program Files\Ampps\php82\php.exe" -l app\Controllers\Absen\Crud.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Controllers\Absen\Ajax.php && & "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaHandlersTest.php`
Expected: `No syntax errors detected` for both controllers and all tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Controllers/Absen/Crud.php app/Controllers/Absen/Ajax.php tests/unit/AbsenMediaHandlersTest.php
git commit -m "feat(absen): write new photos and signatures to private storage and route ajax reads"
```

---

### Task 4: Excel & PDF Dinas Luar Signature Exports

**Files:**
- Modify: `app/Controllers/Export/Excel.php:3950-3996`
- Modify: `app/Views/absen/pdf_rekam_absen_dl.php:75-100`
- Test: `tests/unit/AbsenExportMediaTest.php`

**Interfaces:**
- Consumes: `AbsenMediaStorage::resolveToFile()`, `AbsenMediaStorage::resolveToBinary()`, `AbsenMediaStorage::resolveToDataUri()`
- Produces:
  - `Export\Excel` resolves `ttd_masuk` and `ttd_pulang` directly to disk path for `media:...` values (avoiding temp file creation) and falls back to temp file for legacy base64 rows, tracking temp files for cleanup after `$writer->save(...)`.
  - `pdf_rekam_absen_dl.php` resolves `ttd_masuk` and `ttd_pulang` via `AbsenMediaStorage::resolveToDataUri()` so Dompdf renders both private file-backed signatures and legacy base64 signatures seamlessly.

- [ ] **Step 1: Write failing unit tests in `tests/unit/AbsenExportMediaTest.php`**

- `testPdfViewRendersMigratedFileSignatureAsDataUriAndSkipsEmptyCanvas`: writes a valid PNG to `$storage->saveBase64(...)`, renders `app/Views/absen/pdf_rekam_absen_dl.php` passing `$absen` with one row having `ttd_masuk = $saved['mediaRef']` and `ttd_pulang = 'data:,'` (plus a second row having legacy `data:image/png;base64,...`), injecting `$mediaStorage = $storage`; asserts HTML contains `src="data:image/png;base64,` for both valid signatures and does NOT render an `<img>` for `data:,`.
- `testStorageResolveToFileReturnsDirectPathForExcelDrawing`: asserts `resolveToFile($saved['mediaRef'])` returns exact readable filesystem path with `image/png`, and `resolveToFile($legacyBase64)` returns `null` while `resolveToBinary($legacyBase64)` returns PNG bytes.

- [ ] **Step 2: Run PHPUnit to verify tests fail**

Run: `& "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenExportMediaTest.php`
Expected: FAIL (`pdf_rekam_absen_dl.php` outputs `data:image/png;base64,media:ttd/...` instead of decoded PNG data URI).

- [ ] **Step 3: Update `app/Controllers/Export/Excel.php` and `app/Views/absen/pdf_rekam_absen_dl.php`**

- In `app/Controllers/Export/Excel.php` (`L3950–3996`): instantiate `$mediaStorage = new \App\Libraries\AbsenMediaStorage();` before the row loop. For `ttd_masuk` (column `G`) and `ttd_pulang` (column `K`), resolve `$file = $mediaStorage->resolveToFile($val)`: if found, pass `$file['path']` directly to `$drawing->setPath()`; otherwise call `$bin = $mediaStorage->resolveToBinary($val)`, write temp file (`tempnam(...) . '.png'`), and pass temp path.
- In `app/Views/absen/pdf_rekam_absen_dl.php`: instantiate `$storage = $mediaStorage ?? new \App\Libraries\AbsenMediaStorage();` once at top of `<tbody>`, then resolve `$ttd = $storage->resolveToDataUri($data['ttd_masuk'] ?? null);` and `$ttdPulang = $storage->resolveToDataUri($data['ttd_pulang'] ?? null);` — only output `<img src="<?= $ttd ?>" ...>` when non-empty.

- [ ] **Step 4: Run PHP lint and unit tests to verify pass**

Run: `& "C:\Program Files\Ampps\php82\php.exe" -l app\Controllers\Export\Excel.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Views\absen\pdf_rekam_absen_dl.php && & "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenExportMediaTest.php`
Expected: `No syntax errors detected` and all tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Controllers/Export/Excel.php app/Views/absen/pdf_rekam_absen_dl.php tests/unit/AbsenExportMediaTest.php
git commit -m "feat(absen): support private media files in Excel and PDF Dinas Luar exports"
```

---

### Task 5: Resumable Purge-Lag-Safe Backfill Migrator & Spark CLI Command

**Files:**
- Create: `app/Libraries/AbsenMediaMigrator.php`
- Create: `app/Commands/MigrateAbsenMedia.php`
- Test: `tests/unit/AbsenMediaMigratorTest.php`

**Interfaces:**
- Consumes: `AbsenMediaStorage` (Task 1)
- Produces:
  - `AbsenMediaMigrator::__construct(AbsenMediaStorage $storage, ?callable $historyLenProvider = null, ?callable $sleepCallback = null)`
  - `AbsenMediaMigrator::processRow(array $row, bool $dryRun = false): array` (returns `['patch' => array<col, mediaRef>, 'stats' => array, 'warnings' => string[]]`)
  - `AbsenMediaMigrator::waitForPurgeLag(int $maxHistory, int $maxWaitSec = 60): int` (returns final history length; invokes `$sleepCallback` while `historyLen > $maxHistory`)
  - CLI command `php spark absen:migrate-media` with options `--dry-run`, `--batch=100`, `--start-id=0`, `--sleep-ms=50`, `--max-history=200`, `--limit=0`, `--legacy-dir=...`

- [ ] **Step 1: Write failing unit tests in `tests/unit/AbsenMediaMigratorTest.php`**

- `testProcessRowDeduplicatesMatchingRekamAbsenFileWithoutWritingNewFile`: puts a JPEG in `$baseDir/legacy/abs_1.jpg`, feeds a row whose `image_masuk_1` is the exact base64 of that JPEG and `rekam_absen` references `abs_1.jpg`; asserts `$res['patch']['image_masuk_1'] === 'media:legacy/abs_1.jpg'`, `$res['stats']['dedup_reused'] === 1`, `$res['stats']['extracted_new_photos'] === 0`, and no new file created under `foto/`.
- `testProcessRowExtractsDbOnlyPhotoAndTtdWithVerification`: feeds a row (`created_at = '2024-07-03 10:36:24'`, empty `rekam_absen`) with base64 `image_masuk_1` + PNG `ttd_masuk`; asserts `patch['image_masuk_1']` matches `^media:foto/2024/07/abs_1_image_masuk_1_[a-f0-9]{8}\.jpg$`, `patch['ttd_masuk']` matches `^media:ttd/2024/07/ttd_1_ttd_masuk_[a-f0-9]{8}\.png$`, both files exist on disk with `filesize > 0`, and `extracted_new_photos === 1`, `extracted_new_ttd === 1`.
- `testProcessRowRecoversTruncated2024Base64AndSkipsCorruptAndEmptyCanvas`: feeds truncated JPEG base64 in `image_masuk_1`, `'data:,'` in `image_masuk_2`, corrupt garbage `'data:image/jpeg;base64,@@@@'` in `image_pulang_1`, already-migrated `'media:legacy/x.jpg'` in `image_pulang_2`; asserts `image_masuk_1` is migrated (`truncated_recovered === 1`), `image_masuk_2` & `image_pulang_2` are omitted from `patch`, and `image_pulang_1` is omitted from `patch` with 1 warning logged (`skipped_warnings === 1`).
- `testProcessRowDryRunWritesZeroFiles`: runs with `$dryRun = true` on a DB-only photo + TTD row, asserts `patch` contains expected keys/stats while `foto/` and `ttd/` directories remain empty.
- `testWaitForPurgeLagThrottlesUntilHistoryDropsBelowThreshold`: injects a fake `$historyLenProvider` returning `[350, 260, 120]` and a `$sleepCallback` counter; asserts `waitForPurgeLag(200)` invokes sleep exactly twice and returns `120`.

- [ ] **Step 2: Run PHPUnit to verify tests fail**

Run: `& "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaMigratorTest.php`
Expected: FAIL (`Class "App\Libraries\AbsenMediaMigrator" not found`).

- [ ] **Step 3: Implement `AbsenMediaMigrator` and `MigrateAbsenMedia`**

- Implement `app/Libraries/AbsenMediaMigrator.php`:
  - `processRow(array $row, bool $dryRun = false): array`: skips any column where `isEmptyValue()` or `isMediaRef()` or `strlen($val) <= 100`; builds `rekam_absen` SHA-256 map via `$this->storage->buildRekamHashMap($row['rekam_absen'] ?? null)`; for photo columns checks SHA-256 dedup first (verifying `is_file && filesize > 0`), else writes `abs_{id}_{col}_{hash8}.{ext}` under `foto/YYYY/MM/` (using `created_at` date, falling back to `jam_masuk` then `date('Y-m-d')`); for `ttd_masuk`/`ttd_pulang` writes `ttd_{id}_{col}_{hash8}.{ext}` under `ttd/YYYY/MM/`; always verifies `is_file && filesize > 0` before adding to `$patch`.
  - `waitForPurgeLag(int $maxHistory, int $maxWaitSec = 60): int`: queries `information_schema.INNODB_METRICS WHERE NAME = 'trx_rseg_history_len'` (fallback parse `History list length (\d+)` from `SHOW ENGINE INNODB STATUS`), loops with sleep callback while above `$maxHistory`.
- Implement `app/Commands/MigrateAbsenMedia.php` (`$group = 'Absen'`, `$name = 'absen:migrate-media'`):
  - Raises `memory_limit` to `512M` for CLI safety.
  - Iterates `WHERE id_absen > $lastId ORDER BY id_absen ASC LIMIT $batch`, calls `processRow()`, updates via `$db->table('absen')->where('id_absen', $id)->update($patch)` (preserving historical `updated_at`), calls `waitForPurgeLag($maxHistory)` after each batch, sleeps `--sleep-ms`, writes warnings to `WRITEPATH . 'logs/absen-migrate-warnings.log'`, and prints progress + final summary table.

- [ ] **Step 4: Run PHP lint and full test suite**

Run: `& "C:\Program Files\Ampps\php82\php.exe" -l app\Libraries\AbsenMediaMigrator.php && & "C:\Program Files\Ampps\php82\php.exe" -l app\Commands\MigrateAbsenMedia.php && & "C:\Program Files\Ampps\php82\php.exe" vendor\bin\phpunit tests\unit\AbsenMediaStorageTest.php tests\unit\AbsenFotoControllerTest.php tests\unit\AbsenMediaHandlersTest.php tests\unit\AbsenExportMediaTest.php tests\unit\AbsenMediaMigratorTest.php`
Expected: `No syntax errors detected` and 100% of all unit tests PASS.

- [ ] **Step 5: Commit**

```bash
git add app/Libraries/AbsenMediaMigrator.php app/Commands/MigrateAbsenMedia.php tests/unit/AbsenMediaMigratorTest.php
git commit -m "feat(absen): add resumable deduplicating absen:migrate-media CLI command"
```

---

### Task 6: Production Rollout, Backfill Execution & InnoDB Reclaim (`12.28 GB → < 1 GB`)

**Files:**
- Remote production repo: `/home/bkkr3927/masterkkp_ci4` (synced via git / verified against local commit)
- Remote safety backups: `/root/backup-tuning-20261122-part5/absen_db_only_backup.sql.gz`, `/root/backup-tuning-20261122-part5/absen_backup.sql.gz`
- Remote private media dir: `/home/bkkr3927/masterkkp_ci4/writable/absen_media/`

**Interfaces:**
- Consumes: Tasks 1–5 committed and verified locally
- Produces: Production `bkkr3927_simply.absen` shrunk from `11.19 GB` to `~50 MB`, `absen_backup` archived & dropped, total server DB `< 1 GB` (`~0.92 GB`), all employee attendance photos & signatures moved out of `public_html` into private `writable/absen_media/`.

- [ ] **Step 1: Pre-migration safety backups on production VPS**
  - Dump `bkkr3927_simply.absen_backup` (`217.6 MB`) → `/root/backup-tuning-20261122-part5/absen_backup.sql.gz`.
  - Dump only the `1,368` DB-only photo rows + `3,612` TTD rows from `bkkr3927_simply.absen` (`--where="(rekam_absen IS NULL OR LENGTH(rekam_absen) <= 10) OR LENGTH(IFNULL(ttd_masuk,'')) > 100 OR LENGTH(IFNULL(ttd_pulang,'')) > 100"`) → `/root/backup-tuning-20261122-part5/absen_db_only_backup.sql.gz`.
  - Verify both archives with `gzip -t`.

- [ ] **Step 2: Deploy code to `/home/bkkr3927/masterkkp_ci4` and reload `ea-php83-php-fpm`**
  - Sync committed changes (via git push/pull or verified patch matching HEAD) cleanly with zero stray untracked changes.
  - Create `/home/bkkr3927/masterkkp_ci4/writable/absen_media/{foto,ttd}` owned by `bkkr3927:bkkr3927` (`chmod 0755`).
  - Run `php -l` on all deployed files with `/usr/local/bin/ea-php83`.
  - Run `systemctl reload ea-php83-php-fpm` to flush OPcache deterministically so no stale worker can recreate `dist/foto_absen/`.
  - Probe `https://simply.bkkbandung.net/` (`--resolve simply.bkkbandung.net:443:148.230.98.19`) → verify `HTTP/2 200`.

- [ ] **Step 3: Atomic move `dist/foto_absen/` → `writable/absen_media/legacy/`**
  - Run `mv /home/bkkr3927/public_html/simply.bkkbandung.net/dist/foto_absen /home/bkkr3927/masterkkp_ci4/writable/absen_media/legacy`.
  - Verify `https://simply.bkkbandung.net/dist/foto_absen/66bd7c20acb44.jpg` returns `HTTP/2 404` (public exposure closed immediately).
  - Verify `/home/bkkr3927/masterkkp_ci4/writable/absen_media/legacy` contains `124,300+` files owned by `bkkr3927:bkkr3927`.

- [ ] **Step 4: Dry-run and execute full backfill via `ea-php83 spark absen:migrate-media`**
  - Confirm no `mysqldump` or `/usr/local/cpanel/bin/backup` process is running (`ps aux | grep -E "mysqldump|cpanel/bin/backup"`).
  - Run dry-run as `bkkr3927`: `su - bkkr3927 -s /bin/bash -c "cd /home/bkkr3927/masterkkp_ci4 && /usr/local/bin/ea-php83 spark absen:migrate-media --dry-run --limit=500"`.
  - Verify high dedup rate (~99%) and 0 errors, then run full backfill as `bkkr3927`:
    `su - bkkr3927 -s /bin/bash -c "cd /home/bkkr3927/masterkkp_ci4 && /usr/local/bin/ea-php83 spark absen:migrate-media --batch=100 --sleep-ms=50 --max-history=200"`.

- [ ] **Step 5: Verification gate before space reclaim**
  - Query MariaDB for any remaining `data:image/%` rows across all 6 columns (expected: `0`, or only verified corrupt anomalies in `absen-migrate-warnings.log`).
  - Verify `ls -lh /var/lib/mysql/ibdata1` stayed small (no multi-GB undo bloat).
  - Spot-check via PHP CLI (`AbsenMediaStorage::resolveToBinary`) on historical rows from 2024 (`id_absen = 1`, `id_absen = 100`), 2025, and 2026 (including rows with `ttd_masuk` & `ttd_pulang`) — verify valid JPEG/PNG bytes returned.
  - Spot-check `https://simply.bkkbandung.net/absen/foto/50731/masuk` without session → redirects/denies (protected by `AuthFilter`).

- [ ] **Step 6: Archive drop of `absen_backup` & online InnoDB reclaim on `absen`**
  - Verify `/root/backup-tuning-20261122-part5/absen_backup.sql.gz` exists and passes `gzip -t`, then run `DROP TABLE bkkr3927_simply.absen_backup;`.
  - Run online table rebuild:
    `mysql bkkr3927_simply -e "SET SESSION lock_wait_timeout = 30; ALTER TABLE absen FORCE, ALGORITHM=INPLACE, LOCK=NONE;"`
  - Verify final metrics:
    - `information_schema.tables` sum across ALL databases is **`< 1024 MB` (`< 1 GB`, target `~0.92 GB`)**.
    - `/var/lib/mysql/bkkr3927_simply/absen.ibd` dropped from `12 GB` to `~40–60 MB`.
    - `df -h /` reflects `~10.8 GB` net disk space freed.
    - Live `https://simply.bkkbandung.net/` returns `HTTP/2 200` and PHP-FPM/MariaDB error logs show 0 new errors.
