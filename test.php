<?php
$mysqli = new mysqli('localhost', 'root', 'mysql', 'bkkr3927_simply');
$result = $mysqli->query('SELECT id_data_pegawai, nama, jabatan, id_jabatan FROM data_pegawai LIMIT 5');
while($row = $result->fetch_assoc()) { print_r($row); }
$result = $mysqli->query('SELECT * FROM jabatan LIMIT 5');
while($row = $result->fetch_assoc()) { print_r($row); }
