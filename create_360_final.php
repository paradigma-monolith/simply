<?php
$db = new mysqli('127.0.0.1', 'root', 'mysql', 'bkkr3927_simply');
$sql = "CREATE TABLE IF NOT EXISTS ekip_penilaian_360_final (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penilai_id INT,
    dinilai_id INT,
    minggu_ke INT,
    bulan INT,
    tahun INT,
    hasil_pekerjaan DECIMAL(10,2),
    kualitas_pekerjaan DECIMAL(10,2),
    ketepatan_waktu DECIMAL(10,2),
    efektivitas_efesiensi DECIMAL(10,2),
    tanggung_jawab DECIMAL(10,2),
    kerjasama DECIMAL(10,2),
    presensi DECIMAL(10,2),
    jumlah DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$res = $db->query($sql);
if ($res) echo "Table created";
else echo $db->error;
