<?php
$zip = new ZipArchive;
if ($zip->open('c:\Program Files\Ampps\www\simply\Konsep SIvera\Aplikasi SIVERA.docx') === true) {
    if (($index = $zip->locateName('word/document.xml')) !== false) {
        $data = $zip->getFromIndex($index);
        echo strip_tags($data);
    }
    $zip->close();
}
