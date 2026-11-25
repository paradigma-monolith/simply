<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;
use App\Libraries\RelaxtimeMonitoringAnggaranHelper;
use App\Libraries\RelaxtimeRkksHelper;
use App\Models\RelaxtimeMonitoringAnggaranModel;
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

        $data = [
            'title' => 'RELAXTIME | Monitoring Anggaran',
            'activeMenu' => 'relaxtimeMonitoringAnggaran',
            'rows' => $filteredRows,
            'allDateRows' => $allDateRows,
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
        $allMonths = $this->request->getGet('all_months') === '1'
            || $this->request->getGet('all_dates') === '1';

        $range = RelaxtimeMonitoringAnggaranHelper::normalizeMonthRange(
            $this->request->getGet('bulan_mulai') ?? $this->request->getGet('tgl_mulai'),
            $this->request->getGet('bulan_selesai') ?? $this->request->getGet('tgl_selesai'),
            $allMonths
        );

        $akunFilter = RelaxtimeMonitoringAnggaranHelper::normalizeAkun($this->request->getGet('akun') ?? '', '');
        $picFilter = RelaxtimeMonitoringAnggaranHelper::normalizePic($this->request->getGet('pic') ?? '');

        $rows = $this->monitoringModel->getFiltered([
            'tgl_mulai' => $allMonths ? '' : $range['start_date'],
            'tgl_selesai' => $allMonths ? '' : $range['end_date'],
            'akun' => $akunFilter,
            'pic' => $picFilter,
        ]);

        $stats = RelaxtimeMonitoringAnggaranHelper::buildDashboardStats($rows);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Monitoring Anggaran');

        $sheet->setCellValue('A1', 'LAPORAN MONITORING ANGGARAN - RELAXTIME (ATURAN DEVIASI HAL. III DIPA PER-5/PB/2024)');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('0F172A');

        $periodeLabel = $allMonths
            ? 'Semua Bulan'
            : (RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($range['start_month'])
                . ' s.d. '
                . RelaxtimeMonitoringAnggaranHelper::formatMonthLabel($range['end_month']));
        $filterInfo = sprintf(
            'Periode Bulan: %s | Akun: %s | PIC: %s | Diekspor: %s',
            $periodeLabel,
            $akunFilter !== '' ? $akunFilter : 'Semua',
            $picFilter !== '' ? $picFilter : 'Semua',
            date('d/m/Y H:i')
        );
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2')->getFont()->setSize(10)->getColor()->setRGB('475569');

        $headers = [
            'No', 'Bulan', 'RO', 'Akun', 'Kegiatan', 'PIC',
            'Rencana Pencairan', 'Realisasi', 'Sisa',
            'Deviasi Hal. III (%)', 'Status Hal. III DIPA', 'Nilai Kinerja', '% Realisasi',
        ];
        foreach ($headers as $idx => $h) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '4', $h);
        }
        $sheet->getStyle('A4:M4')->applyFromArray([
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

            $sheet->getStyle("G{$rowNum}:I{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
            $sheet->getStyle("L{$rowNum}")->getNumberFormat()->setFormatCode('0.0');
            $sheet->getStyle("M{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
            $sheet->getStyle("A{$rowNum}:D{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$rowNum}")->getAlignment()->setWrapText(true);

            if ($sisa < 0) {
                $sheet->getStyle("I{$rowNum}")->getFont()->getColor()->setRGB('DC2626');
            }
            $dipaRgb = $dipa['status'] === 'optimal' ? '15803D' : ($dipa['status'] === 'waspada' ? 'B45309' : 'DC2626');
            $sheet->getStyle("J{$rowNum}:K{$rowNum}")->getFont()->getColor()->setRGB($dipaRgb);
            $rowNum++;
        }

        if ($rowNum === 5) {
            $sheet->setCellValue('A5', 'Tidak ada data untuk filter terpilih.');
            $sheet->mergeCells('A5:M5');
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
            $sheet->getStyle("A{$rowNum}:M{$rowNum}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
            ]);
            $sheet->getStyle("G{$rowNum}:I{$rowNum}")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("J{$rowNum}")->getNumberFormat()->setFormatCode('0.00%');
            $sheet->getStyle("L{$rowNum}")->getNumberFormat()->setFormatCode('0.00');
            $sheet->getStyle("M{$rowNum}")->getNumberFormat()->setFormatCode('0.0%');
        }

        $sheet->getStyle("A4:M{$rowNum}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        foreach (['A' => 6, 'B' => 16, 'C' => 18, 'D' => 8, 'E' => 48, 'F' => 16, 'G' => 18, 'H' => 18, 'I' => 18, 'J' => 18, 'K' => 20, 'L' => 14, 'M' => 13] as $col => $w) {
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
        return $this->streamSpreadsheet($spreadsheet, 'Monitoring_Anggaran_' . date('Ymd_His') . '.xlsx');
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
