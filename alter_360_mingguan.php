<?php
$db = new mysqli('127.0.0.1', 'root', 'mysql', 'bkkr3927_simply');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$db->query('ALTER TABLE ekip_penilaian_360_final ADD COLUMN bulan INT AFTER tahun');
$db->query('ALTER TABLE ekip_penilaian_360_final ADD COLUMN minggu_ke INT AFTER bulan');

echo "Table altered successfully\n";
