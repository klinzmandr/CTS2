<?php
session_start();
// AJAX response code - bootstrap is implemented in the receiving page.
include 'Incls/datautils.inc.php';

$notenbr = $_REQUEST['bbid'];
$sql  = "
SELECT * FROM `bboard`
WHERE `SeqNbr` = '$notenbr';"; 

// echo "notenbr: $notenbr, sql: $sql<br>";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$r = $res->fetch_assoc();

// echo '<pre>'; print_r($r); echo '</pre>';

$bbnote = preg_replace('/(?<!href="|">)(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/is', '<a href="\\1" target="_blank">\\1</a>', $r[Note]);

//print_r($_REQUEST);
$note = $_REQUEST['note'];
$bbid = $_REQUEST['bbid'];

//echo "$note - $bbid";
echo '<table class="table" border=1>';
echo "<tr><td><h3>$r[Subject]</h3></td><td>Note Nbr: $r[SeqNbr]</td></tr>";
echo "<tr><td colspan=\"2\">$bbnote</td></tr>";
echo "<tr><td>By: $r[UserID] on $r[DateTime]</td><td>";
if (($_SESSION['CTS_SessionUser'] == $r[UserID]) || ($_SESSION['CTS_SecLevel'] == 'admin')) {
		echo "<a href=\"bboardupdate.php?SeqNbr=$r[SeqNbr]&action=update\"<span title=\"Update Note\" class=\"glyphicon glyphicon-pencil\" style=\"color: blue; font-size: 20px\"></span></a>&nbsp;&nbsp;&nbsp;"; }
	if (($_SESSION['CTS_SessionUser'] == $r[UserID]) || ($_SESSION['CTS_SecLevel'] == 'admin')) {
		echo "<a onclick=\"return confirmContinue()\" href=\"bboard.php?seqnbr=$r[SeqNbr]&action=delete\"<span title=\"Delete Note\" class=\"glyphicon glyphicon-trash\" style=\"color: blue; font-size: 20px\"></span></a>&nbsp;&nbsp;&nbsp;";	}
	echo "<a href=\"bboardprint.php?seqnbr=$r[SeqNbr]&action=print\"<span title=\"Print Note\" class=\"glyphicon glyphicon-print\" style=\"color: blue; font-size: 20px\"></span></a></td></tr>";
echo '</table>';

?>