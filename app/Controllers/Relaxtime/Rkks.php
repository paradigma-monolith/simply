<?php

namespace App\Controllers\Relaxtime;

use App\Controllers\BaseController;
use App\Libraries\RelaxtimeRkksHelper;
use App\Models\RelaxtimeRkksModel;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rkks extends BaseController
{
    protected RelaxtimeRkksModel $rkksModel;

    public function __construct()
    {
        $this->rkksModel = new RelaxtimeRkksModel();
        $this->rkksModel->ensureTables();
    }

    public function index()
    {
        $tahunFilter = (int) ($this->request->getGet('tahun') ?? 0);
        $dokumenList = $this->rkksModel->getDokumenList($tahunFilter > 0 ? $tahunFilter : null);
        $allDokumen = $tahunFilter > 0 ? $this->rkksModel->getDokumenList(null) : $dokumenList;

        $tahunList = [];
        foreach ($allDokumen as $d) {
            $yr = (int) ($d['tahun_anggaran'] ?? 0);
            if ($yr > 0 && !in_array($yr, $tahunList, true)) {
                $tahunList[] = $yr;
            }
        }
        if (empty($tahunList)) {
            $tahunList[] = (int) date('Y');
        }
        rsort($tahunList);

        $requestedDokId = (int) ($this->request->getGet('dokumen') ?? 0);
        $activeDokumen = null;
        if ($requestedDokId > 0) {
            foreach ($dokumenList as $d) {
                if ((int) $d['id_dokumen'] === $requestedDokId) {
                    $activeDokumen = $d;
                    break;
                }
            }
        }
        if ($activeDokumen === null && !empty($dokumenList)) {
            $activeDokumen = $dokumenList[0];
        }

        $items = $activeDokumen
            ? $this->rkksModel->getItemsByDokumen((int) $activeDokumen['id_dokumen'])
            : [];

        $summary = RelaxtimeRkksHelper::buildSummary($items);
        $rekapSubkomponen = RelaxtimeRkksHelper::buildRekapSubkomponen($items);

        $data = [
            'title' => 'SIMANDJA | RKKS (SAKTI)',
            'activeMenu' => 'relaxtimeRkks',
            'dokumenList' => $dokumenList,
            'activeDokumen' => $activeDokumen,
            'items' => $items,
            'summary' => $summary,
            'rekapSubkomponen' => $rekapSubkomponen,
            'tahunList' => $tahunList,
            'tahunFilter' => $tahunFilter,
        ];

        return view('relaxtime/rkks', $data);
    }

    public function importExcel()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            $msg = $file ? $file->getErrorString() : 'File Excel RKKS belum dipilih.';
            return $this->response->setJSON(['success' => false, 'message' => $msg])->setStatusCode(400);
        }

        $ext = strtolower((string) $file->getClientExtension());
        if (!in_array($ext, ['xlsx', 'xls'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format file harus .xlsx atau .xls (ekspor SAKTI Kemenkeu).',
            ])->setStatusCode(400);
        }

        if ($file->getSize() > 15 * 1024 * 1024) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ukuran file melebihi batas maksimal 15 MB.',
            ])->setStatusCode(400);
        }

        $tahun = (int) ($this->request->getPost('tahun_anggaran') ?: date('Y'));
        if ($tahun < 2020 || $tahun > 2100) {
            $tahun = (int) date('Y');
        }

        $namaDok = trim((string) $this->request->getPost('nama_dokumen'));
        if ($namaDok === '') {
            $namaDok = 'RKKS TA ' . $tahun . ' (' . date('d/m H:i') . ')';
        }

        $modeImport = (string) ($this->request->getPost('mode_import') ?: 'new_version');
        $targetDokId = (int) ($this->request->getPost('id_dokumen_target') ?: 0);

        $uploadDir = WRITEPATH . 'uploads';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $randomName = $file->getRandomName();
        $file->move($uploadDir, $randomName);
        $fullPath = rtrim($uploadDir, '\\/') . DIRECTORY_SEPARATOR . $randomName;

        try {
            $parsed = RelaxtimeRkksHelper::parseSaktiWorkbook($fullPath);
        } catch (\Throwable $e) {
            log_message('error', 'Error parsing RKKS Excel: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membaca file Excel RKKS: ' . $e->getMessage(),
            ])->setStatusCode(500);
        } finally {
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }

        $items = $parsed['items'] ?? [];
        if (empty($items)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Struktur RKKS SAKTI tidak ditemukan pada sheet Excel yang diunggah.',
            ])->setStatusCode(400);
        }

        $userId = session()->get('id_user') ?? null;

        if ($modeImport === 'replace_active' && $targetDokId > 0 && $this->rkksModel->getDokumen($targetDokId)) {
            $dokumenId = $targetDokId;
            $this->rkksModel->update($dokumenId, [
                'tahun_anggaran' => $tahun,
                'nama_dokumen' => $namaDok,
            ]);
        } else {
            $dokumenId = $this->rkksModel->createDokumen([
                'tahun_anggaran' => $tahun,
                'nama_dokumen' => $namaDok,
                'kode_satker' => trim((string) ($this->request->getPost('kode_satker') ?? '')),
                'nama_satker' => trim((string) ($this->request->getPost('nama_satker') ?? 'Balai Kekarantinaan Kesehatan Kelas I Bandung')),
                'created_by' => $userId,
            ]);
        }

        $totalPagu = $this->rkksModel->replaceDokumenItems($dokumenId, $items, $userId);

        return $this->response->setJSON([
            'success' => true,
            'id_dokumen' => $dokumenId,
            'inserted_count' => count($items),
            'total_pagu' => $totalPagu,
            'sheet' => $parsed['sheet'] ?? '',
            'summary' => $parsed['summary'] ?? [],
            'message' => 'Berhasil mengimpor ' . count($items) . ' baris hierarki RKKS dari sheet "' . ($parsed['sheet'] ?? '-') . '".',
        ]);
    }

    public function storeDokumen()
    {
        $tahun = (int) ($this->request->getPost('tahun_anggaran') ?: date('Y'));
        $nama = trim((string) $this->request->getPost('nama_dokumen'));
        if ($nama === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama dokumen RKKS wajib diisi.',
            ])->setStatusCode(400);
        }

        $id = $this->rkksModel->createDokumen([
            'tahun_anggaran' => $tahun,
            'nama_dokumen' => $nama,
            'kode_satker' => trim((string) $this->request->getPost('kode_satker')),
            'nama_satker' => trim((string) $this->request->getPost('nama_satker')),
            'created_by' => session()->get('id_user') ?? null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'id_dokumen' => $id,
            'message' => 'Dokumen RKKS baru berhasil dibuat.',
        ]);
    }

    public function deleteDokumen($id)
    {
        $dok = $this->rkksModel->getDokumen((int) $id);
        if (!$dok) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dokumen RKKS tidak ditemukan.'])->setStatusCode(404);
        }

        $this->rkksModel->deleteDokumen((int) $id);
        return $this->response->setJSON(['success' => true, 'message' => 'Dokumen RKKS beserta seluruh isinya berhasil dihapus.']);
    }

    public function storeItem()
    {
        $idDokumen = (int) $this->request->getPost('id_dokumen');
        $dok = $this->rkksModel->getDokumen($idDokumen);
        if (!$dok) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dokumen RKKS tidak ditemukan.'])->setStatusCode(404);
        }

        $levelType = trim((string) $this->request->getPost('level_type'));
        if (!in_array($levelType, RelaxtimeRkksHelper::LEVEL_ORDER, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tipe hierarki RKKS tidak valid.'])->setStatusCode(400);
        }

        $parentIdRaw = $this->request->getPost('parent_id');
        $parentId = ($parentIdRaw !== null && $parentIdRaw !== '' && (int) $parentIdRaw > 0) ? (int) $parentIdRaw : null;

        if ($parentId !== null) {
            $parent = $this->rkksModel->getItem($parentId);
            if (!$parent || (int) $parent['id_dokumen'] !== $idDokumen) {
                return $this->response->setJSON(['success' => false, 'message' => 'Induk hierarki tidak valid.'])->setStatusCode(400);
            }
            $allowed = RelaxtimeRkksHelper::ALLOWED_CHILD_TYPES[$parent['level_type']] ?? [];
            if (!in_array($levelType, $allowed, true)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Level "' . $levelType . '" tidak boleh berada di bawah "' . $parent['level_type'] . '".',
                ])->setStatusCode(400);
            }
        } elseif ($levelType !== 'program') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Node tingkat "' . $levelType . '" harus memiliki induk hierarki.',
            ])->setStatusCode(400);
        }

        $uraian = trim((string) $this->request->getPost('uraian'));
        if ($uraian === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Uraian wajib diisi.'])->setStatusCode(400);
        }

        $kode = trim((string) $this->request->getPost('kode'));
        $headerDepth = 0;
        if ($levelType === 'header_detail') {
            $kode = $kode === '>>' ? '>>' : '>';
            $headerDepth = $kode === '>>' ? 2 : 1;
        } elseif ($levelType === 'detail') {
            $kode = '-';
        } elseif ($kode === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Kode wajib diisi.'])->setStatusCode(400);
        }

        $volRaw = trim((string) $this->request->getPost('volume'));
        $volume = $volRaw !== '' ? (float) str_replace(',', '.', $volRaw) : null;
        $hargaSatuan = RelaxtimeRkksHelper::parseAmount($this->request->getPost('harga_satuan') ?? 0);

        $jumlahRaw = trim((string) $this->request->getPost('jumlah'));
        if ($levelType === 'detail') {
            $jumlah = $jumlahRaw !== ''
                ? RelaxtimeRkksHelper::parseAmount($jumlahRaw)
                : (int) round(($volume ?? 0) * $hargaSatuan);
        } else {
            $jumlah = RelaxtimeRkksHelper::parseAmount($jumlahRaw);
        }

        $isBlokir = $this->request->getPost('is_blokir') ? 1 : 0;
        $nilaiBlokir = $isBlokir
            ? (trim((string) $this->request->getPost('nilai_blokir')) !== '' ? RelaxtimeRkksHelper::parseAmount($this->request->getPost('nilai_blokir')) : $jumlah)
            : 0;

        $newId = $this->rkksModel->insertItemAndRollup([
            'id_dokumen' => $idDokumen,
            'parent_id' => $parentId,
            'level_type' => $levelType,
            'header_depth' => $headerDepth,
            'kode' => strtoupper($kode),
            'uraian' => $uraian,
            'lokasi' => trim((string) $this->request->getPost('lokasi')),
            'kppn' => trim((string) $this->request->getPost('kppn')),
            'jenis_komponen' => strtoupper(trim((string) $this->request->getPost('jenis_komponen'))),
            'volume' => $volume,
            'satuan' => trim((string) $this->request->getPost('satuan')),
            'harga_satuan' => max(0, $hargaSatuan),
            'jumlah' => max(0, $jumlah),
            'sumber_dana' => strtoupper(trim((string) $this->request->getPost('sumber_dana'))),
            'is_blokir' => $isBlokir,
            'nilai_blokir' => max(0, $nilaiBlokir),
            'catatan' => trim((string) $this->request->getPost('catatan')),
            'created_by' => session()->get('id_user') ?? null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'id_item' => $newId,
            'state' => $this->buildLiveState($idDokumen),
            'message' => 'Item RKKS berhasil direkam.',
        ]);
    }

    public function updateItem($id)
    {
        $existing = $this->rkksModel->getItem((int) $id);
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item RKKS tidak ditemukan.'])->setStatusCode(404);
        }

        $uraian = trim((string) $this->request->getPost('uraian'));
        if ($uraian === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Uraian wajib diisi.'])->setStatusCode(400);
        }

        $levelType = $existing['level_type'];
        $kode = trim((string) $this->request->getPost('kode'));
        $headerDepth = (int) $existing['header_depth'];
        if ($levelType === 'header_detail') {
            $kode = $kode === '>>' ? '>>' : '>';
            $headerDepth = $kode === '>>' ? 2 : 1;
        } elseif ($levelType === 'detail') {
            $kode = '-';
        } elseif ($kode === '') {
            $kode = $existing['kode'];
        }

        $volRaw = trim((string) $this->request->getPost('volume'));
        $volume = $volRaw !== '' ? (float) str_replace(',', '.', $volRaw) : null;
        $hargaSatuan = RelaxtimeRkksHelper::parseAmount($this->request->getPost('harga_satuan') ?? 0);

        $jumlahRaw = trim((string) $this->request->getPost('jumlah'));
        if ($levelType === 'detail') {
            $jumlah = $jumlahRaw !== ''
                ? RelaxtimeRkksHelper::parseAmount($jumlahRaw)
                : (int) round(($volume ?? 0) * $hargaSatuan);
        } else {
            $jumlah = $jumlahRaw !== '' ? RelaxtimeRkksHelper::parseAmount($jumlahRaw) : (int) $existing['jumlah'];
        }

        $isBlokir = $this->request->getPost('is_blokir') ? 1 : 0;
        $nilaiBlokir = $isBlokir
            ? (trim((string) $this->request->getPost('nilai_blokir')) !== '' ? RelaxtimeRkksHelper::parseAmount($this->request->getPost('nilai_blokir')) : $jumlah)
            : 0;

        $this->rkksModel->updateItemAndRollup((int) $id, [
            'kode' => strtoupper($kode),
            'uraian' => $uraian,
            'header_depth' => $headerDepth,
            'lokasi' => trim((string) $this->request->getPost('lokasi')),
            'kppn' => trim((string) $this->request->getPost('kppn')),
            'jenis_komponen' => strtoupper(trim((string) $this->request->getPost('jenis_komponen'))),
            'volume' => $volume,
            'satuan' => trim((string) $this->request->getPost('satuan')),
            'harga_satuan' => max(0, $hargaSatuan),
            'jumlah' => max(0, $jumlah),
            'sumber_dana' => strtoupper(trim((string) $this->request->getPost('sumber_dana'))),
            'is_blokir' => $isBlokir,
            'nilai_blokir' => max(0, $nilaiBlokir),
            'catatan' => trim((string) $this->request->getPost('catatan')),
            'updated_by' => session()->get('id_user') ?? null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'id_item' => (int) $id,
            'state' => $this->buildLiveState((int) $existing['id_dokumen']),
            'message' => 'Perubahan item RKKS berhasil disimpan & pagu dihitung ulang.',
        ]);
    }

    public function deleteItem($id)
    {
        $existing = $this->rkksModel->getItem((int) $id);
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item RKKS tidak ditemukan.'])->setStatusCode(404);
        }

        $res = $this->rkksModel->deleteSubtreeAndRollup((int) $id);
        return $this->response->setJSON([
            'success' => true,
            'deleted_count' => $res['deleted_count'],
            'total_pagu' => $res['total_pagu'],
            'state' => $this->buildLiveState((int) $existing['id_dokumen']),
            'message' => $res['deleted_count'] . ' node RKKS berhasil dihapus & pagu diperbarui.',
        ]);
    }

    public function toggleBlokir($id)
    {
        $existing = $this->rkksModel->getItem((int) $id);
        if (!$existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item RKKS tidak ditemukan.'])->setStatusCode(404);
        }

        $newFlag = empty($existing['is_blokir']) ? 1 : 0;
        $newVal = $newFlag ? (int) $existing['jumlah'] : 0;

        $this->rkksModel->updateItemAndRollup((int) $id, [
            'is_blokir' => $newFlag,
            'nilai_blokir' => $newVal,
            'updated_by' => session()->get('id_user') ?? null,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'is_blokir' => $newFlag,
            'state' => $this->buildLiveState((int) $existing['id_dokumen']),
            'message' => $newFlag ? 'Item ditandai Blokir (*).' : 'Status Blokir (*) dilepas.',
        ]);
    }

    private function buildLiveState(int $idDokumen): array
    {
        $items = $this->rkksModel->getItemsByDokumen($idDokumen);
        $itemsJs = [];
        foreach ($items as $it) {
            $itemsJs[] = [
                'id' => (int) $it['id_item'],
                'parent_id' => $it['parent_id'] !== null ? (int) $it['parent_id'] : null,
                'level' => (string) $it['level_type'],
                'header_depth' => (int) ($it['header_depth'] ?? 0),
                'kode' => (string) ($it['kode'] ?? ''),
                'uraian' => (string) ($it['uraian'] ?? ''),
                'lokasi' => (string) ($it['lokasi'] ?? ''),
                'kppn' => (string) ($it['kppn'] ?? ''),
                'jenis_komponen' => (string) ($it['jenis_komponen'] ?? ''),
                'volume' => $it['volume'] !== null ? (float) $it['volume'] : null,
                'satuan' => (string) ($it['satuan'] ?? ''),
                'harga_satuan' => (int) ($it['harga_satuan'] ?? 0),
                'jumlah' => (int) ($it['jumlah'] ?? 0),
                'sumber_dana' => (string) ($it['sumber_dana'] ?? ''),
                'is_blokir' => !empty($it['is_blokir']) ? 1 : 0,
                'nilai_blokir' => (int) ($it['nilai_blokir'] ?? 0),
                'catatan' => (string) ($it['catatan'] ?? ''),
            ];
        }

        return [
            'items' => $itemsJs,
            'summary' => RelaxtimeRkksHelper::buildSummary($items),
            'rekap' => RelaxtimeRkksHelper::buildRekapSubkomponen($items),
        ];
    }

    public function exportExcel()
    {
        $dokId = (int) ($this->request->getGet('dokumen') ?? 0);
        $dok = $dokId > 0 ? $this->rkksModel->getDokumen($dokId) : null;
        if ($dok === null) {
            $list = $this->rkksModel->getDokumenList(null);
            $dok = $list[0] ?? null;
        }
        if ($dok === null) {
            return redirect()->to('/relaxtime/rkks')->with('error', 'Belum ada dokumen RKKS untuk diekspor.');
        }

        $items = $this->rkksModel->getItemsByDokumen((int) $dok['id_dokumen']);
        $rekapRows = RelaxtimeRkksHelper::buildRekapSubkomponen($items);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RKK_SATKER');

        $sheet->setCellValue('A1', 'RINCIAN KERTAS KERJA SATKER (RKKS) - ' . $dok['nama_dokumen']);
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);

        $headers = ['Kode', 'Level', 'Uraian / Rincian', 'Volume', 'Satuan', 'Harga Satuan', 'Jumlah Pagu', 'SD', 'Blokir'];
        foreach ($headers as $idx => $h) {
            $sheet->setCellValue(chr(65 + $idx) . '3', $h);
        }
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $r = 4;
        $levelBg = [
            'program' => 'DBEAFE',
            'kegiatan' => 'E2E8F0',
            'kro' => 'E0E7FF',
            'ro' => 'E0F2FE',
            'komponen' => 'CCFBF1',
            'subkomponen' => 'ECFDF5',
            'akun' => 'FEF3C7',
            'header_detail' => 'F8FAFC',
        ];

        foreach ($items as $item) {
            $lvl = $item['level_type'];
            $indent = match ($lvl) {
                'kegiatan' => '  ',
                'kro' => '    ',
                'ro' => '      ',
                'komponen' => '        ',
                'subkomponen' => '          ',
                'akun' => '            ',
                'header_detail' => ((int) $item['header_depth'] === 2 ? '                >> ' : '              > '),
                'detail' => '                - ',
                default => '',
            };

            $sheet->setCellValueExplicit('A' . $r, (string) $item['kode'], DataType::TYPE_STRING);
            $sheet->setCellValue('B' . $r, RelaxtimeRkksHelper::LEVEL_LABELS[$lvl] ?? $lvl);
            $sheet->setCellValue('C' . $r, $indent . $item['uraian']);
            if ($item['volume'] !== null) {
                $sheet->setCellValue('D' . $r, (float) $item['volume']);
            }
            $sheet->setCellValue('E' . $r, (string) $item['satuan']);
            if ((int) $item['harga_satuan'] > 0) {
                $sheet->setCellValue('F' . $r, (int) $item['harga_satuan']);
                $sheet->getStyle('F' . $r)->getNumberFormat()->setFormatCode('#,##0');
            }
            $sheet->setCellValue('G' . $r, (int) $item['jumlah']);
            $sheet->getStyle('G' . $r)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->setCellValue('H' . $r, (string) $item['sumber_dana']);
            $sheet->setCellValue('I' . $r, !empty($item['is_blokir']) ? '*' : '');

            if (isset($levelBg[$lvl])) {
                $sheet->getStyle("A{$r}:I{$r}")->applyFromArray([
                    'font' => ['bold' => $lvl !== 'header_detail'],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $levelBg[$lvl]]],
                ]);
            }
            $r++;
        }

        if ($r > 4) {
            $sheet->getStyle('A3:I' . ($r - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
        foreach (['A' => 16, 'B' => 14, 'C' => 64, 'D' => 11, 'E' => 12, 'F' => 16, 'G' => 18, 'H' => 8, 'I' => 8] as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Sheet 2: REKAP (seperti Sheet REKAP SAKTI)
        $rekapSheet = $spreadsheet->createSheet();
        $rekapSheet->setTitle('REKAP');
        $rekapSheet->setCellValue('A1', 'REKAP RINCIAN OUTPUT & SUB KOMPONEN - ' . $dok['nama_dokumen']);
        $rekapSheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $rekapHeaders = ['No', 'RINCIAN OUTPUT', 'SUB KOMPONEN', 'KEGIATAN', 'AKUN', 'SUMBER DANA', 'PAGU ANGGARAN'];
        foreach ($rekapHeaders as $idx => $h) {
            $rekapSheet->setCellValue(chr(65 + $idx) . '3', $h);
        }
        $rekapSheet->getStyle('A3:G3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']],
        ]);

        $rr = 4;
        $no = 1;
        foreach ($rekapRows as $rk) {
            $rekapSheet->setCellValue('A' . $rr, $no++);
            $rekapSheet->setCellValueExplicit('B' . $rr, (string) $rk['ro_komponen'], DataType::TYPE_STRING);
            $rekapSheet->setCellValueExplicit('C' . $rr, (string) $rk['subkomponen_kode'], DataType::TYPE_STRING);
            $rekapSheet->setCellValue('D' . $rr, (string) $rk['kegiatan']);
            $rekapSheet->setCellValueExplicit('E' . $rr, (string) $rk['akun'], DataType::TYPE_STRING);
            $rekapSheet->setCellValue('F' . $rr, (string) $rk['sumber_dana']);
            $rekapSheet->setCellValue('G' . $rr, (int) $rk['pagu']);
            $rekapSheet->getStyle('G' . $rr)->getNumberFormat()->setFormatCode('#,##0');
            $rr++;
        }
        if ($rr > 4) {
            $last = $rr - 1;
            $rekapSheet->setCellValue("A{$rr}", 'TOTAL');
            $rekapSheet->mergeCells("A{$rr}:F{$rr}");
            $rekapSheet->setCellValue("G{$rr}", "=SUM(G4:G{$last})");
            $rekapSheet->getStyle("A{$rr}:G{$rr}")->getFont()->setBold(true);
            $rekapSheet->getStyle("G{$rr}")->getNumberFormat()->setFormatCode('#,##0');
            $rekapSheet->getStyle("A3:G{$rr}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
        foreach (['A' => 6, 'B' => 20, 'C' => 15, 'D' => 60, 'E' => 10, 'F' => 14, 'G' => 20] as $col => $w) {
            $rekapSheet->getColumnDimension($col)->setWidth($w);
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $binary = (string) ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="RKKS_' . $dok['tahun_anggaran'] . '_' . date('Ymd_His') . '.xlsx"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($binary);
    }
}
