# Design Spec: Migrasi Media `bkkr3927_simply.absen` ke Private Storage (`writable/absen_media`) & Shrink Total DB `< 1 GB`

**Tanggal:** 2026-11-22  
**Repositori:** `c:\Program Files\Ampps\www\simply` (produksi: `/home/bkkr3927/masterkkp_ci4`, webroot: `/home/bkkr3927/public_html/simply.bkkbandung.net`)  
**Database Target:** MariaDB `10.11.19` — `bkkr3927_simply` pada `srv965025.hstgr.cloud`

---

## 1. Tujuan & Alasan (*Intent & Why*)

Total ukuran seluruh database di server saat ini adalah **12,28 GB** (melebihi `innodb_buffer_pool_size = 10 GB`), dan **91%** di antaranya berasal dari satu tabel: `bkkr3927_simply.absen` (**11,19 GB** / `absen.ibd` `12 GB`, 50.578 baris). Penyebabnya adalah penyimpanan gambar kamera dan tanda tangan dalam format teks *base64* langsung di 6 kolom `MEDIUMTEXT` (`image_masuk_1`, `image_pulang_1`, `image_masuk_2`, `image_pulang_2`, `ttd_masuk`, `ttd_pulang` — total **10,13 GB** base64).

Di sisi lain, `public_html/simply.bkkbandung.net/dist/foto_absen/` saat ini sudah menyimpan **124.300 file (`8,0 GB`)** sejak 15 Agustus 2024 yang ~99,4% identik *byte-for-byte* dengan foto di DB, tetapi dapat diakses secara publik tanpa login.

**Sasaran sukses:**
1. Total seluruh database di server turun dari **12,28 GB → `< 1 GB` (~0,92 GB)** sehingga 100% working set muat di InnoDB buffer pool, backup cepat, dan query `absen` ringan.
2. **Nol duplikasi & hemat disk bersih ~10,8 GB** di VPS (ruang kosong saat ini **212 GB** / 48% terpakai).
3. **100% privat:** seluruh foto wajah dan tanda tangan pegawai (termasuk foto `rekam_absen`) disimpan di luar `public_html` (`writable/absen_media/`) dan hanya diakses melalui route terautentikasi (`AuthFilter`), kecuali tombol unduh DL terakhir pada layar absensi publik yang tetap melayani 1 foto untuk NIP bersangkutan tanpa mengekspos URL file.
4. **Zero downtime & zero data loss** saat deploy dan backfill 50.578 baris (2024–2026), termasuk **1.368 baris foto** dan **3.612 baris tanda tangan** yang saat ini *hanya* ada di database.

---

## 2. Fakta Infrastruktur Terverifikasi di Produksi

- **MariaDB (`10.11.19-MariaDB-log`):**
  - `log_bin = OFF` → tidak ada risiko pembengkakan binary log saat update 50.578 baris.
  - `innodb_file_per_table = ON`, `old_alter_table = DEFAULT` → mendukung `ALTER TABLE ... FORCE, ALGORITHM=INPLACE, LOCK=NONE`.
  - `innodb_undo_tablespaces = 0`, `innodb_undo_log_truncate = OFF`, `ibdata1 = 76M` → **kritis:** backfill wajib memantau `History list length` (purge lag InnoDB) di tiap batch agar `ibdata1` tidak membengkak permanen!
  - Tabel `absen`: **0 trigger**, **0 `FULLTEXT` index**, **0 foreign key**, dan `updated_at` bertipe `datetime NOT NULL` tanpa `ON UPDATE CURRENT_TIMESTAMP` → update langsung via Query Builder (tanpa `AbsenModel::$useTimestamps`) menjaga nilai `updated_at` historis 2024–2026 tetap utuh.
  - Baris pendek non-`data:image/%`: semuanya hanyalah string kosong kamera **`data:,`** (Juli–Agu 2024), **0 path `dist/`** yang eksis di tabel `absen`.
- **Web Server, PHP & Backup:**
  - Pure Apache 2.4 + `ea-php83-php-fpm` aktif (tanpa Nginx proxy / Cloudflare di depan `simply.bkkbandung.net`).
  - `.htaccess` `simply.bkkbandung.net` memiliki `<FilesMatch "\.(jpg|jpeg|png|gif|webp|...)$">` yang memasang `Cache-Control: public, max-age=31536000, immutable` → **kritis:** route privat `rekam-foto` wajib **tanpa ekstensi file** (`/absen/rekam-foto/{id_absen}/{index}`), bukan URL berakhiran `.jpg`.
  - Split directory: `spark` mematok `FCPATH = /home/bkkr3927/masterkkp_ci4/public/`, sementara webroot adalah `/home/bkkr3927/public_html/simply.bkkbandung.net/`. Namun **`WRITEPATH` identik** untuk web maupun CLI (`/home/bkkr3927/masterkkp_ci4/writable/`).
  - `/home/bkkr3927/bin/clean-ci4-writable.sh` hanya membersihkan `session`, `cache`, `debugbar`, dan `logs` — **tidak menyentuh `writable/absen_media/`**.
  - `/home/bkkr3927/cpbackup-exclude.conf` tidak mengecualikan `masterkkp_ci4/writable/absen_media/`, sehingga seluruh media absen otomatis ikut dalam WHM Weekly Backup ke Google Drive (`WHM-Backup-BKK`, jalan Minggu `02:00 WIB`).
  - Deploy produksi menggunakan `git pull` di `/home/bkkr3927/masterkkp_ci4` (`writable/absen_media/` wajib ditambahkan ke `.gitignore` agar tidak pernah terganggu operasi git).

---

## 3. Ruang Lingkup (*Goals, Non-Goals, Anti-Goals*)

### Goals
- Sentralisasi baca/tulis/validasi media absensi di `App\Libraries\AbsenMediaStorage`.
- Menghentikan penulisan *base64* baru ke 6 kolom media `absen` pada semua jalur tulis (`clockIn`, `clockOut`, edit/add manual, `setNewAbsen`, `simpanTandaTangan`).
- Memindahkan secara atomik `public_html/simply.bkkbandung.net/dist/foto_absen/` (`8,0 GB`) → `masterkkp_ci4/writable/absen_media/legacy/` dan melayani modal `rekam_absen` lewat route login tanpa ekstensi `/absen/rekam-foto/{id_absen}/{index}`.
- Menjalankan CLI backfill (`php spark absen:migrate-media`) yang *resumable*, ber-batch, memiliki **InnoDB purge-lag throttling**, deduplikasi SHA-256 terhadap file `rekam_absen`, ekstraksi foto & TTD yang hanya ada di DB, dan verifikasi `filesize > 0` sebelum update DB.
- Reclaim ruang InnoDB via `ALTER TABLE bkkr3927_simply.absen FORCE, ALGORITHM=INPLACE, LOCK=NONE` setelah 100% backfill & spot-check terverifikasi.
- Dump arsip `.sql.gz` untuk tabel tidak terpakai `bkkr3927_simply.absen_backup` (`217,6 MB`, 0 referensi di kode) ke `/root/backup-tuning-20261122-part5/` lalu `DROP TABLE absen_backup` agar total seluruh DB turun di bawah `1 GB` (~0,92 GB).

### Non-Goals
- Tidak mengubah format struktur JSON kolom `rekam_absen` (hanya `27,5 MB` total; nama file lama tetap dibaca dari `writable/absen_media/legacy/`).
- Tidak mengubah tipe kolom `MEDIUMTEXT` pada tabel `absen`.
- Tidak menyentuh Docker, layanan email, atau database aplikasi lain.

### Anti-Goals
- Kehilangan 1 foto atau 1 tanda tangan pun (terutama 1.368 baris foto & 3.612 TTD yang hanya ada di DB).
- Membengkakkan `ibdata1` akibat batch UPDATE terlalu agresif atau bertabrakan dengan backup cron.
- Merusak baris awal Juli 2024 yang terpotong di `65.535` byte (eks tipe `TEXT`) atau baris kosong `data:,`.
- Menggagalkan presensi *live* pegawai (`clockIn`/`clockOut`) apabila terjadi gangguan sementara tulis disk.

---

## 4. Pendekatan yang Dipertimbangkan (*Approaches*)

1. **Opsi 1 — Reuse `public/dist/foto_absen/` (Publik, Nol Duplikat):**
   - *Trade-off:* Perubahan kode paling sedikit dan hemat ~10,8 GB disk, tetapi 124.300+ foto wajah & tanda tangan tetap dapat diakses lewat URL publik tanpa login.
2. **Opsi 2 — Private Full Storage di `writable/absen_media/` (Dipilih — Rekomendasi):**
   - *Alasan dipilih:* Audit kode & log Apache mengonfirmasi **0 aplikasi eksternal** mengakses `/dist/foto_absen/` (100% akses dari `simply.bkkbandung.net/absen/absen_harian`). Controller ringan `App\Controllers\Absen\Foto` sudah ada di balik `AuthFilter`, dan pemindahan `dist/foto_absen` → `writable/absen_media/legacy` berada pada partisi `/dev/sda4` yang sama (`mv` atomik < 10 ms, 0 byte tambahan). Memberikan privasi penuh **sekaligus** hemat ~10,8 GB disk & DB `< 1 GB`.

---

## 5. Arsitektur & Kontrak Teknis

### 5.1 Tata Letak Direktori & Format Nilai DB
- **Base Directory:** `WRITEPATH . 'absen_media'` (`/home/bkkr3927/masterkkp_ci4/writable/absen_media/`).
- **Subfolder:**
  - `writable/absen_media/legacy/` ← hasil `mv` atomik dari `public_html/simply.bkkbandung.net/dist/foto_absen/` (124.300 file sejak 15 Agu 2024).
  - `writable/absen_media/foto/YYYY/MM/` ← foto baru (kamera & upload manual) dan ekstraksi 1.368 baris foto DB-only.
  - `writable/absen_media/ttd/YYYY/MM/` ← tanda tangan baru dan ekstraksi 3.612 baris TTD.
- **Format Nilai Kolom DB** (`image_masuk_1`, `image_masuk_2`, `image_pulang_1`, `image_pulang_2`, `ttd_masuk`, `ttd_pulang`):
  - Nilai kanonik baru memakai prefix **`media:`**, contoh:
    - `media:legacy/abs_6b024794190729.99223564.jpg`
    - `media:foto/2026/11/abs_6b02a1f4c8e12.jpg`
    - `media:ttd/2026/11/ttd_6b02a1f4c9001.png`
  - Nilai kosong `""` atau `"data:,"` dibiarkan apa adanya (tidak diproses).
- **JSON `rekam_absen`:**
  - Entri lama tetap utuh (`"foto": "66bd7c20acb44.jpg"` / `"abs_6b02....jpg"`).
  - Entri baru menyimpan path relatif tanpa prefix `media:`, contoh `"foto": "foto/2026/11/abs_6b02....jpg"`.
- **Resolusi Multi-Format (`AbsenMediaStorage`):**
  1. `media:<rel>` → baca dari `WRITEPATH . 'absen_media/' . <rel>` (dengan *realpath jail* ketat); jika `<rel>` berawalan `legacy/` dan belum dipindah saat jendela transisi, fallback ke `ABSEN_LEGACY_FOTO_DIR` / `FCPATH . 'dist/foto_absen/'`.
  2. `data:image/...;base64,...` → decode biner (toleran untuk baris 2024 yang terpotong di `65.535` byte).
  3. `/dist/...` atau `dist/...` → fallback ke `FCPATH . ltrim($raw, '/')` (jika mengarah ke `dist/foto_absen/...`, otomatis petakan dulu ke `writable/absen_media/legacy/`).

### 5.2 Kebijakan Kegagalan Tulis Disk (*Call oleh Arsitek — untuk dicek user*)
- **Keputusan:** Jika penulisan file ke `writable/absen_media/` gagal saat jam kerja *live* (`clockIn`, `clockOut`, `simpanTandaTangan`), aplikasi **tidak memblokir presensi pegawai**, melainkan sementara menyimpan *base64* asli ke kolom DB + menulis log `log_message('error', ...)` agar presensi tetap sukses 100% dan baris tersebut otomatis dimigrasikan pada eksekusi `absen:migrate-media` berikutnya.

### 5.3 Komponen Kode yang Diubah (`c:\Program Files\Ampps\www\simply`)

| File | Perubahan |
|---|---|
| `.gitignore` | Tambahkan `writable/absen_media/` agar operasi `git` tidak pernah menyentuh file media. |
| `app/Libraries/AbsenMediaStorage.php` *(baru)* | Sentralisasi `saveBase64()`, `saveUploadedFile()`, `resolveToFile()`, `resolveToBinary()`, `resolveToDataUri()`, `resolveRekamFotoPath(string $fotoRef)`, `canonicalFromRekamFoto(string $fotoRef)`, `decodeBase64Tolerant()`. Penulisan atomik (`temp` + `rename`) + cek `is_file && filesize > 0` + validasi anti path-traversal. |
| `app/Config/Routes.php` | Tambah route tanpa ekstensi `GET /absen/rekam-foto/(:num)/(:num)` → `Absen\Foto::rekamFoto/$1/$2` (otomatis terlindungi `AuthFilter` karena pola `absen/*`). |
| `app/Controllers/Absen/Foto.php` | Gunakan `AbsenMediaStorage` di `show($idAbsen, $jenis)` dan tambahkan `rekamFoto($idAbsen, $index)` yang membaca `rekam_absen` baris `$idAbsen`, mengambil item `[$index]['foto']`, lalu men-stream dengan `ETag` + `Cache-Control: private, max-age=86400` + session unlock segera. |
| `app/Controllers/Absen/Crud.php` | - `parseAndSaveFotoAbsen()`: simpan ke `writable/absen_media/foto/YYYY/MM/` lewat `AbsenMediaStorage` (kembalikan `relPath` & `mediaRef`).<br>- `clockIn()` & `clockOut()`: simpan `$fotoCheck['mediaRef']` ke `image_masuk_1/2` & `image_pulang_1/2` (fallback ke `$imageData` + `log_message('error', ...)` jika disk write gagal) dan simpan `$fotoCheck['relPath']` ke `rekam_absen['foto']`.<br>- `updateDataAbsenAll()` (`L1184, L1189`) & `addDataAbsen()` (`L1295`): simpan file upload via `AbsenMediaStorage::saveUploadedFile()`.<br>- `setNewAbsen()` (`L1407`): baca `$image` baik format baru `/absen/rekam-foto/{id}/{index}` (ambil langsung dari `rekam_absen` DB berdasarkan index!) maupun legacy `/dist/foto_absen/{file}`, ubah menjadi `media:...` yang terverifikasi eksis.<br>- `simpanTandaTangan()` (`L1659, L1661`): simpan via `AbsenMediaStorage::saveBase64($signature, 'ttd')` dengan fallback log error jika disk gagal. |
| `app/Controllers/Absen/Ajax.php` | - `getImage()` & `getSecondImage()`: untuk kolom non-kosong (`!== ''` & `!== 'data:,'`), kembalikan URL route login `/absen/foto/{id}/{jenis}` sehingga seluruh view lama (`v2/v3/v4`) bekerja tanpa modifikasi HTML/JS.<br>- `serveFoto()`: delegasikan ke `AbsenMediaStorage`.<br>- `getLatestDLPhoto()`: gunakan `AbsenMediaStorage::resolveToDataUri()` agar tombol publik *Download DL Terakhir* di `index.php` / `index-wfa.php` tetap bekerja tanpa perubahan JS dan tanpa mengekspos file privat. |
| `app/Views/absen/rekam_absen.php` | Ubah loop menjadi `foreach ($getData as $idx => $data)` dan arahkan `src` & `data-src` ke `/absen/rekam-foto/<?= (int) $id_absen; ?>/<?= (int) $idx; ?>` (bebas ekstensi `.jpg` → tidak terkena `.htaccess` public cache!). |
| `app/Views/absen/get_absen_harian.php` | Arahkan `data-src` ke `/absen/foto/<?= (int) $data['id_absen']; ?>/masuk` & `/pulang` bila kolom terisi. |
| `app/Controllers/Export/Excel.php` | Pada export Rekam Absen DL (`L3951–3994`): panggil `AbsenMediaStorage::resolveToFile()` untuk langsung passing path lokal ke `Drawing::setPath()`, fallback ke `resolveToBinary()` + temp file untuk baris base64 lama. |
| `app/Views/absen/pdf_rekam_absen_dl.php` | Gunakan `AbsenMediaStorage::resolveToDataUri()` untuk `ttd_masuk` & `ttd_pulang` agar Dompdf (`chroot = FCPATH`) merender tanda tangan dari `writable/absen_media/` maupun base64 lama. |
| `app/Commands/MigrateAbsenMedia.php` *(baru)* | Perintah CLI `php spark absen:migrate-media` dengan opsi `--dry-run`, `--batch=100`, `--start-id=0`, `--sleep-ms=50`, `--max-history=200`, `--limit=0`, `--legacy-dir=...`. |

### 5.4 Logika CLI Backfill (`php spark absen:migrate-media`) & Anti-Bloat `ibdata1`
1. Query berurut per Primary Key (`id_absen > $lastId ORDER BY id_absen ASC LIMIT $batch`), **idempotent & resumable**.
2. Di tiap batch, cek `History list length` InnoDB (`information_schema.INNODB_METRICS WHERE NAME = 'trx_rseg_history_len'`, fallback parse `SHOW ENGINE INNODB STATUS`). Jika melebihi `--max-history=200`, tidur singkat (mis. 500 ms–2s) sampai purge thread MariaDB membersihkan undo pages → **mencegah `ibdata1` membengkak permanen**.
3. Untuk tiap baris yang memiliki kolom media berpanjang `> 100` dan belum berprefix `media:`:
   - Bangun peta `sha256 => mediaRef` dari file yang dirujuk `rekam_absen` baris tersebut di `WRITEPATH . 'absen_media/'`.
   - Untuk `image_masuk_1/2` & `image_pulang_1/2`:
     - Decode base64 (strict dulu; bila gagal seperti baris Juli 2024 terpotong di `65.535` byte, gunakan decode toleran; bila hasil `< 32` byte atau gagal, lewati kolom & catat warning tanpa mengubah DB).
     - Cocokkan `sha256($binary)`: jika cocok dengan file `rekam_absen` yang ada & `filesize > 0` → set `media:legacy/{filename}` (**0 byte tambahan di disk**); jika tidak cocok → tulis atomik ke `writable/absen_media/foto/YYYY/MM/abs_{id}_{col}_{hash8}.{ext}`, verifikasi `is_file && filesize > 0`, lalu set `media:foto/YYYY/MM/...`.
   - Untuk `ttd_masuk` & `ttd_pulang`: tulis atomik ke `writable/absen_media/ttd/YYYY/MM/ttd_{id}_{col}_{hash8}.{ext}`, verifikasi `filesize > 0`, lalu set `media:ttd/YYYY/MM/...`.
   - Update menggunakan Query Builder langsung (`$db->table('absen')->where('id_absen', $id)->update($patch)`) agar **`updated_at` historis 2024–2026 tidak berubah**.
   - Hanya jalankan ketika tidak ada proses `mysqldump` / WHM backup yang sedang aktif.

---

## 6. Urutan Rollout Produksi (Zero-Downtime & Terkendali)

1. **Test & Verifikasi Lokal:**
   - Lulus `php -l` pada semua file yang berubah + unit test `AbsenMediaStorage` (save, resolve 3 format, path traversal rejection, dedup SHA-256, truncated 65535-byte base64, `data:,` skip).
2. **Pre-Migration Safety Backup (Kecil & Cepat, ~300 MB):**
   - Dump arsip terkompresi khusus **1.368 baris foto DB-only + 3.612 baris TTD** (`absen_db_only_backup.sql.gz` — hanya ~300 MB, bukan 12 GB karena 97% foto lainnya sudah ada utuh di disk!) serta `absen_backup.sql.gz` ke `/root/backup-tuning-20261122-part5/`, verifikasi `gzip -t`.
3. **Deploy Kode + Reload PHP-FPM (`ea-php83-php-fpm`):**
   - Push/pull kode ke `/home/bkkr3927/masterkkp_ci4`, buat `writable/absen_media/{foto,ttd}` (`bkkr3927:bkkr3927`, `0755`), jalankan **`systemctl reload ea-php83-php-fpm`** agar 100% worker langsung memakai kode baru (OPcache bersih — worker lama tidak akan pernah membuat ulang `dist/foto_absen/`).
   - Verifikasi live: `absen_harian`, modal `rekam_absen`, dan `btnDownloadDL` tetap `HTTP 200`.
4. **Pemindahan Atomik `dist/foto_absen/` → `writable/absen_media/legacy/`:**
   - Jalankan `mv /home/bkkr3927/public_html/simply.bkkbandung.net/dist/foto_absen /home/bkkr3927/masterkkp_ci4/writable/absen_media/legacy` (< 10 ms di `/dev/sda4`).
   - Verifikasi segera: modal `rekam_absen` tetap `HTTP 200` dengan header `Cache-Control: private, max-age=86400`, sedangkan akses publik langsung `https://simply.bkkbandung.net/dist/foto_absen/...` mengembalikan `404`.
5. **Dry-Run & Backfill Penuh (dengan Purge-Lag Guard):**
   - Jalankan `/usr/local/bin/ea-php83 spark absen:migrate-media --dry-run --limit=500` → cek angka dedup vs ekstrak.
   - Jalankan migrasi penuh (`--batch=100 --sleep-ms=50 --max-history=200`) di luar jam backup WHM Minggu `02:00`, pantau ukuran `ibdata1`.
   - Verifikasi DB: 0 baris tersisa `LIKE 'data:image/%'` (atau hanya anomali terverifikasi), lalu spot-check render foto 2024 (termasuk `id_absen = 1`), 2025, 2026 + export Excel & PDF DL.
6. **Reclaim Ruang InnoDB (`< 1 GB`) — Gerbang Verifikasi:**
   - Setelah verifikasi Step 5 lulus 100%:
     - `DROP TABLE bkkr3927_simply.absen_backup` *(arsip `.sql.gz` sudah diverifikasi di Step 2)*.
     - Jalankan `SET SESSION lock_wait_timeout = 30; ALTER TABLE bkkr3927_simply.absen FORCE, ALGORITHM=INPLACE, LOCK=NONE;` di jendela trafik rendah (non-blocking bagi presensi live).
   - Verifikasi akhir: `information_schema.tables` total seluruh DB `< 1 GB` (~0,92 GB), `absen.ibd` turun dari `12 GB` → `~50 MB`, `ibdata1` tetap kecil, `df -h /` hemat ~10,8 GB, dan endpoint `simply.bkkbandung.net` normal.

---

## 7. Keputusan yang Diserahkan ke Implementer (*Left to Builder*)
- Penamaan helper internal tambahan & struktur unit test untuk `AbsenMediaStorage`.
- Format output progress & log file migrasi pada `MigrateAbsenMedia`.
- Penyesuaian minor `memory_limit` PHP CLI untuk perintah `spark absen:migrate-media`.
