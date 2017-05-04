<!DOCTYPE html>
<html>
<head>
<title>Report Calls for Today</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

print <<<pagePart1
<h3>Report Calls For Today &nbsp;&nbsp;   <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>
<p class="hidden-print">This report merely lists those calls that have been entered since midnight.</p>
<p class="hidden-print">NOTE:  All reports open in a new window (or tab) ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>

pagePart1;
$today = date("Y-m-d 00:00:01",strtotime(now));
$sql = "SELECT * FROM `calls` 
WHERE `DTOpened` >= '$today'
ORDER BY `CallNbr` DESC;";
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<table class="table table-condensed">
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>';
while ($r = $res->fetch_assoc()) {
	//echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr];
	echo "<tr><td><a href=\"callroview.php?action=button&call=$callnbr\">$callnbr</a></td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTOpened]</td><td>$r[DTPlaced]</td><td>$r[OpenedBy]</td><td>$r[Description]</td></tr>";
	}
echo '</table>';
echo "=== END OF REPORT===<br>";

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
