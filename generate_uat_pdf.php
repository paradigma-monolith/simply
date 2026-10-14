<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Konfigurasi DomPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('isFontSubsettingEnabled', true);
$options->set('defaultMediaType', 'print');

$dompdf = new Dompdf($options);

// HTML Template untuk Laporan UAT
$html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan UAT & Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            margin: 20px;
        }
        h1, h2, h3 {
            color: #004085;
        }
        h1 {
            text-align: center;
            font-size: 16pt;
            border-bottom: 2px solid #004085;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #004085;
        }
        .header-info {
            margin-bottom: 30px;
        }
        .header-info table {
            width: auto;
            margin: 0;
        }
        .header-info th, .header-info td {
            border: none;
            padding: 4px 8px 4px 0;
        }
        .header-info th {
            background-color: transparent;
            color: #333;
        }
        .status-pass {
            color: green;
            font-weight: bold;
        }
        .signature-box {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box td {
            border: none;
            text-align: center;
            width: 50%;
        }
        .sign-area {
            height: 80px;
        }
    </style>
</head>
<body>

    <h1>DOKUMEN USER ACCEPTANCE TESTING (UAT) & MONITORING</h1>

    <div class="header-info">
        <table>
            <tr>
                <th>Nama Proyek / Aplikasi</th>
                <td>: Sistem Digitalisasi Pengawasan Kekarantinaan Kesehatan di Pelabuhan (Simply)</td>
            </tr>
            <tr>
                <th>Modul / Halaman</th>
                <td>: Layanan Kesehatan Kapal (<code>/layanan_kesehatan_kapal</code>)</td>
            </tr>
            <tr>
                <th>Deskripsi Perubahan</th>
                <td>: Modifikasi nilai default filter rentang waktu (tanggal kedatangan). Sebelumnya filter secara default menampilkan data bulan berjalan. Kini diubah menjadi dari awal tahun berjalan (1 Januari) sampai dengan tanggal berjalan + 30 hari ke depan.</td>
            </tr>
            <tr>
                <th>Tanggal Pengujian</th>
                <td>: ' . date('d F Y') . '</td>
            </tr>
        </table>
    </div>

    <h2>1. Skenario Pengujian UAT (Test Cases)</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="20%">Skenario / Test Case</th>
                <th width="15%">Prasyarat</th>
                <th width="20%">Langkah-Langkah</th>
                <th width="25%">Hasil yang Diharapkan</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>TC-01</td>
                <td>Mengakses halaman utama tanpa parameter filter.</td>
                <td>User login sebagai Admin/Petugas.</td>
                <td>Buka menu Layanan Kesehatan Kapal melalui sidebar.</td>
                <td>1. <code>start_date</code> otomatis ' . date('Y-01-01') . '.<br>2. <code>end_date</code> otomatis ' . date('Y-m-d', strtotime('+30 days')) . '.<br>3. Tabel memuat data dalam rentang tersebut.</td>
                <td class="status-pass">LULUS</td>
            </tr>
            <tr>
                <td>TC-02</td>
                <td>Ekspor data ke Excel dengan parameter default.</td>
                <td>Berada di halaman dengan nilai default.</td>
                <td>Klik tombol "Export Excel".</td>
                <td>File Excel yang diunduh hanya berisi data kapal dalam rentang waktu awal tahun s.d +30 hari dari tanggal sekarang.</td>
                <td class="status-pass">LULUS</td>
            </tr>
            <tr>
                <td>TC-03</td>
                <td>Filter data secara manual dengan rentang khusus.</td>
                <td>Berada di halaman Layanan Kesehatan Kapal.</td>
                <td>1. Isi Mulai & Sampai Tanggal.<br>2. Klik "Filter".</td>
                <td>URL memiliki parameter tanggal yang benar dan tabel hanya menampilkan data pada rentang waktu yang dipilih.</td>
                <td class="status-pass">LULUS</td>
            </tr>
            <tr>
                <td>TC-04</td>
                <td>Mereset filter ke kondisi semula.</td>
                <td>Halaman saat ini memiliki parameter tanggal di URL.</td>
                <td>Klik tombol "Reset".</td>
                <td>Halaman dimuat ulang tanpa parameter dan kembali ke filter default awal tahun s.d +30 hari.</td>
                <td class="status-pass">LULUS</td>
            </tr>
            <tr>
                <td>TC-05</td>
                <td>Akses Form Publik oleh Pengguna Luar.</td>
                <td>Buka link Form Publik.</td>
                <td>Akses `/public/layanan_kesehatan_kapal`.</td>
                <td>Formulir publik tetap dapat diakses dan disubmit tanpa terpengaruh perubahan filter di halaman admin.</td>
                <td class="status-pass">LULUS</td>
            </tr>
        </tbody>
    </table>

    <h2>2. Rencana Monitoring (Pasca-Implementasi)</h2>
    <table>
        <thead>
            <tr>
                <th>Aspek Monitoring</th>
                <th>Target / Indikator Kinerja</th>
                <th>Risiko yang Mungkin Terjadi</th>
                <th>Tindakan Pencegahan / Mitigasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Kinerja Database (Load Time)</strong></td>
                <td>Halaman termuat &lt; 3 detik.</td>
                <td>Beban memori PHP meningkat di akhir tahun karena penarikan data selama 1 tahun penuh.</td>
                <td>Memantau waktu respon query. Jika lambat, pertimbangkan migrasi ke Server-side DataTables (AJAX).</td>
            </tr>
            <tr>
                <td><strong>Ekspor Data (Memory Limit)</strong></td>
                <td>Proses ekspor Excel tidak mengalami <i>Memory Exhausted Error</i>.</td>
                <td>Library PhpSpreadsheet memakan RAM tinggi saat mengekspor ribuan baris data sekaligus.</td>
                <td>Memastikan <code>memory_limit</code> PHP minimal 256MB/512MB di environment production.</td>
            </tr>
            <tr>
                <td><strong>Validasi Zona Waktu (+30 Hari)</strong></td>
                <td>Data kapal pre-arrival (sampai H+30) muncul akurat.</td>
                <td>Selisih 1 hari akibat perbedaan zona waktu server (UTC) dan lokal (WIB).</td>
                <td>Memastikan zona waktu aplikasi diatur ke <code>Asia/Jakarta</code> di Konfigurasi CodeIgniter.</td>
            </tr>
        </tbody>
    </table>

    <h2>3. Kesimpulan</h2>
    <p>
        Modifikasi filter tanggal default dari "Awal bulan s.d. Hari ini" menjadi "Awal tahun s.d. Hari ini +30 Hari" pada modul Layanan Kesehatan Kapal telah berhasil diimplementasikan dan diuji. 
        Semua fungsi pencarian, reset, dan ekspor berjalan sesuai spesifikasi tanpa merusak fungsionalitas yang ada. Fitur dinyatakan <strong>SIAP UNTUK PRODUCTION</strong>.
    </p>

    <table class="signature-box">
        <tr>
            <td>
                <strong>Dibuat Oleh (Developer / QA)</strong>
                <div class="sign-area"></div>
                ( ........................................... )<br>
                Tanggal: ' . date('d F Y') . '
            </td>
            <td>
                <strong>Disetujui Oleh (Project Manager / User)</strong>
                <div class="sign-area"></div>
                ( ........................................... )<br>
                Tanggal: ...................................
            </td>
        </tr>
    </table>

</body>
</html>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Simpan ke file
$output = $dompdf->output();
$filePath = __DIR__ . '/Laporan_UAT_Monitoring_Layanan_Kesehatan_Kapal.pdf';
file_put_contents($filePath, $output);

echo "PDF berhasil digenerate di: " . $filePath;
?>