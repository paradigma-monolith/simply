<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;
use App\Libraries\RelaxtimeMonitoringAnggaranHelper;
use App\Libraries\RelaxtimeRkksHelper;
use App\Models\RelaxtimeMonitoringAnggaranModel;
use App\Models\RelaxtimeMonitoringTargetModel;
use App\Models\RelaxtimeRkksModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MonitoringAnggaran extends BaseController
{
    protected RelaxtimeMonitoringAnggaranModel $monitoringModel;

    public function __construct()
    {
        $this->monitoringModel = new RelaxtimeMonitoringAnggaranModel();
        $this->monitoringModel->ensureTable();
    }

    public function index()
    {
        $allMonths = $this->request->getGet('all_months') === '1'
            || $this->request->getGet('all_dates') === '1';

        $range = RelaxtimeMonitoringAnggaranHelper::normalizeMonthRange(
            $this->request->getGet('bulan_mulai') ?? $this->request->getGet('tgl_mulai'),
            $this->request->getGet('bulan_selesai') ?? $this->request->getGet('tgl_selesai'),
            $allMonths
        );

        $akunFilter = RelaxtimeMonitoringAnggaranHelper::normalizeAkun($this->request->getGet('akun') ?? '', '');
        $picFilter = RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getGet('pic') ?? '');

        $dateFilters = [
            'tgl_mulai' => $allMonths ? '' : $range['start_date'],
            'tgl_selesai' => $allMonths ? '' : $range['end_date'],
        ];

        $allDateRows = $this->monitoringModel->getFiltered($dateFilters);
        $filteredRows = array_values(array_filter($allDateRows, static function (array $row) use ($akunFilter, $picFilter): bool {
            if ($akunFilter !== '' && ($row['akun'] ?? '') !== $akunFilter) {
                return false;
            }
            if ($picFilter !== '' && strcasecmp((string) ($row['pic'] ?? ''), $picFilter) !== 0) {
                return false;
            }
            return true;
        }));

        $options = $this->monitoringModel->getDistinctOptions();

        $rkksModel = new RelaxtimeRkksModel();
        $rkksModel->ensureTables();
        $rkksDokList = $rkksModel->getDokumenList(null);
        $rkksDokumen = $rkksDokList[0] ?? null;
        $rkksRekapOptions = [];
        if ($rkksDokumen !== null) {
            $rkksItems = $rkksModel->getItemsByDokumen((int) $rkksDokumen['id_dokumen']);
            $rkksRekapOptions = RelaxtimeRkksHelper::buildRekapSubkomponen($rkksItems);
            foreach ($rkksRekapOptions as $rkOpt) {
                $roVal = trim((string) ($rkOpt['ro_komponen'] ?? ''));
                if ($roVal !== '' && !in_array($roVal, $options['ros'], true)) {
                    $options['ros'][] = $roVal;
                }
            }
            sort($options['ros']);
        }

        $targetYear = (int) date('Y');
        if (!$allMonths && !empty($range['start_month']) && preg_match('/^(\d{4})-/', (string) $range['start_month'], $ymMatch)) {
            $targetYear = (int) $ymMatch[1];
        }

        $isFullTargetYear = !$allMonths
            && ($range['start_month'] ?? '') === $targetYear . '-01'
            && ($range['end_month'] ?? '') === $targetYear . '-12';

        $yearRows = $isFullTargetYear
            ? $allDateRows
            : $this->monitoringModel->getFiltered([
                'tgl_mulai' => $targetYear . '-01-01',
                'tgl_selesai' => $targetYear . '-12-31',
            ]);

        $targetModel = new RelaxtimeMonitoringTargetModel();
        $targetConfig = $targetModel->getConfigByYear($targetYear);

        $data = [
            'title' => 'RELAXTIME | Monitoring Anggaran',
            'activeMenu' => 'relaxtimeMonitoringAnggaran',
            'rows' => $filteredRows,
            'allDateRows' => $allDateRows,
            'yearRows' => $yearRows,
            'targetYear' => $targetYear,
            'targetConfig' => $targetConfig,
            'stats' => RelaxtimeMonitoringAnggaranHelper::buildDashboardStats($filteredRows),
            'filters' => [
                'bulan_mulai' => $allMonths ? '' : $range['start_month'],
                'bulan_selesai' => $allMonths ? '' : $range['end_month'],
                'akun' => $akunFilter,
                'pic' => $picFilter,
                'all_months' => $allMonths ? '1' : '0',
            ],
            'picOptions' => $options['pics'],
            'roOptions' => $options['ros'],
            'rkksDokumen' => $rkksDokumen,
            'rkksRekapOptions' => $rkksRekapOptions,
        ];

        return view('relaxtime/monitoring_anggaran', $data);
    }

    public function saveTargetTriwulan()
    {
        $userId = session()->get('id_user') ?? null;
        $this->releaseSessionLock();

        $tahun = (int) ($this->request->getPost('tahun_anggaran') ?: date('Y'));
        if ($tahun < 2020 || $tahun > 2100) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tahun anggaran tidak valid.',
            ])->setStatusCode(400);
        }

        $paguPost = $this->request->getPost('pagu');
        $persenPost = $this->request->getPost('target_persen');
        $paguPost = is_array($paguPost) ? $paguPost : [];
        $persenPost = is_array($persenPost) ? $persenPost : [];

        $config = [];
        for ($tw = 1; $tw <= 4; $tw++) {
            $config[$tw] = [];
            foreach (array_keys(RelaxtimeMonitoringAnggaranHelper::AKUN_LABELS) as $akun) {
                $rawPagu = $paguPost[$tw][$akun] ?? ($paguPost[(string) $tw][$akun] ?? 0);
                $rawPct = $persenPost[$tw][$akun] ?? ($persenPost[(string) $tw][$akun] ?? 0);
                $pctStr = str_replace(',', '.', trim((string) $rawPct));
                $config[$tw][$akun] = [
                    'pagu' => max(0, RelaxtimeMonitoringAnggaranHelper::parseAmount($rawPagu)),
                    'target_persen' => max(0.0, min(100.0, round((float) $pctStr, 2))),
                ];
            }
        }

        $targetModel = new RelaxtimeMonitoringTargetModel();
        $savedConfig = $targetModel->saveYearConfig($tahun, $config, $userId);

        return $this->response->setJSON([
            'success' => true,
            'tahun' => $tahun,
            'config' => $savedConfig,
            'message' => 'Pagu & target realisasi triwulanan TA ' . $tahun . ' berhasil disimpan.',
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'ro' => 'required|max_length[50]',
            'akun' => 'required|in_list[51,52,53]',
            'kegiatan' => 'required|max_length[2000]',
            'rencana' => 'required',
            'pic' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->respondValidationError($validation->getErrors());
        }

        $bulanRaw = $this->request->getPost('bulan') ?? $this->request->getPost('tanggal');
        $tanggalDb = RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate($bulanRaw, (int) date('Y'), '');
        if ($tanggalDb === '') {
            return $this->respondValidationError(['bulan' => 'Bulan anggaran harus diisi dengan format yang valid (YYYY-MM).']);
        }

        $rencana = RelaxtimeMonitoringAnggaranHelper::parseAmount($this->request->getPost('rencana'));
        $realisasi = RelaxtimeMonitoringAnggaranHelper::parseAmount($this->request->getPost('realisasi') ?? 0);

        if ($rencana < 0) {
            return $this->respondValidationError(['rencana' => 'Rencana pencairan tidak boleh bernilai negatif.']);
        }

        $payload = [
            'tanggal' => $tanggalDb,
            'ro' => trim((string) $this->request->getPost('ro')),
            'akun' => RelaxtimeMonitoringAnggaranHelper::normalizeAkun($this->request->getPost('akun'), '52'),
            'kegiatan' => trim((string) $this->request->getPost('kegiatan')),
            'rencana' => $rencana,
            'pic' => RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getPost('pic')),
            'realisasi' => max(0, $realisasi),
            'sumber_input' => 'manual',
            'created_by' => session()->get('id_user') ?? null,
        ];

        if (!$this->monitoringModel->insert($payload)) {
            $msg = 'Gagal menyimpan data monitoring anggaran.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        $newId = (int) $this->monitoringModel->getInsertID();
        $savedRow = $newId > 0 ? $this->monitoringModel->find($newId) : null;

        $msg = 'Data monitoring anggaran berhasil ditambahkan.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $msg,
                'row' => $savedRow ? $this->formatRowForClient($savedRow) : null,
            ]);
        }
        return redirect()->to('/relaxtime/monitoring-anggaran')->with('success', $msg);
    }

    public function update($id)
    {
        $existing = $this->monitoringModel->find((int) $id);
        if (!$existing) {
            $msg = 'Data monitoring anggaran tidak ditemukan.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(404);
            }
            return redirect()->to('/relaxtime/monitoring-anggaran')->with('error', $msg);
        }

        $validation = \Config\Services::validation();
        $rules = [
            'ro' => 'required|max_length[50]',
            'akun' => 'required|in_list[51,52,53]',
            'kegiatan' => 'required|max_length[2000]',
            'rencana' => 'required',
            'pic' => 'required|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->respondValidationError($validation->getErrors());
        }

        $bulanRaw = $this->request->getPost('bulan') ?? $this->request->getPost('tanggal');
        $tanggalDb = RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate($bulanRaw, (int) date('Y'), '');
        if ($tanggalDb === '') {
            return $this->respondValidationError(['bulan' => 'Bulan anggaran harus diisi dengan format yang valid (YYYY-MM).']);
        }

        $rencana = RelaxtimeMonitoringAnggaranHelper::parseAmount($this->request->getPost('rencana'));
        $realisasi = RelaxtimeMonitoringAnggaranHelper::parseAmount($this->request->getPost('realisasi') ?? 0);

        $payload = [
            'tanggal' => $tanggalDb,
            'ro' => trim((string) $this->request->getPost('ro')),
            'akun' => RelaxtimeMonitoringAnggaranHelper::normalizeAkun($this->request->getPost('akun'), '52'),
            'kegiatan' => trim((string) $this->request->getPost('kegiatan')),
            'rencana' => max(0, $rencana),
            'pic' => RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getPost('pic')),
            'realisasi' => max(0, $realisasi),
            'updated_by' => session()->get('id_user') ?? null,
        ];

        if (!$this->monitoringModel->update((int) $id, $payload)) {
            $msg = 'Gagal memperbarui data monitoring anggaran.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(500);
            }
            return redirect()->back()->withInput()->with('error', $msg);
        }

        $updatedRow = $this->monitoringModel->find((int) $id);
        $msg = 'Data monitoring anggaran berhasil diperbarui.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => $msg,
                'row' => $updatedRow ? $this->formatRowForClient($updatedRow) : null,
            ]);
        }
        return redirect()->to('/relaxtime/monitoring-anggaran')->with('success', $msg);
    }

    public function saveTindakLanjut($id)
    {
        $userId = session()->get('id_user') ?? null;
        $this->releaseSessionLock();

        $existing = $this->monitoringModel->find((int) $id);
        if (!$existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data monitoring anggaran tidak ditemukan.',
            ])->setStatusCode(404);
        }

        $status = trim((string) $this->request->getPost('tindak_lanjut_status'));
        $allowedStatuses = array_keys(RelaxtimeMonitoringAnggaranHelper::TINDAK_LANJUT_LABELS);
        if (!in_array($status, $allowedStatuses, true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Status tindak lanjut revisi tidak valid.',
            ])->setStatusCode(400);
        }

        $targetRaw = trim((string) ($this->request->getPost('tindak_lanjut_target_rencana') ?? ''));
        $targetRencana = $targetRaw !== '' ? max(0, RelaxtimeMonitoringAnggaranHelper::parseAmount($targetRaw)) : null;
        $catatan = trim((string) ($this->request->getPost('tindak_lanjut_catatan') ?? ''));
        $terapkanKeRencana = ((string) $this->request->getPost('terapkan_ke_rencana') === '1');

        $enablePengalihan = ((string) $this->request->getPost('alihkan_anggaran') === '1');
        $pengalihanMode = trim((string) ($this->request->getPost('pengalihan_mode') ?: 'create_new'));
        $nominalPengalihan = max(0, RelaxtimeMonitoringAnggaranHelper::parseAmount($this->request->getPost('nominal_pengalihan') ?? 0));
        $kurangiRencanaAsal = ((string) $this->request->getPost('kurangi_rencana_asal') === '1');

        $now = date('Y-m-d H:i:s');
        $payload = [
            'tindak_lanjut_status'         => $status,
            'tindak_lanjut_catatan'        => $catatan !== '' ? $catatan : null,
            'tindak_lanjut_target_rencana' => $targetRencana,
            'tindak_lanjut_updated_at'     => $now,
            'tindak_lanjut_updated_by'     => $userId,
            'updated_by'                   => $userId,
        ];

        $reallocatedId = null;
        $sourceRencana = (int) ($existing['rencana'] ?? 0);

        if ($enablePengalihan) {
            if ($nominalPengalihan <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nominal pengalihan anggaran harus lebih besar dari Rp 0.',
                ])->setStatusCode(400);
            }
            if ($kurangiRencanaAsal && $nominalPengalihan > $sourceRencana) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Nominal pengalihan (Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ') melebihi Rencana item asal (Rp ' . number_format($sourceRencana, 0, ',', '.') . ').',
                ])->setStatusCode(400);
            }

            if ($pengalihanMode === 'add_to_existing') {
                $targetId = (int) ($this->request->getPost('pengalihan_target_id') ?? 0);
                if ($targetId <= 0 || $targetId === (int) $id) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Pilih item kegiatan tujuan pengalihan yang valid (berbeda dari item asal).',
                    ])->setStatusCode(400);
                }
                $targetItem = $this->monitoringModel->find($targetId);
                if (!$targetItem) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Item kegiatan tujuan pengalihan tidak ditemukan.',
                    ])->setStatusCode(404);
                }

                $targetMonthLabel = RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($targetItem['tanggal'] ?? '');
                $sourceMonthLabel = RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($existing['tanggal'] ?? '');
                $alokasiInfo = mb_substr('Dialihkan Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ' → ' . $targetMonthLabel . ' (' . ($targetItem['ro'] ?: '-') . ')', 0, 250);
                $targetNote = 'Menerima pengalihan Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ' dari ' . $sourceMonthLabel . ' (' . ($existing['ro'] ?: '-') . ' - ' . mb_substr((string) ($existing['kegiatan'] ?? ''), 0, 80) . ')';
                $existingTargetNote = trim((string) ($targetItem['tindak_lanjut_catatan'] ?? ''));
                $combinedTargetNote = $existingTargetNote !== '' ? ($existingTargetNote . ' | ' . $targetNote) : $targetNote;

                $db = \Config\Database::connect();
                $db->transStart();
                if ($kurangiRencanaAsal) {
                    $payload['rencana'] = max(0, $sourceRencana - $nominalPengalihan);
                }
                $payload['tindak_lanjut_pengalihan_nominal'] = $nominalPengalihan;
                $payload['tindak_lanjut_pengalihan_bulan'] = $targetItem['tanggal'] ?? null;
                $payload['tindak_lanjut_pengalihan_target_id'] = $targetId;
                $payload['tindak_lanjut_pengalihan_info'] = $alokasiInfo;
                $this->monitoringModel->update((int) $id, $payload);

                $this->monitoringModel->update($targetId, [
                    'rencana'                   => (int) ($targetItem['rencana'] ?? 0) + $nominalPengalihan,
                    'tindak_lanjut_catatan'     => $combinedTargetNote,
                    'tindak_lanjut_updated_at'  => $now,
                    'tindak_lanjut_updated_by'  => $userId,
                    'updated_by'                => $userId,
                ]);
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal memproses transaksi pengalihan anggaran.',
                    ])->setStatusCode(500);
                }
                $reallocatedId = $targetId;
            } else {
                $bulanTujuanDb = RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate(
                    $this->request->getPost('pengalihan_bulan'),
                    (int) date('Y'),
                    ''
                );
                if ($bulanTujuanDb === '') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Bulan tujuan rencana pengalihan harus diisi (YYYY-MM).',
                    ])->setStatusCode(400);
                }
                $destRo = trim((string) ($this->request->getPost('pengalihan_ro') ?: ($existing['ro'] ?? '')));
                $destAkun = RelaxtimeMonitoringAnggaranHelper::normalizeAkun($this->request->getPost('pengalihan_akun') ?? ($existing['akun'] ?? '52'), '52');
                $destPic = RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getPost('pengalihan_pic') ?? ($existing['pic'] ?? ''));
                $destKegiatan = trim((string) ($this->request->getPost('pengalihan_kegiatan') ?: ('[Pengalihan] ' . ($existing['kegiatan'] ?? ''))));
                if ($destRo === '' || $destKegiatan === '' || $destPic === '') {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'RO, Kegiatan, dan PIC tujuan pengalihan wajib diisi.',
                    ])->setStatusCode(400);
                }

                $targetMonthLabel = RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($bulanTujuanDb);
                $sourceMonthLabel = RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($existing['tanggal'] ?? '');
                $alokasiInfo = mb_substr('Dialihkan Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ' → ' . $targetMonthLabel . ' (' . $destRo . ')', 0, 250);
                $newItemNote = 'Hasil pengalihan anggaran Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ' dari ' . $sourceMonthLabel . ' (' . ($existing['ro'] ?: '-') . ')';

                $db = \Config\Database::connect();
                $db->transStart();
                $this->monitoringModel->insert([
                    'tanggal'                      => $bulanTujuanDb,
                    'ro'                           => mb_substr($destRo, 0, 50),
                    'akun'                         => $destAkun,
                    'kegiatan'                     => $destKegiatan,
                    'rencana'                      => $nominalPengalihan,
                    'pic'                          => mb_substr($destPic, 0, 100),
                    'realisasi'                    => 0,
                    'sumber_input'                 => 'manual',
                    'tindak_lanjut_status'         => 'tidak_perlu',
                    'tindak_lanjut_catatan'        => $newItemNote,
                    'tindak_lanjut_updated_at'     => $now,
                    'tindak_lanjut_updated_by'     => $userId,
                    'created_by'                   => $userId,
                    'updated_by'                   => $userId,
                ]);
                $reallocatedId = (int) $this->monitoringModel->getInsertID();

                if ($kurangiRencanaAsal) {
                    $payload['rencana'] = max(0, $sourceRencana - $nominalPengalihan);
                }
                $payload['tindak_lanjut_pengalihan_nominal'] = $nominalPengalihan;
                $payload['tindak_lanjut_pengalihan_bulan'] = $bulanTujuanDb;
                $payload['tindak_lanjut_pengalihan_target_id'] = $reallocatedId > 0 ? $reallocatedId : null;
                $payload['tindak_lanjut_pengalihan_info'] = $alokasiInfo;
                $this->monitoringModel->update((int) $id, $payload);
                $db->transComplete();

                if ($db->transStatus() === false) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Gagal menyimpan rencana pengalihan anggaran.',
                    ])->setStatusCode(500);
                }
            }
        } else {
            if ($terapkanKeRencana && $targetRencana !== null) {
                $payload['rencana'] = $targetRencana;
            }
            if (!$this->monitoringModel->update((int) $id, $payload)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal menyimpan tindak lanjut revisi.',
                ])->setStatusCode(500);
            }
        }

        $updatedRow = $this->monitoringModel->find((int) $id);
        $reallocatedRow = $reallocatedId ? $this->monitoringModel->find($reallocatedId) : null;
        return $this->response->setJSON([
            'success' => true,
            'message' => $enablePengalihan
                ? 'Tindak lanjut revisi & pengalihan anggaran Rp ' . number_format($nominalPengalihan, 0, ',', '.') . ' berhasil disimpan.'
                : 'Tindak lanjut revisi berhasil disimpan.',
            'row' => $updatedRow ? $this->formatRowForClient($updatedRow) : null,
            'reallocated_row' => $reallocatedRow ? $this->formatRowForClient($reallocatedRow) : null,
        ]);
    }

    public function delete($id)
    {
        $existing = $this->monitoringModel->find((int) $id);
        if (!$existing) {
            $msg = 'Data tidak ditemukan.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(404);
            }
            return redirect()->to('/relaxtime/monitoring-anggaran')->with('error', $msg);
        }

        $this->monitoringModel->delete((int) $id);

        $msg = 'Data monitoring anggaran berhasil dihapus.';
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'deleted_id' => (int) $id,
                'message' => $msg,
            ]);
        }
        return redirect()->to('/relaxtime/monitoring-anggaran')->with('success', $msg);
    }

    public function bulkDelete()
    {
        $rawIds = $this->request->getPost('ids');
        if (!is_array($rawIds)) {
            $rawIds = explode(',', (string) $rawIds);
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $rawIds), static fn($v) => $v > 0)));
        if (empty($ids)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pilih minimal satu baris data untuk dihapus.',
            ])->setStatusCode(400);
        }

        $this->monitoringModel->whereIn('id_monitoring', $ids)->delete();

        return $this->response->setJSON([
            'success' => true,
            'message' => count($ids) . ' data monitoring anggaran berhasil dihapus.',
            'deleted_count' => count($ids),
            'deleted_ids' => $ids,
        ]);
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            $msg = $file ? $file->getErrorString() : 'File Excel belum dipilih.';
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(400);
        }

        $ext = strtolower((string) $file->getClientExtension());
        if (!in_array($ext, ['xlsx', 'xls', 'csv'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format file harus .xlsx, .xls, atau .csv.',
            ])->setStatusCode(400);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ukuran file melebihi batas maksimal 10 MB.',
            ])->setStatusCode(400);
        }

        $tahunAnggaran = (int) ($this->request->getPost('tahun_anggaran') ?: date('Y'));
        if ($tahunAnggaran < 2000 || $tahunAnggaran > 2100) {
            $tahunAnggaran = (int) date('Y');
        }

        $bulanDefault = RelaxtimeMonitoringAnggaranHelper::normalizeMonthToDate(
            $this->request->getPost('bulan_default') ?? $this->request->getPost('tanggal_default'),
            $tahunAnggaran,
            date('Y-m-01')
        );
        $modeImport = (string) ($this->request->getPost('mode_import') ?: 'replace_period');

        $uploadDir = WRITEPATH . 'uploads';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $randomName = $file->getRandomName();
        $file->move($uploadDir, $randomName);
        $fullPath = rtrim($uploadDir, '\\/') . DIRECTORY_SEPARATOR . $randomName;

        try {
            $parsed = RelaxtimeMonitoringAnggaranHelper::parseWorkbook($fullPath, $tahunAnggaran, $bulanDefault);
        } catch (\Throwable $e) {
            log_message('error', 'Error parsing monitoring anggaran Excel: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membaca file Excel: ' . $e->getMessage(),
            ])->setStatusCode(500);
        } finally {
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }

        $rows = $parsed['rows'] ?? [];
        if (empty($rows)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak ada baris kegiatan valid yang ditemukan (pastikan kolom RO, Akun, Kegiatan, Rencana, PIC, dan Realisasi terisi).',
                'sheets_skipped' => $parsed['sheets_skipped'] ?? [],
            ])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $userId = session()->get('id_user') ?? null;
        $batchCode = 'IMP-' . date('YmdHis');

        $db->transStart();

        if ($modeImport === 'replace_period') {
            $periods = [];
            foreach ($rows as $item) {
                if (preg_match('/^(\d{4})-(\d{2})/', (string) $item['tanggal'], $m)) {
                    $periods[$m[1] . '-' . $m[2]] = [(int) $m[1], (int) $m[2]];
                }
            }
            foreach ($periods as [$yr, $mo]) {
                $db->table('relaxtime_monitoring_anggaran')
                    ->where('YEAR(tanggal)', $yr)
                    ->where('MONTH(tanggal)', $mo)
                    ->delete();
            }
        }

        $insertBatch = [];
        foreach ($rows as $item) {
            $insertBatch[] = [
                'tanggal' => $item['tanggal'],
                'ro' => mb_substr((string) ($item['ro'] ?? ''), 0, 50),
                'akun' => mb_substr((string) ($item['akun'] ?? '52'), 0, 10),
                'kegiatan' => (string) ($item['kegiatan'] ?? ''),
                'rencana' => (int) ($item['rencana'] ?? 0),
                'pic' => mb_substr((string) ($item['pic'] ?? ''), 0, 100),
                'realisasi' => (int) ($item['realisasi'] ?? 0),
                'sumber_input' => 'import',
                'import_batch' => $batchCode,
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($insertBatch, 200) as $chunk) {
            $db->table('relaxtime_monitoring_anggaran')->insertBatch($chunk);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan hasil import ke database.',
            ])->setStatusCode(500);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => count($insertBatch) . ' baris kegiatan berhasil diimpor dari ' . count($parsed['sheets_processed']) . ' sheet.',
            'inserted_count' => count($insertBatch),
            'sheets_processed' => $parsed['sheets_processed'],
            'sheets_skipped' => $parsed['sheets_skipped'],
        ]);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Monitoring Anggaran');

        $headers = ['Bulan', 'RO', 'Akun', 'Kegiatan', 'Rencana Pencairan Anggaran', 'PIC', 'Realisasi'];
        foreach ($headers as $idx => $label) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '1', $label);
        }

        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $samples = [
            [date('Y') . '-01', '7960.PEA.001', '52', 'Jejaring Terkait Pencegahan Pelanggaran Kekarantinaan Kesehatan', 2380000, 'Timker 1', 1870000],
            [date('Y') . '-01', '4815.EBA.994.001', '51', 'Gaji dan Tunjangan', 435481000, 'ADUM', 435481000],
            [date('Y') . '-03', '4815.EBB.951', '53', 'Pendukung HUMAS/KIE', 20000000, 'ADUM', 19758000],
        ];

        $r = 2;
        foreach ($samples as $row) {
            $sheet->setCellValueExplicit('A' . $r, $row[0], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $r, $row[1], DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $r, $row[2], DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $r, $row[3]);
            $sheet->setCellValue('E' . $r, $row[4]);
            $sheet->setCellValue('F' . $r, $row[5]);
            $sheet->setCellValue('G' . $r, $row[6]);
            $sheet->getStyle('E' . $r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G' . $r)->getNumberFormat()->setFormatCode('#,##0');
            $r++;
        }

        $sheet->getStyle('A1:G4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach (['A' => 14, 'B' => 20, 'C' => 10, 'D' => 52, 'E' => 26, 'F' => 16, 'G' => 18] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $guide = $spreadsheet->createSheet();
        $guide->setTitle('Panduan');
        $guide->setCellValue('A1', 'Panduan Import Monitoring Anggaran RELAXTIME');
        $guide->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $guide->setCellValue('A3', '1. Format Template Sederhana: Gunakan sheet pertama dengan kolom Bulan (YYYY-MM atau mis. Januari 2026), RO, Akun (51/52/53), Kegiatan, Rencana Pencairan Anggaran, PIC, dan Realisasi.');
        $guide->setCellValue('A4', '2. Format Rekap Bulanan Excel: Sistem juga mendukung upload langsung file rekap bulanan (sheet JAN s.d. DESEMBER) dengan fokus kolom A (RO), B (Akun), C (Kegiatan), D (Rencana), PIC, dan K (Realisasi).');
        $guide->setCellValue('A5', '3. Sheet rekap triwulanan (REKAP TwI, Tw2, TW 3) dan baris Total/kosong dilewati otomatis.');
        $guide->getColumnDimension('A')->setWidth(115);
        $spreadsheet->setActiveSheetIndex(0);

        return $this->streamSpreadsheet($spreadsheet, 'Template_Monitoring_Anggaran.xlsx');
    }

    public function exportExcel()
    {
        $this->releaseSessionLock();

        $allMonths = $this->request->getGet('all_months') === '1'
            || $this->request->getGet('all_dates') === '1';

        $range = RelaxtimeMonitoringAnggaranHelper::normalizeMonthRange(
            $this->request->getGet('bulan_mulai') ?? $this->request->getGet('tgl_mulai'),
            $this->request->getGet('bulan_selesai') ?? $this->request->getGet('tgl_selesai'),
            $allMonths
        );

        $akunList = RelaxtimeMonitoringAnggaranHelper::normalizeAkunList($this->request->getGet('akun'));
        $picFilter = RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getGet('pic') ?? '');

        $rows = $this->monitoringModel->getFiltered([
            'tgl_mulai' => $allMonths ? '' : $range['start_date'],
            'tgl_selesai' => $allMonths ? '' : $range['end_date'],
            'akun' => $akunList,
            'pic' => $picFilter,
        ]);

        $stats = RelaxtimeMonitoringAnggaranHelper::buildDashboardStats($rows);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Monitoring Anggaran');

        $sheet->setCellValue('A1', 'LAPORAN MONITORING ANGGARAN - RELAXTIME (ATURAN DEVIASI HAL. III DIPA PER-5/PB/2024)');
        $sheet->mergeCells('A1:P1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('0F172A');

        $periodeLabel = $allMonths
            ? 'Semua Bulan'
            : (RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($range['start_month'])
                . ' s.d. '
                . RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($range['end_month']));
        $akunLabel = !empty($akunList) ? implode(', ', $akunList) : 'Semua (51, 52, 53)';
        $filterInfo = sprintf(
            'Periode Bulan: %s | Akun: %s | PIC: %s | Diekspor: %s',
            $periodeLabel,
            $akunLabel,
            $picFilter !== '' ? $picFilter : 'Semua',
            date('d/m/Y H:i')
        );
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:P2');
        $sheet->getStyle('A2')->getFont()->setSize(10)->getColor()->setRGB('475569');

        $headers = [
            'No', 'Bulan', 'RO', 'Akun', 'Kegiatan', 'PIC',
            'Rencana Pencairan', 'Realisasi', 'Sisa',
            'Deviasi Hal. III (%)', 'Status Hal. III DIPA', 'Nilai Kinerja', '% Realisasi',
            'Tindak Lanjut Revisi', 'Usulan RPD Revisi', 'Catatan Revisi',
        ];
        foreach ($headers as $idx => $h) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '4', $h);
        }
        $sheet->getStyle('A4:P4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(24);

        $rowNum = 5;
        $no = 1;
        foreach ($rows as $item) {
            $rencana = (int) ($item['rencana'] ?? 0);
            $realisasi = (int) ($item['realisasi'] ?? 0);
            $sisa = $rencana - $realisasi;
            $persen = $rencana > 0 ? ($realisasi / $rencana) : 0;
            $dipa = RelaxtimeMonitoringAnggaranHelper::calculateDeviasiDipa($rencana, $realisasi);
            $tlStatus = (string) ($item['tindak_lanjut_status'] ?? '');
            $tlLabel = $tlStatus !== '' ? RelaxtimeMonitoringAnggaranHelper::formatTindakLanjutLabel($tlStatus) : '-';
            $tlTarget = $item['tindak_lanjut_target_rencana'] !== null ? (int) $item['tindak_lanjut_target_rencana'] : null;
            $tlCatatan = trim((string) ($item['tindak_lanjut_catatan'] ?? ''));
            $tlPengalihanInfo = trim((string) ($item['tindak_lanjut_pengalihan_info'] ?? ''));
            $catatanGabungan = trim(($tlPengalihanInfo !== '' ? '[' . $tlPengalihanInfo . '] ' : '') . $tlCatatan);

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValueExplicit('B' . $rowNum, RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($item['tanggal'] ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $rowNum, (string) ($item['ro'] ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $rowNum, (string) ($item['akun'] ?? ''), DataType::TYPE_STRING);
            $sheet->setCellValue('E' . $rowNum, (string) ($item['kegiatan'] ?? ''));
            $sheet->setCellValue('F' . $rowNum, (string) ($item['pic'] ?? ''));
            $sheet->setCellValue('G' . $rowNum, $rencana);
            $sheet->setCellValue('H' . $rowNum, $realisasi);
            $sheet->setCellValue('I' . $rowNum, "=G{$rowNum}-H{$rowNum}");
            $sheet->setCellValue('J' . $rowNum, $dipa['deviasi_persen'] / 100);
            $sheet->setCellValue('K' . $rowNum, $dipa['status_label']);
            $sheet->setCellValue('L' . $rowNum, $dipa['nilai_kinerja']);
            $sheet->setCellValue('M' . $rowNum, $persen);
            $sheet->setCellValue('N' . $rowNum, $tlLabel);
            if ($tlTarget !== null) {
                $sheet->setCellValue('O' . $rowNum, $tlTarget);
                $sheet->getStyle("O{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            } else {
                $sheet->setCellValue('O' . $rowNum, '-');
            }
            $sheet->setCellValue('P' . $rowNum, $catatanGabungan !== '' ? $catatanGabungan : '-');

            $sheet->getStyle("G{$rowNum}:I{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
            $sheet->getStyle("L{$rowNum}")->getNumberFormat()->setFormatCode('0.0');
            $sheet->getStyle("M{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
            $sheet->getStyle("A{$rowNum}:D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("N{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("P{$rowNum}")->getAlignment()->setWrapText(true);

            if ($sisa < 0) {
                $sheet->getStyle("I{$rowNum}")->getFont()->getColor()->setRGB('DC2626');
            }
            $dipaRgb = $dipa['status'] === 'optimal' ? '15803D' : ($dipa['status'] === 'waspada' ? 'B45309' : 'DC2626');
            $sheet->getStyle("J{$rowNum}:K{$rowNum}")->getFont()->getColor()->setRGB($dipaRgb);
            $rowNum++;
        }

        if ($rowNum === 5) {
            $sheet->setCellValue('A5', 'Tidak ada data untuk filter terpilih.');
            $sheet->mergeCells('A5:P5');
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $rowNum = 6;
        } else {
            $lastDataRow = $rowNum - 1;
            $sheet->setCellValue("A{$rowNum}", 'TOTAL / TERTIMBANG IKPA');
            $sheet->mergeCells("A{$rowNum}:F{$rowNum}");
            $sheet->setCellValue("G{$rowNum}", "=SUM(G5:G{$lastDataRow})");
            $sheet->setCellValue("H{$rowNum}", "=SUM(H5:H{$lastDataRow})");
            $sheet->setCellValue("I{$rowNum}", "=SUM(I5:I{$lastDataRow})");
            $sheet->setCellValue("J{$rowNum}", ($stats['summary']['deviasi_dipa_tertimbang'] ?? 0) / 100);
            $sheet->setCellValue("K{$rowNum}", $stats['summary']['deviasi_dipa_label'] ?? 'Optimal (≤5%)');
            $sheet->setCellValue("L{$rowNum}", $stats['summary']['nilai_ikpa_deviasi'] ?? 100);
            $sheet->setCellValue("M{$rowNum}", "=IF(G{$rowNum}>0,H{$rowNum}/G{$rowNum},0)");
            $sheet->mergeCells("N{$rowNum}:P{$rowNum}");
            $sheet->getStyle("A{$rowNum}:P{$rowNum}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            ]);
            $sheet->getStyle("G{$rowNum}:I{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$rowNum}")->getNumberFormat()->setFormatCode('0.00%');
            $sheet->getStyle("L{$rowNum}")->getNumberFormat()->setFormatCode('0.00');
            $sheet->getStyle("M{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
        }

        $sheet->getStyle("A4:P{$rowNum}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach (['A' => 6, 'B' => 16, 'C' => 18, 'D' => 8, 'E' => 48, 'F' => 16, 'G' => 18, 'H' => 18, 'I' => 18, 'J' => 18, 'K' => 20, 'L' => 14, 'M' => 13, 'N' => 18, 'O' => 18, 'P' => 34] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Sheet 2: Ringkasan Dashboard
        $summarySheet = $spreadsheet->createSheet();
        $summarySheet->setTitle('Ringkasan Dashboard');
        $summarySheet->setCellValue('A1', 'RINGKASAN MONITORING ANGGARAN PER AKUN (DEVIASI HAL. III DIPA)');
        $summarySheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $sumHeaders = ['Akun', 'Uraian', 'Jumlah Kegiatan', 'Rencana', 'Realisasi', 'Sisa', 'Selisih RPD', 'Deviasi Hal. III (%)', 'Status Hal. III', 'Nilai Kinerja', '% Realisasi'];
        foreach ($sumHeaders as $idx => $h) {
            $summarySheet->setCellValue(chr(65 + $idx) . '3', $h);
        }
        $summarySheet->getStyle('A3:K3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
        ]);

        $sr = 4;
        foreach ($stats['by_akun'] as $akunCode => $ak) {
            $summarySheet->setCellValueExplicit('A' . $sr, (string) $akunCode, DataType::TYPE_STRING);
            $summarySheet->setCellValue('B' . $sr, $ak['label']);
            $summarySheet->setCellValue('C' . $sr, $ak['count']);
            $summarySheet->setCellValue('D' . $sr, $ak['rencana']);
            $summarySheet->setCellValue('E' . $sr, $ak['realisasi']);
            $summarySheet->setCellValue('F' . $sr, $ak['sisa']);
            $summarySheet->setCellValue('G' . $sr, $ak['deviasi'] ?? ($ak['realisasi'] - $ak['rencana']));
            $summarySheet->setCellValue('H' . $sr, ($ak['deviasi_dipa_persen'] ?? 0) / 100);
            $summarySheet->setCellValue('I' . $sr, $ak['deviasi_dipa_label'] ?? 'Optimal (≤5%)');
            $summarySheet->setCellValue('J' . $sr, $ak['nilai_deviasi_dipa'] ?? 100);
            $summarySheet->setCellValue('K' . $sr, $ak['persen'] / 100);
            $summarySheet->getStyle("D{$sr}:G{$sr}")->getNumberFormat()->setFormatCode('#,##0');
            $summarySheet->getStyle("H{$sr}")->getNumberFormat()->setFormatCode('0.0%');
            $summarySheet->getStyle("J{$sr}")->getNumberFormat()->setFormatCode('0.0');
            $summarySheet->getStyle("K{$sr}")->getNumberFormat()->setFormatCode('0.0%');
            $sr++;
        }
        $summarySheet->getStyle('A3:K' . ($sr - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $picStart = $sr + 2;
        $summarySheet->setCellValue('A' . $picStart, 'RINGKASAN PER PIC');
        $summarySheet->getStyle('A' . $picStart)->getFont()->setBold(true)->setSize(12);
        $picHeaderRow = $picStart + 2;
        foreach (['PIC', 'Jumlah Kegiatan', 'Rencana', 'Realisasi', 'Sisa', 'Selisih RPD', 'Deviasi Hal. III (%)', 'Status Hal. III', 'Nilai Kinerja', '% Realisasi'] as $idx => $h) {
            $summarySheet->setCellValue(chr(65 + $idx) . $picHeaderRow, $h);
        }
        $summarySheet->getStyle("A{$picHeaderRow}:J{$picHeaderRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E293B']],
        ]);
        $pr = $picHeaderRow + 1;
        foreach ($stats['by_pic'] as $picRow) {
            $summarySheet->setCellValue('A' . $pr, $picRow['pic']);
            $summarySheet->setCellValue('B' . $pr, $picRow['count']);
            $summarySheet->setCellValue('C' . $pr, $picRow['rencana']);
            $summarySheet->setCellValue('D' . $pr, $picRow['realisasi']);
            $summarySheet->setCellValue('E' . $pr, $picRow['sisa']);
            $summarySheet->setCellValue('F' . $pr, $picRow['deviasi'] ?? ($picRow['realisasi'] - $picRow['rencana']));
            $summarySheet->setCellValue('G' . $pr, ($picRow['deviasi_dipa_persen'] ?? 0) / 100);
            $summarySheet->setCellValue('H' . $pr, $picRow['deviasi_dipa_label'] ?? 'Optimal (≤5%)');
            $summarySheet->setCellValue('I' . $pr, $picRow['nilai_deviasi_dipa'] ?? 100);
            $summarySheet->setCellValue('J' . $pr, $picRow['persen'] / 100);
            $summarySheet->getStyle("C{$pr}:F{$pr}")->getNumberFormat()->setFormatCode('#,##0');
            $summarySheet->getStyle("G{$pr}")->getNumberFormat()->setFormatCode('0.0%');
            $summarySheet->getStyle("I{$pr}")->getNumberFormat()->setFormatCode('0.0');
            $summarySheet->getStyle("J{$pr}")->getNumberFormat()->setFormatCode('0.0%');
            $pr++;
        }
        if ($pr > $picHeaderRow + 1) {
            $summarySheet->getStyle("A{$picHeaderRow}:J" . ($pr - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
        foreach (['A' => 18, 'B' => 24, 'C' => 18, 'D' => 20, 'E' => 20, 'F' => 18, 'G' => 18, 'H' => 18, 'I' => 20, 'J' => 14, 'K' => 14] as $col => $w) {
            $summarySheet->getColumnDimension($col)->setWidth($w);
        }

        $spreadsheet->setActiveSheetIndex(0);
        $akunFileTag = (!empty($akunList) && count($akunList) < 3) ? ('Akun_' . implode('_', $akunList) . '_') : '';
        return $this->streamSpreadsheet($spreadsheet, 'Monitoring_Anggaran_' . $akunFileTag . date('Ymd_His') . '.xlsx');
    }

    private function streamSpreadsheet(Spreadsheet $spreadsheet, string $filename)
    {
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $binary = (string) ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($binary);
    }

    private function formatRowForClient(array $row): array
    {
        return [
            'id' => (int) ($row['id_monitoring'] ?? 0),
            'tanggal' => (string) ($row['tanggal'] ?? ''),
            'ro' => (string) ($row['ro'] ?? ''),
            'akun' => (string) ($row['akun'] ?? ''),
            'kegiatan' => (string) ($row['kegiatan'] ?? ''),
            'rencana' => (int) ($row['rencana'] ?? 0),
            'pic' => (string) ($row['pic'] ?? ''),
            'realisasi' => (int) ($row['realisasi'] ?? 0),
            'sumber_input' => (string) ($row['sumber_input'] ?? 'manual'),
            'tindak_lanjut_status' => (string) ($row['tindak_lanjut_status'] ?? ''),
            'tindak_lanjut_catatan' => (string) ($row['tindak_lanjut_catatan'] ?? ''),
            'tindak_lanjut_target_rencana' => isset($row['tindak_lanjut_target_rencana']) && $row['tindak_lanjut_target_rencana'] !== null ? (int) $row['tindak_lanjut_target_rencana'] : null,
            'tindak_lanjut_pengalihan_nominal' => isset($row['tindak_lanjut_pengalihan_nominal']) && $row['tindak_lanjut_pengalihan_nominal'] !== null ? (int) $row['tindak_lanjut_pengalihan_nominal'] : null,
            'tindak_lanjut_pengalihan_bulan' => (string) ($row['tindak_lanjut_pengalihan_bulan'] ?? ''),
            'tindak_lanjut_pengalihan_target_id' => isset($row['tindak_lanjut_pengalihan_target_id']) && $row['tindak_lanjut_pengalihan_target_id'] !== null ? (int) $row['tindak_lanjut_pengalihan_target_id'] : null,
            'tindak_lanjut_pengalihan_info' => (string) ($row['tindak_lanjut_pengalihan_info'] ?? ''),
            'tindak_lanjut_updated_at' => (string) ($row['tindak_lanjut_updated_at'] ?? ''),
            'created_at' => (string) ($row['created_at'] ?? ''),
            'updated_at' => (string) ($row['updated_at'] ?? ''),
        ];
    }

    private function respondValidationError(array $errors)
    {
        $message = implode(' ', array_values($errors));
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $message !== '' ? $message : 'Validasi gagal.',
                'errors' => $errors,
            ])->setStatusCode(400);
        }

        return redirect()->back()->withInput()->with('error', $message);
    }
}
