<!DOCTYPE html>
<html>
<head>
<title>BBoard Print</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
echo "<div class=\"hidden-print\">";
include 'Incls/mainmenu.inc.php';
echo '</div>';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$seqnbr = isset($_REQUEST['seqnbr'])? $_REQUEST['seqnbr'] : ""; 

$sql = "SELECT * FROM `bboard` WHERE `SeqNbr` = '$seqnbr';";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();

print <<<pagePart1
<div class="container">
<h3>Bulletin Board Note $seqnbr</h3>
<table class="table">
<tr><td><h4>$r[Subject]</h4></td></tr>
<tr><td>$r[Note]</td></tr>
<tr><td>By: $r[UserID] on $r[DateTime]</td></tr>
</table>
<br>

<div class="hidden-print">
<a class="btn btn-success" href="bboard.php">CONTINUE</a>
</div>

</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
