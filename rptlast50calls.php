<!DOCTYPE html>
<html>
<head>
<title>Last 50 Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc';
//include 'Incls/mainmenu.inc';
include 'Incls/datautils.inc';

print <<<pagePart1
<h3>Last 50 Calls Report  <a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>  
<p>This report will provide a listing of the last 50 calls logged into the database sorted in revers chronological order with the newest call at the top.</p>
<p>NOTE:  All report links open the call in a format ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>

pagePart1;

$sql = "SELECT * FROM `calls` ORDER BY `CallNbr` DESC LIMIT 0,50;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<table class="table table-condensed">
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>';
while ($r = $res->fetch_assoc()) {
	//echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr];
	echo "<tr><td align=\"center\"><a href=\"callroview.php?action=button&call=$callnbr\">$callnbr</a></td>";
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
