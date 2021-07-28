<?php
session_start();
include 'Incls/datautils.inc.php';

print_r($_REQUEST);
$exparray = explode("&", rawurldecode($_REQUEST['form']));
print_r($exparray);
$diary = $_REQUEST['diary'];
print_r($diary);

$upd = array();
foreach ($exparray as $l) {
  list($p1, $p2) = preg_split('/=/', $l);
  $upd[$p1] = $p2;
  }

$tod = date('Y-m-d H:i:s', strtotime('now'));
$notehdr = 'Date/Time: '.$tod." &nbsp;&nbsp;By: ".$_SESSION['CTS_SessionUser'];
$upd['NotesDiary'] = "$notehdr<ul>" . $upd['Notes'] . "</ul>$diary";

unset($upd['action']);
unset($upd['Notes']);
// print_r($upd);
$where = "`CallNbr`='" . $upd['CallNbr'] . "'";
sqlupdate('calls', $upd, $where);

?>