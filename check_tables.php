<?php
require 'vendor/autoload.php';

$db = new mysqli('localhost', 'root', 'mysql', 'bkkr3927_simply');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$result = $db->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    echo $row[0] . "\n";
}
