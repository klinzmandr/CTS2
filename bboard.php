<!DOCTYPE html>
<html>
<head>
<title>Bulletin Board</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>

<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

print <<<pagePart1
<div class="container">
<h2 class="hidden-print">Bulletin Board&nbsp;&nbsp;<a href="bboardupdate.php?action=addnew"><span title="Add New Note" class="glyphicon glyphicon-plus" style="color: blue; font-size: 20px"></span></a></h2>
<script>
function confirmContinue() {
	var r=confirm("This action cannot be reversed.\\n\\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

pagePart1;
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$seqnbr = isset($_REQUEST['seqnbr'])? $_REQUEST['seqnbr'] : ""; 

if ($action == 'delete') {
	echo "delete $seqnbr requested<br>";
	$sql = "DELETE FROM `bboard` WHERE `SeqNbr` = '$seqnbr';";
	$rc = doSQLsubmitted($sql);		// returns affected_rows for delete
	if ($rc > 0) 
		echo "Deletion of note $seqnbr successful<br>";
	else
		echo "Error on delete of note $seqnbr<br>";
	}

if ($action == 'update') {
	echo "update $seqnbr requested<br>";
	}

$sql = "SELECT * FROM `bboard` WHERE '1' ORDER BY `DateTime` DESC;";
$res = doSQLsubmitted($sql);
while ($r = $res->fetch_assoc()) {
  echo '<table border="0" class="table-condensed">';
	//echo '<pre> bboard '; print_r($r); echo '</pre>';
	echo "<tr><td><h3>$r[Subject]</h3></td><td>Note Nbr: $r[SeqNbr]</td></tr>";
	echo "<tr><td colspan=\"2\">$r[Note]</td></tr>";
	echo "<tr><td>By: $r[UserID] on $r[DateTime]</td>";
	echo '<td class="hidden-print" align="right">';
	if (($_SESSION['CTS_SessionUser'] == $r[UserID]) || ($_SESSION['CTS_SecLevel'] == 'admin'))
		echo "<a href=\"bboardupdate.php?SeqNbr=$r[SeqNbr]&action=update\"<span title=\"Update Note\" class=\"glyphicon glyphicon-pencil\" style=\"color: blue; font-size: 20px\"></span></a>&nbsp;&nbsp;&nbsp;";
	if (($_SESSION['CTS_SessionUser'] == $r[UserID]) || ($_SESSION['CTS_SecLevel'] == 'admin'))
		echo "<a onclick=\"return confirmContinue()\" href=\"bboard.php?seqnbr=$r[SeqNbr]&action=delete\"<span title=\"Delete Note\" class=\"glyphicon glyphicon-trash\" style=\"color: blue; font-size: 20px\"></span></a>&nbsp;&nbsp;&nbsp;";	
	echo "<a href=\"bboardprint.php?seqnbr=$r[SeqNbr]&action=print\"<span title=\"Print Note\" class=\"glyphicon glyphicon-print\" style=\"color: blue; font-size: 20px\"></span></a>";
	echo '</td></tr>';
	echo '<tr class="hidden-print"><td colspan=2 align="center">=================================</td><tr>
	</table>
	<div class="page-break"></div>';
	
	}

?>
</div>  <!-- container -->

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
