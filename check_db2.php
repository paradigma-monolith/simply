<?php
$conn = new mysqli('localhost', 'root', 'mysql', 'bkkr3927_cop');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SHOW COLUMNS FROM tbl_pasien");
while ($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}
?>