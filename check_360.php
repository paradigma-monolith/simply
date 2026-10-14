<?php
$db = new mysqli('127.0.0.1', 'root', 'mysql', 'bkkr3927_simply');
$res = $db->query("SHOW TABLES LIKE '%360%'");
while($row = $res->fetch_row()) {
    print_r($row);
}
