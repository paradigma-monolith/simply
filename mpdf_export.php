<?php
require 'vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML(file_get_contents('logika_skoring_kapal.html'));
$mpdf->Output('Logika_Skoring_Pengawasan_Kapal.pdf', 'F');
?>