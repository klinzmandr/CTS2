<?php
session_start();
include 'Incls/datautils.inc.php';

// print_r($_REQUEST);
$exparray = explode("&", rawurldecode($_REQUEST['form']));
// echo 'form: '; print_r($exparray);
$diary = $_REQUEST['diary'];
// echo 'diary: '; var_dump($diary);

$upd = array();
foreach ($exparray as $l) {
  list($p1, $p2) = preg_split('/=/', $l);
  $upd[$p1] = $p2;
  }

$upd['ResTOD'] = date('H:i', strtotime('now'));
$upd['ResBy'] = $_SESSION['CTS_SessionUser'];
$upd['ResTelephone'] = $_SESSION['CTS_VolTelephone'];
$upd['Status'] = 'Closed';
$upd['DTClosed'] = date('Y-m-d H:i', strtotime('now'));
$upd['LastUpdater'] = $_SESSION['CTS_SessionUser'];

$tod = date('Y-m-d H:i:s', strtotime('now'));
$notehdr = 'Date/Time: '.$tod." &nbsp;&nbsp;By: ".$_SESSION['CTS_SessionUser'];
$upd['NotesDiary'] = "$notehdr<ul>" . $upd['Notes'] . "<br>Call Closed.</ul>$diary";

unset($upd[action]);
unset($upd['Notes']);

// var_dump($diary);
// print_r($upd);
$where = "`CallNbr`='" . $upd['CallNbr'] . "'";
sqlupdate('calls', $upd, $where);

?>