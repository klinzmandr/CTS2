<?php 
// ref: https://www.cyberciti.biz/faq/php-redirect/
// NOTE: NO OTHER OUTPUT CAN BE CREATED BEFORE THIS CODE IS EXECUTED 
session_start();
include 'Incls/datautils.inc.php';
$file = $_REQUEST['dsp'];
$re = '/(\d{2,3})/';
preg_match($re, $file, $matches);
$docnbr = $matches[0];
if (file_exists($file)) {
  addlogentry("display doc $docnbr");
  header("Location: $file");
  exit;
  }
echo "file $file does not exists<br><br><br>";
?>