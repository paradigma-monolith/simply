<?php 
require 'vendor/autoload.php'; 
require 'app/Config/Database.php'; 
$db = \Config\Database::connect(); 
$fields = $db->getFieldNames('bkkr3927_cop');
print_r($fields);
?>