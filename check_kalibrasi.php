<?php
require 'vendor/autoload.php';
$db = new mysqli('127.0.0.1', 'root', 'mysql', 'bkkr3927_simply');
$sql = "CREATE TABLE IF NOT EXISTS ekip_kalibrasi_perilaku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    penilai_id INT,
    dinilai_id INT,
    minggu_ke INT,
    bulan INT,
    tahun INT,
    loyalitas DECIMAL(10,2),
    cara_kerja DECIMAL(10,2),
    target_kinerja DECIMAL(10,2),
    tata_kelola DECIMAL(10,2),
    lingkungan DECIMAL(10,2),
    grand_total DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$res = $db->query($sql);
if ($res) echo "Table created";
else echo $db->error;
