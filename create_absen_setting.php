<?php
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'root', 'mysql', 'bkkr3927_simply');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Create table absen_setting
$sql = "CREATE TABLE IF NOT EXISTS absen_setting (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    show_wfo TINYINT(1) DEFAULT 1,
    show_wfa TINYINT(1) DEFAULT 1,
    show_dl TINYINT(1) DEFAULT 1
)";
if ($db->query($sql) === TRUE) {
    echo "Table absen_setting created successfully\n";
} else {
    echo "Error creating table: " . $db->error . "\n";
}

// Insert default row if not exists
$result = $db->query("SELECT * FROM absen_setting");
if ($result->num_rows == 0) {
    $db->query("INSERT INTO absen_setting (show_wfo, show_wfa, show_dl) VALUES (1, 1, 1)");
    echo "Inserted default row\n";
}

$db->close();
