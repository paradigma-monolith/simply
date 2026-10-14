<?php

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if (!function_exists('generate_qr')) {
    function generate_qr($qr, $title = '', $warna = '')
    {
        $writer = new PngWriter();

        if ($warna == 'Red') {
            $warna = new Color(255, 0, 0);
        } elseif ($warna == 'Green') {
            $warna = new Color(0, 255, 0);
        } elseif ($warna == 'Blue') {
            $warna = new Color(0, 0, 255);
        } elseif ($warna == 'Yellow') {
            $warna = new Color(255, 255, 0);
        } elseif ($warna == 'Orange') {
            $warna = new Color(255, 165, 0);
        } elseif ($warna == 'Purple') {
            $warna = new Color(128, 0, 128);
        } elseif ($warna == 'Pink') {
            $warna = new Color(255, 192, 203);
        } elseif ($warna == 'Brown') {
            $warna = new Color(165, 42, 42);
        } elseif ($warna == 'Black') {
            $warna = new Color(0, 0, 0);
        } elseif ($warna == 'White') {
            $warna = new Color(255, 255, 255);
        } elseif ($warna == 'Gray') {
            $warna = new Color(128, 128, 128);
        } elseif ($warna == 'Silver') {
            $warna = new Color(192, 192, 192);
        } elseif ($warna == 'Gold') {
            $warna = new Color(255, 215, 0);
        } elseif ($warna == 'Maroon') {
            $warna = new Color(128, 0, 0);
        } elseif ($warna == 'Navy') {
            $warna = new Color(0, 0, 128);
        } elseif ($warna == 'Turquoise') {
            $warna = new Color(64, 224, 208);
        } elseif ($warna == 'Indigo') {
            $warna = new Color(75, 0, 130);
        } elseif ($warna == 'Cyan') {
            $warna = new Color(0, 255, 255);
        } elseif ($warna == 'Magenta') {
            $warna = new Color(255, 0, 255);
        } elseif ($warna == 'Olive') {
            $warna = new Color(128, 128, 0);
        } elseif ($warna == 'Teal') {
            $warna = new Color(0, 128, 128);
        } elseif ($warna == 'Beige') {
            $warna = new Color(245, 245, 220);
        } elseif ($warna == 'Ivory') {
            $warna = new Color(255, 255, 240);
        } elseif ($warna == 'Coral') {
            $warna = new Color(255, 127, 80);
        } elseif ($warna == 'Lavender') {
            $warna = new Color(230, 230, 250);
        } elseif ($warna == 'Peach') {
            $warna = new Color(255, 218, 185);
        } elseif ($warna == 'Mint') {
            $warna = new Color(189, 252, 201);
        } elseif ($warna == 'Slate') {
            $warna = new Color(112, 128, 144);
        } elseif ($warna == 'Charcoal') {
            $warna = new Color(54, 69, 79);
        } elseif ($warna == 'Ruby') {
            $warna = new Color(224, 17, 95);
        } else {
            $warna = new Color(0, 0, 0);
        }

        $qrCode = QrCode::create($qr)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor($warna)
            ->setBackgroundColor(new Color(255, 255, 255));
        // Create generic logo
        $logo = Logo::create('dist/img/logo-kesehatan-border-white.png')
            ->setResizeToWidth(50)
            ->setPunchoutBackground(true);

        // Create generic label
        $label = Label::create($title)
            ->setTextColor(new Color(0, 0, 0));

        $result = $writer->write($qrCode, $logo, $label);

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        return $result->getDataUri();
    }
}

if (!function_exists('tgl_indo')) {
    function date_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('bulan')) {
    function bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Januari";
                break;
            case 2:
                return "Februari";
                break;
            case 3:
                return "Maret";
                break;
            case 4:
                return "April";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Juni";
                break;
            case 7:
                return "Juli";
                break;
            case 8:
                return "Agustus";
                break;
            case 9:
                return "September";
                break;
            case 10:
                return "Oktober";
                break;
            case 11:
                return "November";
                break;
            case 12:
                return "Desember";
                break;
        }
    }
}

if (!function_exists('bulan2')) {
    function bulan2($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Ags";
                break;
            case 9:
                return "Sep";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }
    }
}

if (!function_exists('romawi')) {
    function romawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}

//Format Shortdate
if (!function_exists('shortdate_indo')) {
    function shortdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = short_bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . '/' . $bulan . '/' . $tahun;
    }
}

if (!function_exists('shortdate_indo2')) {
    function shortdate_indo2($tgl)
    {
        $ubah = $tgl;
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('thisGet')) {
    function thisGet()
    {
        return "/get/(:any)";
    }
}

if (!function_exists('shortdate_indo3')) {
    function shortdate_indo3($tgl)
    {
        $ubah = $tgl;
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = bulan2($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('short_bulan')) {
    function short_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "01";
                break;
            case 2:
                return "02";
                break;
            case 3:
                return "03";
                break;
            case 4:
                return "04";
                break;
            case 5:
                return "05";
                break;
            case 6:
                return "06";
                break;
            case 7:
                return "07";
                break;
            case 8:
                return "08";
                break;
            case 9:
                return "09";
                break;
            case 10:
                return "10";
                break;
            case 11:
                return "11";
                break;
            case 12:
                return "12";
                break;
        }
    }
}

//Format Medium date
if (!function_exists('mediumdate_indo')) {
    function mediumdate_indo($tgl)
    {
        $ubah = gmdate($tgl, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tanggal = $pecah[2];
        $bulan = bulan($pecah[1]);
        $tahun = $pecah[0];
        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (!function_exists('medium_bulan')) {
    function medium_bulan($bln)
    {
        switch ($bln) {
            case 1:
                return "Jan";
                break;
            case 2:
                return "Feb";
                break;
            case 3:
                return "Mar";
                break;
            case 4:
                return "Apr";
                break;
            case 5:
                return "Mei";
                break;
            case 6:
                return "Jun";
                break;
            case 7:
                return "Jul";
                break;
            case 8:
                return "Ags";
                break;
            case 9:
                return "Sep";
                break;
            case 10:
                return "Okt";
                break;
            case 11:
                return "Nov";
                break;
            case 12:
                return "Des";
                break;
        }
    }
}

//Long date indo Format
if (!function_exists('longdate_indo')) {
    function longdate_indo($tanggal)
    {
        $ubah = gmdate($tanggal, time() + 60 * 60 * 8);
        $pecah = explode("-", $ubah);
        $tgl = $pecah[2];
        $bln = $pecah[1];
        $thn = $pecah[0];
        $bulan = bulan($pecah[1]);

        $nama = date("l", mktime(0, 0, 0, $bln, $tgl, $thn));
        $nama_hari = "";
        if ($nama == "Sunday") {
            $nama_hari = "Minggu";
        } else if ($nama == "Monday") {
            $nama_hari = "Senin";
        } else if ($nama == "Tuesday") {
            $nama_hari = "Selasa";
        } else if ($nama == "Wednesday") {
            $nama_hari = "Rabu";
        } else if ($nama == "Thursday") {
            $nama_hari = "Kamis";
        } else if ($nama == "Friday") {
            $nama_hari = "Jumat";
        } else if ($nama == "Saturday") {
            $nama_hari = "Sabtu";
        }
        return $nama_hari . ',' . $tgl . ' ' . $bulan . ' ' . $thn;
    }
}

function penyebut($nilai)
{
    $nilai = (int) abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai)) . ' RUPIAH';
    } else {
        $hasil = trim(penyebut($nilai)) . ' RUPIAH';
    }
    return strtoupper($hasil);
}



function selisih_hari($tgl1 = '', $tgl2 = "")
{
    $tgl1 = new DateTime($tgl1);
    $tgl2 = new DateTime($tgl2);

    if ($tgl1->format('Y-m-d') == '0000-00-00' || $tgl2->format('Y-m-d') == '0000-00-00') {
        return '-';
    } else {
        $jarak = $tgl2->diff($tgl1);
        return $jarak->days + 1;
    }
}
function selisih_hari_else_sabtu_minggu($tgl1 = '', $tgl2 = "")
{
    $tgl1 = new DateTime($tgl1);
    $tgl2 = new DateTime($tgl2);
    $interval = $tgl2->diff($tgl1);

    $days = $interval->days + 1; // Tambahkan 1 hari karena tanggal awal juga dihitung
    $weekends = 0;
    for ($i = 0; $i < $interval->days; $i++) {
        $day = $tgl1->format('N'); // Hari dalam format 1 (Senin) - 7 (Minggu)
        if ($day == 6 || $day == 7) {
            $weekends++;
        }
        $tgl1->modify('+1 day');
    }

    $workdays = $days - $weekends;
    return $workdays;
}

//Format Medium date
if (!function_exists('name_capitalize')) {
    function name_capitalize($nama)
    {
        $str = $nama;
        $jabatan_akhirNama = $str;
        $jabatan_awalNama = $str;
        $jabatanAkhir = '';
        $jabatanAwal = '';

        $pos = strpos($jabatan_akhirNama, ",");
        if ($pos != false)
            $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

        $pos = strpos($jabatan_awalNama, ",");
        if ($pos != false)
            $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

        $pos = strpos($jabatan_awalNama, ".");
        if ($pos != false)
            $nama = substr($jabatan_awalNama, $pos + 2);
        else
            $nama = $jabatan_awalNama;

        $pos = strpos($jabatan_awalNama, ".");
        if ($pos != false)
            $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

        $array = explode(",", $jabatan_akhirNama);
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] != $str)
                $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
        }

        $nama = ucwords(strtolower($nama));
        return $jabatanAwal . ($jabatanAwal == '' ? '' : ' ') . $nama . $jabatanAkhir;
    }
}

if (!function_exists('name_uppercase')) {
    function name_uppercase($nama)
    {
        $str = $nama;
        $jabatan_akhirNama = $str;
        $jabatan_awalNama = $str;
        $jabatanAkhir = '';
        $jabatanAwal = '';

        $pos = strpos($jabatan_akhirNama, ",");
        if ($pos != false)
            $jabatan_akhirNama = substr($jabatan_akhirNama, $pos + 2);

        $pos = strpos($jabatan_awalNama, ",");
        if ($pos != false)
            $jabatan_awalNama = substr($jabatan_awalNama, 0, $pos + 0);

        $pos = strpos($jabatan_awalNama, ".");
        if ($pos != false)
            $nama = substr($jabatan_awalNama, $pos + 2);
        else
            $nama = $jabatan_awalNama;

        $pos = strpos($jabatan_awalNama, ".");
        if ($pos != false)
            $jabatanAwal = substr($jabatan_awalNama, 0, $pos + 1);

        $array = explode(",", $jabatan_akhirNama);
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] != $str)
                $jabatanAkhir = $jabatanAkhir . ', ' . $array[$i];
        }

        $nama = strtoupper($nama);
        return $jabatanAwal . ($jabatanAwal == '' ? '' : ' ') . $nama . $jabatanAkhir;
    }
}


//Format Medium date
if (!function_exists('selisi_waktu')) {
    function selisi_waktu($waktuAwal, $waktuAkhir = null)
    {
        // Membuat objek DateTime untuk waktu awal
        $waktuAwal = new DateTime($waktuAwal);

        // Membuat objek DateTime untuk waktu akhir (waktu saat ini)
        if ($waktuAkhir == null) {
            $waktuAkhir = new DateTime();
        } else {
            $waktuAkhir = new DateTime($waktuAkhir);
        }

        // Menghitung selisih waktu
        $selisih = $waktuAwal->diff($waktuAkhir);

        // Menampilkan selisih waktu sesuai kriteria
        if ($selisih->y >= 1) {
            return $selisih->y . " tahun";
        } elseif ($selisih->m >= 1) {
            return $selisih->m . " bulan";
        } elseif ($selisih->d >= 7) {
            return floor($selisih->d / 7) . " minggu";
        } elseif ($selisih->d >= 1) {
            return $selisih->d . " hari";
        } elseif ($selisih->h >= 1) {
            return $selisih->h . " jam";
        } else {
            return $selisih->i . " menit";
        }
    }
}
if (!function_exists('hitungHari')) {
    function hitungHari($waktuAwal, $waktuAkhir, $format = 'Y-m-d')
    {
        // Konversi string tanggal ke objek DateTime
        $tanggalAwal = new DateTime($waktuAwal);
        $tanggalAkhir = new DateTime($waktuAkhir);

        // Hitung selisih waktu dalam hari
        $selisih = $tanggalAwal->diff($tanggalAkhir);

        // Ambil nilai selisih dalam hari
        return $selisih->days;
    }
}

if (!function_exists('generate_base64')) {
    function generate_base64($imagePath)
    {
        $imageData = file_get_contents($imagePath);
        $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $base64Image = base64_encode($imageData);

        return 'data:image/' . $imageExtension . ';base64,' . $base64Image;
    }
}
if (!function_exists('haversine_distance')) {
    function  haversine_distance($lat1, $lon1, $lat2, $lon2)
    {
      $earth_radius = 6371000; // dalam meter
    
      // Konversi derajat ke radian
      $lat1_rad = deg2rad($lat1);
      $lon1_rad = deg2rad($lon1);
      $lat2_rad = deg2rad($lat2);
      $lon2_rad = deg2rad($lon2);
    
      // Selisih koordinat
      $delta_lat = $lat2_rad - $lat1_rad;
      $delta_lon = $lon2_rad - $lon1_rad;
    
      // Rumus Haversine
      $a = sin($delta_lat / 2) * sin($delta_lat / 2) +
        cos($lat1_rad) * cos($lat2_rad) *
        sin($delta_lon / 2) * sin($delta_lon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
      // Hitung jarak
      $distance = $earth_radius * $c;
    
      return $distance;
    }
}
if (!function_exists('convertToSuperscript')) {
    function convertToSuperscript($text)
    {
        $superscripts = [
            '0' => '⁰',
            '1' => '¹',
            '2' => '²',
            '3' => '³',
            '4' => '⁴',
            '5' => '⁵',
            '6' => '⁶',
            '7' => '⁷',
            '8' => '⁸',
            '9' => '⁹',
        ];

        return preg_replace_callback('/\d/', function ($matches) use ($superscripts) {
            return $superscripts[$matches[0]];
        }, $text);
    }
}
if (!function_exists('replaceStarWithDegree')) {
    function replaceStarWithDegree($text)
    {
        if (strpos($text, '*') !== false) {
            return str_replace('*', '°', $text);
        }
        return $text;
    }
}

if (!function_exists('hitungSelisihMenitAbsensi')) {
    function hitungSelisihMenitAbsensi($waktu1, $waktu2) {
        $datetime1 = strtotime($waktu1);
        $datetime2 = strtotime($waktu2);
        return abs(($datetime2 - $datetime1) / 60);
    }
}
if (!function_exists('statusAbsensi')) {
    function statusAbsensi($jamMasuk, $jamPulang, $aturanJamMasuk, $aturanJamPulang, $toleransiKeterlambatan) {
        if ($aturanJamMasuk == null && $aturanJamPulang == null && $toleransiKeterlambatan == null) {
            return [
                'masuk' => 'hijau',
                'pulang' => 'hijau'
            ];
        }
        $statusmasuk = "merah"; 
        $statuspulang = "merah"; 
        $hitungKeterlambatan = null; 
        
        if ($jamMasuk !== null && $jamPulang !== null) {
            $jamMasukFormatted = substr($jamMasuk, 11, 8);
            $jamPulangFormatted = substr($jamPulang, 11, 8);
        
            $batasToleransiFormatted = date("H:i", strtotime($aturanJamMasuk) + (hitungSelisihMenitAbsensi("00:00", $toleransiKeterlambatan) * 60));
        
            if ($jamMasukFormatted > $aturanJamMasuk && $jamMasukFormatted <= $batasToleransiFormatted) {
                $hitungKeterlambatan = hitungSelisihMenitAbsensi($aturanJamMasuk, $jamMasukFormatted);
                $statusmasuk = "kuning"; 
            } elseif ($jamMasukFormatted > $batasToleransiFormatted) {
                $hitungKeterlambatan = null; 
                $statusmasuk = "merah"; 
            } else {
                $statusmasuk = "hijau"; 
            }
        
            if ($hitungKeterlambatan !== null) { 
                $jamPulangMinimalMenit = strtotime($aturanJamPulang) + ($hitungKeterlambatan * 60);
                $jamPulangMinimal = date("H:i", $jamPulangMinimalMenit);
        
                if ($jamPulangFormatted >= $jamPulangMinimal) {
                    $statusmasuk = "hijau"; 
                    $statuspulang = "hijau";
                } else {
                    $statusmasuk = "merah";
                    $statuspulang = "merah";
                }
            } else { 
                if ($jamPulangFormatted >= $aturanJamPulang) {
                    $statuspulang = "hijau"; 
                } else {
                    $statuspulang = "merah"; 
                }
            }
        } elseif ($jamMasuk !== null) {
            $jamMasukFormatted = substr($jamMasuk, 11, 8);
            $batasToleransiFormatted = date("H:i", strtotime($aturanJamMasuk) + (hitungSelisihMenitAbsensi("00:00", $toleransiKeterlambatan) * 60));
        
            if ($jamMasukFormatted > $aturanJamMasuk && $jamMasukFormatted <= $batasToleransiFormatted) {
                $hitungKeterlambatan = hitungSelisihMenitAbsensi($aturanJamMasuk, $jamMasukFormatted);
                $statusmasuk = "kuning";
            } elseif ($jamMasukFormatted > $batasToleransiFormatted) {
                $hitungKeterlambatan = null; 
                $statusmasuk = "merah"; 
            } else {
                $statusmasuk = "hijau"; 
            }
            $statuspulang = "merah";
        } else {
            $statusmasuk = "merah";
            $statuspulang = "merah";
        }
        return [
            'masuk' => $statusmasuk,
            'pulang' => $statuspulang
        ];
    }
}

if (!function_exists('hitungJamKerja')) {
    function hitungJamKerja($jamMasuk, $jamPulang)
    {
        if (empty($jamMasuk) || empty($jamPulang)) {
            return "Data tidak lengkap";
        }

        try {
            // Membuat objek DateTime untuk jam masuk dan jam pulang
            $waktuMasuk = new DateTime($jamMasuk);
            $waktuPulang = new DateTime($jamPulang);

            // Menghitung selisih waktu
            $selisih = $waktuMasuk->diff($waktuPulang);

            // Format hasil
            $jam = $selisih->h;
            $menit = $selisih->i;
            
            if ($jam > 0 && $menit > 0) {
                return $jam . " jam " . $menit . " menit";
            } elseif ($jam > 0) {
                return $jam . " jam";
            } elseif ($menit > 0) {
                return $menit . " menit";
            } else {
                return "0 menit";
            }
        } catch (Exception $e) {
            return "Error menghitung jam kerja";
        }
    }
}

if (!function_exists('generateRandomText')) {
    function generateRandomText($length = 32)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $poolLength = strlen($pool);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $poolLength - 1); // Lebih aman
            $result .= $pool[$randomIndex];
        }

        return $result;
    }
}


