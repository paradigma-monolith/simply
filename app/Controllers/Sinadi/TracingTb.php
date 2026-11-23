<?php

namespace App\Controllers\Sinadi;

use App\Controllers\BaseController;
use App\Libraries\SinadiTracingTbSchema;
use App\Models\SinadiTracingTbModel;
use App\Models\SinadiTracingTbDokumenModel;
use App\Models\SinadiTracingTbVerifikatorModel;
use App\Models\SinadiTracingTbLokusModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class TracingTb extends BaseController
{
    protected SinadiTracingTbModel $tracingTbModel;
    protected SinadiTracingTbDokumenModel $dokumenModel;
    protected SinadiTracingTbVerifikatorModel $verifikatorModel;
    protected SinadiTracingTbLokusModel $lokusModel;

    private const ALLOWED_EXTENSIONS = ['pdf'];
    private const MAX_FILE_BYTES = 15728640; // 15 MB

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        SinadiTracingTbSchema::ensureTables(\Config\Database::connect());
        $this->tracingTbModel = new SinadiTracingTbModel();
        $this->dokumenModel = new SinadiTracingTbDokumenModel();
        $this->verifikatorModel = new SinadiTracingTbVerifikatorModel();
        $this->lokusModel = new SinadiTracingTbLokusModel();
    }

    /**
     * Ambil profil & hak akses Tracing TB untuk user yang sedang login.
     */
    private function getUserContext(): array
    {
        $idUser = (int) (session()->get('id_user') ?? 0);
        $username = (string) (session()->get('username') ?? '');
        $role = (string) (session()->get('role') ?? '');

        if ($idUser <= 0 && $username !== '') {
            $uRow = $this->userModel->select('id_user')->where('username', $username)->first();
            $idUser = (int) ($uRow['id_user'] ?? 0);
        }

        $aksesRow = null;
        if ($username !== '') {
            $aksesRow = $this->sinadiAksesModel
                ->select('sinadi_akses.status')
                ->join('user', 'user.id_user = sinadi_akses.id_user')
                ->where('user.username', $username)
                ->first();
        }
        $isSuperUser = ($role === 'Admin') || (!empty($aksesRow) && ($aksesRow['status'] ?? '') === 'Akses Diterima');

        $verifRow = $idUser > 0
            ? $this->verifikatorModel
                ->where('id_user', $idUser)
                ->where('peran', 'verifikator_bkk')
                ->first()
            : null;

        $isVerifBkk = !empty($verifRow);
        $verifNoHp = !empty($verifRow['no_hp']) ? (string) $verifRow['no_hp'] : '';

        if ($verifNoHp === '' && $idUser > 0) {
            $uTelp = $this->userModel->select('no_telp')->where('id_user', $idUser)->first();
            $verifNoHp = trim((string) ($uTelp['no_telp'] ?? ''));
        }

        return [
            'id_user'          => $idUser,
            'username'         => $username,
            'nama'             => (string) (session()->get('nama') ?? ''),
            'role'             => $role,
            'is_super_user'    => $isSuperUser,
            'is_verif_bkk'     => $isVerifBkk,
            'can_verify_bkk'   => $isVerifBkk || $isSuperUser,
            'can_manage_verif' => $isSuperUser,
            'no_hp'            => $verifNoHp,
        ];
    }

    private function canViewRecord(array $row, array $ctx): bool
    {
        if ($ctx['is_super_user'] || $ctx['is_verif_bkk']) {
            return true;
        }
        return (int) ($row['created_by'] ?? 0) === $ctx['id_user'];
    }

    private function canEditRecord(array $row, array $ctx): bool
    {
        if ($ctx['is_super_user']) {
            return true;
        }
        if ((int) ($row['created_by'] ?? 0) !== $ctx['id_user']) {
            return false;
        }
        return in_array((string) ($row['status_pengajuan'] ?? 'Draft'), ['Draft', 'Perbaikan BKK'], true);
    }

    private function getStorageDir(): string
    {
        $dir = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'file-tracing-tb' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        return $dir;
    }

    private function getTemplateDir(): string
    {
        $dir = rtrim(ROOTPATH, '/\\') . DIRECTORY_SEPARATOR . 'template doc tracing tb' . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        return $dir;
    }

    /**
     * Pindai semua file template yang tersimpan di folder "template doc tracing tb" (termasuk subfolder).
     */
    private function listTemplateFiles(): array
    {
        $baseDir = $this->getTemplateDir();
        $realBase = realpath($baseDir);
        if ($realBase === false || !is_dir($realBase)) {
            return [];
        }

        $ignored = ['.ds_store', 'thumbs.db', 'desktop.ini', '.gitkeep', 'index.html'];
        $files = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($realBase, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }
            $name = $fileInfo->getFilename();
            if ($name === '' || $name[0] === '.' || substr($name, 0, 2) === '~$' || in_array(strtolower($name), $ignored, true)) {
                continue;
            }

            $fullPath = $fileInfo->getRealPath();
            if ($fullPath === false) {
                continue;
            }

            $rel = ltrim(str_replace('\\', '/', substr($fullPath, strlen($realBase))), '/');
            $dirPart = dirname($rel);
            $folder = ($dirPart === '.' || $dirPart === '') ? 'Umum' : $dirPart;
            $size = (int) $fileInfo->getSize();
            $ext = strtolower($fileInfo->getExtension());

            if ($size >= 1048576) {
                $sizeLabel = number_format($size / 1048576, 1) . ' MB';
            } elseif ($size >= 1024) {
                $sizeLabel = number_format($size / 1024, 0) . ' KB';
            } else {
                $sizeLabel = $size . ' B';
            }

            $files[] = [
                'rel_path'     => $rel,
                'name'         => $name,
                'folder'       => $folder,
                'ext'          => $ext,
                'size_bytes'   => $size,
                'size_label'   => $sizeLabel,
                'download_url' => base_url('sinadi/tracing-tb/download-template?file=' . rawurlencode($rel)),
            ];
        }

        usort($files, static function (array $a, array $b): int {
            $cmpFolder = strcasecmp($a['folder'], $b['folder']);
            return $cmpFolder !== 0 ? $cmpFolder : strcasecmp($a['name'], $b['name']);
        });

        return $files;
    }

    /**
     * Download satu file template dari folder "template doc tracing tb".
     */
    public function downloadTemplate()
    {
        $relPath = trim((string) ($this->request->getGet('file') ?? ''));
        if ($relPath === '' || strpos($relPath, "\0") !== false) {
            return redirect()->back()->with('error', 'Nama file template tidak valid.');
        }

        $baseDir = $this->getTemplateDir();
        $realBase = realpath($baseDir);
        if ($realBase === false) {
            return redirect()->back()->with('error', 'Folder template tidak ditemukan.');
        }

        $normalizedRel = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim($relPath, '/\\'));
        $targetReal = realpath($realBase . DIRECTORY_SEPARATOR . $normalizedRel);

        if (
            $targetReal === false
            || !is_file($targetReal)
            || !is_readable($targetReal)
            || stripos($targetReal, $realBase . DIRECTORY_SEPARATOR) !== 0
        ) {
            return redirect()->back()->with('error', 'File template tidak ditemukan.');
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $filename = basename($targetReal);
        $filesize = (int) filesize($targetReal);
        $fileObj = new \CodeIgniter\Files\File($targetReal, true);
        $mime = $fileObj->getMimeType() ?: 'application/octet-stream';

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code(200);
        header('Content-Type: ' . $mime, true);
        header('Content-Disposition: attachment; filename="' . str_replace(['"', "\r", "\n"], '', $filename) . '"', true);
        header('Content-Length: ' . $filesize, true);
        header('Cache-Control: private, max-age=300', true);

        if ($filesize > 0 && strtolower($this->request->getMethod()) !== 'head') {
            $fp = fopen($targetReal, 'rb');
            if ($fp !== false) {
                while (!feof($fp)) {
                    $chunk = fread($fp, 65536);
                    if ($chunk === false || $chunk === '') {
                        break;
                    }
                    echo $chunk;
                    flush();
                }
                fclose($fp);
            }
        }
        exit;
    }

    /**
     * Download semua file template di folder "template doc tracing tb" sekaligus sebagai arsip ZIP.
     */
    public function downloadAllTemplatesZip()
    {
        $files = $this->listTemplateFiles();
        if (empty($files)) {
            return redirect()->back()->with('error', 'Belum ada file template di folder template doc tracing tb.');
        }
        if (!class_exists('\ZipArchive')) {
            return redirect()->back()->with('error', 'Ekstensi ZipArchive tidak tersedia di server.');
        }

        $realBase = realpath($this->getTemplateDir());
        $tempZip = tempnam(sys_get_temp_dir(), 'tb_tpl_');
        $zip = new \ZipArchive();
        if ($zip->open($tempZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            @unlink($tempZip);
            return redirect()->back()->with('error', 'Gagal membuat file ZIP template.');
        }

        foreach ($files as $f) {
            $fullPath = $realBase . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $f['rel_path']);
            if (is_file($fullPath) && is_readable($fullPath)) {
                $zip->addFile($fullPath, $f['rel_path']);
            }
        }
        $zip->close();

        if (!is_file($tempZip) || filesize($tempZip) === 0) {
            @unlink($tempZip);
            return redirect()->back()->with('error', 'Arsip ZIP template kosong.');
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code(200);
        header('Content-Type: application/zip', true);
        header('Content-Disposition: attachment; filename="Template_Dokumen_Tracing_TB.zip"', true);
        header('Content-Length: ' . filesize($tempZip), true);
        readfile($tempZip);
        @unlink($tempZip);
        exit;
    }

    private function getDokumenIndexed(int $idTracingTb): array
    {
        $rows = $this->dokumenModel->where('id_tracing_tb', $idTracingTb)->findAll();
        $indexed = [];
        foreach ($rows as $r) {
            $indexed[$r['kode_dokumen']] = $r;
        }
        return $indexed;
    }

    /**
     * Halaman utama daftar pengajuan & verifikasi Tracing TB.
     */
    public function index()
    {
        $ctx = $this->getUserContext();
        $statusFilter = trim((string) ($this->request->getGet('status') ?? ''));

        $builder = $this->tracingTbModel
            ->select('sinadi_tracing_tb.*, user.nama AS nama_pengaju')
            ->join('user', 'user.id_user = sinadi_tracing_tb.created_by', 'left');

        if (!$ctx['is_super_user'] && !$ctx['is_verif_bkk']) {
            $builder->where('sinadi_tracing_tb.created_by', $ctx['id_user']);
        } elseif (!$ctx['is_super_user']) {
            // Verifikator BKK melihat semua yang sudah diajukan + draft miliknya sendiri
            $builder->groupStart()
                ->where('sinadi_tracing_tb.status_pengajuan !=', 'Draft')
                ->orWhere('sinadi_tracing_tb.created_by', $ctx['id_user'])
                ->groupEnd();
        }

        $allRows = $builder->orderBy('sinadi_tracing_tb.created_at', 'DESC')->findAll();

        // Hitung statistik dokumen yang terunggah per pengajuan (single query)
        $docCounts = [];
        if (!empty($allRows)) {
            $ids = array_column($allRows, 'id_tracing_tb');
            $rawCounts = $this->dokumenModel->builder()
                ->select('id_tracing_tb, COUNT(CASE WHEN nama_file IS NOT NULL AND nama_file != "" THEN 1 END) AS uploaded_count, SUM(checklist_bkk) AS checked_bkk')
                ->whereIn('id_tracing_tb', $ids)
                ->groupBy('id_tracing_tb')
                ->get()
                ->getResultArray();
            foreach ($rawCounts as $rc) {
                $docCounts[(int) $rc['id_tracing_tb']] = [
                    'uploaded'    => (int) ($rc['uploaded_count'] ?? 0),
                    'checked_bkk' => (int) ($rc['checked_bkk'] ?? 0),
                ];
            }
        }

        $summary = [
            'total'        => count($allRows),
            'menunggu_bkk' => 0,
            'perbaikan'    => 0,
            'acc_bkk'      => 0,
        ];

        $filteredRows = [];
        foreach ($allRows as $row) {
            $st = (string) ($row['status_pengajuan'] ?? 'Draft');
            if ($st === 'Diajukan') {
                $summary['menunggu_bkk']++;
            } elseif ($st === 'Perbaikan BKK') {
                $summary['perbaikan']++;
            } elseif ($st === 'ACC BKK') {
                $summary['acc_bkk']++;
            }

            if ($statusFilter !== '' && $st !== $statusFilter) {
                continue;
            }
            $id = (int) $row['id_tracing_tb'];
            $row['doc_stats'] = $docCounts[$id] ?? ['uploaded' => 0, 'checked_bkk' => 0];
            $row['can_edit'] = $this->canEditRecord($row, $ctx);
            $filteredRows[] = $row;
        }

        $totalDocCatalog = count(SinadiTracingTbSchema::getDokumenMap());

        // Daftar Verifikator BKK aktif untuk info ringkas
        $verifikatorList = $this->verifikatorModel
            ->select('sinadi_tracing_tb_verifikator.*, user.nama, data_pegawai.nip')
            ->join('user', 'user.id_user = sinadi_tracing_tb_verifikator.id_user', 'left')
            ->join('data_pegawai', 'data_pegawai.id_user = sinadi_tracing_tb_verifikator.id_user', 'left')
            ->where('sinadi_tracing_tb_verifikator.peran', 'verifikator_bkk')
            ->orderBy('user.nama', 'ASC')
            ->findAll();

        $verifBkkNames = [];
        foreach ($verifikatorList as $v) {
            $nama = trim((string) ($v['nama'] ?? ''));
            if ($nama !== '') {
                $verifBkkNames[] = $nama;
            }
        }

        return view('sinadi/tracing_tb/index', [
            'title'           => 'Sinadi | Tracing TB',
            'activeMenu'      => 'sinadiTracingTb',
            'ctx'             => $ctx,
            'rows'            => $filteredRows,
            'summary'         => $summary,
            'statusFilter'    => $statusFilter,
            'totalDocCatalog' => $totalDocCatalog,
            'verifBkkNames'   => $verifBkkNames,
            'templateFiles'   => $this->listTemplateFiles(),
        ]);
    }

    /**
     * Form input Tracing TB baru.
     */
    public function create()
    {
        $ctx = $this->getUserContext();
        $accounts = SinadiTracingTbSchema::getAccounts();
        $rincian = SinadiTracingTbSchema::buildRincianAnggaran(null, false);

        return view('sinadi/tracing_tb/form', [
            'title'         => 'Sinadi | Input Tracing TB',
            'activeMenu'    => 'sinadiTracingTb',
            'mode'          => 'create',
            'ctx'           => $ctx,
            'accounts'      => $accounts,
            'record'        => [
                'id_tracing_tb'    => 0,
                'lokus'            => '',
                'instansi'         => SinadiTracingTbSchema::DEFAULT_INSTANSI,
                'tanggal_kegiatan' => date('Y-m-d'),
                'kode_kegiatan'    => SinadiTracingTbSchema::DEFAULT_KODE_KEGIATAN,
                'nama_kegiatan'    => SinadiTracingTbSchema::DEFAULT_NAMA_KEGIATAN,
                'non_radiografer'  => 0,
                'catatan_pengaju'  => '',
                'nama_pengaju_ttd' => $ctx['nama'],
                'tgl_ttd_pengaju'  => date('Y-m-d'),
                'ttd_pengaju'      => '',
                'status_pengajuan' => 'Draft',
            ],
            'rincian'       => $rincian,
            'dokumenMap'    => [],
            'lokusList'     => SinadiTracingTbSchema::getLokusList(),
            'templateFiles' => $this->listTemplateFiles(),
        ]);
    }

    /**
     * Form edit Tracing TB & manajemen unggah dokumen.
     */
    public function edit($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel->find((int) $id);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data Tracing TB tidak ditemukan.');
        }
        if (!$this->canEditRecord($row, $ctx)) {
            return redirect()->to('/sinadi/tracing-tb/detail/' . (int) $id)
                ->with('error', 'Anda tidak memiliki izin mengedit data pada status ini.');
        }

        $decoded = !empty($row['rincian_anggaran']) ? json_decode((string) $row['rincian_anggaran'], true) : null;
        $savedItems = is_array($decoded) && isset($decoded['items']) ? $decoded['items'] : null;
        $rincian = SinadiTracingTbSchema::buildRincianAnggaran($savedItems, !empty($row['non_radiografer']));

        return view('sinadi/tracing_tb/form', [
            'title'         => 'Sinadi | Edit Tracing TB',
            'activeMenu'    => 'sinadiTracingTb',
            'mode'          => 'edit',
            'ctx'           => $ctx,
            'accounts'      => SinadiTracingTbSchema::getAccounts(),
            'record'        => $row,
            'rincian'       => $rincian,
            'dokumenMap'    => $this->getDokumenIndexed((int) $id),
            'lokusList'     => SinadiTracingTbSchema::getLokusList(),
            'templateFiles' => $this->listTemplateFiles(),
        ]);
    }

    /**
     * Simpan data Tracing TB (Create / Update). Mendukung AJAX JSON dan form POST biasa.
     */
    public function save()
    {
        $ctx = $this->getUserContext();
        $isAjax = $this->request->isAJAX();

        $id = (int) ($this->request->getPost('id_tracing_tb') ?? 0);
        $lokus = trim((string) ($this->request->getPost('lokus') ?? ''));
        $instansi = trim((string) ($this->request->getPost('instansi') ?? SinadiTracingTbSchema::DEFAULT_INSTANSI));
        $tanggalKegiatan = trim((string) ($this->request->getPost('tanggal_kegiatan') ?? ''));
        $kodeKegiatan = trim((string) ($this->request->getPost('kode_kegiatan') ?? SinadiTracingTbSchema::DEFAULT_KODE_KEGIATAN));
        $namaKegiatan = trim((string) ($this->request->getPost('nama_kegiatan') ?? SinadiTracingTbSchema::DEFAULT_NAMA_KEGIATAN));
        $catatanPengaju = trim((string) ($this->request->getPost('catatan_pengaju') ?? ''));
        $namaPengajuTtd = trim((string) ($this->request->getPost('nama_pengaju_ttd') ?? ''));
        $tglTtdPengaju = trim((string) ($this->request->getPost('tgl_ttd_pengaju') ?? ''));
        $ttdPengajuBaru = trim((string) ($this->request->getPost('ttd_pengaju') ?? ''));
        $hapusTtdPengaju = ((string) ($this->request->getPost('hapus_ttd_pengaju') ?? '0') === '1');
        $nonRadiografer = (int) ($this->request->getPost('non_radiografer') ? 1 : 0);
        $submitAction = trim((string) ($this->request->getPost('submit_action') ?? 'draft'));

        if ($lokus === '' || $tanggalKegiatan === '') {
            $errMsg = 'Lokus dan Tanggal Kegiatan wajib diisi.';
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $errMsg]);
            }
            return redirect()->back()->withInput()->with('error', $errMsg);
        }

        $existing = null;
        if ($id > 0) {
            $existing = $this->tracingTbModel->find($id);
            if (!$existing) {
                $errMsg = 'Data Tracing TB tidak ditemukan.';
                if ($isAjax) {
                    return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => $errMsg]);
                }
                return redirect()->to('/sinadi/tracing-tb')->with('error', $errMsg);
            }
            if (!$this->canEditRecord($existing, $ctx)) {
                $errMsg = 'Anda tidak memiliki izin mengubah data ini.';
                if ($isAjax) {
                    return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => $errMsg]);
                }
                return redirect()->to('/sinadi/tracing-tb/detail/' . $id)->with('error', $errMsg);
            }
        }

        $hasNewTtd = ($ttdPengajuBaru !== '' && strpos($ttdPengajuBaru, 'data:image/') === 0);
        $hasExistingTtd = (!$hapusTtdPengaju && !empty($existing['ttd_pengaju']));

        if ($submitAction === 'ajukan' && !$hasNewTtd && !$hasExistingTtd) {
            $errMsg = 'Tanda tangan pengaju wajib diisi sebelum mengajukan verifikasi.';
            if ($isAjax) {
                return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $errMsg]);
            }
            return redirect()->back()->withInput()->with('error', $errMsg);
        }

        $postAnggaran = $this->request->getPost('anggaran');
        $rincian = SinadiTracingTbSchema::buildRincianAnggaran(
            is_array($postAnggaran) ? $postAnggaran : null,
            $nonRadiografer === 1
        );

        $statusPengajuan = $existing ? (string) $existing['status_pengajuan'] : 'Draft';
        if ($submitAction === 'ajukan') {
            $statusPengajuan = 'Diajukan';
        } elseif ($submitAction === 'draft' && !$existing) {
            $statusPengajuan = 'Draft';
        }

        $payload = [
            'lokus'                  => $lokus,
            'instansi'               => $instansi !== '' ? $instansi : SinadiTracingTbSchema::DEFAULT_INSTANSI,
            'tanggal_kegiatan'       => $tanggalKegiatan,
            'kode_kegiatan'          => $kodeKegiatan !== '' ? $kodeKegiatan : SinadiTracingTbSchema::DEFAULT_KODE_KEGIATAN,
            'nama_kegiatan'          => $namaKegiatan !== '' ? $namaKegiatan : SinadiTracingTbSchema::DEFAULT_NAMA_KEGIATAN,
            'non_radiografer'        => $nonRadiografer,
            'rincian_anggaran'       => json_encode($rincian, JSON_UNESCAPED_UNICODE),
            'total_jasa_profesi'     => $rincian['subtotals']['522151'],
            'total_perjalanan_dinas' => $rincian['subtotals']['524111'],
            'total_belanja_bahan'    => $rincian['subtotals']['521211'],
            'total_anggaran'         => $rincian['grand_total'],
            'catatan_pengaju'        => $catatanPengaju,
            'nama_pengaju_ttd'       => $namaPengajuTtd !== '' ? $namaPengajuTtd : $ctx['nama'],
            'tgl_ttd_pengaju'        => $tglTtdPengaju !== '' ? $tglTtdPengaju : date('Y-m-d'),
            'status_pengajuan'       => $statusPengajuan,
            'updated_by'             => $ctx['id_user'],
        ];

        if ($hasNewTtd) {
            $payload['ttd_pengaju'] = $ttdPengajuBaru;
        } elseif ($hapusTtdPengaju) {
            $payload['ttd_pengaju'] = null;
        }

        // Jika diajukan ulang setelah perbaikan BKK, reset keputusan BKK agar diverifikasi ulang
        if ($submitAction === 'ajukan' && $existing && ($existing['status_pengajuan'] ?? '') === 'Perbaikan BKK') {
            $payload['status_verif_bkk'] = null;
        }

        if ($id > 0) {
            $this->tracingTbModel->update($id, $payload);
        } else {
            $payload['created_by'] = $ctx['id_user'];
            $id = (int) $this->tracingTbModel->insert($payload, true);
        }

        // Fallback: proses file yang ikut terkirim langsung via multipart POST (non-AJAX)
        $uploadedWarnings = [];
        $files = $this->request->getFiles();
        if (!empty($files['dokumen']) && is_array($files['dokumen'])) {
            $catalog = SinadiTracingTbSchema::getDokumenMap();
            foreach ($files['dokumen'] as $kodeDok => $fileObj) {
                if (!isset($catalog[$kodeDok]) || !$fileObj || $fileObj->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $res = $this->storeSingleDocument($id, $kodeDok, $fileObj);
                if (!$res['ok']) {
                    $uploadedWarnings[] = $catalog[$kodeDok]['nama_dokumen'] . ': ' . $res['message'];
                }
            }
        }

        $msg = ($submitAction === 'ajukan')
            ? 'Data Tracing TB berhasil disimpan dan diajukan ke Verifikator.'
            : 'Data Tracing TB berhasil disimpan sebagai Draft.';

        if (!empty($uploadedWarnings)) {
            $msg .= ' Catatan unggahan: ' . implode('; ', $uploadedWarnings);
        }

        if ($isAjax) {
            return $this->response->setJSON([
                'status'        => 'success',
                'id_tracing_tb' => $id,
                'status_pengajuan' => $payload['status_pengajuan'],
                'message'       => $msg,
                'redirect_url'  => base_url('sinadi/tracing-tb/detail/' . $id),
                'edit_url'      => base_url('sinadi/tracing-tb/edit/' . $id),
            ]);
        }

        return redirect()->to('/sinadi/tracing-tb/detail/' . $id)->with('pesan', $msg);
    }

    /**
     * Endpoint AJAX unggah satu file dokumen (menghindari batas max_file_uploads=20 PHP).
     */
    public function uploadDokumen()
    {
        $ctx = $this->getUserContext();
        $idTracingTb = (int) ($this->request->getPost('id_tracing_tb') ?? 0);
        $kodeDokumen = trim((string) ($this->request->getPost('kode_dokumen') ?? ''));

        $row = $this->tracingTbModel->find($idTracingTb);
        if (!$row) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Data Tracing TB tidak ditemukan.']);
        }
        if (!$this->canEditRecord($row, $ctx)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Anda tidak memiliki izin mengunggah dokumen pada data ini.']);
        }

        $catalog = SinadiTracingTbSchema::getDokumenMap();
        if (!isset($catalog[$kodeDokumen])) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'Kode dokumen tidak dikenali.']);
        }

        $file = $this->request->getFile('file');
        if (!$file) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => 'File tidak ditemukan dalam permintaan.']);
        }

        $stored = $this->storeSingleDocument($idTracingTb, $kodeDokumen, $file);
        if (!$stored['ok']) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error', 'message' => $stored['message']]);
        }

        return $this->response->setJSON([
            'status'      => 'success',
            'message'     => 'Dokumen berhasil diunggah.',
            'dokumen'     => $stored['dokumen'],
            'preview_url' => base_url('sinadi/tracing-tb/preview-dokumen/' . $stored['dokumen']['id_dokumen']),
        ]);
    }

    private function storeSingleDocument(int $idTracingTb, string $kodeDokumen, $file): array
    {
        if (!$file->isValid()) {
            return ['ok' => false, 'message' => 'Upload gagal: ' . $file->getErrorString()];
        }
        if ($file->hasMoved()) {
            return ['ok' => false, 'message' => 'File sudah dipindahkan sebelumnya.'];
        }
        if ($file->getSize() > self::MAX_FILE_BYTES) {
            return ['ok' => false, 'message' => 'Ukuran file melebihi batas maksimal 15 MB.'];
        }

        $ext = strtolower((string) $file->getClientExtension());
        if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
            return ['ok' => false, 'message' => 'Format file tidak didukung. Hanya file PDF (.pdf) yang diperbolehkan.'];
        }

        $tmpPath = (string) $file->getTempName();
        if ($tmpPath !== '' && is_readable($tmpPath)) {
            $fp = @fopen($tmpPath, 'rb');
            $sig = $fp !== false ? fread($fp, 5) : '';
            if ($fp !== false) {
                fclose($fp);
            }
            if ($sig !== '%PDF-') {
                return ['ok' => false, 'message' => 'File tidak valid sebagai dokumen PDF. Pastikan file benar-benar berformat PDF.'];
            }
        }

        $catalog = SinadiTracingTbSchema::getDokumenMap();
        $meta = $catalog[$kodeDokumen];

        $storageDir = $this->getStorageDir();
        $rand = substr(sha1(uniqid((string) mt_rand(), true)), 0, 8);
        $newName = 'tb_' . $idTracingTb . '_' . $kodeDokumen . '_' . date('YmdHis') . '_' . $rand . '.' . $ext;
        $origName = mb_substr((string) $file->getClientName(), 0, 240);
        $fileSize = (int) $file->getSize();
        $mimeType = (string) ($file->getClientMimeType() ?: 'application/octet-stream');

        $file->move($storageDir, $newName);

        $existingDoc = $this->dokumenModel
            ->where('id_tracing_tb', $idTracingTb)
            ->where('kode_dokumen', $kodeDokumen)
            ->first();

        if ($existingDoc) {
            if (!empty($existingDoc['nama_file'])) {
                $oldPath = $storageDir . basename((string) $existingDoc['nama_file']);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $this->dokumenModel->update((int) $existingDoc['id_dokumen'], [
                'nama_file'      => $newName,
                'nama_file_asli' => $origName,
                'ukuran_file'    => $fileSize,
                'mime_type'      => $mimeType,
            ]);
            $docId = (int) $existingDoc['id_dokumen'];
        } else {
            $docId = (int) $this->dokumenModel->insert([
                'id_tracing_tb'  => $idTracingTb,
                'kode_akun'      => $meta['kode_akun'],
                'kode_dokumen'   => $kodeDokumen,
                'nama_dokumen'   => $meta['nama_dokumen'],
                'kategori_wajib' => $meta['kategori_wajib'],
                'nama_file'      => $newName,
                'nama_file_asli' => $origName,
                'ukuran_file'    => $fileSize,
                'mime_type'      => $mimeType,
            ], true);
        }

        return [
            'ok'      => true,
            'dokumen' => [
                'id_dokumen'     => $docId,
                'kode_dokumen'   => $kodeDokumen,
                'nama_file'      => $newName,
                'nama_file_asli' => $origName,
                'ukuran_file'    => $fileSize,
                'mime_type'      => $mimeType,
            ],
        ];
    }

    /**
     * Hapus file fisik pada satu baris dokumen (tanpa menghapus catatan verifikasi jika ada).
     */
    public function hapusDokumen($idDokumen)
    {
        $ctx = $this->getUserContext();
        $doc = $this->dokumenModel->find((int) $idDokumen);
        if (!$doc) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Dokumen tidak ditemukan.']);
        }
        $parent = $this->tracingTbModel->find((int) $doc['id_tracing_tb']);
        if (!$parent || !$this->canEditRecord($parent, $ctx)) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Tidak memiliki izin menghapus file dokumen ini.']);
        }

        if (!empty($doc['nama_file'])) {
            $fullPath = $this->getStorageDir() . basename((string) $doc['nama_file']);
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }

        $this->dokumenModel->update((int) $idDokumen, [
            'nama_file'      => null,
            'nama_file_asli' => null,
            'ukuran_file'    => 0,
            'mime_type'      => null,
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'File dokumen berhasil dihapus.',
        ]);
    }

    /**
     * Streaming preview / download file dokumen dengan pelepasan session lock & HTTP Range.
     */
    public function previewDokumen($idDokumen)
    {
        $ctx = $this->getUserContext();
        $doc = $this->dokumenModel->find((int) $idDokumen);
        if (!$doc || empty($doc['nama_file'])) {
            return $this->response->setStatusCode(404)->setBody('Dokumen tidak ditemukan.');
        }

        $parent = $this->tracingTbModel->find((int) $doc['id_tracing_tb']);
        if (!$parent || !$this->canViewRecord($parent, $ctx)) {
            return $this->response->setStatusCode(403)->setBody('Akses ditolak.');
        }

        $filename = basename((string) $doc['nama_file']);
        $fullpath = $this->getStorageDir() . $filename;
        if (!is_file($fullpath) || !is_readable($fullpath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan di server.');
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $filesize = (int) filesize($fullpath);
        $mtime = filemtime($fullpath) ?: time();
        $fileObj = new \CodeIgniter\Files\File($fullpath, true);
        $mime = $fileObj->getMimeType() ?: ($doc['mime_type'] ?: 'application/octet-stream');
        $downloadMode = ((string) $this->request->getGet('download') === '1');
        $inlineTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $disposition = (!$downloadMode && in_array($mime, $inlineTypes, true)) ? 'inline' : 'attachment';
        $displayName = str_replace(['"', "\r", "\n"], '', (string) ($doc['nama_file_asli'] ?: $filename));

        $etag = '"' . md5($filename . '|' . $filesize . '|' . $mtime) . '"';
        $lastModifiedHttp = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        $rangeHeader = $this->request->getServer('HTTP_RANGE');
        $ifNoneMatch = $this->request->getServer('HTTP_IF_NONE_MATCH');

        header_remove('Pragma');
        header_remove('Expires');
        header('Cache-Control: private, max-age=3600, must-revalidate', true);
        header('ETag: ' . $etag, true);
        header('Last-Modified: ' . $lastModifiedHttp, true);
        header('Accept-Ranges: bytes', true);

        if (empty($rangeHeader) && !empty($ifNoneMatch) && trim((string) $ifNoneMatch) === $etag) {
            http_response_code(304);
            exit;
        }

        $start = 0;
        $end = $filesize > 0 ? $filesize - 1 : 0;
        $statusCode = 200;

        if ($filesize > 0 && !empty($rangeHeader) && preg_match('/^bytes=(\d*)-(\d*)$/i', trim((string) $rangeHeader), $m)) {
            $rStart = $m[1];
            $rEnd = $m[2];
            if ($rStart === '' && $rEnd !== '') {
                $start = max(0, $filesize - (int) $rEnd);
                $end = $filesize - 1;
            } elseif ($rStart !== '') {
                $start = (int) $rStart;
                $end = ($rEnd !== '') ? min($filesize - 1, (int) $rEnd) : ($filesize - 1);
            }
            if ($start < 0 || $start > $end || $start >= $filesize) {
                http_response_code(416);
                header('Content-Range: bytes */' . $filesize, true);
                exit;
            }
            $statusCode = 206;
        }

        $length = $filesize > 0 ? ($end - $start + 1) : 0;
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code($statusCode);
        header('Content-Type: ' . $mime, true);
        header('Content-Disposition: ' . $disposition . '; filename="' . $displayName . '"', true);
        header('Content-Length: ' . $length, true);
        if ($statusCode === 206) {
            header("Content-Range: bytes {$start}-{$end}/{$filesize}", true);
        }

        if ($length === 0 || strtolower($this->request->getMethod()) === 'head') {
            exit;
        }

        @set_time_limit(0);
        $fp = fopen($fullpath, 'rb');
        if ($fp === false) {
            http_response_code(500);
            exit;
        }
        if ($start > 0) {
            fseek($fp, $start);
        }
        $remaining = $length;
        while ($remaining > 0 && !feof($fp)) {
            if (connection_status() !== CONNECTION_NORMAL) {
                break;
            }
            $chunk = fread($fp, min(65536, $remaining));
            if ($chunk === false || $chunk === '') {
                break;
            }
            echo $chunk;
            $remaining -= strlen($chunk);
            flush();
        }
        fclose($fp);
        exit;
    }

    /**
     * Halaman Detail & Verifikasi Checklist (Verifikator BKK).
     */
    public function detail($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel
            ->select('sinadi_tracing_tb.*, user.nama AS nama_pengaju')
            ->join('user', 'user.id_user = sinadi_tracing_tb.created_by', 'left')
            ->where('sinadi_tracing_tb.id_tracing_tb', (int) $id)
            ->first();

        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data Tracing TB tidak ditemukan.');
        }
        if (!$this->canViewRecord($row, $ctx)) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Anda tidak memiliki akses melihat data ini.');
        }

        $decoded = !empty($row['rincian_anggaran']) ? json_decode((string) $row['rincian_anggaran'], true) : null;
        $savedItems = is_array($decoded) && isset($decoded['items']) ? $decoded['items'] : null;
        $rincian = SinadiTracingTbSchema::buildRincianAnggaran($savedItems, !empty($row['non_radiografer']));

        return view('sinadi/tracing_tb/detail', [
            'title'        => 'Sinadi | Verifikasi Tracing TB',
            'activeMenu'   => 'sinadiTracingTb',
            'ctx'          => $ctx,
            'record'       => $row,
            'canEdit'      => $this->canEditRecord($row, $ctx),
            'canVerifyBkk' => !empty($ctx['can_verify_bkk']),
            'accounts'     => SinadiTracingTbSchema::getAccounts(),
            'rincian'      => $rincian,
            'dokumenMap'   => $this->getDokumenIndexed((int) $id),
        ]);
    }

    /**
     * Simpan hasil pemeriksaan Verifikator BKK beserta checklist & catatan per dokumen.
     */
    public function simpanVerifikasi($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel->find((int) $id);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data Tracing TB tidak ditemukan.');
        }

        if (!$ctx['can_verify_bkk']) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai Verifikator BKK Tracing TB.');
        }

        $keputusan = strtoupper(trim((string) ($this->request->getPost('keputusan') ?? '')));
        if (!in_array($keputusan, ['ACC', 'PERBAIKAN'], true)) {
            return redirect()->back()->with('error', 'Pilih keputusan verifikasi (ACC atau Perbaikan).');
        }

        $tglVerif = trim((string) ($this->request->getPost('tgl_verif') ?? date('Y-m-d')));
        $namaVerif = trim((string) ($this->request->getPost('nama_verif') ?? $ctx['nama']));
        $noHpVerif = trim((string) ($this->request->getPost('no_hp_verif') ?? $ctx['no_hp']));
        $catatanUmum = trim((string) ($this->request->getPost('catatan_umum') ?? ''));
        $parafBaru = trim((string) ($this->request->getPost('paraf_data') ?? ''));

        $checklistInput = $this->request->getPost('checklist');
        $catatanDocInput = $this->request->getPost('catatan_dokumen');
        $checklistArr = is_array($checklistInput) ? $checklistInput : [];
        $catatanDocArr = is_array($catatanDocInput) ? $catatanDocInput : [];

        $catalog = SinadiTracingTbSchema::getDokumenMap();
        $existingDocs = $this->getDokumenIndexed((int) $id);

        foreach ($catalog as $kodeDok => $meta) {
            $checked = isset($checklistArr[$kodeDok]) ? 1 : 0;
            $note = trim((string) ($catatanDocArr[$kodeDok] ?? ''));

            if (isset($existingDocs[$kodeDok])) {
                $this->dokumenModel->update((int) $existingDocs[$kodeDok]['id_dokumen'], [
                    'checklist_bkk' => $checked,
                    'catatan_bkk'   => $note,
                ]);
            } elseif ($checked === 1 || $note !== '') {
                $this->dokumenModel->insert([
                    'id_tracing_tb'  => (int) $id,
                    'kode_akun'      => $meta['kode_akun'],
                    'kode_dokumen'   => $kodeDok,
                    'nama_dokumen'   => $meta['nama_dokumen'],
                    'kategori_wajib' => $meta['kategori_wajib'],
                    'checklist_bkk'  => $checked,
                    'catatan_bkk'    => $note,
                ]);
            }
        }

        $updateHeader = [
            'updated_by'        => $ctx['id_user'],
            'status_verif_bkk'  => $keputusan,
            'tgl_verif_bkk'     => $tglVerif !== '' ? $tglVerif : date('Y-m-d'),
            'nama_verif_bkk'    => $namaVerif !== '' ? $namaVerif : $ctx['nama'],
            'no_hp_verif_bkk'   => $noHpVerif,
            'catatan_verif_bkk' => $catatanUmum,
            'id_verif_bkk'      => $ctx['id_user'],
            'status_pengajuan'  => ($keputusan === 'ACC') ? 'ACC BKK' : 'Perbaikan BKK',
        ];

        if ($parafBaru !== '' && strpos($parafBaru, 'data:image/') === 0) {
            $updateHeader['paraf_verif_bkk'] = $parafBaru;
        }

        $this->tracingTbModel->update((int) $id, $updateHeader);

        return redirect()->to('/sinadi/tracing-tb/detail/' . (int) $id)
            ->with('pesan', "Hasil pemeriksaan Verifikator BKK ({$keputusan}) berhasil disimpan.");
    }

    /**
     * Ajukan pengajuan Draft / Perbaikan ke antrean verifikasi BKK.
     */
    public function ajukan($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel->find((int) $id);
        if (!$row || !$this->canEditRecord($row, $ctx)) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data tidak dapat diajukan.');
        }

        if (empty($row['ttd_pengaju'])) {
            return redirect()->to('/sinadi/tracing-tb/edit/' . (int) $id)
                ->with('error', 'Silakan lengkapi tanda tangan pengaju terlebih dahulu sebelum mengajukan verifikasi.');
        }

        $update = [
            'status_pengajuan' => 'Diajukan',
            'updated_by'       => $ctx['id_user'],
        ];
        if (($row['status_pengajuan'] ?? '') === 'Perbaikan BKK') {
            $update['status_verif_bkk'] = null;
        }

        $this->tracingTbModel->update((int) $id, $update);
        return redirect()->to('/sinadi/tracing-tb/detail/' . (int) $id)
            ->with('pesan', 'Pengajuan Tracing TB berhasil dikirim untuk verifikasi BKK.');
    }

    /**
     * Hapus pengajuan beserta seluruh file fisiknya.
     */
    public function hapus($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel->find((int) $id);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data tidak ditemukan.');
        }
        if (!$this->canEditRecord($row, $ctx)) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Anda tidak memiliki izin menghapus data ini.');
        }

        $docs = $this->dokumenModel->where('id_tracing_tb', (int) $id)->findAll();
        $storageDir = $this->getStorageDir();
        foreach ($docs as $d) {
            if (!empty($d['nama_file'])) {
                $path = $storageDir . basename((string) $d['nama_file']);
                if (is_file($path)) {
                    @unlink($path);
                }
            }
        }

        $this->dokumenModel->where('id_tracing_tb', (int) $id)->delete();
        $this->tracingTbModel->delete((int) $id);

        return redirect()->to('/sinadi/tracing-tb')->with('pesan', 'Data Tracing TB berhasil dihapus.');
    }

    /**
     * Siapkan data cetak / PDF Form Checklist Tracing TB.
     */
    private function buildPrintData(int $id, array $ctx): ?array
    {
        $row = $this->tracingTbModel
            ->select('sinadi_tracing_tb.*, user.nama AS nama_pengaju')
            ->join('user', 'user.id_user = sinadi_tracing_tb.created_by', 'left')
            ->where('sinadi_tracing_tb.id_tracing_tb', $id)
            ->first();

        if (!$row || !$this->canViewRecord($row, $ctx)) {
            return null;
        }

        $decoded = !empty($row['rincian_anggaran']) ? json_decode((string) $row['rincian_anggaran'], true) : null;
        $savedItems = is_array($decoded) && isset($decoded['items']) ? $decoded['items'] : null;
        $rincian = SinadiTracingTbSchema::buildRincianAnggaran($savedItems, !empty($row['non_radiografer']));

        $kopBkk = '';
        $kopPath = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'Kop_BKK_bandung.png';
        if (is_file($kopPath) && is_readable($kopPath)) {
            $kopRaw = file_get_contents($kopPath);
            if ($kopRaw !== false) {
                $kopBkk = 'data:image/png;base64,' . base64_encode($kopRaw);
            }
        }

        return [
            'record'     => $row,
            'accounts'   => SinadiTracingTbSchema::getAccounts(),
            'rincian'    => $rincian,
            'dokumenMap' => $this->getDokumenIndexed($id),
            'kopBkk'     => $kopBkk,
        ];
    }

    /**
     * Tampilan cetak Form Checklist Verifikasi sesuai format Excel.
     */
    public function cetak($id)
    {
        $ctx = $this->getUserContext();
        $data = $this->buildPrintData((int) $id, $ctx);
        if ($data === null) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $data['isPdf'] = false;
        return view('sinadi/tracing_tb/cetak', $data);
    }

    /**
     * Generate & download Form Checklist Verifikasi Tracing TB dalam format PDF (Dompdf).
     */
    public function downloadPdf($id)
    {
        $ctx = $this->getUserContext();
        $data = $this->buildPrintData((int) $id, $ctx);
        if ($data === null) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        $data['isPdf'] = true;
        $html = view('sinadi/tracing_tb/cetak', $data);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $cacheDir = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . 'cache';
        if (is_dir($cacheDir) && is_writable($cacheDir)) {
            $options->set('tempDir', $cacheDir);
        }

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfBytes = $dompdf->output();

        $lokusSlug = preg_replace('/[^A-Za-z0-9_-]+/', '_', trim((string) ($data['record']['lokus'] ?? 'Lokus')));
        $lokusSlug = trim((string) $lokusSlug, '_') ?: 'Lokus';
        $tglSlug = !empty($data['record']['tanggal_kegiatan'])
            ? date('Ymd', strtotime((string) $data['record']['tanggal_kegiatan']))
            : date('Ymd');
        $filename = 'Form_Checklist_Tracing_TB_' . mb_substr($lokusSlug, 0, 50) . '_' . $tglSlug . '.pdf';

        $disposition = ((string) $this->request->getGet('preview') === '1') ? 'inline' : 'attachment';

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code(200);
        header('Content-Type: application/pdf', true);
        header('Content-Disposition: ' . $disposition . '; filename="' . str_replace(['"', "\r", "\n"], '', $filename) . '"', true);
        header('Content-Length: ' . strlen($pdfBytes), true);
        header('Cache-Control: private, max-age=0, must-revalidate', true);

        echo $pdfBytes;
        exit;
    }

    /**
     * Download semua file dokumen yang telah diunggah pengaju pada satu pengajuan dalam format ZIP.
     */
    public function downloadDokumenZip($id)
    {
        $ctx = $this->getUserContext();
        $row = $this->tracingTbModel->find((int) $id);
        if (!$row || !$this->canViewRecord($row, $ctx)) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Data tidak ditemukan atau akses ditolak.');
        }

        if (!class_exists('\ZipArchive')) {
            return redirect()->back()->with('error', 'Ekstensi ZipArchive tidak tersedia di server.');
        }

        $dokumenMap = $this->getDokumenIndexed((int) $id);
        $accounts = SinadiTracingTbSchema::getAccounts();
        $storageDir = $this->getStorageDir();

        $tempZip = tempnam(sys_get_temp_dir(), 'tb_docs_');
        if ($tempZip === false) {
            return redirect()->back()->with('error', 'Gagal menyiapkan file ZIP sementara.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($tempZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            @unlink($tempZip);
            return redirect()->back()->with('error', 'Gagal membuat arsip ZIP dokumen.');
        }

        $addedCount = 0;
        foreach ($accounts as $kodeAkun => $akun) {
            $akunSlug = preg_replace('/[^A-Za-z0-9]+/', '_', (string) ($akun['nama'] ?? ''));
            $folderName = $kodeAkun . '_' . trim((string) $akunSlug, '_');

            $no = 1;
            foreach ($akun['dokumen'] as $doc) {
                $kodeDok = $doc['kode'];
                $saved = $dokumenMap[$kodeDok] ?? null;
                if (empty($saved['nama_file'])) {
                    $no++;
                    continue;
                }

                $physicalPath = $storageDir . basename((string) $saved['nama_file']);
                if (!is_file($physicalPath) || !is_readable($physicalPath)) {
                    $no++;
                    continue;
                }

                $ext = strtolower((string) pathinfo($physicalPath, PATHINFO_EXTENSION));
                if ($ext === '' && !empty($saved['nama_file_asli'])) {
                    $ext = strtolower((string) pathinfo((string) $saved['nama_file_asli'], PATHINFO_EXTENSION));
                }

                $docSlug = preg_replace('/[^A-Za-z0-9]+/', '_', (string) $doc['nama']);
                $docSlug = trim((string) $docSlug, '_');
                $docSlug = mb_substr($docSlug, 0, 75);
                $entryName = sprintf('%s/%02d_%s%s', $folderName, $no, $docSlug ?: $kodeDok, $ext !== '' ? '.' . $ext : '');

                if ($zip->addFile($physicalPath, $entryName)) {
                    $addedCount++;
                }
                $no++;
            }
        }

        $zip->close();

        if ($addedCount === 0 || !is_file($tempZip) || filesize($tempZip) === 0) {
            @unlink($tempZip);
            return redirect()->back()->with('error', 'Belum ada file dokumen yang diunggah pada pengajuan ini.');
        }

        $lokusSlug = preg_replace('/[^A-Za-z0-9_-]+/', '_', trim((string) ($row['lokus'] ?? 'Lokus')));
        $lokusSlug = trim((string) $lokusSlug, '_') ?: 'Lokus';
        $tglSlug = !empty($row['tanggal_kegiatan'])
            ? date('Ymd', strtotime((string) $row['tanggal_kegiatan']))
            : date('Ymd');
        $zipFilename = 'Dokumen_Tracing_TB_' . mb_substr($lokusSlug, 0, 50) . '_' . $tglSlug . '.zip';
        $filesize = (int) filesize($tempZip);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        @set_time_limit(0);
        http_response_code(200);
        header('Content-Type: application/zip', true);
        header('Content-Disposition: attachment; filename="' . str_replace(['"', "\r", "\n"], '', $zipFilename) . '"', true);
        header('Content-Length: ' . $filesize, true);
        header('Cache-Control: private, max-age=0, must-revalidate', true);

        $fp = fopen($tempZip, 'rb');
        if ($fp !== false) {
            while (!feof($fp)) {
                $chunk = fread($fp, 65536);
                if ($chunk === false || $chunk === '') {
                    break;
                }
                echo $chunk;
                flush();
            }
            fclose($fp);
        }
        @unlink($tempZip);
        exit;
    }

    /**
     * Manajemen Verifikator BKK Tracing TB.
     */
    public function verifikator()
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')
                ->with('error', 'Halaman Manajemen Verifikator hanya dapat diakses oleh Admin / Super User SINADI.');
        }

        $users = $this->userModel
            ->select('user.id_user, user.nama, user.username, user.no_telp AS no_hp, data_pegawai.id_data_pegawai, data_pegawai.nip')
            ->join('data_pegawai', 'data_pegawai.id_user = user.id_user', 'left')
            ->orderBy('user.nama', 'ASC')
            ->findAll();

        $verifBkk = $this->verifikatorModel
            ->select('sinadi_tracing_tb_verifikator.*, user.nama, user.username, user.no_telp AS pegawai_no_hp, data_pegawai.nip')
            ->join('user', 'user.id_user = sinadi_tracing_tb_verifikator.id_user', 'left')
            ->join('data_pegawai', 'data_pegawai.id_user = sinadi_tracing_tb_verifikator.id_user', 'left')
            ->where('sinadi_tracing_tb_verifikator.peran', 'verifikator_bkk')
            ->orderBy('user.nama', 'ASC')
            ->findAll();

        return view('sinadi/tracing_tb/verifikator', [
            'title'      => 'Sinadi | Manajemen Verifikator BKK Tracing TB',
            'activeMenu' => 'sinadiTracingTbVerifikator',
            'ctx'        => $ctx,
            'users'      => $users,
            'verifBkk'   => $verifBkk,
        ]);
    }

    /**
     * Tambah pegawai ke daftar Verifikator BKK.
     */
    public function tambahVerifikator()
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Akses ditolak.');
        }

        $idUser = (int) ($this->request->getPost('id_user') ?? 0);
        $peran = 'verifikator_bkk';
        $noHp = trim((string) ($this->request->getPost('no_hp') ?? ''));

        if ($idUser <= 0) {
            return redirect()->to('/sinadi/tracing-tb/verifikator')->with('error', 'Pilih pegawai yang akan dijadikan Verifikator BKK.');
        }

        $exists = $this->verifikatorModel
            ->where('id_user', $idUser)
            ->where('peran', $peran)
            ->first();

        if ($exists) {
            if ($noHp !== '') {
                $this->verifikatorModel->update((int) $exists['id_verifikator'], ['no_hp' => $noHp]);
                return redirect()->to('/sinadi/tracing-tb/verifikator')->with('pesan', 'Nomor HP Verifikator BKK berhasil diperbarui.');
            }
            return redirect()->to('/sinadi/tracing-tb/verifikator')->with('info', 'Pegawai tersebut sudah terdaftar sebagai Verifikator BKK.');
        }

        $peg = $this->dataPegawaiModel->select('id_data_pegawai')->where('id_user', $idUser)->first();
        $uTelp = $this->userModel->select('no_telp')->where('id_user', $idUser)->first();
        $finalHp = $noHp !== '' ? $noHp : trim((string) ($uTelp['no_telp'] ?? ''));

        $this->verifikatorModel->insert([
            'id_user'         => $idUser,
            'id_data_pegawai' => isset($peg['id_data_pegawai']) ? (int) $peg['id_data_pegawai'] : null,
            'peran'           => $peran,
            'no_hp'           => $finalHp !== '' ? $finalHp : null,
            'created_by'      => $ctx['id_user'],
        ]);

        return redirect()->to('/sinadi/tracing-tb/verifikator')->with('pesan', 'Verifikator BKK berhasil ditambahkan.');
    }

    /**
     * Hapus pegawai dari daftar verifikator Tracing TB.
     */
    public function hapusVerifikator($id)
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Akses ditolak.');
        }

        $row = $this->verifikatorModel->find((int) $id);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb/verifikator')->with('error', 'Data verifikator tidak ditemukan.');
        }

        $this->verifikatorModel->delete((int) $id);
        return redirect()->to('/sinadi/tracing-tb/verifikator')->with('pesan', 'Verifikator berhasil dihapus.');
    }

    /**
     * Pengaturan Master Lokus Tracing TB (Tambah, Edit, Hapus).
     */
    public function lokus()
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')
                ->with('error', 'Halaman Pengaturan Lokus hanya dapat diakses oleh Admin / Super User SINADI.');
        }

        $lokusRows = $this->lokusModel
            ->orderBy('urutan', 'ASC')
            ->orderBy('id_lokus', 'ASC')
            ->findAll();

        // Hitung pemakaian masing-masing lokus di pengajuan Tracing TB yang sudah tersimpan
        $usageRows = \Config\Database::connect()->table('sinadi_tracing_tb')
            ->select('lokus, COUNT(*) AS total')
            ->groupBy('lokus')
            ->get()
            ->getResultArray();
        $usageMap = [];
        foreach ($usageRows as $ur) {
            $usageMap[(string) $ur['lokus']] = (int) $ur['total'];
        }

        $maxUrutan = 0;
        $activeCount = 0;
        foreach ($lokusRows as &$lr) {
            $u = (int) ($lr['urutan'] ?? 0);
            if ($u > $maxUrutan) {
                $maxUrutan = $u;
            }
            if ((int) ($lr['is_active'] ?? 0) === 1) {
                $activeCount++;
            }
            $lr['usage_count'] = $usageMap[(string) $lr['nama_lokus']] ?? 0;
        }
        unset($lr);

        return view('sinadi/tracing_tb/lokus', [
            'title'       => 'Sinadi | Pengaturan Lokus Tracing TB',
            'activeMenu'  => 'sinadiTracingTbLokus',
            'ctx'         => $ctx,
            'lokusRows'   => $lokusRows,
            'activeCount' => $activeCount,
            'nextUrutan'  => $maxUrutan + 1,
        ]);
    }

    /**
     * Tambah Lokus baru ke pengaturan Tracing TB.
     */
    public function tambahLokus()
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Akses ditolak.');
        }

        $namaLokus = trim(preg_replace('/\s+/', ' ', (string) ($this->request->getPost('nama_lokus') ?? '')));
        $urutanRaw = (int) ($this->request->getPost('urutan') ?? 0);
        $isActive = ($this->request->getPost('is_active') !== null)
            ? ((string) $this->request->getPost('is_active') === '1' ? 1 : 0)
            : 1;

        if ($namaLokus === '') {
            return redirect()->to('/sinadi/tracing-tb/lokus')->withInput()->with('error', 'Nama Lokus wajib diisi.');
        }
        if (mb_strlen($namaLokus) > 255) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->withInput()->with('error', 'Nama Lokus maksimal 255 karakter.');
        }

        $db = \Config\Database::connect();
        $dup = $db->table('sinadi_tracing_tb_lokus')
            ->select('id_lokus')
            ->where('LOWER(TRIM(nama_lokus))', mb_strtolower($namaLokus))
            ->get()
            ->getRowArray();

        if ($dup) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->withInput()->with('error', 'Lokus "' . $namaLokus . '" sudah terdaftar.');
        }

        if ($urutanRaw <= 0) {
            $maxRow = $this->lokusModel->selectMax('urutan')->first();
            $urutanRaw = ((int) ($maxRow['urutan'] ?? 0)) + 1;
        }

        $this->lokusModel->insert([
            'nama_lokus' => $namaLokus,
            'urutan'     => $urutanRaw,
            'is_active'  => $isActive,
            'created_by' => $ctx['id_user'],
            'updated_by' => $ctx['id_user'],
        ]);

        return redirect()->to('/sinadi/tracing-tb/lokus')->with('pesan', 'Lokus "' . $namaLokus . '" berhasil ditambahkan.');
    }

    /**
     * Perbarui data Lokus Tracing TB (+ opsi sinkronisasi ke pengajuan yang sudah ada).
     */
    public function updateLokus($id)
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Akses ditolak.');
        }

        $idLokus = (int) $id;
        $row = $this->lokusModel->find($idLokus);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->with('error', 'Data lokus tidak ditemukan.');
        }

        $namaLokus = trim(preg_replace('/\s+/', ' ', (string) ($this->request->getPost('nama_lokus') ?? '')));
        $urutan = max(1, (int) ($this->request->getPost('urutan') ?? ($row['urutan'] ?? 1)));
        $isActive = ((string) ($this->request->getPost('is_active') ?? '0') === '1') ? 1 : 0;
        $syncRecords = ((string) ($this->request->getPost('sync_records') ?? '0') === '1');

        if ($namaLokus === '') {
            return redirect()->to('/sinadi/tracing-tb/lokus')->with('error', 'Nama Lokus tidak boleh kosong.');
        }
        if (mb_strlen($namaLokus) > 255) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->with('error', 'Nama Lokus maksimal 255 karakter.');
        }

        $db = \Config\Database::connect();
        $dup = $db->table('sinadi_tracing_tb_lokus')
            ->select('id_lokus')
            ->where('LOWER(TRIM(nama_lokus))', mb_strtolower($namaLokus))
            ->where('id_lokus !=', $idLokus)
            ->get()
            ->getRowArray();

        if ($dup) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->with('error', 'Lokus "' . $namaLokus . '" sudah digunakan oleh data lain.');
        }

        $oldNama = (string) $row['nama_lokus'];
        $this->lokusModel->update($idLokus, [
            'nama_lokus' => $namaLokus,
            'urutan'     => $urutan,
            'is_active'  => $isActive,
            'updated_by' => $ctx['id_user'],
        ]);

        if ($syncRecords && $oldNama !== $namaLokus) {
            $db->table('sinadi_tracing_tb')
                ->where('lokus', $oldNama)
                ->update(['lokus' => $namaLokus]);
        }

        return redirect()->to('/sinadi/tracing-tb/lokus')->with('pesan', 'Lokus "' . $namaLokus . '" berhasil diperbarui.');
    }

    /**
     * Hapus Lokus dari pengaturan (data pengajuan lama tetap aman menyimpan riwayat namanya).
     */
    public function hapusLokus($id)
    {
        $ctx = $this->getUserContext();
        if (!$ctx['can_manage_verif']) {
            return redirect()->to('/sinadi/tracing-tb')->with('error', 'Akses ditolak.');
        }

        $idLokus = (int) $id;
        $row = $this->lokusModel->find($idLokus);
        if (!$row) {
            return redirect()->to('/sinadi/tracing-tb/lokus')->with('error', 'Data lokus tidak ditemukan.');
        }

        $nama = (string) $row['nama_lokus'];
        $this->lokusModel->delete($idLokus);

        return redirect()->to('/sinadi/tracing-tb/lokus')->with('pesan', 'Lokus "' . $nama . '" berhasil dihapus dari daftar pilihan.');
    }
}
