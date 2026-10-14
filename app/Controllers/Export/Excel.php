<?php

namespace App\Controllers\Export;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class Excel extends BaseController
{
  public function column()
  {
    $columns = [];

    for ($i = 'A'; $i !== 'AA'; $i++) {
      $columns[] = $i;
    }

    for ($i = 'AA'; $i !== 'BA'; $i++) {
      $columns[] = $i;
    }

    return $columns;
  }
  public function infografis_laporan() 
  {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('harian');
    
    $kolom = $this->column();
    
    $headTable = [
        'No',
        'Tanggal',
        'Unit Kerja',
        'Kapal Datang Dari Dalam Negeri',
        'Kapal Berangkat Ke Dalam Negeri',
        'Abk Datang Dari Dalam Negeri',
        'Abk Berangkat Ke Dalam Negeri',
        'Penumpang Datang Dari Dalam Negeri (Kapal)',
        'Penumpang Berangkat Ke Dalam Negeri (Kapal)',
        'Kapal Datang Dari Luar Negeri',
        'Kapal Berangkat Ke Luar Negeri',
        'Abk Datang Dari Luar Negeri',
        'Abk Berangkat Ke Luar Negeri',
        'Penumpang Datang Dari Luar Negeri (Kapal)',
        'Penumpang Berangkat Ke Luar Negeri (Kapal)',
        'Pesawat Datang Dari Dalam Negeri',
        'Pesawat Berangkat Ke Dalam Negeri',
        'Crew Datang Dari Dalam Negeri',
        'Crew Berangkat Ke Dalam Negeri',
        'Penumpang Datang Dari Dalam Negeri (Pesawat)',
        'Penumpang Berangkat Ke Dalam Negeri (Pesawat)',
        'Pesawat Datang Dari Luar Negeri',
        'Pesawat Berangkat Ke Luar Negeri',
        'Crew Datang Dari Luar Negeri',
        'Crew Berangkat Ke Luar Negeri',
        'Penumpang Datang Dari Luar Negeri (Pesawat)',
        'Penumpang Berangkat Ke Luar Negeri (Pesawat)',
        'Phqc',
        'Cop',
        'Sscec',
        'Sscc',
        'P3k',
        'Icv',
        'Sklt',
        'Siaos',
        'Siaj',
        'Kir',
        'Vaksin Mm',
        'Vaksin Yf',
        'Vaksin Covid',
        'Vaksin Influenza',
        'Vaksin Tifoid',
        'Bukes',
        'Kontraindikasi',
        'Elektronik Icv',
    ];

    $baris = 1;
    $kolomAkhir = "";
    
    $date = $this->request->getVar('tanggal-infografis');
    $date_awal = substr($date, 0, 10);
    $date_akhir = substr($date, 13, 10);
    $getData = $this->queryModel->getAllInfografis($date_awal, $date_akhir);
    
    for ($i=0; $i<count($headTable); $i++) {
        $kolomAkhir = $kolom[$i] . $baris;
        if(in_array($kolom[$i], ["A", "B", "C"])) {
            $sheet->getColumnDimension($kolom[$i])->setAutoSize(true);
        } else {
            $sheet->getStyle($kolom[$i])->getAlignment()->setWrapText(true);
            $sheet->getColumnDimension($kolom[$i])->setWidth(7.5);
        }
        $sheet->setCellValue($kolomAkhir, $headTable[$i]);
    }
    
    $baris = 2;
    $nomor = 1;
    foreach($getData as $data) {
        $i=0;
        
        // $sheet->getStyle($kolom[$i++] . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF0000");
        
        $sheet->setCellValue($kolom[$i++] . $baris, $nomor++);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['tanggal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['unit_kerja']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kapal_datang_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kapal_berangkat_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['abk_datang_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['abk_berangkat_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_datang_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_berangkat_negeri_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kapal_datang_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kapal_berangkat_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['abk_datang_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['abk_berangkat_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_datang_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_berangkat_luar_kapal']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['pesawat_datang_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['pesawat_berangkat_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['crew_datang_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['crew_berangkat_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_datang_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_berangkat_negeri_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['pesawat_datang_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['pesawat_berangkat_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['crew_datang_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['crew_berangkat_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_datang_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['penumpang_berangkat_luar_pesawat']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['phqc']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['cop']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['sscec']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['sscc']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['p3k']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['icv']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['sklt']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['siaos']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['siaj']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kir']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['vaksin_mm']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['vaksin_yf']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['vaksin_covid']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['vaksin_influenza']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['vaksin_tifoid']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['bukes']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['kontraindikasi']);
        $sheet->setCellValue($kolom[$i++] . $baris, $data['e_icv']);
        
        $baris++;
    }
    
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];
    $sheet->getStyle('A1:' . $kolom[$i-1].($baris-1))->applyFromArray($styleArray);
    

    // Sheet 2: "rekap"
    $sheetRekap = $spreadsheet->createSheet(); // Membuat sheet baru
    $sheetRekap->setTitle('rekap kapal');

    // Contoh data untuk sheet "rekap"
    $sheetRekap->setCellValue('A1', 'WILAYAH KERJA');
    $sheetRekap->setCellValue('B1', 'KEDATANGAN');
    $sheetRekap->setCellValue('K1', 'KEBERANGKATAN');
    $sheetRekap->setCellValue('B2', 'DALAM NEGERI');
    $sheetRekap->setCellValue('E2', 'LUAR NEGERI');
    $sheetRekap->setCellValue('H2', 'LUAR NEGERI TERJANGKIT');
    $sheetRekap->setCellValue('K2', 'DALAM NEGERI');
    $sheetRekap->setCellValue('N2', 'LUAR NEGERI');
    $sheetRekap->setCellValue('Q2', 'LUAR NEGERI TERJANGKIT');
    $sheetRekap->setCellValue('B3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('C3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('D3', 'JUMLAH PENUMPANG');
    $sheetRekap->setCellValue('E3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('F3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('G3', 'JUMLAH PENUMPANG');
    $sheetRekap->setCellValue('H3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('I3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('J3', 'JUMLAH PENUMPANG');
    $sheetRekap->setCellValue('K3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('L3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('M3', 'JUMLAH PENUMPANG');
    $sheetRekap->setCellValue('N3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('O3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('P3', 'JUMLAH PENUMPANG');
    $sheetRekap->setCellValue('Q3', 'JUMLAH KAPAL');
    $sheetRekap->setCellValue('R3', 'JUMLAH KRU');
    $sheetRekap->setCellValue('S3', 'JUMLAH PENUMPANG');
    
    $sheetRekap->mergeCells('A1:A3');
    $sheetRekap->mergeCells('B1:J1');
    $sheetRekap->mergeCells('K1:S1');
    $sheetRekap->mergeCells('B2:D2');
    $sheetRekap->mergeCells('E2:G2');
    $sheetRekap->mergeCells('H2:J2');
    $sheetRekap->mergeCells('K2:M2');
    $sheetRekap->mergeCells('N2:P2');
    $sheetRekap->mergeCells('Q2:S2');
    
    $sheetRekap->setCellValue('A4', 'Pelabuhan Laut Balongan');
    $sheetRekap->setCellValue('A5', 'Pelabuhan Laut Cirebon');
    $sheetRekap->setCellValue('A6', 'Pelabuhan Laut Indramayu');
    $sheetRekap->setCellValue('A7', 'Pelabuhan Patimban');
    $sheetRekap->setCellValue('A8', 'Pelabuhan Ratu Sukabumi');
    
    $sheetRekap->getStyle("A1:S8")->applyFromArray($styleArray);
    $sheetRekap->getStyle("A1:S8")->getAlignment()->setWrapText(true);
    $sheetRekap->getColumnDimension("A")->setWidth(20);
    $sheetRekap->getColumnDimension("B")->setWidth(14);
    $sheetRekap->getColumnDimension("C")->setWidth(14);
    $sheetRekap->getColumnDimension("D")->setWidth(14);
    $sheetRekap->getColumnDimension("E")->setWidth(14);
    $sheetRekap->getColumnDimension("F")->setWidth(14);
    $sheetRekap->getColumnDimension("G")->setWidth(14);
    $sheetRekap->getColumnDimension("H")->setWidth(14);
    $sheetRekap->getColumnDimension("I")->setWidth(14);
    $sheetRekap->getColumnDimension("J")->setWidth(14);
    $sheetRekap->getColumnDimension("K")->setWidth(14);
    $sheetRekap->getColumnDimension("L")->setWidth(14);
    $sheetRekap->getColumnDimension("M")->setWidth(14);
    $sheetRekap->getColumnDimension("N")->setWidth(14);
    $sheetRekap->getColumnDimension("O")->setWidth(14);
    $sheetRekap->getColumnDimension("P")->setWidth(14);
    $sheetRekap->getColumnDimension("Q")->setWidth(14);
    $sheetRekap->getColumnDimension("R")->setWidth(14);
    $sheetRekap->getColumnDimension("S")->setWidth(14);
    
    
    $sheetRekap->setCellValue('B4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!D2:D1000)');
    $sheetRekap->setCellValue('C4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!F2:F1000)');
    $sheetRekap->setCellValue('D4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!H2:H1000)');
    $sheetRekap->setCellValue('E4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!J2:J1000)');
    $sheetRekap->setCellValue('F4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!L2:L1000)');
    $sheetRekap->setCellValue('G4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!N2:N1000)');
    $sheetRekap->setCellValue('K4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!E2:E1000)');
    $sheetRekap->setCellValue('L4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!G2:G1000)');
    $sheetRekap->setCellValue('M4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!I2:I1000)');
    $sheetRekap->setCellValue('N4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!K2:K1000)');
    $sheetRekap->setCellValue('O4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!M2:M1000)');
    $sheetRekap->setCellValue('P4', '=SUMIF(harian!C2:C1000, "BALONGAN ", harian!O2:O1000)');

    $sheetRekap->setCellValue('B5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!D2:D1000)');
    $sheetRekap->setCellValue('C5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!F2:F1000)');
    $sheetRekap->setCellValue('D5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!H2:H1000)');
    $sheetRekap->setCellValue('E5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!J2:J1000)');
    $sheetRekap->setCellValue('F5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!L2:L1000)');
    $sheetRekap->setCellValue('G5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!N2:N1000)');
    $sheetRekap->setCellValue('K5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!E2:E1000)');
    $sheetRekap->setCellValue('L5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!G2:G1000)');
    $sheetRekap->setCellValue('M5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!I2:I1000)');
    $sheetRekap->setCellValue('N5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!K2:K1000)');
    $sheetRekap->setCellValue('O5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!M2:M1000)');
    $sheetRekap->setCellValue('P5', '=SUMIF(harian!C2:C1000, "CIREBON", harian!O2:O1000)');

    $sheetRekap->setCellValue('B6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!D2:D1000)');
    $sheetRekap->setCellValue('C6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!F2:F1000)');
    $sheetRekap->setCellValue('D6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!H2:H1000)');
    $sheetRekap->setCellValue('E6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!J2:J1000)');
    $sheetRekap->setCellValue('F6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!L2:L1000)');
    $sheetRekap->setCellValue('G6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!N2:N1000)');
    $sheetRekap->setCellValue('K6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!E2:E1000)');
    $sheetRekap->setCellValue('L6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!G2:G1000)');
    $sheetRekap->setCellValue('M6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!I2:I1000)');
    $sheetRekap->setCellValue('N6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!K2:K1000)');
    $sheetRekap->setCellValue('O6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!M2:M1000)');
    $sheetRekap->setCellValue('P6', '=SUMIF(harian!C2:C1000, "INDRAMAYU", harian!O2:O1000)');

    $sheetRekap->setCellValue('B7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!D2:D1000)');
    $sheetRekap->setCellValue('C7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!F2:F1000)');
    $sheetRekap->setCellValue('D7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!H2:H1000)');
    $sheetRekap->setCellValue('E7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!J2:J1000)');
    $sheetRekap->setCellValue('F7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!L2:L1000)');
    $sheetRekap->setCellValue('G7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!N2:N1000)');
    $sheetRekap->setCellValue('K7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!E2:E1000)');
    $sheetRekap->setCellValue('L7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!G2:G1000)');
    $sheetRekap->setCellValue('M7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!I2:I1000)');
    $sheetRekap->setCellValue('N7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!K2:K1000)');
    $sheetRekap->setCellValue('O7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!M2:M1000)');
    $sheetRekap->setCellValue('P7', '=SUMIF(harian!C2:C1000, "PATIMBAN", harian!O2:O1000)');

    $sheetRekap->setCellValue('B8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!D2:D1000)');
    $sheetRekap->setCellValue('C8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!F2:F1000)');
    $sheetRekap->setCellValue('D8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!H2:H1000)');
    $sheetRekap->setCellValue('E8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!J2:J1000)');
    $sheetRekap->setCellValue('F8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!L2:L1000)');
    $sheetRekap->setCellValue('G8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!N2:N1000)');
    $sheetRekap->setCellValue('K8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!E2:E1000)');
    $sheetRekap->setCellValue('L8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!G2:G1000)');
    $sheetRekap->setCellValue('M8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!I2:I1000)');
    $sheetRekap->setCellValue('N8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!K2:K1000)');
    $sheetRekap->setCellValue('O8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!M2:M1000)');
    $sheetRekap->setCellValue('P8', '=SUMIF(harian!C2:C1000, "PELABUHAN RATU", harian!O2:O1000)');

    $centerStyle = [
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      ],
    ];
    $sheetRekap->getStyle('A1:S8')->applyFromArray($centerStyle);

    $spreadsheet->setActiveSheetIndexByName('rekap kapal');
    // $sheet->mergeCells('A1:A3');


    // $sheet->getRowDimension(1)->setRowHeight(70);
    // $sheet->getColumnDimension('A')->setWidth(7.5);


    $namaFile = "Laporan Infografis (" . $date . ").xlsx";
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
    
  private function contoh()
  {
    $contoh = $this->infografisKapalModel->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Hello World 1');
    $sheet->setCellValue('B1', 'Hello World 2');
    $sheet->setCellValue('C1', 'Hello World 3');
    $sheet->setCellValue('D1', 'Hello World 4');
    $sheet->setCellValue('E1', 'Hello World 5');
    $sheet->setCellValue('F1', 'Hello World 6');
    $sheet->setCellValue('G1', '');

    $column = 2; // start kolom 
    foreach ($contoh as $key => $data) {
      $sheet->setCellValue('A' . $column, ($column - 1));
      $sheet->setCellValue('B' . $column, $data['created_at']);
      $sheet->setCellValue('C' . $column, $data['created_at']);
      $sheet->setCellValue('D' . $column, $data['created_at']);
      $sheet->setCellValue('E' . $column, $data['created_at']);
      $sheet->setCellValue('F' . $column, $data['created_at']);
      $sheet->setCellValue('G' . $column, '');
      $column++;
    }

    // title bold
    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    // title background color
    $sheet->getStyle('A1:F1')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('FFFFFF00');
    $sheet->getStyle('G1:G' . ($column) - 1)->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('00000000');

    // bordered
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '00000000'],
        ],
      ],
    ];
    $sheet->getStyle('A1:F' . ($column - 1))->applyFromArray($styleArray);

    // auto size
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setWidth(0.25);

    $namaFile = 'contacts.xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  public function infografis()
  {
    $date = $this->request->getVar('tanggal-infografis');

    $date_awal = substr($date, 0, 10);
    $date_akhir = substr($date, 13, 10);
    
    // Ubah format tanggal pada $date_awal dan $date_akhir
    $date_awal = date('Y-m-d 00:00:00', strtotime($date_awal));
    $date_akhir = date('Y-m-d 23:59:59', strtotime($date_akhir));

    $year = '';

    if (substr($date_awal, 0, 10) == substr($date_akhir, 0, 10)) {
      $year = substr($date_awal, 0, 10);
    } else {
      $year = substr($date_awal, 0, 10) . ' - ' . substr($date_akhir, 0, 10);
    }


    $kapal = $this->infografisKapalModel->select('
    unit_kerja,
      SUM(kapal_datang_negeri) AS kapal_datang_negeri,
      SUM(kapal_datang_luar) AS kapal_datang_luar,
      SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri,
      SUM(kapal_berangkat_luar) AS kapal_berangkat_luar,
      SUM(abk_datang_negeri) AS abk_datang_negeri,
      SUM(abk_datang_luar) AS abk_datang_luar,
      SUM(abk_berangkat_negeri) AS abk_berangkat_negeri,
      SUM(abk_berangkat_luar) AS abk_berangkat_luar,
      SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
      SUM(penumpang_datang_luar) AS penumpang_datang_luar,
      SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
      SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->groupBy('unit_kerja')->findAll();
    $pesawat = $this->infografisPesawatModel->select('
    unit_kerja,
      SUM(pesawat_datang_negeri) AS pesawat_datang_negeri,
      SUM(pesawat_datang_luar) AS pesawat_datang_luar,
      SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri,
      SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar,
      SUM(crew_datang_negeri) AS crew_datang_negeri,
      SUM(crew_datang_luar) AS crew_datang_luar,
      SUM(crew_berangkat_negeri) AS crew_berangkat_negeri,
      SUM(crew_berangkat_luar) AS crew_berangkat_luar,
      SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
      SUM(penumpang_datang_luar) AS penumpang_datang_luar,
      SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
      SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->groupBy('unit_kerja')->findAll();
    $vaksin = $this->infografisVaksinModel->select('
    unit_kerja,
      SUM(phqc) AS phqc,
      SUM(cop) AS cop,
      SUM(sscec) AS sscec,
      SUM(sscc) AS sscc,
      SUM(p3k) AS p3k,
      SUM(icv) AS icv,
      SUM(sklt) AS sklt,
      SUM(siaos) AS siaos,
      SUM(siaj) AS siaj,
      SUM(kir) AS kir,
      SUM(vaksin_mm) AS vaksin_mm,
      SUM(vaksin_yf) AS vaksin_yf,
      SUM(vaksin_covid) AS vaksin_covid,
      SUM(bukes) AS bukes,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->groupBy('unit_kerja')->findAll();
    $total_kapal = $this->infografisKapalModel->select('
    SUM(kapal_datang_negeri) AS kapal_datang_negeri,
      SUM(kapal_datang_luar) AS kapal_datang_luar,
      SUM(kapal_berangkat_negeri) AS kapal_berangkat_negeri,
      SUM(kapal_berangkat_luar) AS kapal_berangkat_luar,
      SUM(abk_datang_negeri) AS abk_datang_negeri,
      SUM(abk_datang_luar) AS abk_datang_luar,
      SUM(abk_berangkat_negeri) AS abk_berangkat_negeri,
      SUM(abk_berangkat_luar) AS abk_berangkat_luar,
      SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
      SUM(penumpang_datang_luar) AS penumpang_datang_luar,
      SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
      SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->findAll();
    $total_pesawat = $this->infografisPesawatModel->select('
    SUM(pesawat_datang_negeri) AS pesawat_datang_negeri,
      SUM(pesawat_datang_luar) AS pesawat_datang_luar,
      SUM(pesawat_berangkat_negeri) AS pesawat_berangkat_negeri,
      SUM(pesawat_berangkat_luar) AS pesawat_berangkat_luar,
      SUM(crew_datang_negeri) AS crew_datang_negeri,
      SUM(crew_datang_luar) AS crew_datang_luar,
      SUM(crew_berangkat_negeri) AS crew_berangkat_negeri,
      SUM(crew_berangkat_luar) AS crew_berangkat_luar,
      SUM(penumpang_datang_negeri) AS penumpang_datang_negeri,
      SUM(penumpang_datang_luar) AS penumpang_datang_luar,
      SUM(penumpang_berangkat_negeri) AS penumpang_berangkat_negeri,
      SUM(penumpang_berangkat_luar) AS penumpang_berangkat_luar,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->findAll();
    $total_vaksin = $this->infografisVaksinModel->select('
    SUM(phqc) AS phqc,
      SUM(cop) AS cop,
      SUM(sscec) AS sscec,
      SUM(sscc) AS sscc,
      SUM(p3k) AS p3k,
      SUM(icv) AS icv,
      SUM(sklt) AS sklt,
      SUM(siaos) AS siaos,
      SUM(siaj) AS siaj,
      SUM(kir) AS kir,
      SUM(vaksin_mm) AS vaksin_mm,
      SUM(vaksin_yf) AS vaksin_yf,
      SUM(vaksin_covid) AS vaksin_covid,
      SUM(bukes) AS bukes,
      date(created_at) AS created_at
      ')->where('created_at BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')->findAll();
    $unit_kerja = $this->unitKerjaModel->findAll();

    if ($kapal == null && $pesawat == null && $vaksin == null) {
      session()->setFlashdata('error', 'Data pada tanggal ' . $date_awal . ' sampai ' . $date_akhir . ' tidak ditemukan.');
      return redirect()->to($_SERVER['HTTP_REFERER']);
    }

    // Proses Export Excel

    $spreadsheet = new Spreadsheet();
    $spreadsheet->createSheet();
    // Create a new worksheet called "My Data"
    $key = 0;
    foreach ($unit_kerja as $sheets) :
      // sheet
      $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, substr($sheets['unit_kerja'], 0, 30));
      $spreadsheet->addSheet($sheet, $key++);
      $this->template_infografis($sheet, $year);
      foreach ($kapal as $aKapal) {
        if ($sheets['unit_kerja'] == $aKapal['unit_kerja']) {

          $sheet->setCellValue('B4', $aKapal['kapal_datang_luar']);
          $sheet->setCellValue('B5', $aKapal['kapal_datang_negeri']);
          $sheet->setCellValue('B6', $aKapal['kapal_datang_luar'] + $aKapal['kapal_datang_negeri']);
          $sheet->setCellValue('C4', $aKapal['abk_datang_luar']);
          $sheet->setCellValue('C5', $aKapal['abk_datang_negeri']);
          $sheet->setCellValue('C6', $aKapal['abk_datang_luar'] + $aKapal['abk_datang_negeri']);
          $sheet->setCellValue('D4', $aKapal['penumpang_datang_luar']);
          $sheet->setCellValue('D5', $aKapal['penumpang_datang_negeri']);
          $sheet->setCellValue('D6', $aKapal['penumpang_datang_luar'] + $aKapal['penumpang_datang_negeri']);
          $sheet->setCellValue('B7', $aKapal['kapal_berangkat_luar']);
          $sheet->setCellValue('B8', $aKapal['kapal_berangkat_negeri']);
          $sheet->setCellValue('B9', $aKapal['kapal_berangkat_luar'] + $aKapal['kapal_berangkat_negeri']);
          $sheet->setCellValue('C7', $aKapal['abk_berangkat_luar']);
          $sheet->setCellValue('C8', $aKapal['abk_berangkat_negeri']);
          $sheet->setCellValue('C9', $aKapal['abk_berangkat_luar'] + $aKapal['abk_berangkat_negeri']);
          $sheet->setCellValue('D7', $aKapal['penumpang_berangkat_luar']);
          $sheet->setCellValue('D8', $aKapal['penumpang_berangkat_negeri']);
          $sheet->setCellValue('D9', $aKapal['penumpang_berangkat_luar'] + $aKapal['penumpang_berangkat_negeri']);
        }
      }
      foreach ($pesawat as $aPesawat) {
        if ($sheets['unit_kerja'] == $aPesawat['unit_kerja']) {
          $sheet->setCellValue('F4', $aPesawat['pesawat_datang_luar']);
          $sheet->setCellValue('F5', $aPesawat['pesawat_datang_negeri']);
          $sheet->setCellValue('F6', $aPesawat['pesawat_datang_luar'] + $aPesawat['pesawat_datang_negeri']);
          $sheet->setCellValue('G4', $aPesawat['crew_datang_luar']);
          $sheet->setCellValue('G5', $aPesawat['crew_datang_negeri']);
          $sheet->setCellValue('G6', $aPesawat['crew_datang_luar'] + $aPesawat['crew_datang_negeri']);
          $sheet->setCellValue('H4', $aPesawat['penumpang_datang_luar']);
          $sheet->setCellValue('H5', $aPesawat['penumpang_datang_negeri']);
          $sheet->setCellValue('H6', $aPesawat['penumpang_datang_luar'] + $aPesawat['penumpang_datang_negeri']);
          $sheet->setCellValue('F7', $aPesawat['pesawat_berangkat_luar']);
          $sheet->setCellValue('F8', $aPesawat['pesawat_berangkat_negeri']);
          $sheet->setCellValue('F9', $aPesawat['pesawat_berangkat_luar'] + $aPesawat['pesawat_berangkat_negeri']);
          $sheet->setCellValue('G7', $aPesawat['crew_berangkat_luar']);
          $sheet->setCellValue('G8', $aPesawat['crew_berangkat_negeri']);
          $sheet->setCellValue('G9', $aPesawat['crew_berangkat_luar'] + $aPesawat['crew_berangkat_negeri']);
          $sheet->setCellValue('H7', $aPesawat['penumpang_berangkat_luar']);
          $sheet->setCellValue('H8', $aPesawat['penumpang_berangkat_negeri']);
          $sheet->setCellValue('H9', $aPesawat['penumpang_berangkat_luar'] + $aPesawat['penumpang_berangkat_negeri']);
        }
      }
      foreach ($vaksin as $aVaksin) {
        if ($sheets['unit_kerja'] == $aVaksin['unit_kerja']) {
          $sheet->setCellValue('B12', $aVaksin['phqc']);
          $sheet->setCellValue('B13', $aVaksin['cop']);
          $sheet->setCellValue('B14', $aVaksin['sscec']);
          $sheet->setCellValue('B15', $aVaksin['sscc']);
          $sheet->setCellValue('B16', $aVaksin['p3k']);
          $sheet->setCellValue('B17', $aVaksin['icv']);
          $sheet->setCellValue('B18', $aVaksin['sklt']);
          $sheet->setCellValue('B19', $aVaksin['siaos']);
          $sheet->setCellValue('B20', $aVaksin['siaj']);
          $sheet->setCellValue('B21', $aVaksin['kir']);
          $sheet->setCellValue('B22', $aVaksin['vaksin_mm']);
          $sheet->setCellValue('B23', $aVaksin['vaksin_yf']);
          $sheet->setCellValue('B24', $aVaksin['vaksin_covid']);
          $sheet->setCellValue('B25', $aVaksin['bukes']);
        }
      }

    endforeach;

    $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'TOTAL KESELURUHAN');
    $spreadsheet->addSheet($sheet, $key++);
    $this->template_infografis($sheet, $year);
    foreach ($total_kapal as $aKapal) {

      $sheet->setCellValue('B4', $aKapal['kapal_datang_luar']);
      $sheet->setCellValue('B5', $aKapal['kapal_datang_negeri']);
      $sheet->setCellValue('B6', $aKapal['kapal_datang_luar'] + $aKapal['kapal_datang_negeri']);
      $sheet->setCellValue('C4', $aKapal['abk_datang_luar']);
      $sheet->setCellValue('C5', $aKapal['abk_datang_negeri']);
      $sheet->setCellValue('C6', $aKapal['abk_datang_luar'] + $aKapal['abk_datang_negeri']);
      $sheet->setCellValue('D4', $aKapal['penumpang_datang_luar']);
      $sheet->setCellValue('D5', $aKapal['penumpang_datang_negeri']);
      $sheet->setCellValue('D6', $aKapal['penumpang_datang_luar'] + $aKapal['penumpang_datang_negeri']);
      $sheet->setCellValue('B7', $aKapal['kapal_berangkat_luar']);
      $sheet->setCellValue('B8', $aKapal['kapal_berangkat_negeri']);
      $sheet->setCellValue('B9', $aKapal['kapal_berangkat_luar'] + $aKapal['kapal_berangkat_negeri']);
      $sheet->setCellValue('C7', $aKapal['abk_berangkat_luar']);
      $sheet->setCellValue('C8', $aKapal['abk_berangkat_negeri']);
      $sheet->setCellValue('C9', $aKapal['abk_berangkat_luar'] + $aKapal['abk_berangkat_negeri']);
      $sheet->setCellValue('D7', $aKapal['penumpang_berangkat_luar']);
      $sheet->setCellValue('D8', $aKapal['penumpang_berangkat_negeri']);
      $sheet->setCellValue('D9', $aKapal['penumpang_berangkat_luar'] + $aKapal['penumpang_berangkat_negeri']);
    }
    foreach ($total_pesawat as $aPesawat) {
      $sheet->setCellValue('F4', $aPesawat['pesawat_datang_luar']);
      $sheet->setCellValue('F5', $aPesawat['pesawat_datang_negeri']);
      $sheet->setCellValue('F6', $aPesawat['pesawat_datang_luar'] + $aPesawat['pesawat_datang_negeri']);
      $sheet->setCellValue('G4', $aPesawat['crew_datang_luar']);
      $sheet->setCellValue('G5', $aPesawat['crew_datang_negeri']);
      $sheet->setCellValue('G6', $aPesawat['crew_datang_luar'] + $aPesawat['crew_datang_negeri']);
      $sheet->setCellValue('H4', $aPesawat['penumpang_datang_luar']);
      $sheet->setCellValue('H5', $aPesawat['penumpang_datang_negeri']);
      $sheet->setCellValue('H6', $aPesawat['penumpang_datang_luar'] + $aPesawat['penumpang_datang_negeri']);
      $sheet->setCellValue('F7', $aPesawat['pesawat_berangkat_luar']);
      $sheet->setCellValue('F8', $aPesawat['pesawat_berangkat_negeri']);
      $sheet->setCellValue('F9', $aPesawat['pesawat_berangkat_luar'] + $aPesawat['pesawat_berangkat_negeri']);
      $sheet->setCellValue('G7', $aPesawat['crew_berangkat_luar']);
      $sheet->setCellValue('G8', $aPesawat['crew_berangkat_negeri']);
      $sheet->setCellValue('G9', $aPesawat['crew_berangkat_luar'] + $aPesawat['crew_berangkat_negeri']);
      $sheet->setCellValue('H7', $aPesawat['penumpang_berangkat_luar']);
      $sheet->setCellValue('H8', $aPesawat['penumpang_berangkat_negeri']);
      $sheet->setCellValue('H9', $aPesawat['penumpang_berangkat_luar'] + $aPesawat['penumpang_berangkat_negeri']);
    }
    foreach ($total_vaksin as $aVaksin) {
      $sheet->setCellValue('B12', $aVaksin['phqc']);
      $sheet->setCellValue('B13', $aVaksin['cop']);
      $sheet->setCellValue('B14', $aVaksin['sscec']);
      $sheet->setCellValue('B15', $aVaksin['sscc']);
      $sheet->setCellValue('B16', $aVaksin['p3k']);
      $sheet->setCellValue('B17', $aVaksin['icv']);
      $sheet->setCellValue('B18', $aVaksin['sklt']);
      $sheet->setCellValue('B19', $aVaksin['siaos']);
      $sheet->setCellValue('B20', $aVaksin['siaj']);
      $sheet->setCellValue('B21', $aVaksin['kir']);
      $sheet->setCellValue('B22', $aVaksin['vaksin_mm']);
      $sheet->setCellValue('B23', $aVaksin['vaksin_yf']);
      $sheet->setCellValue('B24', $aVaksin['vaksin_covid']);
      $sheet->setCellValue('B25', $aVaksin['bukes']);
    }



    $namaFile = 'Rekaptulasi Data Kekarantinaan (' . $year . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  private function template_infografis($workSheet, $year)
  {

    $workSheet->setCellValue('A1', 'REKAPITULASI DATA KEGIATAN KEKARANTINAAN KESEHATAN KKP BANDUNG PADA ' . $year);
    $workSheet->setCellValue('B3', 'KAPAL');
    $workSheet->setCellValue('C3', 'ABK');
    $workSheet->setCellValue('D3', 'PENUMPANG');
    $workSheet->setCellValue('F3', 'PESAWAT');
    $workSheet->setCellValue('G3', 'CREW');
    $workSheet->setCellValue('H3', 'PENUMPANG');

    $workSheet->setCellValue('A4', 'KEDATANGAN LN');
    $workSheet->setCellValue('A5', 'KEDATANGAN DN');
    $workSheet->setCellValue('A6', 'TOTAL KEDATANGAN');
    $workSheet->setCellValue('A7', 'KEBERANGKATAN LN');
    $workSheet->setCellValue('A8', 'KEBERANGKATAN DN');
    $workSheet->setCellValue('A9', 'TOTAL KEBERANGKATAN');

    $workSheet->setCellValue('B11', 'Jumlah');
    $workSheet->setCellValue('A12', 'PHQC');
    $workSheet->setCellValue('A13', 'COP');
    $workSheet->setCellValue('A14', 'SSCEC');
    $workSheet->setCellValue('A15', 'SSCC');
    $workSheet->setCellValue('A16', 'P3K');
    $workSheet->setCellValue('A17', 'ICV');
    $workSheet->setCellValue('A18', 'SKLT');
    $workSheet->setCellValue('A19', 'SIAOS');
    $workSheet->setCellValue('A20', 'SURAT IZIN ANGKUT JENAZAH');
    $workSheet->setCellValue('A21', 'PENGUJIAN KESEHATAN (KIR)');
    $workSheet->setCellValue('A22', 'Vaksin MM');
    $workSheet->setCellValue('A23', 'Vaksin YF');
    $workSheet->setCellValue('A24', 'Vaksin Covid');
    $workSheet->setCellValue('A25', 'Buku Kesehatan');

    // merge
    $workSheet->mergeCells('A1:H1');


    // title bold
    $workSheet->getStyle('A1:F1')->getFont()->setBold(true);
    $workSheet->getStyle('B3:H3')->getFont()->setBold(true);

    // title background color
    $workSheet->getStyle('E3:E9')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('00000000');
    $workSheet->getStyle('A6:D6')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('C1C1C1C1');
    $workSheet->getStyle('A9:D9')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('C1C1C1C1');
    $workSheet->getStyle('F6:H6')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('C1C1C1C1');
    $workSheet->getStyle('F9:H9')->getFill()
      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
      ->getStartColor()->setARGB('C1C1C1C1');

    // bordered
    $workSheet->getStyle('A12:B25')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $workSheet->getStyle('A3:H3')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $workSheet->getStyle('A4:H9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $workSheet->getStyle('A4:D9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $workSheet->getStyle('F4:H9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $workSheet->getStyle('A3:A9')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

    $workSheet->getStyle('A1:H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $workSheet->getStyle('B11')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $workSheet->getStyle('A1:H24')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

    // auto size
    $workSheet->getColumnDimension('A')->setWidth(35);
    $workSheet->getColumnDimension('B')->setWidth(14);
    $workSheet->getColumnDimension('C')->setWidth(14);
    $workSheet->getColumnDimension('D')->setWidth(14);
    $workSheet->getColumnDimension('E')->setWidth(0.25);
    $workSheet->getColumnDimension('F')->setWidth(14);
    $workSheet->getColumnDimension('G')->setWidth(14);
    $workSheet->getColumnDimension('H')->setWidth(14);
  }
  
  public function export_data_sop()
  {
    $divisi = $this->request->getVar("pilih_divisi");
    $status = $this->request->getVar("status");
    $tahun_1 = $this->request->getVar("tahun_1"); // 2023
    $tahun_2 = $this->request->getVar("tahun_2"); // 2024
    
    $keterangan = "";
    
    $this->sipepesModel
        ->select('user.nama as nama_pegawai, sipepes_doksop.*')
        ->join('user', 'user.id_user=sipepes_doksop.diubah_oleh')
        ->whereIn('jenis_dokumen', ['SOP Utama', 'SOP Pendukung']); // Use whereIn for multiple types
    if ($divisi != ""){
        $this->sipepesModel->where('unit_kerja', $divisi);
        $keterangan .= "-". $divisi ."-";
    } else {
        $keterangan .= "-Semua Divisi-";
    }
    if ($status != ""){
        $this->sipepesModel->where('status', $status);
        $keterangan .= "-". $status ."-";
    } else {
        $keterangan .= "-Semua Status-";
    }
    if ($tahun_1 != "" && $tahun_2 != "") {
        $this->sipepesModel->where("tanggal_efektif BETWEEN '$tahun_1-01-01' AND '$tahun_2-12-31'");
        if($tahun_1 == $tahun_2){
            $keterangan .= "-". $tahun_2 ."-";
        } else {
            $keterangan .= "-". $tahun_1 ."_". $tahun_2 ."-";
        }
    }
    
    $data = $this->sipepesModel->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Nomor Dokumen');
    $sheet->setCellValue('C1', 'Nama Dokumen');
    $sheet->setCellValue('D1', 'Divisi');
    $sheet->setCellValue('E1', 'Tanggal Pembuatan');
    $sheet->setCellValue('F1', 'Tanggal Revisi');
    $sheet->setCellValue('G1', 'Tanggal Efektif');
    $sheet->setCellValue('H1', 'Diubah Oleh');
    $sheet->setCellValue('I1', 'Terakhir Diubah');
    $sheet->setCellValue('J1', 'Status SOP');

    $column = 2; // start kolom 
    foreach ($data as $sop) {
      $sheet->setCellValue('A' . $column, ($column - 1));
      $sheet->setCellValue('B' . $column, $sop['no_dokumen']);
      $sheet->setCellValue('C' . $column, $sop['judul']);
      $sheet->setCellValue('D' . $column, $sop['unit_kerja']);
      $sheet->setCellValue('E' . $column, date('d-m-Y', strtotime($sop['tanggal_pembuatan'])));
      $sheet->setCellValue('F' . $column, date('d-m-Y', strtotime($sop['tanggal_revisi'])) . ' (Rev ' . $sop['revisi_ke'] . ')');
      $sheet->setCellValue('G' . $column, date('d-m-Y', strtotime($sop['tanggal_efektif'])));
      $sheet->setCellValue('H' . $column, $sop['nama_pegawai']);
      $sheet->setCellValue('I' . $column, $sop['updated_at']);
      $sheet->setCellValue('J' . $column, $sop['status']);
      $column++;
    }

    // title bold
    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    // title background color
    // $sheet->getStyle('A1:C1')->getFill()
    //   ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //   ->getStartColor()->setARGB('FFFFFF00');

    // bordered
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '00000000'],
        ],
      ],
    ];
    $sheet->getStyle('A1:J' . ($column - 1))->applyFromArray($styleArray);

    // auto size
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getColumnDimension('J')->setAutoSize(true);

    $namaFile = 'Daftar SOP (' .$keterangan. ') '. date('Y-m-d_H.i.s') .'.xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function export_data_arsip()
  {
    $by = $this->dataPegawaiModel
      ->select('id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $periode = $this->request->getVar("tanggal");
    $startDate = substr($periode, 0, 10);
    $endDate = substr($periode, 13, 10);

    if ($this->request->getVar("sipeditAdministrator") == 0)
      $data = $this->sipeditArsipModel
        ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai')
        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori')
        ->where('data_pegawai.id_status_pegawai >=', "2")
        ->where('data_pegawai.id_status_pegawai <=', "3")
        ->where('sipedit_arsip.id_data_pegawai', $by)
        ->where('arsip_tanggal >=', $startDate)
        ->where('arsip_selesai <=', $endDate)
        ->orderBy('sipedit_arsip.created_at', 'desc')
        ->findAll();
    else
      $data = $this->sipeditArsipModel
        ->select('data_pegawai.nama as pemilik_arsip, sipedit_kategori.kategori_nama as kategori, sipedit_arsip.*')
        ->join('data_pegawai', 'data_pegawai.id_data_pegawai=sipedit_arsip.id_data_pegawai')
        ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori=sipedit_arsip.id_sipedit_kategori')
        ->where('data_pegawai.id_status_pegawai >=', "2")
        ->where('data_pegawai.id_status_pegawai <=', "3")
        ->where('arsip_tanggal >=', $startDate)
        ->where('arsip_selesai <=', $endDate)
        ->orderBy('sipedit_arsip.created_at', 'desc')
        ->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Waktu Upload');
    $sheet->setCellValue('C1', 'Pemilik');
    $sheet->setCellValue('D1', 'Nama Pelatihan');
    $sheet->setCellValue('E1', 'Lembaga Pelatihan');
    $sheet->setCellValue('F1', 'Kategori');
    $sheet->setCellValue('G1', 'Tanggal Mulai');
    $sheet->setCellValue('H1', 'Tanggal Selesai');
    $sheet->setCellValue('I1', 'JPL');

    $column = 2; // start kolom 
    foreach ($data as $sop) {
      $sheet->setCellValue('A' . $column, ($column - 1));
      $sheet->setCellValue('B' . $column, $sop['created_at']);
      $sheet->setCellValue('C' . $column, $sop['pemilik_arsip']);
      $sheet->setCellValue('D' . $column, $sop['arsip_kode']);
      $sheet->setCellValue('E' . $column, $sop['arsip_nama']);
      $sheet->setCellValue('F' . $column, $sop['kategori']);
      $sheet->setCellValue('G' . $column, date('d-m-Y', strtotime($sop['arsip_tanggal'])));
      $sheet->setCellValue('H' . $column, date('d-m-Y', strtotime($sop['arsip_selesai'])));
      $sheet->setCellValue('I' . $column, $sop['arsip_jpl']);
      $column++;
    }

    // title bold
    $sheet->getStyle('A1:I1')->getFont()->setBold(true);

    // bordered
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '00000000'],
        ],
      ],
    ];
    $sheet->getStyle('A1:I' . ($column - 1))->applyFromArray($styleArray);

    // auto size
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);

    $namaFile = 'Daftar Arsip.xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function export_dashboard_data_arsip()
  {
    $tahunIni = $this->request->getVar("sipedit_tanggal");
    $bulan = $this->request->getVar("sipedit_bulan");
    $subTitle = '';
    if ($tahunIni == "all") {
      $tahunIni = "Semua Periode";
      $subTitle = $tahunIni;
      $data = [
        'total_jpl' => $this->sipeditArsipModel
          ->select("count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
          ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->where('data_pegawai.id_status_pegawai >=', "2")
          ->where('data_pegawai.id_status_pegawai <=', "3")
          ->first(),
        'total_jpl_per_kategori' => $this->sipeditArsipModel
          ->select("sipedit_kategori.kategori_nama, count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
          ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
          ->where('data_pegawai.id_status_pegawai >=', "2")
          ->where('data_pegawai.id_status_pegawai <=', "3")
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->groupBy('sipedit_arsip.id_sipedit_kategori')
          ->findAll(),
        'data' => $this->dataPegawaiModel
          ->select("data_pegawai.*, 
                  (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                    FROM sipedit_arsip 
                    WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai) AS total_arsip_jpl")
          ->notLike('data_pegawai.nip', '0000%')
          ->where('data_pegawai.status_bekerja', 'Aktif')
          // ->having("total_arsip_jpl < 20 OR total_arsip_jpl IS NULL")
          ->orderBy('total_arsip_jpl DESC')
          ->findAll()
      ];
    } else {
      $data = [
        'total_jpl' => $this->sipeditArsipModel
          ->select("count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
          ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
          ->where('data_pegawai.id_status_pegawai >=', "2")
          ->where('data_pegawai.id_status_pegawai <=', "3")
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->where("YEAR(sipedit_arsip.arsip_tanggal)", $tahunIni)
          ->where("MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan")
          ->first(),
        'total_jpl_per_kategori' => $this->sipeditArsipModel
          ->select("sipedit_kategori.kategori_nama, count(sipedit_arsip.id_sipedit_arsip) as total_jpl")
          ->join('data_pegawai', 'data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai')
          ->join('sipedit_kategori', 'sipedit_kategori.id_sipedit_kategori = sipedit_arsip.id_sipedit_kategori')
          ->where('data_pegawai.id_status_pegawai >=', "2")
          ->where('data_pegawai.id_status_pegawai <=', "3")
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->where("YEAR(sipedit_arsip.arsip_tanggal)", $tahunIni)
          ->where("MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan")
          ->groupBy('sipedit_arsip.id_sipedit_kategori')
          ->findAll(),
        'data' => $this->dataPegawaiModel
          ->select("data_pegawai.*, 
                  (SELECT COALESCE(SUM(sipedit_arsip.arsip_jpl), 0) 
                    FROM sipedit_arsip 
                    WHERE data_pegawai.id_data_pegawai = sipedit_arsip.id_data_pegawai 
                    AND YEAR(sipedit_arsip.arsip_tanggal) = $tahunIni
                    AND MONTH(sipedit_arsip.arsip_tanggal) BETWEEN 1 AND $bulan) AS total_arsip_jpl")
          ->notLike('data_pegawai.nip', '0000%')
          ->where('data_pegawai.id_status_pegawai >=', "2")
          ->where('data_pegawai.id_status_pegawai <=', "3")
          ->where('data_pegawai.status_bekerja', 'Aktif')
          ->orderBy('total_arsip_jpl DESC')
          ->findAll()
      ];
    }
    // dd($data);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // bordered
    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '00000000'],
        ],
      ],
    ];


    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kategori');
    $sheet->setCellValue('C1', 'Total Pelatihan');
    $sheet->setCellValue('D1', 'Persentase');

    $column = 2; // start kolom 
    foreach ($data['total_jpl_per_kategori'] as $key) {
      $persentase = round(($key['total_jpl'] / $data['total_jpl']['total_jpl']) * 100, 2) . '%';
      $sheet->setCellValue('A' . $column, ($column - 1));
      $sheet->setCellValue('B' . $column, $key['kategori_nama']);
      $sheet->setCellValue('C' . $column, $key['total_jpl']);
      $sheet->setCellValue('D' . $column, $persentase);
      $column++;
    }
    $sheet->getStyle('A1:D' . ($column - 1))->applyFromArray($styleArray);

    $startColumn = $column + 1;
    $nextColumn = $startColumn;
    $sheet->setCellValue('A' . $nextColumn, 'No');
    $sheet->setCellValue('B' . $nextColumn, 'Status');
    $sheet->setCellValue('C' . $nextColumn, 'Jumlah');
    $sheet->setCellValue('D' . $nextColumn, 'Persentase (%)');
    $nextColumn++;

    $sheet->setCellValue('F1', 'No');
    $sheet->setCellValue('G1', 'Nama');
    $sheet->setCellValue('H1', 'NIP');
    $sheet->setCellValue('I1', 'Periode');
    $sheet->setCellValue('J1', 'Keterangan');
    $sheet->setCellValue('K1', 'Total JPL (Min 20 JPL/tahun) ' . $subTitle);

    $column = 2;
    foreach ($data['data'] as $key) {
      if ($key['total_arsip_jpl'] < 20)
        $sheet->getStyle('J' . $column)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');

      $sheet->setCellValue('F' . $column, ($column - 1));
      $sheet->setCellValue('G' . $column, $key['nama']);
      $sheet->setCellValue('H' . $column, '`' . $key['nip']);
      $sheet->setCellValue('I' . $column, $tahunIni);
      $sheet->setCellValue('J' . $column, '=IF(K' . $column . ' >= 20, "Sudah memenuhi", "Belum memenuhi")');
      $sheet->setCellValue('K' . $column, $key['total_arsip_jpl']);
      $column++;
    }
    $sheet->getStyle('F1:K' . ($column - 1))->applyFromArray($styleArray);


    $nomor = 1;
    $sheet->setCellValue('A' . $nextColumn, $nomor++);
    $sheet->setCellValue('B' . $nextColumn, 'Sudah memenuhi');
    $sheet->setCellValue('C' . $nextColumn, '=COUNTIF(J2:J' . ($column - 1) . ', "Sudah memenuhi")');
    $sheet->setCellValue('D' . $nextColumn, '=COUNTIF(J2:J' . ($column - 1) . ', "Sudah memenuhi") / COUNTA(J2:J' . ($column - 1) . ')*100');
    $nextColumn++;
    $sheet->setCellValue('A' . $nextColumn, $nomor++);
    $sheet->setCellValue('B' . $nextColumn, 'Belum memenuhi');
    $sheet->setCellValue('C' . $nextColumn, '=COUNTIF(J2:J' . ($column - 1) . ', "Belum memenuhi")');
    $sheet->setCellValue('D' . $nextColumn, '=COUNTIF(J2:J' . ($column - 1) . ', "Belum memenuhi") / COUNTA(J2:J' . ($column - 1) . ')*100');
    $nextColumn++;

    $sheet->getStyle('A' . $startColumn . ':D' . ($nextColumn - 1))->applyFromArray($styleArray);

    // title bold
    $sheet->getStyle('A1:D1')->getFont()->setBold(true);
    $sheet->getStyle('F1:K1')->getFont()->setBold(true);
    $sheet->getStyle('A' . $startColumn . ':D' . $startColumn . '')->getFont()->setBold(true);


    // auto size
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getColumnDimension('J')->setAutoSize(true);
    $sheet->getColumnDimension('K')->setAutoSize(true);

    $subName = $tahunIni;
    $namaFile = 'Dashboard Data Arsip (' . $subName . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function export_rencana_kerja()
  {
    $getTahun = $this->request->getVar("tahunRencanaKerja");
    $by = $this->dataPegawaiModel
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();
    $getAnggota = $this->wbkAggotaModel->where('id_data_pegawai', $by['id_data_pegawai'])->first();

    if ($getTahun) {
      $getPoin = $this->wbkRencanaKerjaModel
        ->select('wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
        ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
        ->where('YEAR(wbk_rencana_kerja.created_at)', $getTahun)
        ->where('LENGTH(poin2) =', 0)
        ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
        ->findAll();

      $getSubPoin = $this->wbkRencanaKerjaModel
        ->select('wbk_rencana_kerja.*, COUNT(wbk_arsip.id_wbk_arsip) AS jumlah_file')
        ->join('wbk_arsip', 'wbk_rencana_kerja.id_wbk_rencana_kerja = wbk_arsip.id_wbk_rencana_kerja', 'left')
        ->where('YEAR(wbk_rencana_kerja.created_at)', $getTahun)
        ->where('LENGTH(poin2) !=', 0)
        ->groupBy('wbk_rencana_kerja.id_wbk_rencana_kerja')
        ->findAll();


      if ($getAnggota['keterangan'] == 'Koordinator' || $getAnggota['keterangan'] == 'Anggota1' || $getAnggota['keterangan'] == 'Anggota') {
        $getPokja = $this->wbkPokjaModel
          ->where('id_wbk_pokja', $getAnggota['id_wbk_pokja'])
          ->where('LENGTH(keterangan) !=', 0)
          ->findAll();
      } else {
        $getPokja = $this->wbkPokjaModel->where('LENGTH(keterangan) !=', 0)->findAll();
      }
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kegiatan');
    $sheet->setCellValue('C1', 'Indikator');
    $sheet->setCellValue('D1', 'Bukti Fisik');
    $sheet->setCellValue('E1', 'Target (Jumlah Kegiatan)');
    $sheet->setCellValue('F1', 'Target Waktu');
    $sheet->setCellValue('F2', 'Januari');
    $sheet->setCellValue('G2', 'Februari');
    $sheet->setCellValue('H2', 'Maret');
    $sheet->setCellValue('I2', 'April');
    $sheet->setCellValue('J2', 'Mei');
    $sheet->setCellValue('K2', 'Juni');
    $sheet->setCellValue('L2', 'Juli');
    $sheet->setCellValue('M2', 'Agustus');
    $sheet->setCellValue('N2', 'September');
    $sheet->setCellValue('O2', 'Oktober');
    $sheet->setCellValue('P2', 'November');
    $sheet->setCellValue('Q2', 'Desember');

    $sheet->mergeCells('A1:A2');
    $sheet->mergeCells('B1:B2');
    $sheet->mergeCells('C1:C2');
    $sheet->mergeCells('D1:D2');
    $sheet->mergeCells('E1:E2');
    $sheet->mergeCells('F1:Q1');

    $baris = 3; // start kolom

    foreach ($getPokja as $pokja) {
      $sheet->setCellValue('A' . $baris, $pokja['nama_pokja']);
      $baris++;
      foreach ($getPoin as $poin) {
        if ($poin['id_wbk_pokja'] == $pokja['id_wbk_pokja']) {
          // Set nilai $poin['kegiatan'] di kolom A dan baris yang sesuai
          $sheet->setCellValue('A' . $baris, $poin['poin1']);
          $sheet->setCellValue('B' . $baris, $poin['kegiatan']);
          $sheet->setCellValue('C' . $baris, $poin['indikator']);
          $sheet->setCellValue('D' . $baris, $poin['bukti_fisik']);
          $sheet->setCellValue('E' . $baris, $poin['target_jml_kegiatan']);
          $baris++;

          foreach ($getSubPoin as $subPoin) {
            if ($subPoin['id_wbk_pokja'] == $pokja['id_wbk_pokja']) {
              if ($subPoin['poin1'] == $poin['poin1']) {
                $sheet->setCellValue('A' . $baris, $subPoin['poin1'] . '.' . $subPoin['poin2']);
                $sheet->setCellValue('B' . $baris, $subPoin['kegiatan']);
                $sheet->setCellValue('C' . $baris, $subPoin['indikator']);
                $sheet->setCellValue('D' . $baris, $subPoin['bukti_fisik']);
                $sheet->setCellValue('E' . $baris, $subPoin['jumlah_file'] . '/' . $subPoin['target_jml_kegiatan']);

                $target_waktu = explode('|', $subPoin['target_waktu']);
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                  $warna = in_array($bulan, $target_waktu) ? 'OK' : '';

                  // Hitung indeks kolom berdasarkan perbedaan antara $bulan dan $target_waktu
                  $indeks_kolom = $bulan + 5; // 5 adalah indeks kolom untuk F

                  // Pastikan indeks kolom tidak melebihi Q (kolom ke-17)
                  if ($indeks_kolom <= 17) {
                    $cell = $sheet->getCellByColumnAndRow($indeks_kolom, $baris);
                    $style = $cell->getStyle();
                    $fill = $style->getFill();

                    // Set warna fill menjadi biru jika kondisinya terpenuhi
                    if ($warna == 'OK') {
                      $fill->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('0000FF'); // Warna biru
                    }
                  }
                }

                $baris++;
              }
            }
          }
        }
      }
    }

    // title bold
    $sheet->getStyle('A1:Q2')->getFont()->setBold(true);
    $sheet->getStyle('A1:Q2')->getAlignment()->setHorizontal(true);
    $sheet->getStyle('A1:Q2')->getAlignment()->setVertical(true);

    $styleArray = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
          'color' => ['argb' => '00000000'],
        ],
      ],
    ];
    $sheet->getStyle('A1:Q' . ($baris - 1))->applyFromArray($styleArray);

    // auto size
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $namaFile = 'Rencana Kerja.xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function realisasiRelaxtime()
  {
    $year = $this->request->getVar("tahun");
    // Create a new Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menamai lembar kerja (sheet) awal
    $spreadsheet->getActiveSheet()->setTitle('MasterSheet');
    // Menyimpan indeks sheet sebelumnya
    $indeksMasterSheet = $spreadsheet->getIndex($spreadsheet->getActiveSheet());


    // Lokasi gambar
    $imagePath = 'dist/img/logo_kkp.png';

    // Buat objek gambar
    $drawing = new Drawing();
    $drawing->setName('Image');
    $drawing->setDescription('Image');
    $drawing->setPath($imagePath);
    $drawing->setCoordinates('A1'); // Koordinat sel tempat gambar akan ditempatkan
    $drawing->setOffsetX(0);
    $drawing->setOffsetY(3);
    $drawing->setWidth(88); // Lebar gambar
    $drawing->setHeight(88); // Tinggi gambar

    // Tambahkan gambar ke lembar kerja
    $drawing->setWorksheet($sheet);

    // Set the column widths
    $sheet->getColumnDimension('A')->setWidth(13);
    $sheet->getColumnDimension('B')->setWidth(64);
    $sheet->getColumnDimension('C')->setWidth(6.5);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(14);
    $sheet->getColumnDimension('F')->setWidth(17);

    // Merge cells and set the content
    $sheet->mergeCells('B1:B2');
    $sheet->setCellValue('B1', 'REALISASI ANGGARAN TAHUN ' . $year);
    $sheet->getStyle('B1')->getFont()->setSize(20);
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('B3', 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG');
    $sheet->getStyle('B3')->getFont()->setSize(14);
    $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('C1:E3');
    $sheet->setCellValue('C1', 'Rincian Biaya');
    $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('F1', 'Total Pagu');
    $sheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('A5', 'Kode');
    $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('A5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('B5', 'Uraian');
    $sheet->getStyle('B5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('B5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('C4:C5');
    $sheet->setCellValue('C4', 'Vol');
    $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('D4:D5');
    $sheet->setCellValue('D4', 'Satuan');
    $sheet->getStyle('D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('E4:E5');
    $sheet->setCellValue('E4', 'Harga');
    $sheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('F2:F3');
    $sheet->getStyle('F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('F2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('F4:F5');
    $sheet->setCellValue('F4', 'Jumlah');
    $sheet->getStyle('F4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


    $sheet->mergeCells('G1:L1');
    $sheet->setCellValue('G1', 'Triwulan 1');
    $sheet->getStyle('G1')->getFont()->setSize(16);
    $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('M1:R1');
    $sheet->setCellValue('M1', 'Triwulan 2');
    $sheet->getStyle('M1')->getFont()->setSize(16);
    $sheet->getStyle('M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('M1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('S1:X1');
    $sheet->setCellValue('S1', 'Triwulan 3');
    $sheet->getStyle('S1')->getFont()->setSize(16);
    $sheet->getStyle('S1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('S1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->mergeCells('Y1:AD1');
    $sheet->setCellValue('Y1', 'Triwulan 4');
    $sheet->getStyle('Y1')->getFont()->setSize(16);
    $sheet->getStyle('Y1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('Y1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


    // Set content for additional columns in a loop
    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $startColumn = 'G';
    for ($i = 0; $i < 12; $i++) {
      $column1 = $startColumn++;
      $column2 = $startColumn++;
      $sheet->getColumnDimension($column1)->setWidth(10);
      $sheet->getColumnDimension($column2)->setWidth(16);

      // Merge cells for each set of columns
      $headerRange = $column1 . '2:' . $column1 . '3';
      $sheet->mergeCells($headerRange);
      $sheet->getStyle($column1 . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheet->getStyle($column1 . '2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      // Set content for merged cells
      for ($j = 2; $j <= 5; $j++) {
        $cell1 = $column1 . $j;
        $sheet->setCellValue($cell1, 100 / 100); // harus dibagi 100
        $sheet->getStyle($cell1)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
      }

      // Merge cells for each set of columns
      $headerRange = $column2 . '2:' . $column2 . '3';
      $sheet->mergeCells($headerRange);
      $sheet->getStyle($column2 . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheet->getStyle($column2 . '2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      // Set content for merged cells
      // for ($j = 2; $j <= 5; $j++) {
      //   $cell2 = $column2 . $j;
      //   $sheet->setCellValue($cell2, $number);
      //   $sheet->getStyle($cell2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      // }

      // Set content for individual cells in the same row
      $individualCell1 = $column1 . '4';
      $individualCell2 = $column2 . '4';
      $sheet->setCellValue($individualCell1, $bulan[$i]);
      $headerRangeBulan = $individualCell1 . ":" . $individualCell2;
      $sheet->mergeCells($headerRangeBulan);
      $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle($individualCell1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      // Set content for individual cells in the next row
      $individualCell1 = $column1 . '5';
      $individualCell2 = $column2 . '5';
      $sheet->setCellValue($individualCell1, "(%)");
      $sheet->setCellValue($individualCell2, "Jumlah");
      $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheet->getStyle($individualCell1)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle($individualCell2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle($individualCell2)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    }

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    $sheet->getStyle('A5')->applyFromArray($borderStyle);
    $sheet->getStyle('B1:F5')->applyFromArray($borderStyle);
    $sheet->getStyle('G2:AD5')->applyFromArray($borderStyle);
    // Set bold font for multiple columns (contoh untuk kolom C sampai E)
    $sheet->getStyle('A1:AD5')->getFont()->setBold(true);

    // Set background color for a single column (contoh untuk kolom B)
    $sheet->getStyle('A1:A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
    $sheet->getStyle('A5:B5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('538dd5');
    $sheet->getStyle('C1:F5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('538dd5');

    // Set background color for multiple columns (contoh untuk kolom C sampai E)
    $sheet->getStyle('F2:F3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ff0000');

    $sheet->getStyle('G1:L5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('fcd5b4');
    $sheet->getStyle('M1:R5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('b7dee8');
    $sheet->getStyle('S1:X5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('92d050');
    $sheet->getStyle('Y1:AD5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('ffd966');

    // Menghapus warna untuk kolom H2, J2, dan L2
    $excludeCells = ['H2', 'J2', 'L2', 'N2', 'P2', 'R2', 'T2', 'V2', 'X2', 'Z2', 'AB2', 'AD2'];
    foreach ($excludeCells as $cell) {
      $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_NONE);
    }

    $dataAll = $this->relaxtimeDetailUraianModel
      ->join('relaxtime_uraian', 'relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian')
      ->join('relaxtime_komponen', 'relaxtime_komponen.id_relaxtime_komponen=relaxtime_detail_uraian.id_relaxtime_komponen')
      ->join('relaxtime_ro', 'relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro')
      ->join('relaxtime_kro', 'relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro')
      ->join('relaxtime_rkt', 'relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt')
      ->where('relaxtime_ro.tahun', $year)
      ->where('relaxtime_detail_uraian.tahun', $year)
      ->findAll();

    $kode_rkt = "";
    $kode_digit_kro = "";
    $kode_kro = "";
    $kode_ro = "";
    $kode_komponen = "";
    $kode_sub_komponen = "";
    $kode_uraian = "";
    $column1 = "A";
    $column2 = "B";
    $column3 = "C";
    $column4 = "D";
    $column5 = "E";
    $column6 = "F";
    $urutan = 6;
    $rkt = [];
    $digit_kro = [];
    $kro = [];
    $ro = [];
    $komponen = [];
    $sub_komponen = [];
    $uraian = [];
    $jumlahArrayTotal = '';
    $jumlahArrayRKT = [];
    $jumlahArrayDigitKRO = [];
    $jumlahArrayKRO = [];
    $jumlahArrayRO = [];
    $jumlahArrayKomponen = [];
    $jumlahArraySubKomponen = [];
    $jumlahArrayUraian = [];
    $urutanAwalColumnRKT = 0;
    $urutanAwalColumnDigitKRO = 0;
    $urutanAkhirColumnDigitKRO = 0;
    $urutanAwalColumnKRO = 0;
    $urutanAkhirColumnKRO = 0;
    $urutanAwalColumnRO = 0;
    $urutanAkhirColumnRO = 0;
    $urutanAwalColumnKomponen = 0;
    $urutanAkhirColumnKomponen = 0;
    $urutanAwalColumnSubKomponen = 0;
    $urutanAkhirColumnSubKomponen = 0;
    $urutanAwalColumnDetailUraian = 0;
    $urutanAkhirColumnDetailUraian = 0;
    $firstLineRKT = true;
    $firstLineDigitKRO = true;
    $firstLineKRO = true;
    $firstLineRO = true;
    $firstLineKomponen = true;
    $firstLineSubKomponen = true;
    $firstLineUraian = true;
    $loop1 = 0;
    $loop2 = 0;
    $loop3 = 0;
    $loop4 = 0;
    $loop5 = 0;
    $loop6 = 0;
    $loop7 = 0;
    $baris = [];

    foreach ($dataAll as $data) {

      if ($data['id_relaxtime_rkt'] != $kode_rkt) {
        $jumlahArrayTotal .= ($jumlahArrayTotal == '' ? '' : ',') . $column6 . $urutan;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['kode_rkt']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_rkt']);
        $sheet->setCellValue($individualCell3, '');
        $sheet->setCellValue($individualCell4, '');
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, '');

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle($individualCell1 . ":" . $individualCell6)->getFont()->setBold(true);
        $sheet->getStyle($individualCell1 . ":" . $individualCell6)->getFont()->setUnderline(true);

        $kode_rkt = $data['id_relaxtime_rkt'];

        $rkt[$loop1] = $column6 . $urutan;
        $urutan++;
        if ($firstLineRKT == true) {
          $urutanAwalColumnRKT = $urutan;
          $firstLineRKT = false;
        } else {
          $jumlahArrayRKT[$loop1 - 1] = $column6 . $urutanAwalColumnRKT;
          $urutanAwalColumnRKT = $urutan;
        }
        $loop1++;
      }

      if ($data['digit_kro'] != $kode_digit_kro) {

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['digit_kro']);
        $sheet->setCellValue($individualCell2, $data['detail_digit_kro']);
        $sheet->setCellValue($individualCell3, '');
        $sheet->setCellValue($individualCell4, '');
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, '');

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $kode_digit_kro = $data['digit_kro'];
        $kode_kro = "";

        $digit_kro[$loop2] = $column6 . $urutan;
        $urutan++;
        if ($firstLineDigitKRO == true) {
          $urutanAwalColumnDigitKRO = $urutan;
          $jumlahArrayDigitKRO[$loop2] = '';
          $firstLineDigitKRO = false;
        } else {
          // $jumlahArrayDigitKRO[$loop2 - 1] .= ($urutanAkhirColumnDigitKRO > $urutanAwalColumnDigitKRO ? ',' . $column6 . $urutanAkhirColumnDigitKRO : '');
          $urutanAwalColumnDigitKRO = $urutan;
          $jumlahArrayDigitKRO[$loop2] = '';
        }
        $loop2++;
      }

      if ($data['digit_kro'] . $data['kode_kro'] != $kode_kro) {
        $urutanAkhirColumnDigitKRO = $urutan;
        $jumlahArrayDigitKRO[$loop2 - 1] .= ($jumlahArrayDigitKRO[$loop2 - 1] == '' ? '' : ",") . $column6 . $urutanAkhirColumnDigitKRO;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['digit_kro'] . $data['kode_kro']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_kro']);
        $sheet->setCellValue($individualCell3, ""); // EDIT vol KRO
        $sheet->setCellValue($individualCell4, ""); // EDIT biarkan kosong 
        $sheet->setCellValue($individualCell5, "");
        $sheet->setCellValue($individualCell6, ""); // EDIT 

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $kode_kro = $data['digit_kro'] . $data['kode_kro'];
        $kode_ro = "";

        $kro[$loop3] = $column6 . $urutan;
        $urutan++;
        if ($firstLineKRO == true) {
          $urutanAwalColumnKRO = $urutan;
          $jumlahArrayKRO[$loop3] = '';
          $firstLineKRO = false;
        } else {
          // $jumlahArrayKRO[$loop3 - 1] .= ($urutanAkhirColumnKRO > $urutanAwalColumnKRO ? ',' . $column6 . $urutanAkhirColumnKRO : '');
          $urutanAwalColumnKRO = $urutan;
          $jumlahArrayKRO[$loop3] = '';
        }
        $loop3++;
      }

      if ($data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'] != $kode_ro) {
        $urutanAkhirColumnKRO = $urutan;
        $jumlahArrayKRO[$loop3 - 1] .= ($jumlahArrayKRO[$loop3 - 1] == "" ? '' : ',') . $column6 . $urutanAkhirColumnKRO;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_ro']);
        $sheet->setCellValue($individualCell3, $data['vol_ro']);
        $sheet->setCellValue($individualCell4, $data['sat_ro']);
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, ''); // EDIT

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $kode_ro = $data['digit_kro'] . $data['kode_kro'] . $data['kode_relaxtime_ro'];
        $kode_komponen = "";

        $ro[$loop4] = $column6 . $urutan;
        $urutan++;
        if ($firstLineRO == true) {
          $urutanAwalColumnRO = $urutan;
          $jumlahArrayRO[$loop4] = '';
          $firstLineRO = false;
        } else {
          // $jumlahArrayRO[$loop4 - 1] .= ($urutanAkhirColumnRO > $urutanAwalColumnRO ? ',' . $column6 . $urutanAkhirColumnRO : '');
          $urutanAwalColumnRO = $urutan;
          $jumlahArrayRO[$loop4] = '';
        }
        $loop4++;
      }

      if ($data['kode_komponen'] != $kode_komponen) {
        $urutanAkhirColumnRO = $urutan;
        $jumlahArrayRO[$loop4 - 1] .= ($jumlahArrayRO[$loop4 - 1] == "" ? '' : ',') . $column6 . $urutanAkhirColumnRO;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['kode_komponen']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_komponen']);
        $sheet->setCellValue($individualCell3, ''); // EDIT biarkan kosong 
        $sheet->setCellValue($individualCell4, '');
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, ''); // EDIT

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $kode_komponen = $data['kode_komponen'];
        $kode_sub_komponen = "";

        $komponen[$loop5] = $column6 . $urutan;
        $urutan++;
        if ($firstLineKomponen == true) {
          $urutanAwalColumnKomponen = $urutan;
          $jumlahArrayKomponen[$loop5] = '';
          $firstLineKomponen = false;
        } else {
          // $jumlahArrayKomponen[$loop5 - 1] .= ($urutanAkhirColumnKomponen > $urutanAwalColumnKomponen ? ',' . $column6 . $urutanAkhirColumnKomponen : '');
          $urutanAwalColumnKomponen = $urutan;
          $jumlahArrayKomponen[$loop5] = '';
        }
        $loop5++;
      }

      if ($data['kode_sub_komponen'] != $kode_sub_komponen) {
        $urutanAkhirColumnKomponen = $urutan;
        $jumlahArrayKomponen[$loop5 - 1] .= ($jumlahArrayKomponen[$loop5 - 1] == "" ? '' : ',') . $column6 . $urutanAkhirColumnKomponen;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['kode_sub_komponen']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_sub_komponen']);
        $sheet->setCellValue($individualCell3, ''); // EDIT biarkan kosong 
        $sheet->setCellValue($individualCell4, '');
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, ''); // EDIT

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $kode_sub_komponen = $data['kode_sub_komponen'];
        $kode_uraian = "";

        $sub_komponen[$loop6] = $column6 . $urutan;
        $urutan++;
        if ($firstLineSubKomponen == true) {
          $urutanAwalColumnSubKomponen = $urutan;
          $jumlahArraySubKomponen[$loop6] = '';
          $firstLineSubKomponen = false;
        } else {
          // $jumlahArraySubKomponen[$loop6 - 1] .= ($urutanAkhirColumnSubKomponen > $urutanAwalColumnSubKomponen ? ',' . $column6 . $urutanAkhirColumnSubKomponen : '');
          $urutanAwalColumnSubKomponen = $urutan;
          $jumlahArraySubKomponen[$loop6] = '';
        }
        $loop6++;
      }

      if ($data['kode_uraian'] != $kode_uraian) {
        $urutanAkhirColumnSubKomponen = $urutan;
        $jumlahArraySubKomponen[$loop6 - 1] .= ($jumlahArraySubKomponen[$loop6 - 1] == "" ? '' : ',') . $column6 . $urutanAkhirColumnSubKomponen;

        $individualCell1 =  $column1 . $urutan;
        $individualCell2 =  $column2 . $urutan;
        $individualCell3 =  $column3 . $urutan;
        $individualCell4 =  $column4 . $urutan;
        $individualCell5 =  $column5 . $urutan;
        $individualCell6 =  $column6 . $urutan;

        $sheet->setCellValue($individualCell1, $data['kode_uraian']);
        $sheet->setCellValue($individualCell2, $data['deskripsi_uraian']);
        $sheet->setCellValue($individualCell3, '');
        $sheet->setCellValue($individualCell4, '');
        $sheet->setCellValue($individualCell5, '');
        $sheet->setCellValue($individualCell6, ''); // EDIT

        $sheet->getStyle($individualCell1)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $kode_uraian = $data['kode_uraian'];

        $uraian[$loop7] = $column6 . $urutan;
        $urutan++;
        if ($firstLineUraian == true) {
          $urutanAwalColumnDetailUraian = $urutan;
          $firstLineUraian = false;
        } else {
          $jumlahArrayUraian[$loop7 - 1] = $column6 . $urutanAwalColumnDetailUraian . ($urutanAkhirColumnDetailUraian > $urutanAwalColumnDetailUraian ? ':' . $column6 . $urutanAkhirColumnDetailUraian : '');

          $urutanAwalColumnDetailUraian = $urutan;
        }
        $loop7++;
      } else {
        $urutanAkhirColumnDetailUraian = $urutan;
      }


      $individualCell1 =  $column1 . $urutan;
      $individualCell2 =  $column2 . $urutan;
      $individualCell3 =  $column3 . $urutan;
      $individualCell4 =  $column4 . $urutan;
      $individualCell5 =  $column5 . $urutan;
      $individualCell6 =  $column6 . $urutan;

      $sheet->setCellValue($individualCell1, '');
      $sheet->setCellValue($individualCell2, $data['deskripsi_detail_uraian']);
      $sheet->setCellValue($individualCell3, $data['vol_detail_uraian']);
      $sheet->setCellValue($individualCell4, $data['sat_detail_uraian']);
      $sheet->setCellValue($individualCell5, $data['harga_detail_uraian']);
      $sheet->setCellValue($individualCell6, $data['jumlah_detail_uraian']);
      $baris[] += $urutan;

      $urutan++;
    }
    $sheet->getStyle($column1 . "6:" . $column6 . ($urutan - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle($column2 . "6:" . $column2 . ($urutan - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle($column3 . "6:" . $column3 . ($urutan - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle($column4 . "6:" . $column4 . ($urutan - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle($column5 . "6:" . $column5 . ($urutan - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle($column6 . "6:" . $column6 . ($urutan - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // Rumus Jumlah
    $jumlahArrayRKT[$loop1 - 1] = $column6 . $urutanAwalColumnRKT;
    $jumlahArrayUraian[$loop7 - 1] = $column6 . $urutanAwalColumnDetailUraian . ($urutanAkhirColumnDetailUraian > $urutanAwalColumnDetailUraian ? ':' . $column6 . $urutanAkhirColumnDetailUraian : '');

    $columnRealisasi = ['H', 'J', 'L', 'N', 'P', 'R', 'T', 'V', 'X', 'Z', 'AB', 'AD'];
    $sheet->setCellValue("F2", "=SUM(" . $jumlahArrayTotal . ")");
    $sheet->getStyle("F2")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    for ($j = 0; $j < count($columnRealisasi); $j++) {
      $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], 'F2'),  "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayTotal) . ")");
      $sheet->getStyle(str_replace('F', $columnRealisasi[$j], 'F2'))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    }
    for ($i = 0; $i < count($rkt); $i++) {
      $sheet->setCellValue($rkt[$i], "=SUM(" . $jumlahArrayRKT[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $rkt[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayRKT[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($digit_kro); $i++) {
      $sheet->setCellValue($digit_kro[$i], "=SUM(" . $jumlahArrayDigitKRO[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $digit_kro[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayDigitKRO[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($kro); $i++) {
      $sheet->setCellValue($kro[$i], "=SUM(" . $jumlahArrayKRO[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $kro[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayKRO[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($ro); $i++) {
      $sheet->setCellValue($ro[$i], "=SUM(" . $jumlahArrayRO[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $ro[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayRO[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($komponen); $i++) {
      $sheet->setCellValue($komponen[$i], "=SUM(" . $jumlahArrayKomponen[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $komponen[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayKomponen[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($sub_komponen); $i++) {
      $sheet->setCellValue($sub_komponen[$i], "=SUM(" . $jumlahArraySubKomponen[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $sub_komponen[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArraySubKomponen[$i]) . ")");
      }
    }
    for ($i = 0; $i < count($uraian); $i++) {
      $sheet->setCellValue($uraian[$i], "=SUM(" . $jumlahArrayUraian[$i] . ")");
      for ($j = 0; $j < count($columnRealisasi); $j++) {
        $sheet->setCellValue(str_replace('F', $columnRealisasi[$j], $uraian[$i]), "=SUM(" . str_replace('F', $columnRealisasi[$j], $jumlahArrayUraian[$i]) .
          ($j != 0 ? ',' . str_replace('F', $columnRealisasi[$j - 1], $uraian[$i]) : '')
          . ")");
      }
    }

    // Menambahkan sheet baru

    for ($i = 0; $i < count($bulan); $i++) {
      $sheetBaru = $spreadsheet->createSheet();
      // Menamai sheet baru
      $namaIndex = "indeks" . $bulan[$i];
      $sheetBaru->setTitle($bulan[$i]);
      ${$namaIndex} = $spreadsheet->getIndex($spreadsheet->getActiveSheet());

      $formattedMonth  = str_pad($i + 1, 2, '0', STR_PAD_LEFT); // Memastikan dua digit, misalnya, 01, 02, ..., 12
      $dataBaru = $this->relaxtimeRealisasiModel
        ->join('relaxtime_detail_uraian', 'relaxtime_detail_uraian.id_relaxtime_detail_uraian=relaxtime_realisasi.id_relaxtime_detail_uraian')
        ->join('relaxtime_uraian', 'relaxtime_uraian.id_relaxtime_uraian=relaxtime_detail_uraian.id_relaxtime_uraian')
        ->join('relaxtime_komponen', 'relaxtime_komponen.id_relaxtime_komponen=relaxtime_detail_uraian.id_relaxtime_komponen')
        ->join('relaxtime_ro', 'relaxtime_ro.id_relaxtime_ro=relaxtime_komponen.id_relaxtime_ro')
        ->join('relaxtime_kro', 'relaxtime_kro.id_relaxtime_kro=relaxtime_ro.id_relaxtime_kro')
        ->join('relaxtime_rkt', 'relaxtime_rkt.id_relaxtime_rkt=relaxtime_kro.id_relaxtime_rkt')
        ->orWhere("DATE_FORMAT(relaxtime_realisasi.tanggal, '%Y-%m-%d') LIKE '$year-$formattedMonth-%'")
        ->findAll();

      $sheetBaru->setCellValue('B1', 'Tanggal');
      $sheetBaru->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheetBaru->getStyle('B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      $sheetBaru->setCellValue('C1', 'Kode');
      $sheetBaru->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
      $sheetBaru->getStyle('C1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      $sheetBaru->setCellValue('D1', 'Uraian');
      $sheetBaru->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
      $sheetBaru->getStyle('D1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      $sheetBaru->setCellValue('E1', 'Realisasi');
      $sheetBaru->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheetBaru->getStyle('E1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


      $sheetBaru->getColumnDimension('A')->setWidth(0);
      $sheetBaru->getColumnDimension('C')->setWidth(25);
      $sheetBaru->getColumnDimension('D')->setWidth(64);
      $sheetBaru->getColumnDimension('E')->setWidth(17);

      $addRows = 2;
      foreach ($dataBaru as $key => $data) {
        $id = "C" . $data['id_relaxtime_rkt'] . $data['id_relaxtime_kro'] . $data['id_relaxtime_ro'] . $data['id_relaxtime_komponen'] . $data['id_relaxtime_uraian'] . $data['id_relaxtime_detail_uraian'];
        $kode = $data['kode_rkt'] . "\n" . $data['digit_kro'] . "." . $data['kode_kro'] . "." . $data['kode_relaxtime_ro'] . "." . $data['kode_komponen'] . "." . $data['kode_uraian'];
        $sheetBaru->setCellValue('A' . $key + $addRows, $id);
        $sheetBaru->setCellValue('B' . $key + $addRows, $data['tanggal']);
        $sheetBaru->setCellValue('C' . $key + $addRows, $kode);
        $sheetBaru->setCellValue('D' . $key + $addRows, $data['deskripsi_uraian'] . "\n" . $data['deskripsi_detail_uraian']);
        $sheetBaru->setCellValue('E' . $key + $addRows, $data['realisasi']);
        $sheetBaru->getStyle('D' . $key + $addRows)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheetBaru->getStyle('D' . $key + $addRows)->getAlignment()->setWrapText(true);
        $sheetBaru->getStyle('C' . $key + $addRows)->getAlignment()->setWrapText(true);
      }
    }




    // Beralih kembali ke sheet sebelumnya
    $spreadsheet->setActiveSheetIndex($indeksMasterSheet);

    // Menambahkan kolom baru di paling kiri (kolom A)
    $sheet->insertNewColumnBefore('A', 1);
    $columnRealisasi = ['I', 'K', 'M', 'O', 'Q', 'S', 'U', 'W', 'Y', 'AA', 'AC', 'AE'];
    $columnRealisasiPersent = ['H', 'J', 'L', 'N', 'P', 'R', 'T', 'V', 'X', 'Z', 'AB', 'AD'];
    foreach ($dataAll as $key => $data) {
      // Menggabungkan ID dari beberapa kolom menjadi satu string
      $id = implode('', [
        'C', $data['id_relaxtime_rkt'], $data['id_relaxtime_kro'],
        $data['id_relaxtime_ro'], $data['id_relaxtime_komponen'],
        $data['id_relaxtime_uraian'], $data['id_relaxtime_detail_uraian']
      ]);

      // Set nilai sel pada kolom A dengan ID yang telah dibentuk
      $sheet->setCellValue('A' . $baris[$key], $id);

      for ($i = 0; $i < count($bulan); $i++) {
        $rumusRealisasi = "=SUMIFS(" . $bulan[$i] . "!E:E, " . $bulan[$i] . "!A:A, MasterSheet!A" . $baris[$key] . ")";
        $sheet->setCellValue($columnRealisasi[$i] . $baris[$key], $rumusRealisasi);
      }
    }

    for ($i = 6; $i < $urutan; $i++) {
      for ($j = 0; $j < count($columnRealisasiPersent); $j++) {
        $sheet->setCellValue($columnRealisasiPersent[$j] . $i, "=" . $columnRealisasi[$j] . $i . "/G" . $i); // harus dibagi 100
        $sheet->getStyle($columnRealisasiPersent[$j] . $i)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
      }
    }
    for ($i = 0; $i < count($bulan); $i++) {
      $sheet->setCellValue($columnRealisasiPersent[$i] . "2", "=" . $columnRealisasi[$i] . "2" . "/G" . "2"); // harus dibagi 100
      $sheet->getStyle($columnRealisasiPersent[$i] . "2")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
    }
    $sheet->getColumnDimension('A')->setWidth(0);

    $sheet->getStyle("G6:G1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("I6:I1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("K6:K1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("M6:M1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("O6:O1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("Q6:Q1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("U6:U1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("W6:W1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("Y6:Y1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("AA6:AA1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("AC6:AC1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->getStyle("AE6:AE1200")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    // dd();


    $namaFile = 'Data Realisasi (' . date("Y") . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
public function cuti()
  {
    $tanggal = $this->request->getVar("tanggal");
    $date_awal = str_replace("-", "/", substr($tanggal, 0, 10));
    $date_akhir = str_replace("-", "/", substr($tanggal, 13, 10));

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    // Menamai lembar kerja (sheet) awal
    $spreadsheet->getActiveSheet()->setTitle('Akumulatif');
    // Menyimpan indeks sheet sebelumnya
    $indeksMasterSheet = $spreadsheet->getIndex($spreadsheet->getActiveSheet());


    // Set the column widths
    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(16);
    $sheet->getColumnDimension('E')->setWidth(16);
    $sheet->getColumnDimension('F')->setWidth(16);
    $sheet->getColumnDimension('G')->setWidth(16);
    $sheet->getColumnDimension('H')->setWidth(16);

    // Merge cells and set the content
    $sheet->mergeCells('A1:A2');
    $sheet->mergeCells('B1:B2');
    $sheet->mergeCells('C1:C2');
    $sheet->mergeCells('H1:H2');
    $sheet->mergeCells('D1:G1');
    $sheet->setCellValue('A1', 'NO. ');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('B1', 'NAMA');
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C1', 'NIP');
    $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('D1', 'CUTI');
    $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('H1', 'IZIN');
    $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('D2', 'TAHUNAN');
    $sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('E2', 'SAKIT');
    $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('F2', 'ALASAN PENTING');
    $sheet->getStyle('F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('G2', 'MELAHIRKAN');
    $sheet->getStyle('G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $dataPegawai = $this->dataPegawaiModel
      ->join('golongan', 'id_golongan')
      ->join('status_pegawai', 'id_status_pegawai')
      ->where('status_bekerja', 'Aktif')
      ->whereIn('status_pegawai.status_pegawai', ['PNS', 'PPPK', 'PPNPN'])
      ->orderBy('status_pegawai', 'ASC')
      ->orderBy('golongan', 'DESC')
      ->findAll();

    $row = 3;
    $number = 1;
    foreach ($dataPegawai as $data) {
      $sheet->setCellValueExplicit("A" . $row, $number++ . '.', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->getStyle("A" . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheet->setCellValue("B" . $row, $data['nama']);
      $sheet->getCell("C" . $row++)->setValueExplicit($data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    }
    $sheet->getStyle('A1:H' . ($row - 1))->applyFromArray($borderStyle);
    $jmlDataPegawai = $row;

    // Simpan indeks sheet sebelumnya
    $previousSheetIndex = $spreadsheet->getIndex($spreadsheet->getActiveSheet());

    $sheetBaru = $spreadsheet->createSheet();
    // Menamai sheet baru
    $sheetBaru->setTitle("DataCuti");
    
    $sheetBaru->setCellValue('A1', 'NO');
    $sheetBaru->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('B1', 'NIP');
    $sheetBaru->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('C1', 'NAMA');
    $sheetBaru->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('D1', 'JENIS CUTI');
    $sheetBaru->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('E1', 'TANGGAL AWAL');
    $sheetBaru->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('F1', 'TANGGAL AKHIR');
    $sheetBaru->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('G1', 'TOTAL');
    $sheetBaru->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    $dataCuti = $this->cutiDataCutiModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = cuti_data_cuti.id_data_pegawai')
      ->where('cuti_data_cuti.id_cuti_status_kepegawaian', "2")
      ->Where('cuti_data_cuti.id_cuti_status_atasan', "2")
      ->Where('cuti_data_cuti.id_cuti_status_pejabat', "2")
      ->where('tanggal_awal BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
      ->findAll();

    // dd($dataCuti);

    $row = 2;
    $number = 1;
    foreach ($dataCuti as $data) {
      $tanggal1 = explode('|', $data['tanggal_awal']);
      $tanggal2 = explode('|', $data['tanggal_akhir']);
      for ($i = 0; $i < count($tanggal1); $i++) {
        if ($date_akhir >= $tanggal1[$i]) {
          // d($tanggal1[$i], $tanggal2[$i]);
          // d((hitungHari($tanggal1[$i], $tanggal2[$i]) + 1));
          $sheetBaru->setCellValueExplicit("A" . $row, $number++ . '.', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
          $sheetBaru->getCell("B" . $row)->setValueExplicit($data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
          $sheetBaru->setCellValue("C" . $row, $data['nama']);
          $sheetBaru->setCellValue("D" . $row, $data['jenis_cuti']);
          $sheetBaru->setCellValue("E" . $row, $tanggal1[$i]);
          $sheetBaru->setCellValue("F" . $row, $tanggal2[$i]);
          $sheetBaru->setCellValue("G" . $row++, (hitungHari($tanggal1[$i], $tanggal2[$i]) + 1));
        }
      }
    }
    
    $sheetBaru->getColumnDimension('A')->setAutoSize(true);
    $sheetBaru->getColumnDimension('B')->setAutoSize(true);
    $sheetBaru->getColumnDimension('C')->setAutoSize(true);
    $sheetBaru->getColumnDimension('D')->setAutoSize(true);
    $sheetBaru->getColumnDimension('E')->setAutoSize(true);
    $sheetBaru->getColumnDimension('F')->setAutoSize(true);
    $sheetBaru->getColumnDimension('G')->setAutoSize(true);
    $sheetBaru->getStyle('A1:G' . ($row - 1))->applyFromArray($borderStyle);

    $sheetBaru = $spreadsheet->createSheet();
    // Menamai sheet baru
    $sheetBaru->setTitle("DataPermohonan");
    
    $sheetBaru->setCellValue('A1', 'NO');
    $sheetBaru->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('B1', 'NIP');
    $sheetBaru->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('C1', 'NAMA');
    $sheetBaru->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('D1', 'JENIS IZIN');
    $sheetBaru->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheetBaru->setCellValue('E1', 'TANGGAL');
    $sheetBaru->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $dataPermohoan = $this->cutiDataPermohonanModel
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = cuti_data_permohonan.id_data_pegawai')
      ->where('cuti_data_permohonan.id_cuti_status_kepegawaian', "2")
      ->where('cuti_data_permohonan.id_cuti_status_atasan', "2")
      ->where('STR_TO_DATE(cuti_data_permohonan.tanggal, "%d %M %Y") BETWEEN "' . $date_awal . '" AND "' . $date_akhir . '"', null, false)
      ->findAll();

    $row = 2;
    $number = 1;
    foreach ($dataPermohoan as $data) {
      // d($tanggal1[$i], $tanggal2[$i]);
      // d((hitungHari($tanggal1[$i], $tanggal2[$i]) + 1));
      $sheetBaru->setCellValueExplicit("A" . $row, $number++ . '.', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheetBaru->getCell("B" . $row)->setValueExplicit($data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheetBaru->setCellValue("C" . $row, $data['nama']);
      $sheetBaru->setCellValue("D" . $row, $data['jenis_permohonan']);
      $sheetBaru->setCellValue("E" . $row++, $data['tanggal']);
    }
    
    $sheetBaru->getColumnDimension('A')->setAutoSize(true);
    $sheetBaru->getColumnDimension('B')->setAutoSize(true);
    $sheetBaru->getColumnDimension('C')->setAutoSize(true);
    $sheetBaru->getColumnDimension('D')->setAutoSize(true);
    $sheetBaru->getColumnDimension('E')->setAutoSize(true);
    $sheetBaru->getStyle('A1:E' . ($row - 1))->applyFromArray($borderStyle);

    // $spreadsheet->setActiveSheetIndex($previousSheetIndex);
    // $activeSheet = $spreadsheet->getActiveSheet();

    $kodeD = '=SUMIFS(DataCuti!G:G, DataCuti!D:D, "Cuti Tahunan", DataCuti!B:B, Akumulatif!C:C)';
    $kodeE = '=SUMIFS(DataCuti!G:G, DataCuti!D:D, "Cuti Sakit", DataCuti!B:B, Akumulatif!C:C)';
    $kodeF = '=SUMIFS(DataCuti!G:G, DataCuti!D:D, "Cuti Karena Alasan Penting", DataCuti!B:B, Akumulatif!C:C)';
    $kodeG = '=SUMIFS(DataCuti!G:G, DataCuti!D:D, "Cuti Melahirkan", DataCuti!B:B, Akumulatif!C:C)';
    $kodeH = '=COUNTIF(DataPermohonan!B:B,Akumulatif!C:C)';
    for ($i = 3; $i < $jmlDataPegawai; $i++) {
      $sheet->setCellValue('D' . $i, $kodeD);
      $sheet->setCellValue('E' . $i, $kodeE);
      $sheet->setCellValue('F' . $i, $kodeF);
      $sheet->setCellValue('G' . $i, $kodeG);
      $sheet->setCellValue('H' . $i, $kodeH);
    }
    $spreadsheet->setActiveSheetIndex(0);
    $namaFile = 'Data Cuti (' . $tanggal . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function sinadi()
  {
    $tanggal = $this->request->getVar("tanggal");
    $date_awal = str_replace("-", "/", substr($tanggal, 0, 10));
    $date_akhir = str_replace("-", "/", substr($tanggal, 13, 10));

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    // Menamai lembar kerja (sheet) awal
    $spreadsheet->getActiveSheet()->setTitle('DataSinadi');

    // Set the column widths
    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(50);
    $sheet->getColumnDimension('C')->setWidth(13);
    $sheet->getColumnDimension('D')->setWidth(13);
    $sheet->getColumnDimension('E')->setWidth(40);
    $sheet->getColumnDimension('F')->setWidth(30);
    $sheet->getColumnDimension('G')->setWidth(20);
    $sheet->getColumnDimension('H')->setWidth(35);
    $sheet->getColumnDimension('I')->setWidth(50);
    $sheet->getColumnDimension('J')->setWidth(9);
    $sheet->getColumnDimension('K')->setWidth(25);
    $sheet->getColumnDimension('L')->setWidth(13);
    $sheet->getColumnDimension('M')->setWidth(25);
    $sheet->getColumnDimension('N')->setWidth(13);
    $sheet->getColumnDimension('O')->setWidth(19);
    $sheet->getColumnDimension('P')->setWidth(23);
    $sheet->getColumnDimension('Q')->setWidth(20);
    $sheet->getColumnDimension('R')->setWidth(20);
    $sheet->getColumnDimension('S')->setWidth(20);


    $sheet->mergeCells('A1:A2');
    $sheet->mergeCells('B1:B2');
    $sheet->mergeCells('C1:D1');
    $sheet->mergeCells('E1:E2');
    $sheet->mergeCells('F1:F2');
    $sheet->mergeCells('G1:G2');
    $sheet->mergeCells('H1:H2');
    $sheet->mergeCells('I1:I2');
    $sheet->mergeCells('J1:J2');
    $sheet->mergeCells('K1:L1');
    $sheet->mergeCells('M1:N1');
    $sheet->mergeCells('O1:P1');
    $sheet->mergeCells('Q1:Q2');
    $sheet->mergeCells('R1:R2');
    $sheet->mergeCells('S1:S2');

    // Merge cells and set the content
    $sheet->setCellValue('A1', 'NO. ');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('B1', 'Nama Kegiatan');
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C1', 'Tanggal Pelaksanaan');
    $sheet->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C2', 'Berangkat');
    $sheet->getStyle('C2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('C2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('D2', 'Pulang');
    $sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('E1', 'Tempat Perjalanan');
    $sheet->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('F1', 'Mata Anggaran');
    $sheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('F1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('G1', 'NIP');
    $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('G1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('H1', 'Nama Pegawai');
    $sheet->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('H1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('I1', 'Jabatan Pegawai');
    $sheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('I1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('J1', 'Golongan');
    $sheet->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('J1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('K1', 'Surat Tugas');
    $sheet->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('K2', 'Nomor');
    $sheet->getStyle('K2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('L2', 'Tanggal');
    $sheet->getStyle('L2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('L2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('M1', 'SPPD');
    $sheet->getStyle('M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('M1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('M2', 'Nomor');
    $sheet->getStyle('M2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('M2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('N2', 'Tanggal');
    $sheet->getStyle('N2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('N2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('O1', 'Kota');
    $sheet->getStyle('O1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('O1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('O2', 'Asal');
    $sheet->getStyle('O2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('O2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('P2', 'Tujuan');
    $sheet->getStyle('P2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('P2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('Q1', 'Nominal Transport');
    $sheet->getStyle('Q1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('Q1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('R1', 'Uang Harian');
    $sheet->getStyle('R1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('R1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('S1', 'Biaya Penginapan');
    $sheet->getStyle('S1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('S1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $dataSinadi = $this->sinadiPerjalananDinasModel
      ->join('sinadi_perjalanan_dinas_peserta', 'id_perjalanan')
      ->where('tgl_pelaksanaan BETWEEN "' . $date_awal . '" and "' . $date_akhir . '"')
      ->orderBy('sinadi_perjalanan_dinas_peserta.id_perjalanan, sinadi_perjalanan_dinas.tgl_pelaksanaan', 'DESC')
      ->findAll();

    $styleArray = [
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'rgb' => 'CECECE', // Ganti dengan warna yang diinginkan, contoh: 'FF0000' untuk merah
        ],
      ],
    ];
    $pic = [
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'rgb' => '16B3AC', // Ganti dengan warna yang diinginkan, contoh: 'FF0000' untuk merah
        ],
      ],
    ];

    $row = 3;
    $number = 1;
    foreach ($dataSinadi as $data) {
      $sheet->setCellValueExplicit("A" . $row, $number++ . '.', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->getStyle("A" . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
      $sheet->setCellValue("B" . $row, $data['nama_kegiatan']);
      $sheet->setCellValue("C" . $row, $data['tgl_pergi']);
      $sheet->setCellValue("D" . $row, $data['tgl_pulang']);
      $sheet->setCellValue("E" . $row, $data['tempat_perjalanan']);
      $sheet->setCellValue("F" . $row, $data['mata_anggaran']);
      $sheet->getCell("G" . $row)->setValueExplicit($data['nip_pegawai'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->setCellValue("H" . $row, $data['nama_pegawai']);
      $sheet->setCellValue("I" . $row, $data['jabatan_pegawai']);
      $sheet->setCellValue("J" . $row, $data['golongan']);
      $sheet->setCellValue("K" . $row, $data['no_surat_tugas']);
      $sheet->setCellValue("L" . $row, $data['tgl_surat_tugas']);
      $sheet->setCellValue("M" . $row, $data['no_sppd']);
      $sheet->setCellValue("N" . $row, $data['tgl_sppd']);
      $sheet->setCellValue("O" . $row, $data['kota_asal']);
      $sheet->setCellValue("P" . $row, $data['kota']);
      $sheet->setCellValue("Q" . $row, $data['transport_jumlah']);
      $sheet->setCellValue("R" . $row, $data['harian_jumlah']);
      $sheet->setCellValue("S" . $row, $data['penginapan_jumlah']);
      $sheet->getStyle("B" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("E" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("F" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("H" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("I" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("K" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("M" . $row)->getAlignment()->setWrapText(true);
      $sheet->getStyle("P" . $row)->getAlignment()->setWrapText(true);
      if ($data['kota'] == $data['kota_asal']) {
        $sheet->getStyle('A' . $row . ':S' . $row)->applyFromArray($styleArray);
      }
      if ($data['nama_pegawai'] == $data['pic']) {
        $sheet->getStyle("H" . $row)->applyFromArray($pic);
      }

      $row++;
    }
    $sheet->getStyle('A1:S' . ($row - 1))->applyFromArray($borderStyle);
    $sheet->getStyle('A3:S' . ($row - 1))->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
    $styleArray = [
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'rgb' => 'FFFF00', // Ganti dengan warna yang diinginkan, contoh: 'FF0000' untuk merah
        ],
      ],
      'font' => [
        'bold' => true,
      ],
    ];

    $sheet->getStyle('A1:S2')->applyFromArray($styleArray);

    // $style = $sheet->getStyle('A3:S' . ($row - 1));
    // $alignment = $style->getAlignment();
    // $alignment->setWrapText(true);

    $namaFile = 'Data Sinadi (' . $tanggal . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function uklw_penjamah_makanan()
  {
    $id = $this->request->getVar("pemkes");
    $getData = $this->uklwPemkesModel->where('id_uklw_pemkes', $id)
      ->select("*, uklw_pasien.nama as nama_pasien, user.nama as nama_petugas, uklw_pemkes.created_at")
      ->join('user', 'uklw_pemkes.created_by=user.id_user')
      ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien=uklw_pemkes.id_uklw_pasien')
      ->first();

    $created_at = $getData['created_at'];
    $profil_kantor = $this->profilKantorModel
      ->where('tertanggal <=', $created_at)
      ->orderBy('tertanggal', 'DESC')
      ->first();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    // Menamai lembar kerja (sheet) awal
    $spreadsheet->getActiveSheet()->setTitle('Penjamah Makanan');

    $sheet->getStyle('A:L')->getFont()->setName('Arial');

    $sheet->getColumnDimension('A')->setWidth(3);
    $sheet->getColumnDimension('B')->setWidth(6);
    $sheet->getColumnDimension('C')->setWidth(53.5);
    $sheet->getColumnDimension('D')->setWidth(1.5);
    $sheet->getColumnDimension('E')->setWidth(9);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(3);
    $sheet->getColumnDimension('H')->setWidth(9);
    $sheet->getColumnDimension('I')->setWidth(9);
    $sheet->getColumnDimension('J')->setWidth(38);
    $sheet->getColumnDimension('K')->setWidth(6.5);
    $sheet->getColumnDimension('L')->setWidth(6.5);
    $sheet->getColumnDimension('M')->setWidth(2.5);

    $sheet->setCellValue('A2', 'PEMERIKSAAN KESEHATAN PENJAMAH MAKANAN');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->mergeCells('A2:L2');
    $sheet->setCellValue('A3', name_uppercase($profil_kantor['nama_kantor']));
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->mergeCells('A3:L3');

    $sheet->getStyle('A2:A3')->getFont()->setSize(14);
    $sheet->getStyle('A2:A3')->getFont()->setBold(true);
    $sheet->getStyle('A2:A3')->getFont()->setName('Calibri');


    $sheet->setCellValue('A7', 'I.');
    $sheet->setCellValue('B7', 'Data Umum Penjamah Makanan');
    $sheet->mergeCells('B7:I7');
    $sheet->getStyle('A7:B7')->getFont()->setBold(true);

    $sheet->setCellValue('B8', '1');
    $sheet->setCellValue('B9', '2');
    $sheet->setCellValue('B10', '3');
    $sheet->setCellValue('B11', '4');
    $sheet->setCellValue('B12', '5');
    $sheet->setCellValue('B13', '6');
    $sheet->getStyle('B8:B13')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B8:B13')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet->setCellValue('C8', 'Nama Lengkap');
    $sheet->setCellValue('C9', 'Umur');
    $sheet->setCellValue('C10', 'Jenis Kelamin');
    $sheet->setCellValue('C11', 'Jenis Pekerjaan');
    $sheet->setCellValue('C12', 'Pendidikan');
    $sheet->setCellValue('C13', 'Alamat (tempat tinggal)');

    $sheet->setCellValue('E8', name_capitalize($getData['nama_pasien']));
    $sheet->setCellValue('E9', selisi_waktu($getData['tgl_lahir'], (date("Y-m-d", strtotime($getData['created_at'])))));
    $sheet->setCellValue('E10', $getData['jk']);
    $sheet->setCellValue('E11', $getData['pekerjaan']);
    $sheet->setCellValue('E12', $getData['pendidikan']);
    $sheet->setCellValue('E13', $getData['alamat']);
    // $sheet->mergeCells('E8:J8');
    // $sheet->mergeCells('E9:J9');
    // $sheet->mergeCells('E10:J10');
    // $sheet->mergeCells('E11:J11');
    // $sheet->mergeCells('E12:J12');
    // $sheet->mergeCells('E13:J13');

    $sheet->setCellValue('D8', ':');
    $sheet->setCellValue('D9', ':');
    $sheet->setCellValue('D10', ':');
    $sheet->setCellValue('D11', ':');
    $sheet->setCellValue('D12', ':');
    $sheet->setCellValue('D13', ':');


    $sheet->setCellValue('A15', 'II.');
    $sheet->setCellValue('B15', 'Pelayanan Kesehatan Kerja');
    $sheet->mergeCells('B15:I15');
    $sheet->getStyle('A15:B15')->getFont()->setBold(true);

    $baris = 16;
    $sheet->setCellValue('A' . $baris . '', 'A.');
    $sheet->setCellValue('B' . $baris . '', 'Wawancara');
    $sheet->mergeCells('B' . $baris . ':I' . $baris);
    $sheet->getStyle('A' . $baris . ':B' . $baris)->getFont()->setBold(true);

    $sheet->setCellValue('K' . $baris, 'Ya');
    $sheet->setCellValue('L' . $baris, 'Tidak');
    $sheet->getStyle('K' . $baris . ':L' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('K' . $baris . ':L' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('K' . $baris . ':L' . $baris)->applyFromArray($borderStyle);
    $baris++;

    $array = unserialize($getData['wawancara']);
    foreach ($array as $no => $data) {
      $sheet->setCellValue('B' . $baris, ($no + 1) . '.');
      $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      $sheet->setCellValue('C' . $baris, $data['wawancara']);
      if ($data['hasil_wawancara'] == 'Ya') {
        $sheet->setCellValue('K' . $baris, 'P');
      } else {
        $sheet->setCellValue('L' . $baris, 'P');
      }
      $sheet->getStyle('K' . $baris . ':L' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('K' . $baris . ':L' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('K' . $baris . ':L' . $baris)->getFont()->setSize(14);
      $sheet->getStyle('K' . $baris . ':L' . $baris)->getFont()->setName('Wingdings 2');
      $sheet->getStyle('K' . $baris . ':L' . $baris)->applyFromArray($borderStyle);

      $baris++;
    }
    $baris++;

    $sheet->setCellValue('A' . $baris . '', 'C.');
    $sheet->setCellValue('B' . $baris . '', 'Pemeriksaan dan Riwayat Kesehatan Penjamah Makanan');
    $sheet->getStyle('A' . $baris . ':B' . $baris)->getFont()->setBold(true);
    $baris++;

    $no = 1;
    $array = unserialize($getData['riwayat_penyakit']);
    foreach ($array as $data) {
      $sheet->setCellValue('B' . $baris, $no++ . '.');
      $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

      $sheet->setCellValue('C' . $baris, name_capitalize($data['riwayat_penyakit']));
      $sheet->setCellValue('D' . $baris, ':');
      $sheet->setCellValue('E' . $baris, $data['hasil_riwayat_penyakit']);
      $baris++;
    }
    $sheet->setCellValue('B' . $baris, $no++ . '.');
    $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C' . $baris, 'Tanda-tanda Vital');
    $baris++;

    $findArray = ['Tekanan Darah', 'Nadi', 'Respirasi', 'Suhu', 'Tinggi Badan', 'Berat Badan'];
    $perbandingan = ['TEKANAN DARAH', 'HEART RATE', 'RESPIRATION RATE', 'SUHU', 'TINGGI BADAN', 'BERAT BADANG'];
    $satuanArray = ['mmHg', 'x/mnt', 'x/mnt', 'x/mnt', 'CM', 'Kg'];

    $array = unserialize($getData['pemeriksaan_fisik']);
    for ($i = 0; $i < count($findArray); $i++) {
      $sheet->setCellValue('C' . $baris, $findArray[$i]);
      $sheet->setCellValue('D' . $baris, ':');
      $sheet->setCellValue('F' . $baris, $satuanArray[$i]);
      foreach ($array as $data) {
        if (strpos(strtolower($data['pemeriksaan_fisik']), strtolower($perbandingan[$i])) !== false) {
          $sheet->setCellValue('E' . $baris, $data['hasil_pemeriksaan_fisik']);
        }
      }
      if ($i > 4) {
        $sheet->setCellValue('B' . $baris, $no++ . '.');
        $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
      }
      $baris++;
    }

    $sheet->setCellValue('E' . $baris, 'Kebersihan');
    $sheet->getStyle('E' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->mergeCells('E' . $baris . ':J' . $baris);
    $sheet->getStyle('E' . $baris)->getFont()->setBold(true);
    $baris++;

    $sheet->setCellValue('B' . $baris, $no++ . '.');
    $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C' . $baris, 'Personal Hygiene');
    $sheet->setCellValue('E' . $baris, 'Ya');
    $sheet->setCellValue('F' . $baris, 'Tidak');
    $sheet->setCellValue('G' . $baris, 'Keterangan');
    $sheet->mergeCells('G' . $baris . ':J' . $baris);
    $sheet->getStyle('E' . $baris . ':G' . $baris)->getFont()->setBold(true);
    $sheet->getStyle('E' . $baris . ':G' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('E' . $baris . ':G' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->getStyle('E' . $baris . ':J' . $baris)->applyFromArray($borderStyle);
    $baris++;

    $array = unserialize($getData['hygiene']);
    foreach ($array as $data) {
      $sheet->setCellValue('C' . $baris, $data['hygiene']);
      $sheet->setCellValue('G' . $baris, $data['ket_hygiene']);
      $sheet->mergeCells('G' . $baris . ':J' . $baris);
      if ($data['hasil_hygiene'] == 'Ya') {
        $sheet->setCellValue('E' . $baris, 'P');
      } else {
        $sheet->setCellValue('F' . $baris, 'P');
      }
      $sheet->getStyle('E' . $baris . ':F' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $sheet->getStyle('E' . $baris . ':F' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('E' . $baris . ':F' . $baris)->getFont()->setSize(14);
      $sheet->getStyle('E' . $baris . ':F' . $baris)->getFont()->setName('Wingdings 2');
      $sheet->getStyle('E' . $baris . ':J' . $baris)->applyFromArray($borderStyle);
      $baris++;
    }
    $baris++;

    $sheet->setCellValue('B' . $baris, $no++ . '.');
    $sheet->getStyle('B' . $baris)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('B' . $baris)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet->setCellValue('C' . $baris, 'Pemeriksaan Fisik');
    $array = unserialize($getData['pemeriksaan_fisik_penjamah']);
    foreach ($array as $data) {
      $sheet->setCellValue('C' . $baris, $data['pemeriksaan_fisik_penjamah']);
      $sheet->setCellValue('G' . $baris, ':');
      $sheet->setCellValue('H' . $baris, $data['ket_pemeriksaan_fisik_penjamah']);
      $sheet->mergeCells('H' . $baris . ':L' . $baris);

      $baris++;
    }

    for ($row = 7; $row < $baris; $row++) {
      $sheet->getRowDimension($row)->setRowHeight(20);
    }
    $baris++;
    $baris++;
    $sheet->setCellValue('C' . $baris, 'Penjamah Yang Diperiksa');
    $sheet->setCellValue('J' . $baris, $getData['kota'] . ', ' . mediumdate_indo(date("Y-m-d", strtotime($getData['created_at']))));
    $sheet->mergeCells('J' . $baris . ':L' . $baris);
    $baris += 5;
    $sheet->setCellValue('J' . $baris, 'Petugas');
    $sheet->mergeCells('J' . $baris . ':L' . $baris);
    $baris++;
    $sheet->setCellValue('C' . $baris, name_uppercase($getData['nama_pasien']));
    $sheet->setCellValue('J' . $baris, name_uppercase($getData['nama_petugas']));
    $sheet->getStyle('C' . $baris . ':J' . $baris)->getFont()->setBold(true);
    $sheet->mergeCells('J' . $baris . ':L' . $baris);


    $sheet->getPageSetup()->setPrintArea('A:M');
    $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
    // $sheet->getPageSetup()->setScale(100); // 100% scaling

    // Mengatur skala agar area cetak mengisi satu halaman
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);

    // Mengatur margin halaman untuk memberi ruang di sekitar area cetak
    $sheet->getPageMargins()->setTop(0.5);
    $sheet->getPageMargins()->setRight(0.5);
    $sheet->getPageMargins()->setLeft(0.5);
    $sheet->getPageMargins()->setBottom(0.5);

    $namaFile = 'Penjamah Makanan (' . mediumdate_indo(date("Y-m-d", strtotime($getData['created_at']))) . '_' . $getData['nama_pasien'] . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function sinadiLembarVerifikasi($id) {
      $get = $this->sinadiLembarVerifikasiModel
      ->join('sinadi_perjalanan_dinas', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_lembar_verifikasi.id_perjalanan')
      ->join('sinadi_perjalanan_dinas_peserta', 'sinadi_perjalanan_dinas.id_perjalanan=sinadi_perjalanan_dinas_peserta.id_perjalanan')
      ->where('sinadi_lembar_verifikasi.id_perjalanan', $id)
      ->first();
      
      if($get) {
        $by = $this->dataPegawaiModel
          ->join('user', 'user.id_user=data_pegawai.id_user')
          ->where('user.username', session()->get('username'))
          ->first();
          
        $suplier = unserialize($get['suplier']);
        $daftar_periksa = unserialize($get['daftar_periksa']);
        
        // Terapkan fungsi name_capitalize() ke tiap elemen
        $processed = array_map('name_capitalize', $suplier);
        
        // Gabungkan menjadi string biasa dengan pemisah koma
        $suplier = implode(', ', $processed);
          
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->getColumnDimension('B')->setWidth(4);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(2);
        $sheet->getColumnDimension('E')->setWidth(5);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(4);
        $sheet->getColumnDimension('H')->setWidth(41);
        $sheet->getColumnDimension('I')->setWidth(11);
        
        $sheet->getStyle("B2:I37")->getAlignment()->setWrapText(true);
        $sheet->getStyle('B2:I37')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        $sheet->mergeCells('B2:I2');
        $sheet->mergeCells('E4:G4');
        $sheet->mergeCells('E5:I5');
        $sheet->mergeCells('E6:I6');
        $sheet->mergeCells('E7:I7');
        $sheet->mergeCells('E8:I8');
        $sheet->mergeCells('B10:I10');
        $sheet->mergeCells('B11:F11');
        $sheet->mergeCells('H11:I11');
        $sheet->mergeCells('F9:I9');
        
        
        $borderStyle = [
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '000000'],
            ],
          ],
        ];
        $borderStyleOutline = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('B4:D4')->applyFromArray($borderStyle);
        $sheet->getStyle('E4:I4')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('B5:I8')->applyFromArray($borderStyle);
        $sheet->getStyle('B9:D9')->applyFromArray($borderStyle);
        $sheet->getStyle('E9:I9')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('B10:I11')->applyFromArray($borderStyle);
        $sheet->getStyle('B12:E37')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('F12:F37')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('H12:H37')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('I12:I37')->applyFromArray($borderStyleOutline);
        $sheet->getStyle('G12:G37')->applyFromArray($borderStyleOutline);
        
        $sheet->getStyle('F12:F37')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I12:I37')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        for ($row = 12; $row <= 37; $row++) {
          $sheet->mergeCells('B' . $row . ':E' . $row);
        }
        
        $sheet->setCellValue('B2', 'LEMBAR VERIFIKASI KEUANGAN');
        $sheet->getStyle('B2')->getFont()->setBold(true);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('B4', '1');
        $sheet->setCellValue('B5', '2');
        $sheet->setCellValue('B6', '3');
        $sheet->setCellValue('B7', '4');
        $sheet->setCellValue('B8', '5');
        $sheet->setCellValue('B9', '6');
        
        $sheet->setCellValue('C4', 'NO. SPBY/TANGGAL');
        $sheet->setCellValue('C5', 'NAMA SUPPLIER');
        $sheet->setCellValue('C6', 'JENIS TAGIHAN/SUMBER DANA');
        $sheet->setCellValue('C7', 'MAK');
        $sheet->setCellValue('C8', 'URAIAN KEGIATAN');
        $sheet->setCellValue('C9', 'NILAI TAGIHAN');
        
        $sheet->setCellValue('D4', ':');
        $sheet->setCellValue('D5', ':');
        $sheet->setCellValue('D6', ':');
        $sheet->setCellValue('D7', ':');
        $sheet->setCellValue('D8', ':');
        $sheet->setCellValue('D9', ':');
        $sheet->getStyle('D4:D9')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('E4', $get['no_spby']);
        $sheet->setCellValue('H4', "tgl. " . mediumdate_indo($get['tgl']));
        $sheet->setCellValue('E5', $suplier);
        $sheet->setCellValue('E6', $get['tagihan']);
        $sheet->setCellValue('E7', $get['mata_anggaran']);
        $sheet->setCellValue('E8', $get['uraian']);
        $sheet->setCellValue('E9', 'Rp');
        $sheet->setCellValue('F9', $get['total_tagihan']);
        $sheet->getStyle("F9")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('F9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        
        $sheet->setCellValue('B10', 'KELENGKAPAN DATA DUKUNG');
        $sheet->getStyle('B10')->getFont()->setBold(true);
        $sheet->getStyle('B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('B11', 'Daftar Periksa');
        $sheet->getStyle('B11')->getFont()->setBold(true);
        $sheet->getStyle('B11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('H11', 'Daftar Periksa');
        $sheet->getStyle('H11')->getFont()->setBold(true);
        $sheet->getStyle('H11')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->setCellValue('B12', 'BELANJA BARANG/JASA');
        $sheet->getStyle('B12')->getFont()->setBold(true);
        $sheet->setCellValue('B13', "- Surat Pesanan/SPK/Surat Perjanjian");
        $sheet->setCellValue('B14', "- Berita Acara Pembayaran");
        $sheet->setCellValue('B15', "- BAST/Surat Jalan/Tanda Terima Barang");
        $sheet->setCellValue('B16', "- Faktur Pajak (Jika transaksi diatas Rp 2.000.000,-)");
        $sheet->setCellValue('B17', "- Faktur/Invoice");
        $sheet->setCellValue('B18', "- Kwitansi");
        $sheet->setCellValue('B19', "- NPWP dan Rekening Bank");
        $sheet->setCellValue('B20', "- Foto Barang/Laporan Kegiatan ");
        $sheet->setCellValue('B21', "- Dokumen kelengkapan lainnya (TKDN)");
        
        $sheet->setCellValue('B23', 'PERJALANAN DINAS DALAM KOTA/TRANSLOK');
        $sheet->getStyle('B23')->getFont()->setBold(true);
        
        $column = 24;
        if($get['kota_asal'] == $get['kota']) {
            foreach($daftar_periksa as $data):
                $sheet->setCellValue('B' . $column, "- " . $data['list']);
                $sheet->setCellValue('F' . $column++, ($data['value'] == "checked" ? "√" : ""));
            endforeach;
        } else {
            $sheet->setCellValue('B24', "- Nominatif");
            $sheet->setCellValue('B25', "- Rincian Biaya/Kuitansi ");
            $sheet->setCellValue('B26', "- Surat Pengeluaran Riil");
            $sheet->setCellValue('B27', "- Surat Tugas yang telah di tandatangani dan stempel Instansi Tujuan ");
            $sheet->setCellValue('B28', "- Laporan dan Dokumentasi Kegiatan");
        }
        
        $sheet->setCellValue('B30', 'BELANJA KONSUMSI');
        $sheet->getStyle('B30')->getFont()->setBold(true);
        $sheet->setCellValue('B31', "- Kuitansi dan Bukti Pembelian ");
        $sheet->setCellValue('B32', "- Surat Tugas/Surat Kegiatan");
        $sheet->setCellValue('B33', "- Daftar Hadir");
        $sheet->setCellValue('B34', "- Dokumentasi Kegiatan");
        $sheet->setCellValue('B35', "- Dokumen kelengkapan lainnya");
        
        $sheet->setCellValue('H12', 'PERJALANAN DINAS LUAR KOTA');
        $sheet->getStyle('H12')->getFont()->setBold(true);
        
        $column = 13;
        if($get['kota_asal'] != $get['kota']) {
            foreach($daftar_periksa as $data):
                $sheet->setCellValue('H' . $column, "- " . $data['list']);
                $sheet->setCellValue('I' . $column++, ($data['value'] == "checked" ? "√" : ""));
            endforeach;
        } else {
            $sheet->setCellValue('H13', "- Nominatif");
            $sheet->setCellValue('H14', "- Rincian Biaya Perjalanan Dinas");
            $sheet->setCellValue('H15', "- Surat Pengeluaran Riil");
            $sheet->setCellValue('H16', "- SPPD yang telah di tandatangai Kepala Balai, Instansi Tujuan dan PPK");
            $sheet->setCellValue('H17', "- Bukti pembayaran penginapan");
            $sheet->setCellValue('H18', "- Bukti pengeluaran transportasi");
            $sheet->setCellValue('H19', "- Surat Tugas");
            $sheet->setCellValue('H20', "- Surat Undangan Kegiatan");
            $sheet->setCellValue('H21', "- Laporan dan Dokumentasi Kegiatan dilengkapi dengan Geotag");
        }
        
        $sheet->setCellValue('H23', 'HONOR NARASUMBER');
        $sheet->getStyle('H23')->getFont()->setBold(true);
        $sheet->setCellValue('H24', "- Nominatif");
        $sheet->setCellValue('H25', "- Kuitansi Pembayaran");
        $sheet->setCellValue('H26', "- Daftar Hadir");
        $sheet->setCellValue('H27', "- Surat Tugas");
        $sheet->setCellValue('H28', "- Surat Undangan ");
        $sheet->setCellValue('H29', "- Dokumentasi Kegiatan");
        $sheet->setCellValue('H30', "- NPWP dan Rekening Bank");
        
        $sheet->setCellValue('H39', "Bandung, " . mediumdate_indo($get['tgl']));
        $sheet->setCellValue('H40', "Pemeriksa");
        
        if($by) {
            $sheet->setCellValue('H44', name_capitalize($by['nama']));
            $sheet->setCellValueExplicit('H45', 'NIP. ' . $by['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        }
        
        $verif_5 = null;
        if($get['verifikator_5'] != null) $verif_5 = unserialize($get['verifikator_5']);
        $verif_4 = null;
        if($get['verifikator_4'] != null) $verif_4 = unserialize($get['verifikator_4']);
        $verif_3 = null;
        if($get['verifikator_3'] != null) $verif_3 = unserialize($get['verifikator_3']);
        $verif_2 = null;
        if($get['verifikator_2'] != null) $verif_2 = unserialize($get['verifikator_2']);
        $verif_1 = null;
        if($get['verifikator_1'] != null) $verif_1 = unserialize($get['verifikator_1']);
        
        $ttd = false;
        $imagePath = 'dist/img/logo_kkp.png';
        
        if ($by) {
            // Daftar variabel verifikator
            $verifikators = [$verif_1, $verif_2, $verif_3, $verif_4, $verif_5];
        
            foreach ($verifikators as $verif) {
                if (is_array($verif)) {
                    // Loop dari akhir ke awal
                    for ($i = count($verif) - 1; $i >= 0; $i--) {
                        if (isset($verif[$i]['nip']) && $verif[$i]['nip'] == $by['nip']) {
                            $ttd = true;
                            $imagePath = $verif[$i]['signature'];
                            break 2; // keluar dari loop for dan foreach sekaligus
                        }
                    }
                }
            }
        }
        
        if($ttd) {
            if (str_starts_with($imagePath, '/')) {
                $imagePath = substr($imagePath, 1);
            }
            // Buat objek gambar
            $drawing = new Drawing();
            $drawing->setName('Image');
            $drawing->setDescription('Image');
            $drawing->setPath($imagePath);
            $drawing->setCoordinates('H41'); // Koordinat sel tempat gambar akan ditempatkan
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(0);
            $originalWidth = $drawing->getWidth();  // lebar asli gambar
            $originalHeight = $drawing->getHeight(); // tinggi asli gambar
            
            $newWidth = 123;
            $newHeight = ($originalHeight / $originalWidth) * $newWidth;
            
            $drawing->setWidth($newWidth);
            $drawing->setHeight(round($newHeight));
        
            // Tambahkan gambar ke lembar kerja
            $drawing->setWorksheet($sheet);
        }
        
        $namaFile = 'Lembar Verifikasi (' . mediumdate_indo($get['tgl']) . ') - ' . preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', $get['nama_kegiatan']) . '.xlsx'; // nama file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $namaFile);
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
      } else {
          session()->setFlashdata('error', 'Data ini tidak melewati verifikator/ Lembar verifikasi tidak dapat diproses!');
          return redirect()->to('/sinadi/verifikasiSurat');
      }
  }
  
  public function uklw_pemkes()
  {
    $tanggal = $this->request->getVar("filter_tanggal");
    if (empty($tanggal)) {
        $tanggal = date('Y-m-d') . " - " . date('Y-m-d');
    }
    $tanggalArray = explode(" - ", $tanggal);
    $start = $tanggalArray[0];
    $end = isset($tanggalArray[1]) ? $tanggalArray[1] : $tanggalArray[0];
    $status_pemeriksaan = $this->request->getVar("status_pemeriksaan");
    $kota = $this->request->getVar("kota");
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    if ($start == $end) {
      $tanggal_file = mediumdate_indo(date("Y-m-d", strtotime($start)));
    } else {
      $tanggal_file = mediumdate_indo(date("Y-m-d", strtotime($start))) . " - " . mediumdate_indo(date("Y-m-d", strtotime($end)));
    }
    
    $jenisPemkes = $this->request->getVar("pemeriksaan");
    if(in_array("Pemeriksaan Fisik & Riwayat Penyakit", $jenisPemkes, TRUE)) {
        $this->uklwPasienModel
          ->select("*, 
              uklw_pasien.nama, 
              DATE_FORMAT(uklw_pemkes.created_at, '%Y-%m-%d') as created_pemkes,
              DATE_FORMAT(uklw_catatan_pemeriksaan.created_at, '%Y-%m-%d') as created_catatan,
              created.nama AS created, 
              updated.nama as updated
            ");
        $this->uklwPasienModel->join('uklw_pemkes', 'id_uklw_pasien');
        $this->uklwPasienModel->join('uklw_catatan_pemeriksaan', 'id_uklw_pemkes', 'LEFT');
        $this->uklwPasienModel->join('user AS created', 'created.id_user=uklw_pemkes.created_by');
        $this->uklwPasienModel->join('user AS updated', 'updated.id_user=uklw_pemkes.created_by');
        if ($status_pemeriksaan != '') {
          $this->uklwPasienModel->where('status_pemeriksaan', $status_pemeriksaan);
        }
        if ($kota != '') {
          $this->uklwPasienModel->where('kota', $kota);
        }
        $this->uklwPasienModel->having('created_pemkes >=', $start);
        $this->uklwPasienModel->having('created_pemkes <=', $end);
        $this->uklwPasienModel->orderBy('uklw_pemkes.created_at', 'desc');
        $getData = $this->uklwPasienModel->findAll();
    
        
        $sheet->setTitle('PEMKES');
    
        // Set border for cells
        $borderStyle = [
          'borders' => [
            'allBorders' => [
              'borderStyle' => Border::BORDER_THIN,
              'color' => ['argb' => '000000'],
            ],
          ],
        ];
    
    
        $sheet->setCellValue('A1', 'PELAYANAN TERPADU UPAYA KESEHATAN | DATA PEMERIKSAAN FISIK DAN RIWAYAT PENYAKIT');
        $sheet->setCellValue('A2', name_uppercase($tanggal_file));
    
        $sheet->setCellValue('A4', 'Tanggal');
        $sheet->setCellValue('B4', 'Nomor Identitas');
        $sheet->setCellValue('C4', 'Nama Pasien');
        $sheet->setCellValue('D4', 'Tanggal Lahir');
        $sheet->setCellValue('E4', 'No. HP');
        $sheet->setCellValue('F4', 'Status Pemeriksaan');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->mergeCells('D4:D5');
        $sheet->mergeCells('E4:E5');
        $sheet->mergeCells('F4:F5');
    
        $column_field = ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA'];
        $colom_pemeriksaan_fisik_awal = '';
        $colom_pemeriksaan_fisik_akhir = '';
        $jml_pemeriksaan_fisik = 0;
        $colom_riwayat_penyakit_awal = '';
        $colom_riwayat_penyakit_akhir = '';
        $jml_riwayat_penyakit = 0;
        $colom_wawancara_awal = '';
        $colom_wawancara_akhir = '';
        $jml_wawancara = 0;
        $colom_hygiene_awal = '';
        $colom_hygiene_akhir = '';
        $jml_hygiene = 0;
        $colom_pemeriksaan_fisik_penjamah_awal = '';
        $colom_pemeriksaan_fisik_penjamah_akhir = '';
        $jml_pemeriksaan_fisik_penjamah = 0;
        $i = 0;
    
        // Pembuatan Header
        $array = unserialize($getData[0]['pemeriksaan_fisik']);
        $sheet->setCellValue($column_field[$i] . '4', 'Pemeriksaan Fisik');
        $cekArray = false;
        foreach ($array as $data) {
          if ($cekArray == false) {
            $cekArray = true;
            $colom_pemeriksaan_fisik_awal = $column_field[$i];
            $sheet->setCellValue($column_field[$i++] . '5', 'Kota');
          }
          $jml_pemeriksaan_fisik++;
          $sheet->setCellValue($column_field[$i++] . '5', $data['pemeriksaan_fisik']);
        }
        $sheet->setCellValue($column_field[$i++] . '5', 'User Input');
        if ($cekArray == true) {
          $colom_pemeriksaan_fisik_akhir = $column_field[$i - 1];
          $sheet->mergeCells($colom_pemeriksaan_fisik_awal . '4:' . $colom_pemeriksaan_fisik_akhir . '4');
        }
    
        // Pembuatan Header
        for ($j = 0; $j < count($getData); $j++) {
          if ($getData[$j]['riwayat_penyakit'] != null && $getData[$j]['riwayat_penyakit'] != '') {
            $array = unserialize($getData[$j]['riwayat_penyakit']);
            $sheet->setCellValue($column_field[$i] . '4', 'Riwayat Penyakit');
            $cekArray = false;
            foreach ($array as $data) {
              if ($cekArray == false) {
                $cekArray = true;
                $colom_riwayat_penyakit_awal = $column_field[$i];
              }
              $jml_riwayat_penyakit++;
              $sheet->setCellValue($column_field[$i++] . '5', $data['riwayat_penyakit']);
            }
            $sheet->setCellValue($column_field[$i++] . '5', 'User Input');
            if ($cekArray == true) {
              $colom_riwayat_penyakit_akhir = $column_field[$i - 1];
              $sheet->mergeCells($colom_riwayat_penyakit_awal . '4:' . $colom_riwayat_penyakit_akhir . '4');
            }
            break;
          }
        }
    
        // Pembuatan Header
        for ($j = 0; $j < count($getData); $j++) {
          if ($getData[$j]['wawancara'] != null && $getData[$j]['wawancara'] != '') {
            $array = unserialize($getData[$j]['wawancara']);
            $sheet->setCellValue($column_field[$i] . '4', 'Wawancara');
            $cekArray = false;
            foreach ($array as $data) {
              if ($cekArray == false) {
                $cekArray = true;
                $colom_wawancara_awal = $column_field[$i];
              }
              $jml_wawancara++;
              $sheet->setCellValue($column_field[$i++] . '5', $data['wawancara']);
            }
            $sheet->setCellValue($column_field[$i++] . '5', 'User Input');
            if ($cekArray == true) {
              $colom_wawancara_akhir = $column_field[$i - 1];
              $sheet->mergeCells($colom_wawancara_awal . '4:' . $colom_wawancara_akhir . '4');
            }
            break;
          }
        }
    
        // Pembuatan Header
        for ($j = 0; $j < count($getData); $j++) {
          if ($getData[$j]['hygiene'] != null && $getData[$j]['hygiene'] != '') {
            $array = unserialize($getData[$j]['hygiene']);
            $sheet->setCellValue($column_field[$i] . '4', 'Hygiene');
            $cekArray = false;
            foreach ($array as $data) {
              if ($cekArray == false) {
                $cekArray = true;
                $colom_hygiene_awal = $column_field[$i];
              }
              $jml_hygiene++;
              $sheet->setCellValue($column_field[$i++] . '5', $data['hygiene']);
            }
            $sheet->setCellValue($column_field[$i++] . '5', 'User Input');
            if ($cekArray == true) {
              $colom_hygiene_akhir = $column_field[$i - 1];
              $sheet->mergeCells($colom_hygiene_awal . '4:' . $colom_hygiene_akhir . '4');
            }
            break;
          }
        }
    
        // Pembuatan Header
        for ($j = 0; $j < count($getData); $j++) {
          if ($getData[$j]['pemeriksaan_fisik_penjamah'] != null && $getData[$j]['pemeriksaan_fisik_penjamah'] != '') {
            $array = unserialize($getData[$j]['pemeriksaan_fisik_penjamah']);
            $sheet->setCellValue($column_field[$i] . '4', 'Pemeriksaan Fisik Penjamah');
            $cekArray = false;
            foreach ($array as $data) {
              if ($cekArray == false) {
                $cekArray = true;
                $colom_pemeriksaan_fisik_penjamah_awal = $column_field[$i];
              }
              $jml_pemeriksaan_fisik_penjamah++;
              $sheet->setCellValue($column_field[$i++] . '5', $data['pemeriksaan_fisik_penjamah']);
            }
            $sheet->setCellValue($column_field[$i++] . '5', 'User Input');
            if ($cekArray == true) {
              $colom_pemeriksaan_fisik_penjamah_akhir = $column_field[$i - 1];
              $sheet->mergeCells($colom_pemeriksaan_fisik_penjamah_awal . '4:' . $colom_pemeriksaan_fisik_penjamah_akhir . '4');
            }
            break;
          }
        }
    
        $colomAkhir = "E";
        if ($colom_pemeriksaan_fisik_penjamah_akhir != '') {
          $sheet->mergeCells('A1:' . $colom_pemeriksaan_fisik_penjamah_akhir . '1');
          $sheet->mergeCells('A2:' . $colom_pemeriksaan_fisik_penjamah_akhir . '2');
          $colomAkhir = $colom_pemeriksaan_fisik_penjamah_akhir;
        } else {
          if ($colom_hygiene_akhir != '') {
            $sheet->mergeCells('A1:' . $colom_hygiene_akhir . '1');
            $sheet->mergeCells('A2:' . $colom_hygiene_akhir . '2');
            $colomAkhir = $colom_hygiene_akhir;
          } else {
            if ($colom_wawancara_akhir != '') {
              $sheet->mergeCells('A1:' . $colom_wawancara_akhir . '1');
              $sheet->mergeCells('A2:' . $colom_wawancara_akhir . '2');
              $colomAkhir = $colom_wawancara_akhir;
            } else {
              if ($colom_riwayat_penyakit_akhir != '') {
                $sheet->mergeCells('A1:' . $colom_riwayat_penyakit_akhir . '1');
                $sheet->mergeCells('A2:' . $colom_riwayat_penyakit_akhir . '2');
                $colomAkhir = $colom_riwayat_penyakit_akhir;
              } else {
                if ($colom_pemeriksaan_fisik_akhir != '') {
                  $sheet->mergeCells('A1:' . $colom_pemeriksaan_fisik_akhir . '1');
                  $sheet->mergeCells('A2:' . $colom_pemeriksaan_fisik_akhir . '2');
                  $colomAkhir = $colom_pemeriksaan_fisik_akhir;
                } else {
                  $sheet->mergeCells('A1:E1');
                  $sheet->mergeCells('A2:E2');
                }
              }
            }
          }
        }
    
    
        $sheet->getStyle('A1:' . $colomAkhir . '5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:' . $colomAkhir . '5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
        $sheet->getRowDimension('5')->setRowHeight(92);
        foreach ($column_field as $c) {
          $sheet->getStyle('5')->getAlignment()->setWrapText(true);
          $sheet->getColumnDimension($c)->setWidth(22);
          if ($c == $colomAkhir) {
            break;
          }
        }
    
        $sheet->getColumnDimension('A')->setWidth(13);
        $sheet->getColumnDimension('B')->setWidth(16);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(19);
     
    
        $colom = 6;
        
        foreach ($getData as $d) {
          $sheet->setCellValue('A' . $colom, $d['created_pemkes']);
          $sheet->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
          $sheet->setCellValue('C' . $colom, $d['nama']);
          $sheet->setCellValue('D' . $colom, $d['tgl_lahir']);
          $sheet->setCellValue('E' . $colom, $d['no_hp']);
          $sheet->setCellValue('F' . $colom, $d['status_pemeriksaan']);
    
          if ($jml_pemeriksaan_fisik != 0) {
            $array = unserialize($d['pemeriksaan_fisik']);
            $count = 0;
            if ($array) {
              // d($array);
              if (strlen($colom_pemeriksaan_fisik_awal) == 2) {
                $countChar = substr($colom_pemeriksaan_fisik_awal, 1);
                $awal = substr($colom_pemeriksaan_fisik_awal, 0, 1);
                $inputKota = true;
                for ($i = ord($countChar); $i < ord($countChar) + ($jml_pemeriksaan_fisik + 1); $i++) {
                  if ($inputKota) {
                    $inputKota = false;
                    $sheet->setCellValue($awal . chr($i) . $colom, $d['kota']);
                  } else {
                    $sheet->setCellValue($awal . chr($i) . $colom, $array[$count++]['hasil_pemeriksaan_fisik']);
                  }
                }
                $sheet->setCellValue($awal . chr($i) . $colom, $d['updated']);
              } else {
                $inputKota = true;
                for ($i = ord($colom_pemeriksaan_fisik_awal); $i < ord($colom_pemeriksaan_fisik_awal) + ($jml_pemeriksaan_fisik + 1); $i++) {
                  if ($inputKota) {
                    $inputKota = false;
                    $sheet->setCellValue(chr($i) . $colom, $d['kota']);
                  } else {
                    // $nilai = $array[$count]['hasil_pemeriksaan_fisik'];
                    // d($count++);
                    // d($array[$count++]['hasil_pemeriksaan_fisik']);
                    $sheet->setCellValue(chr($i) . $colom, isset($array[$count]['hasil_pemeriksaan_fisik']) ? $array[$count]['hasil_pemeriksaan_fisik'] : "");
                    $count++;
                  }
                  // d($array[$count++]['pemeriksaan_fisik']);
                }
                $sheet->setCellValue(chr($i) . $colom, $d['updated']);
              }
            }
          }
    
          if ($jml_riwayat_penyakit != 0) {
            $array = unserialize($d['riwayat_penyakit']);
            $count = 0;
            if ($array) {
              if (strlen($colom_riwayat_penyakit_awal) == 2) {
                $countChar = substr($colom_riwayat_penyakit_awal, 1);
                $awal = substr($colom_riwayat_penyakit_awal, 0, 1);
                for ($i = ord($countChar); $i < ord($countChar) + $jml_riwayat_penyakit; $i++) {
                  $sheet->setCellValue($awal . chr($i) . $colom, $array[$count++]['hasil_riwayat_penyakit']);
                }
                $sheet->setCellValue($awal . chr($i) . $colom, $d['updated']);
              } else {
                for ($i = ord($colom_riwayat_penyakit_awal); $i < ord($colom_riwayat_penyakit_awal) + $jml_riwayat_penyakit; $i++) {
                  if (isset($array[$count])) {
                    if (isset($array[$count]['hasil_riwayat_penyakit'])) {
                        $sheet->setCellValue(chr($i) . $colom, $array[$count++]['hasil_riwayat_penyakit']);
                    } 
                  }
                }
                $sheet->setCellValue(chr($i) . $colom, $d['updated']);
              }
            }
          }
    
          if ($jml_wawancara != 0) {
            $array = unserialize($d['wawancara']);
            $count = 0;
            if ($array) {
              if (strlen($colom_wawancara_awal) == 2) {
                $countChar = substr($colom_wawancara_awal, 1);
                $awal = substr($colom_wawancara_awal, 0, 1);
                for ($i = 0; $i < $jml_wawancara; $i++) {
                  $charIndex = ord($countChar) - 65 + $i;
                  $excelColumn = '';
                  while ($charIndex >= 0) {
                    $remainder = $charIndex % 26;
                    $excelColumn = chr(65 + $remainder) . $excelColumn;
                    $charIndex = floor($charIndex / 26) - 1;
                  }
                  $sheet->setCellValue($awal . $excelColumn . $colom, $array[$count++]['hasil_wawancara']);
                }
                $charIndex = ord($countChar) - 65 + $i;
                $excelColumn = '';
                while ($charIndex >= 0) {
                  $remainder = $charIndex % 26;
                  $excelColumn = chr(65 + $remainder) . $excelColumn;
                  $charIndex = floor($charIndex / 26) - 1;
                }
                $sheet->setCellValue($awal . $excelColumn . $colom, $d['updated']);
              } else {
                for ($i = 0; $i < $jml_wawancara; $i++) {
                  $charIndex = ord($colom_wawancara_awal) - 65 + $i;
                  $excelColumn = '';
                  while ($charIndex >= 0) {
                    $remainder = $charIndex % 26;
                    $excelColumn = chr(65 + $remainder) . $excelColumn;
                    $charIndex = floor($charIndex / 26) - 1;
                  }
                  $sheet->setCellValue($excelColumn . $colom, $array[$count++]['hasil_wawancara']);
                }
                $charIndex = ord($colom_wawancara_awal) - 65 + $i;
                $excelColumn = '';
                while ($charIndex >= 0) {
                  $remainder = $charIndex % 26;
                  $excelColumn = chr(65 + $remainder) . $excelColumn;
                  $charIndex = floor($charIndex / 26) - 1;
                }
                // dd($excelColumn . $colom);
                $sheet->setCellValue($excelColumn . $colom, $d['updated']);
              }
            }
          }
    
          if ($jml_hygiene != 0) {
            $array = unserialize($d['hygiene']);
            $count = 0;
            if ($array)
              if (strlen($colom_hygiene_awal) == 2) {
                $countChar = substr($colom_hygiene_awal, 1);
                $awal = substr($colom_hygiene_awal, 0, 1);
                for ($i = ord($countChar); $i < ord($countChar) + $jml_hygiene; $i++) {
                  $sheet->setCellValue($awal . chr($i) . $colom, $array[$count++]['hasil_hygiene']);
                }
                $sheet->setCellValue($awal . chr($i)  . $colom, $d['updated']);
              } else {
                for ($i = ord($colom_hygiene_awal); $i < ord($colom_hygiene_awal) + $jml_hygiene; $i++) {
                  $sheet->setCellValue(chr($i) . $colom, $array[$count++]['hasil_hygiene']);
                }
                $sheet->setCellValue(chr($i) . $colom, $d['updated']);
              }
          }
    
          if ($jml_pemeriksaan_fisik_penjamah != 0) {
            $array = unserialize($d['pemeriksaan_fisik_penjamah']);
            // d($array);
            $count = 0;
            if ($array) {
              if (strlen($colom_pemeriksaan_fisik_penjamah_awal) == 2) {
                $countChar = substr($colom_pemeriksaan_fisik_penjamah_awal, 1);
                $awal = substr($colom_pemeriksaan_fisik_penjamah_awal, 0, 1);
                for ($i = ord($countChar); $i < ord($countChar) + $jml_pemeriksaan_fisik_penjamah; $i++) {
                  $sheet->setCellValue($awal . chr($i) . $colom, $array[$count++]['ket_pemeriksaan_fisik_penjamah']);
                  // d($array[$count++]['ket_pemeriksaan_fisik_penjamah']);
                }
                $sheet->setCellValue($awal . chr($i) . $colom, $d['updated']);
              } else {
                for ($i = ord($colom_pemeriksaan_fisik_penjamah_awal); $i < ord($colom_pemeriksaan_fisik_penjamah_awal) + $jml_pemeriksaan_fisik_penjamah; $i++) {
                  $sheet->setCellValue(chr($i) . $colom, $array[$count++]['ket_pemeriksaan_fisik_penjamah']);
                }
                $sheet->setCellValue(chr($i) . $colom, $d['updated']);
              }
            }
          }
          $colom++;
        }
        
        $sheet->getStyle('A4:' . $colomAkhir . ($colom - 1))->applyFromArray($borderStyle);
    }
    
    // =====================================================================================================
    if(in_array("Skrining TBC", $jenisPemkes, TRUE)) {
        $sheetTBC = $spreadsheet->createSheet();
        $sheetTBC->setTitle('Skrining TBC');
        
        $sheetTBC->setCellValue('A1', 'Tanggal');
        $sheetTBC->setCellValue('B1', 'Nomor Identitas');
        $sheetTBC->setCellValue('C1', 'Nama Pasien');
        $sheetTBC->setCellValue('D1', 'Skrining TBC');
        $sheetTBC->setCellValue('E1', 'User Input');
        
        if ($status_pemeriksaan != '') {
          $this->uklwPasienModel->where('status_pemeriksaan', $status_pemeriksaan);
        }
        $getData = $this->uklwSkriningTBCModel
            ->select('user.nama as user, uklw_skrining_tbc.skrining_tbc_gejala, uklw_pasien.nama, uklw_pasien.nomor_identitas, uklw_skrining_tbc.created_at as tanggal') 
            ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien = uklw_skrining_tbc.id_uklw_pasien')
            ->join('user', 'uklw_skrining_tbc.updated_by=user.id_user')
            ->where('uklw_skrining_tbc.created_at >=', $start . ' 00:00:00')
            ->where('uklw_skrining_tbc.created_at <=', $end . ' 23:59:59') 
            ->get()
            ->getResultArray();
        
        foreach ($getData as &$row) {
            if (isset($row['skrining_tbc_gejala'])) {
                $unserializedData = @unserialize($row['skrining_tbc_gejala']);
                if ($unserializedData !== false) {
                    $row['skrining_tbc_gejala'] = $unserializedData;
                } else {
                    $row['skrining_tbc_gejala'] = [];
                }
            }
        }
        
        $colom = 2;
        
        foreach ($getData as $d) {
            $sheetTBC->setCellValue('A' . $colom, $d['tanggal']);
            $sheetTBC->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetTBC->setCellValue('C' . $colom, $d['nama']);
            
            $skrining_tbc_gejala = "";
            foreach($d['skrining_tbc_gejala'] as $as) {
                $skrining_tbc_gejala .= $as["skrining_tbc_gejala"] . ": ". $as["hasil_skrining_tbc_gejala"] . "\n";
            }
            
            $sheetTBC->setCellValue('D' . $colom, $skrining_tbc_gejala);
            $sheetTBC->setCellValue('E' . $colom, $d['user']);
            
            $colom++;
        }
        
        $lastRow = $colom - 1;
        $range = "A1:E" . $lastRow;
        
        $sheetTBC->getStyle($range)->getAlignment()->setWrapText(true);
        $sheetTBC->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheetTBC->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        foreach (range('A', 'E') as $column) {
            $sheetTBC->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    // =====================================================================================================
    if(in_array("Skrining HIV", $jenisPemkes, TRUE)) {
        $sheetHIV = $spreadsheet->createSheet();
        $sheetHIV->setTitle('Skrining HIV');
        
        $sheetHIV->setCellValue('A1', 'Tanggal');
        $sheetHIV->setCellValue('B1', 'Nomor Identitas');
        $sheetHIV->setCellValue('C1', 'Nama Pasien');
        $sheetHIV->setCellValue('D1', 'Skrining HIV');
        $sheetHIV->setCellValue('E1', 'User Input');
        
        $getData = $this->uklwSkriningHIVModel
            ->select('user.nama as user, uklw_skrining_hiv.skrining_hiv, uklw_pasien.nama, uklw_pasien.nomor_identitas, uklw_skrining_hiv.created_at as tanggal') 
            ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien = uklw_skrining_hiv.id_uklw_pasien')
            ->join('user', 'uklw_skrining_hiv.updated_by=user.id_user')
            ->where('uklw_skrining_hiv.created_at >=', $start . ' 00:00:00')
            ->where('uklw_skrining_hiv.created_at <=', $end . ' 23:59:59')
            ->get()
            ->getResultArray();
        
        foreach ($getData as &$row) {
            if (isset($row['skrining_hiv'])) {
                $unserializedData = @unserialize($row['skrining_hiv']);
                if ($unserializedData !== false) {
                    $row['skrining_hiv'] = $unserializedData;
                } else {
                    $row['skrining_hiv'] = [];
                }
            }
        }
        
        $colom = 2;
        foreach ($getData as $d) {
            $sheetHIV->setCellValue('A' . $colom, $d['tanggal']);
            $sheetHIV->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetHIV->setCellValue('C' . $colom, $d['nama']);
            
            $skrining_hiv = "";
            foreach($d['skrining_hiv'] as $as) {
                $skrining_hiv .= $as["skrining_hiv"] . ": ". $as["hasil_skrining_hiv"] . "\n";
            }
            
            $sheetHIV->setCellValue('D' . $colom, $skrining_hiv);
            $sheetHIV->setCellValue('E' . $colom, $d['user']);
            
            $colom++;
        }
        
        $lastRow = $colom - 1;
        $range = "A1:E" . $lastRow;
        
        $sheetHIV->getStyle($range)->getAlignment()->setWrapText(true);
        $sheetHIV->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheetHIV->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        foreach (range('A', 'E') as $column) {
            $sheetHIV->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    // =====================================================================================================
    if(in_array("Skrining PTM", $jenisPemkes, TRUE)) {
        $sheetPTM = $spreadsheet->createSheet();
        $sheetPTM->setTitle('Skrining Penyakit Tidak Menular');
        
        $sheetPTM->setCellValue('A1', 'Tanggal');
        $sheetPTM->setCellValue('B1', 'Nomor Identitas');
        $sheetPTM->setCellValue('C1', 'Nama Pasien');
        $sheetPTM->setCellValue('D1', 'Skrining Penyakit Tidak Menular');
        $sheetPTM->setCellValue('E1', 'User Input');
        
        $getData = $this->uklwFaktorRisikoPTMModel
            ->select('user.nama as user, uklw_faktor_risiko_ptm.skrining_penyakit_tidak_menular, uklw_pasien.nama, uklw_pasien.nomor_identitas, uklw_faktor_risiko_ptm.created_at as tanggal') 
            ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien = uklw_faktor_risiko_ptm.id_uklw_pasien')
            ->join('user', 'uklw_faktor_risiko_ptm.updated_by=user.id_user')
            ->where('uklw_faktor_risiko_ptm.created_at >=', $start . ' 00:00:00')
            ->where('uklw_faktor_risiko_ptm.created_at <=', $end . ' 23:59:59') 
            ->get()
            ->getResultArray();
        
        foreach ($getData as &$row) {
            if (isset($row['skrining_penyakit_tidak_menular'])) {
                $unserializedData = @unserialize($row['skrining_penyakit_tidak_menular']);
                if ($unserializedData !== false) {
                    $row['skrining_penyakit_tidak_menular'] = $unserializedData;
                } else {
                    $row['skrining_penyakit_tidak_menular'] = [];
                }
            }
        }
        
        $colom = 2;
        foreach ($getData as $d) {
            $sheetPTM->setCellValue('A' . $colom, $d['tanggal']);
            $sheetPTM->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetPTM->setCellValue('C' . $colom, $d['nama']);
            
            $skrining_penyakit_tidak_menular = "";
            foreach($d['skrining_penyakit_tidak_menular'] as $as) {
                $skrining_penyakit_tidak_menular .= $as["skrining_penyakit_tidak_menular"] . ": ". $as["hasil_skrining_penyakit_tidak_menular"] . "\n";
            }
            
            $sheetPTM->setCellValue('D' . $colom, $skrining_penyakit_tidak_menular);
            $sheetPTM->setCellValue('E' . $colom, $d['user']);
            
            $colom++;
        }
        
        $lastRow = $colom - 1;
        $range = "A1:E" . $lastRow;
        
        $sheetPTM->getStyle($range)->getAlignment()->setWrapText(true);
        $sheetPTM->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheetPTM->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        foreach (range('A', 'E') as $column) {
            $sheetPTM->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    
    // =====================================================================================================
    if(in_array("Laboratorium", $jenisPemkes, TRUE)) {
        $sheetLaboratorium = $spreadsheet->createSheet();
        $sheetLaboratorium->setTitle('Laboratorium');
        
        $sheetLaboratorium->setCellValue('A1', 'Tanggal');
        $sheetLaboratorium->setCellValue('B1', 'Nomor Identitas');
        $sheetLaboratorium->setCellValue('C1', 'Nama Pasien');
        $sheetLaboratorium->setCellValue('D1', 'Kimia Klinik');
        $sheetLaboratorium->setCellValue('E1', 'Nilai Rujukan Kimia Klinik');
        $sheetLaboratorium->setCellValue('F1', 'Hematologi');
        $sheetLaboratorium->setCellValue('G1', 'Nilai Rujukan Hematologi');
        $sheetLaboratorium->setCellValue('H1', 'Kualitatif');
        $sheetLaboratorium->setCellValue('I1', 'Nilai Rujukan Kualitatif');
        $sheetLaboratorium->setCellValue('J1', 'User Input');
        
        $getData = $this->uklwLaboratoriumModel
            ->select('user.nama as user, uklw_laboratorium.laboratorium_kimia_klinik, uklw_laboratorium.laboratorium_hematologi, uklw_laboratorium.laboratorium_kualitatif, uklw_pasien.nama, uklw_pasien.nomor_identitas, uklw_laboratorium.created_at as tanggal') 
            ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien = uklw_laboratorium.id_uklw_pasien')
            ->join('user', 'uklw_laboratorium.updated_by=user.id_user')
            ->where('uklw_laboratorium.created_at >=', $start . ' 00:00:00')
            ->where('uklw_laboratorium.created_at <=', $end . ' 23:59:59') 
            ->get()
            ->getResultArray();
        
        foreach ($getData as &$row) {
            if (isset($row['laboratorium_kimia_klinik'])) {
                $unserializedData = @unserialize($row['laboratorium_kimia_klinik']);
                if ($unserializedData !== false) {
                    $row['laboratorium_kimia_klinik'] = $unserializedData;
                } else {
                    $row['laboratorium_kimia_klinik'] = [];
                }
            }
            if (isset($row['laboratorium_hematologi'])) {
                $unserializedData = @unserialize($row['laboratorium_hematologi']);
                if ($unserializedData !== false) {
                    $row['laboratorium_hematologi'] = $unserializedData;
                } else {
                    $row['laboratorium_hematologi'] = [];
                }
            }
            if (isset($row['laboratorium_kualitatif'])) {
                $unserializedData = @unserialize($row['laboratorium_kualitatif']);
                if ($unserializedData !== false) {
                    $row['laboratorium_kualitatif'] = $unserializedData;
                } else {
                    $row['laboratorium_kualitatif'] = [];
                }
            }
        }
        
        $colom = 2;
        foreach ($getData as $d) {
            $sheetLaboratorium->setCellValue('A' . $colom, $d['tanggal']);
            $sheetLaboratorium->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetLaboratorium->setCellValue('C' . $colom, $d['nama']);
            
            
            $laboratorium_kimia_klinik = "";
            $nilai_rujukan = "";
            foreach($d['laboratorium_kimia_klinik'] as $as) {
                $laboratorium_kimia_klinik .= $as["laboratorium_kimia_klinik"] . ": ". $as["hasil_laboratorium_kimia_klinik"] . (isset($as["satuan_laboratorium_kimia_klinik"]) ? " " . $as["satuan_laboratorium_kimia_klinik"] : "") . "\n";
                $nilai_rujukan .= $as["laboratorium_kimia_klinik"] . ": ". $as["nilai_rujukan_laboratorium_kimia_klinik"] . "\n";
            }
            
            $sheetLaboratorium->setCellValue('D' . $colom, $laboratorium_kimia_klinik);
            $sheetLaboratorium->setCellValue('E' . $colom, $nilai_rujukan);
    
            
            $laboratorium_hematologi = "";
            $nilai_rujukan = "";
            foreach($d['laboratorium_hematologi'] as $as) {
                $laboratorium_hematologi .= $as["laboratorium_hematologi"] . ": ". $as["hasil_laboratorium_hematologi"] . (isset($as["satuan_laboratorium_hematologi"]) ? " " . $as["satuan_laboratorium_hematologi"] : "") . "\n";
                $nilai_rujukan .= $as["laboratorium_hematologi"] . ": ". $as["nilai_rujukan_laboratorium_hematologi"] . "\n";
            }
            
            $sheetLaboratorium->setCellValue('F' . $colom, $laboratorium_hematologi);
            $sheetLaboratorium->setCellValue('G' . $colom, $nilai_rujukan);
    
            
            
            $laboratorium_kualitatif = "";
            $nilai_rujukan = "";
            foreach($d['laboratorium_kualitatif'] as $as) {
                $laboratorium_kualitatif .= $as["laboratorium_kualitatif"] . ": ". $as["hasil_laboratorium_kualitatif"] . "\n";
                $nilai_rujukan .= $as["laboratorium_kualitatif"] . ": ". $as["nilai_rujukan_laboratorium_kualitatif"] . "\n";
            }
            
            $sheetLaboratorium->setCellValue('H' . $colom, $laboratorium_kualitatif);
            $sheetLaboratorium->setCellValue('I' . $colom, $nilai_rujukan);
            
            $sheetLaboratorium->setCellValue('J' . $colom, $d['user']);
            
            $colom++;
        }
        
        $lastRow = $colom - 1;
        $range = "A1:J" . $lastRow;
        
        $sheetLaboratorium->getStyle($range)->getAlignment()->setWrapText(true);
        $sheetLaboratorium->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheetLaboratorium->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        foreach (range('A', 'J') as $column) {
            $sheetLaboratorium->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    if(in_array("Pemeriksaan Dokter", $jenisPemkes, TRUE)) {
        $sheetPemeriksaanDokter = $spreadsheet->createSheet();
        $sheetPemeriksaanDokter->setTitle('Catatan Dr.');
        
        $sheetPemeriksaanDokter->setCellValue('A1', 'Tanggal');
        $sheetPemeriksaanDokter->setCellValue('B1', 'Nomor Identitas');
        $sheetPemeriksaanDokter->setCellValue('C1', 'Nama Pasien');
        $sheetPemeriksaanDokter->setCellValue('D1', 'Subjective');
        $sheetPemeriksaanDokter->setCellValue('E1', 'Diagnosa Medis');
        $sheetPemeriksaanDokter->setCellValue('F1', 'Kesimpulan');
        $sheetPemeriksaanDokter->setCellValue('G1', 'Tindakan');
        $sheetPemeriksaanDokter->setCellValue('H1', 'Obat');
        $sheetPemeriksaanDokter->setCellValue('I1', 'Rujuk');
        $sheetPemeriksaanDokter->setCellValue('J1', 'User Input');
        
        $getData = $this->uklwCatatanPemeriksaanModel
            ->select('user.nama as user, uklw_catatan_pemeriksaan.*, uklw_pasien.nama, uklw_pasien.nomor_identitas, uklw_catatan_pemeriksaan.created_at as tanggal') 
            ->join('uklw_pemkes', 'uklw_catatan_pemeriksaan.id_uklw_pemkes = uklw_pemkes.id_uklw_pemkes')
            ->join('uklw_pasien', 'uklw_pasien.id_uklw_pasien = uklw_pemkes.id_uklw_pasien')
            ->join('user', 'uklw_catatan_pemeriksaan.updated_by=user.id_user')
            ->where('uklw_catatan_pemeriksaan.created_at >=', $start . ' 00:00:00')
            ->where('uklw_catatan_pemeriksaan.created_at <=', $end . ' 23:59:59') 
            ->get()
            ->getResultArray();
        
        foreach ($getData as &$row) {
            if (isset($row['assesment'])) {
                $unserializedData = @unserialize($row['assesment']);
                if ($unserializedData !== false) {
                    $row['assesment'] = $unserializedData;
                } else {
                    $row['assesment'] = [];
                }
            }
            if (isset($row['planning'])) {
                $unserializedData = @unserialize($row['planning']);
                if ($unserializedData !== false) {
                    $row['planning'] = $unserializedData;
                } else {
                    $row['planning'] = [];
                }
            }
        }
        
        $colom = 2;
        foreach ($getData as $d) {
            $sheetPemeriksaanDokter->setCellValue('A' . $colom, $d['tanggal']);
            $sheetPemeriksaanDokter->setCellValueExplicit('B' . $colom, $d['nomor_identitas'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheetPemeriksaanDokter->setCellValue('C' . $colom, $d['nama']);
            
            $sheetPemeriksaanDokter->setCellValue('D' . $colom, $d['subjective']);
            $sheetPemeriksaanDokter->setCellValue('E' . $colom, $d['assesment']['assesment_diagnosa_medis']);
            $sheetPemeriksaanDokter->setCellValue('F' . $colom, $d['assesment']['assesment_kesimpulan']);
            
            $sheetPemeriksaanDokter->setCellValue('G' . $colom, $d['planning']['planning_tindakan']);
            
            $setObat = "";
            foreach($d['planning']['planning_obat'] as $obat) {
                $setObat .= "Obat   : " . $obat['planning_obat'] . "\n";
                $setObat .= "Jumlah : " . $obat['planning_obat_jumlah'] . "\n";
                $setObat .= "Dosis  : " . $obat['planning_obat_sehari'] . " sehari\n";
                $setObat .= "Waktu  : " . $obat['planning_obat_waktu'] . "\n";
                $setObat .= "Ket    : " . $obat['planning_obat_keterangan'] . "\n";
            }
            
            $sheetPemeriksaanDokter->setCellValue('H' . $colom, $setObat);
            $sheetPemeriksaanDokter->setCellValue('I' . $colom, $d['planning']['planning_tempat_rujuk']);
            
            $sheetPemeriksaanDokter->setCellValue('J' . $colom, $d['user']);
            
            $colom++;
        }
        
        $lastRow = $colom - 1;
        $range = "A1:J" . $lastRow;
        
        $sheetPemeriksaanDokter->getStyle($range)->getAlignment()->setWrapText(true);
        $sheetPemeriksaanDokter->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheetPemeriksaanDokter->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        
        foreach (range('A', 'J') as $column) {
            $sheetPemeriksaanDokter->getColumnDimension($column)->setAutoSize(true);
        }
    }
    
    
    // =====================================================================================================

    $namaFile = 'Pemkes (' . $tanggal_file . ') - ('. implode(" - ", $jenisPemkes) .').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function rekamAbsenDL()
  {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $tanggalInput = $this->request->getVar("tanggal");
    $filterLokasi = $this->request->getVar("filter_lokasi");
    if (empty($tanggalInput)) {
        $tanggalInput = date('Y-m-d') . " - " . date('Y-m-d');
    }
    $tanggal = explode(" - ", $tanggalInput);
    $tglAwal = $tanggal[0];
    $tglAkhir = isset($tanggal[1]) ? $tanggal[1] : $tanggal[0];

    $builder = $this->absenModel
      ->select('id_absen, absen.id_data_pegawai, status_masuk, status_pulang, alamat_masuk, alamat_pulang, jam_masuk, jam_pulang, ttd_masuk, ttd_pulang, nip, nama')
      ->join('data_pegawai', 'data_pegawai.id_data_pegawai = absen.id_data_pegawai')
      ->join('golongan', 'data_pegawai.id_golongan = golongan.id_golongan')
      ->where('DATE(jam_masuk) >=', $tglAwal)
      ->where('DATE(jam_masuk) <=', $tglAkhir)
      ->groupStart()
        ->where('status_masuk', 'DL')
        ->orWhere('status_pulang', 'DL')
      ->groupEnd();

    if (!empty($filterLokasi)) {
        $keywords = array_filter(array_map('trim', explode(',', $filterLokasi)));
        if (!empty($keywords)) {
            $builder->groupStart();
            $first = true;
            foreach ($keywords as $kw) {
                if ($first) {
                    $builder->groupStart();
                    $first = false;
                } else {
                    $builder->orGroupStart();
                }
                $builder->like('alamat_masuk', $kw)
                        ->orLike('alamat_pulang', $kw)
                        ->groupEnd();
            }
            $builder->groupEnd();
        }
    }

    $getAbsen = $builder->orderBy('DATE(jam_masuk), golongan.urutan', 'desc')->findAll();

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NIP');
    $sheet->setCellValue('C1', 'Nama');
    $sheet->setCellValue('D1', 'Status Masuk');
    $sheet->setCellValue('E1', 'Alamat Masuk');
    $sheet->setCellValue('F1', 'Jam Masuk');
    $sheet->setCellValue('G1', 'TTD Masuk');
    $sheet->setCellValue('H1', 'Status Pulang');
    $sheet->setCellValue('I1', 'Alamat Pulang');
    $sheet->setCellValue('J1', 'Jam Pulang');
    $sheet->setCellValue('K1', 'TTD Pulang');
    $sheet->setCellValue('L1', 'Jam Kerja');

    foreach (range('A', 'L') as $col) {
      $sheet->getStyle($col . '1')->getFont()->setBold(true);
      $sheet->getStyle($col . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    $sheet->getColumnDimension('A')->setWidth(5);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(35);
    $sheet->getColumnDimension('D')->setWidth(15);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(35);
    $sheet->getColumnDimension('H')->setWidth(15);
    $sheet->getColumnDimension('I')->setWidth(50);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(35);
    $sheet->getColumnDimension('L')->setWidth(15);

    helper('tanggal');

    foreach ($getAbsen as $key => $data) {
      $row = $key + 2;
      $sheet->setCellValue('A' . $row, $key + 1);
      $sheet->getCell('B' . $row)->setValueExplicit($data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->setCellValue('C' . $row, $data['nama']);
      $sheet->setCellValue('D' . $row, $data['status_masuk']);
      $sheet->setCellValue('E' . $row, $data['alamat_masuk']);
      $sheet->setCellValue('F' . $row, $data['jam_masuk']);
      
      $sheet->setCellValue('H' . $row, $data['status_pulang']);
      $sheet->setCellValue('I' . $row, $data['alamat_pulang']);
      $sheet->setCellValue('J' . $row, $data['jam_pulang']);
      
      $jamKerja = selisi_waktu($data['jam_masuk'], $data['jam_pulang']);
      $sheet->setCellValue('L' . $row, strip_tags($jamKerja));
      
      $sheet->getRowDimension($row)->setRowHeight(115);
      $sheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
      $sheet->getStyle('A'.$row.':L'.$row)->getAlignment()->setWrapText(true);

      // Render TTD Masuk
      if (!empty($data['ttd_masuk'])) {
          $base64Ttd = $data['ttd_masuk'];
          if (strpos($base64Ttd, 'base64,') !== false) {
              $base64Ttd = substr($base64Ttd, strpos($base64Ttd, ',') + 1);
          }
          $imgDataTtd = base64_decode($base64Ttd);
          if ($imgDataTtd) {
              $tempTtd = tempnam(sys_get_temp_dir(), 'ttd_dl_masuk_') . '.png';
              file_put_contents($tempTtd, $imgDataTtd);
              
              $drawingTtd = new Drawing();
              $drawingTtd->setName('TTD Masuk');
              $drawingTtd->setDescription('TTD Masuk');
              $drawingTtd->setPath($tempTtd);
              $drawingTtd->setCoordinates('G' . $row);
              $drawingTtd->setHeight(80);
              $drawingTtd->setOffsetX(15);
              $drawingTtd->setOffsetY(15);
              $drawingTtd->setWorksheet($sheet);
          }
      }
      
      // Render TTD Pulang
      if (!empty($data['ttd_pulang'])) {
          $base64TtdPulang = $data['ttd_pulang'];
          if (strpos($base64TtdPulang, 'base64,') !== false) {
              $base64TtdPulang = substr($base64TtdPulang, strpos($base64TtdPulang, ',') + 1);
          }
          $imgDataTtdPulang = base64_decode($base64TtdPulang);
          if ($imgDataTtdPulang) {
              $tempTtdPulang = tempnam(sys_get_temp_dir(), 'ttd_dl_pulang_') . '.png';
              file_put_contents($tempTtdPulang, $imgDataTtdPulang);
              
              $drawingTtdPulang = new Drawing();
              $drawingTtdPulang->setName('TTD Pulang');
              $drawingTtdPulang->setDescription('TTD Pulang');
              $drawingTtdPulang->setPath($tempTtdPulang);
              $drawingTtdPulang->setCoordinates('K' . $row);
              $drawingTtdPulang->setHeight(80);
              $drawingTtdPulang->setOffsetX(15);
              $drawingTtdPulang->setOffsetY(15);
              $drawingTtdPulang->setWorksheet($sheet);
          }
      }
    }

    $lastRow = count($getAbsen) + 1;
    $sheet->getStyle('A1:L' . $lastRow)->applyFromArray($borderStyle);

    $namaFile = 'Rekam_Absen_Dinas_Luar_' . $tglAwal . '_sd_' . $tglAkhir . '.xlsx';

    $writer = new Xlsx($spreadsheet);

    ob_end_clean(); // Menghapus buffer sebelumnya untuk mencegah korup
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit();
  }

  public function rekamAbsen()
  {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $tanggalInput = $this->request->getVar("tanggal");
    if (empty($tanggalInput)) {
        $tanggalInput = date('Y-m-d') . " - " . date('Y-m-d');
    }
    $tanggal = explode(" - ", $tanggalInput);
    $tglAwal = $tanggal[0];
    $tglAkhir = isset($tanggal[1]) ? $tanggal[1] : $tanggal[0];

    $getAbsen = $this->absenModel
      ->select('id_absen, absen.id_data_pegawai, status_masuk, status_pulang, alamat_masuk, latitude_masuk, longitude_masuk, jam_masuk, alamat_pulang, latitude_pulang, longitude_pulang, jam_pulang, 
      unit_kerja, nip, nama, absen.keterangan')
      ->join('data_pegawai', 'id_data_pegawai')
      ->join('golongan', 'id_golongan')
      ->join('unit_kerja', 'id_unit_kerja')
      ->where('DATE(jam_masuk) >=', $tglAwal)
      ->where('DATE(jam_masuk) <=', $tglAkhir)
      ->orderBy('DATE(jam_masuk), golongan.urutan', 'desc')
      ->findAll();

    // Ambil semua data alamat dari model
    $alamat_zona = $this->absenZonaModel
      ->select("alamat")
      ->where('status', 'Terkonfirmasi')
      ->findAll();

    // Ubah format $alamat_zona menjadi array asosiatif
    $getAlamat = [];
    foreach ($alamat_zona as $row) {
      $getAlamat[] = $row['alamat'];
    }

    // Set border for cells
    $borderStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['argb' => '000000'],
        ],
      ],
    ];

    $sheet->setCellValue('A1', 'Tanggal Kehadiran');
    $sheet->setCellValue('B1', 'Wilayah Kerja');
    $sheet->setCellValue('C1', 'NIP/NOPEG.');
    $sheet->setCellValue('D1', 'Nama Pegawai');
    $sheet->setCellValue('E1', 'Jam Masuk');
    $sheet->setCellValue('F1', 'Jam Pulang');
    $sheet->setCellValue('G1', 'Status Masuk');
    $sheet->setCellValue('H1', 'Status Pulang');
    $sheet->setCellValue('I1', 'Jam Kerja');
    $sheet->setCellValue('J1', 'Lokasi Masuk');
    $sheet->setCellValue('K1', 'Lokasi Pulang');
    $sheet->setCellValue('L1', 'Status Absensi Masuk');
    $sheet->setCellValue('M1', 'Status Absensi Pulang');
    $kolom = 0;
    foreach ($getAbsen as $key => $data) {
      $sheet->setCellValue('A' . ($key + 2), date('Y-m-d', strtotime($data['jam_masuk'])));
      $sheet->setCellValue('B' . ($key + 2), $data['unit_kerja']);
      $sheet->getCell('C' . ($key + 2))->setValueExplicit($data['nip'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
      $sheet->setCellValue('D' . ($key + 2), $data['nama']);
      $setJamMasuk = date('H:i:s', strtotime($data['jam_masuk']));
      if($data['keterangan'] != "") {
          $setJamMasuk = $data['keterangan'];
      }
      $setJamPulang = $data['jam_pulang'] != null ? date('H:i:s', strtotime($data['jam_pulang'])) : '';
      if($data['keterangan'] != "") {
          $setJamPulang = $data['keterangan'];
      }
      $sheet->setCellValue('E' . ($key + 2), $setJamMasuk);
      $sheet->setCellValue('F' . ($key + 2), $setJamPulang);
      // Set format cells E and F as Time
      if($data['keterangan'] == "") {
          $sheet->getStyle('E' . ($key + 2))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_TIME4);
          $sheet->getStyle('F' . ($key + 2))->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_TIME4);
      }

      if ($data['jam_pulang'] == null) {
        $sheet->getStyle('F' . ($key + 2))->applyFromArray([
          'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => [
              'rgb' => 'FF0000',
            ],
          ],
        ]);
      }

      if($data['keterangan'] == "") {
          $sheet->setCellValue(
            'G' . ($key + 2),
            ($data['jam_masuk'] != null && $data['jam_masuk'] != "") ? ((int) date('H', strtotime($data['jam_masuk'])) < 8 || ((int) date('H', strtotime($data['jam_masuk'])) == 8 && (int) date('i', strtotime($data['jam_masuk'])) == 00)) ? 'Normal' : 'Terlambat' : 'Terlambat'
          );
          if ($data['jam_masuk'] != null && $data['jam_masuk'] != "") {
            if ((int) date('H', strtotime($data['jam_masuk'])) < 8 || ((int) date('H', strtotime($data['jam_masuk'])) == 8 && (int) date('i', strtotime($data['jam_masuk'])) == 0)) {
            } else {
              $sheet->getStyle('G' . ($key + 2))->getFont()->getColor()->setARGB('FF000000');
            }
          } else {
            $sheet->getStyle('G' . ($key + 2))->getFont()->getColor()->setARGB('FF000000');
          }
          
          $sheet->setCellValue(
            'H' . ($key + 2),
            ($data['jam_pulang'] != null && $data['jam_pulang'] != "")
              ? ((date('N', strtotime($data['jam_pulang'])) == 5)
                ? (((int) date('H', strtotime($data['jam_pulang'])) > 16 || ((int) date('H', strtotime($data['jam_pulang'])) == 16 && (int) date('i', strtotime($data['jam_pulang'])) >= 30))
                  ? 'Normal'
                  : 'Lebih Awal')
                : (((int) date('H', strtotime($data['jam_pulang'])) >= 16) ? 'Normal' : 'Lebih Awal'))
              : 'Lebih Awal'
          );

          if ($data['jam_pulang'] != null && $data['jam_pulang'] != "") {
            if (date('N', strtotime($data['jam_pulang'])) == 5) {
              // Hari Jumat: cek jam pulang setelah pukul 16:30
              if ((int) date('H', strtotime($data['jam_pulang'])) > 16 || ((int) date('H', strtotime($data['jam_pulang'])) == 16 && (int) date('i', strtotime($data['jam_pulang'])) >= 30)) {
                // Tidak perlu melakukan apa pun
              } else {
                // Atur warna font menjadi hitam jika tidak sesuai kondisi
                $sheet->getStyle('H' . ($key + 2))->getFont()->getColor()->setARGB('FF000000');
              }
            } else {
              // Bukan hari Jumat: cek jam pulang setelah pukul 16:00
              if ((int) date('H', strtotime($data['jam_pulang'])) >= 16) {
                // Tidak perlu melakukan apa pun
              } else {
                // Atur warna font menjadi hitam jika tidak sesuai kondisi
                $sheet->getStyle('H' . ($key + 2))->getFont()->getColor()->setARGB('FF000000');
              }
            }
          } else {
            // $data['jam_pulang'] adalah null atau kosong: atur warna font menjadi hitam
            $sheet->getStyle('H' . ($key + 2))->getFont()->getColor()->setARGB('FF000000');
          }

          // Set the formula to calculate the difference between jam_pulang and jam_masuk
          $sheet->setCellValue('I' . ($key + 2), '=F' . ($key + 2) . ' - E' . ($key + 2));
          // Set format cell I as Time
          $sheet->getStyle('I' . ($key + 2))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_TIME4);
    
          $sheet->setCellValue('J' . ($key + 2), 'Tahap Pengembangan');
          $sheet->setCellValue('K' . ($key + 2), 'Tahap Pengembangan');
          $sheet->setCellValue('L' . ($key + 2), $data['status_masuk']);
          $sheet->setCellValue('M' . ($key + 2), $data['status_pulang']);
        //   --ValidationLocation--
        //   $sheet->setCellValue('J' . ($key + 2), in_array($data['alamat_masuk'], $getAlamat) ? 'Sesuai' : 'Tidak Sesuai');
        //   $sheet->setCellValue('K' . ($key + 2), in_array($data['alamat_pulang'], $getAlamat) ? 'Sesuai' : 'Tidak Sesuai');
        //   if (!in_array($data['alamat_masuk'], $getAlamat)) {
        //     $sheet->getStyle('J' . ($key + 2))->getFont()->getColor()->setARGB('FF0000'); // Merah jika tidak sesuai
        //   }
        //   if (!in_array($data['alamat_pulang'], $getAlamat)) {
        //     $sheet->getStyle('K' . ($key + 2))->getFont()->getColor()->setARGB('FF0000'); // Merah jika tidak sesuai
        //   }
        // --EndValidationLocation--
      } else {
          $sheet->setCellValue('G' . ($key + 2), $data['keterangan']);
          $sheet->setCellValue('H' . ($key + 2), $data['keterangan']);
          $sheet->getStyle('E' . ($key + 2) . ":K" . ($key + 2))->applyFromArray([
            'fill' => [
              'fillType' => Fill::FILL_SOLID,
              'startColor' => [
                'rgb' => 'FFFF00',
              ],
            ],
          ]);
          
      }
      $kolom = $key + 2;
    }

    // Bordered
    $sheet->getStyle('A1' . ':M' . ($kolom))->applyFromArray($borderStyle);
    // Menyesuaikan lebar kolom
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);
    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getColumnDimension('J')->setAutoSize(true);
    $sheet->getColumnDimension('K')->setAutoSize(true);
    $sheet->getColumnDimension('L')->setAutoSize(true);
    $sheet->getColumnDimension('M')->setAutoSize(true);

    $namaFile = 'Rekam Absen (' . ($tanggal[0] == $tanggal[1] ? $tanggal[1] : $tanggal[0] . ' - ' . $tanggal[1]) . ').xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
  
  public function export_lhkpn_spt()
  {
    $by = $this->dataPegawaiModel
      ->select('id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    $periode = $this->request->getVar("tanggal_dokumen");
    $startDate = substr($periode, 0, 10);
    $endDate = substr($periode, 13, 10);

    // Query data berdasarkan role dan filter tanggal jika ada
    if (session()->get('role') == "Admin") {
      $dataQuery = $this->lhkpnSptModel
        ->select('lhkpn_spt.*, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'lhkpn_spt.id_data_pegawai=data_pegawai.id_data_pegawai');

      if ($startDate && $endDate) {
        $dataQuery->where('lhkpn_spt.created_at >=', $startDate . ' 00:00:00')
          ->where('lhkpn_spt.created_at <=', $endDate . ' 23:59:59');
      }

      $data = $dataQuery->findAll();
    } else {
      $dataQuery = $this->lhkpnSptModel
        ->select('lhkpn_spt.*, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'lhkpn_spt.id_data_pegawai=data_pegawai.id_data_pegawai')
        ->where('id_data_pegawai', $by['id_data_pegawai']);

      if ($startDate && $endDate) {
        $dataQuery->where('lhkpn_spt.created_at >=', $startDate . ' 00:00:00')
          ->where('lhkpn_spt.created_at <=', $endDate . ' 23:59:59');
      }

      $data = $dataQuery->findAll();
    }

    // Proses Export ke Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Waktu Upload');
    $sheet->setCellValue('C1', 'Tahun');
    $sheet->setCellValue('D1', 'Pemilik');
    $sheet->setCellValue('E1', 'Dokumen');

    $column = 2;
    $files = []; // Menyimpan daftar file yang akan dimasukkan ke ZIP
    foreach ($data as $key => $lhkpnspt) {
      $sheet->setCellValue('A' . $column, $key + 1);
      $sheet->setCellValue('B' . $column, $lhkpnspt['created_at']);
      $sheet->setCellValue('C' . $column, $lhkpnspt['tanggal_dokumen']);
      $sheet->setCellValue('D' . $column, $lhkpnspt['nama_pegawai']);
      $sheet->setCellValue('E' . $column, $lhkpnspt['dokumen']);
      $column++;
    }

    // Format Excel
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    $sheet->getStyle('A1:E' . ($column - 1))->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ]);
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $namaFile = 'Data_LHKPN_SPT.xlsx'; // nama file
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }

  public function export_lhkpn_spt_year($year)
  {
    $by = $this->dataPegawaiModel
      ->select('id_data_pegawai')
      ->join('user', 'user.id_user=data_pegawai.id_user')
      ->where('user.username', session()->get('username'))
      ->first();

    // Query data berdasarkan role dan filter tahun
    $dataQuery = $this->lhkpnSptModel
        ->select('lhkpn_spt.*, data_pegawai.nama as nama_pegawai')
        ->join('data_pegawai', 'lhkpn_spt.id_data_pegawai=data_pegawai.id_data_pegawai');

    if (session()->get('role') != "Admin") {
        $dataQuery->where('lhkpn_spt.id_data_pegawai', $by['id_data_pegawai']);
    }

    $dataQuery->where('YEAR(lhkpn_spt.tanggal_dokumen)', $year);
    $data = $dataQuery->findAll();

    // Proses Export ke Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Waktu Upload');
    $sheet->setCellValue('C1', 'Tahun');
    $sheet->setCellValue('D1', 'Pemilik');
    $sheet->setCellValue('E1', 'Dokumen');

    $column = 2;
    foreach ($data as $key => $lhkpnspt) {
      $sheet->setCellValue('A' . $column, $key + 1);
      $sheet->setCellValue('B' . $column, $lhkpnspt['created_at']);
      $sheet->setCellValue('C' . $column, $lhkpnspt['tanggal_dokumen']);
      $sheet->setCellValue('D' . $column, $lhkpnspt['nama_pegawai']);
      $sheet->setCellValue('E' . $column, $lhkpnspt['dokumen']);
      $column++;
    }

    // Format Excel
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    $sheet->getStyle('A1:E' . ($column - 1))->applyFromArray([
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
    ]);
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);

    $namaFile = 'Data_LHKPN_SPT_' . $year . '.xlsx';
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $namaFile);
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
  }
}
