<?php
session_start();
include 'Incls/datautils.inc.php';

// 
//print_r($_REQUEST);

$PN = $_REQUEST['PN'];
$sql = "SELECT * FROM `calls` WHERE `PrimaryPhone` = '$PN' ORDER BY `calls`.`CallNbr`  ASC";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$accum = array();
$accum[0] = 0; $accum[1] = ''; $accum[2] = ''; $accum[3] = '';
$accum[4] = ''; $accum[5] = ''; $accum[6] = ''; $accum[7] = '';
 
$accum[0] = $rc; 
while ($r = $res->fetch_assoc()) {
  if ($r['Name'] != '') $accum[1] = $r['Name']; 
  if ($r['Address'] != '') $accum[2] = $r['Address']; 
  if ($r['City'] != '') $accum[3] = $r['City']; 
  if ($r['State'] != '') $accum[4] = $r['State']; 
  if ($r['Zip'] != '') $accum[5] = $r['Zip']; 
  if ($r['EMail'] != '') $accum[6] = $r['EMail']; 
  if ($r['CallNbr'] != '') $accum[7] = $r['CallNbr']; 
  }
// print_r($accum);
echo implode(':',$accum);
?>