<?php
session_start();

$before = $_SESSION['flag'];

$_SESSION['flag'] = 'newflag';
$after = $_SESSION['flag'];
 
//echo "Session before: $before, after: $after<br>";
//print_r($_REQUEST);
$dp = $_REQUEST['dp'];
$cpn = $_REQUEST['cpn'];
$desc = $_REQUEST['desc'];

echo "dp: $dp - $cpn: $cpn - desc: $desc";

?>