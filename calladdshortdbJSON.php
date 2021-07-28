<?php
session_start();
include 'Incls/datautils.inc.php';

// print_r($_REQUEST);
// add new call record to database
$nowdt = date('Y-m-d H:i', strtotime('now'));
$ymdhm = date('Y-m-d H:i', strtotime($_REQUEST['NOWDT']));
$hlvuser = $_SESSION['CTS_SessionUser'];
$upd['Status'] = $_REQUEST['ACTION'];
$upd['DTPlaced'] = $ymdhm;
$upd['DTOpened'] = $nowdt;
$upd['OpenedBy'] = $hlvuser;
$upd['Reason'] = $_REQUEST['RE'];
$upd['LastUpdater'] = $hlvuser;
$upd['Name'] = $_REQUEST['CN'];
$upd["PrimaryPhone"] = $_REQUEST['PN'];
$upd['Description'] = $_REQUEST['CD'];
$upd['EMail'] = $_REQUEST['EM'];
$upd['CallLocation'] = $_REQUEST['CL'];
$upd['AnimalLocation'] = $_REQUEST['CL'];
$upd['Address'] = $_REQUEST['AD'];
$upd['City'] = $_REQUEST['CI'];
$upd['State'] = $_REQUEST['ST'];
$upd['Zip'] = $_REQUEST['ZI'];
$upd['TimeToResolve'] = '<15';
$upd['Property'] = 'NA';
$upd['Species'] = 'NA';
$upd['NotesDiary'] = "DateTime: $nowdt &nbsp;&nbsp;By: $hlvuser <ul>Added short form call";
if ($upd['Status'] == 'Closed') {
  $upd['DTClosed'] = $ymdhm;
  $upd['Resolution'] = $_REQUEST['CR'];
  $upd['NotesDiary'] .= " and closed immediately";
  }
$upd['NotesDiary'] .= "</ul>";

// print_r($upd);
// echo '8888';
// exit;

// insert record
sqlinsert('calls', $upd);
addlogentry("New short form call inserted");

// read new record back to get call number for 
$sql = "SELECT * FROM `calls` WHERE `DTOpened` = '$nowdt';";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();
// echo '<pre>DB record '; print_r($r); echo'</pre>';
$callnbr = $r['CallNbr'];
$_SESSION['4log'] = $callnbr;
echo $r['CallNbr'];                // report call# of new call
?>