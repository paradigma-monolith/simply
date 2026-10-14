<?php
$db = new mysqli('127.0.0.1', 'root', 'mysql', 'bkkr3927_simply');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Describe table
$res = $db->query('DESCRIBE ekip_penilaian_360_final');
if ($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $db->error;
}
