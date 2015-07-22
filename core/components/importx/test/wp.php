<?php
require '../processors/prepare/wordpressimport.php';

$wp = new WordpressImport();

echo '<pre>';

print_r($wp->fileToArray('powergear.wordpress.2015-07-09.xml'));