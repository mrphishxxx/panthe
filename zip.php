<?php
$zip = new ZipArchive;
if ($zip->open('wordpress.zip') === TRUE) {
    $zip->extractTo($_SERVER['DOCUMENT_ROOT'].'/');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?> 