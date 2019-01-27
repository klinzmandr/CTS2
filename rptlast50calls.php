<?php
session_start();
if (!isset($_SESSION['CTS_SessionUser'])) {
  echo '<h1>SESSION HAS TIMED OUT.</h1>';
  exit;
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Last 50 Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>

<h3>Last 50 Calls Report  
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;&nbsp;
<a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>  
<div id=help>
<p class="hidden-print">This report will provide a listing of the last 50 calls logged into the database sorted in revers chronological order with the newest call at the top.</p>
<p class="hidden-print">NOTE:  All report links open the call in a format ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>
</div>
<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';

$sql = "SELECT *  FROM `calls` WHERE `Status` != 'New' 
ORDER BY `DTOpened` DESC LIMIT 0,50;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<table class="table table-condensed table-hover">
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th><th>Resolution</th></tr>';
while ($r = $res->fetch_assoc()) {
	// echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr];
	echo "<tr onclick=\"window.location='callroview.php?action=button&call=$callnbr'\" style='cursor: pointer;'>
	<td align=\"center\">$callnbr</td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTOpened]</td><td>$r[DTPlaced]</td><td>$r[OpenedBy]</td><td>$r[Description]</td><td>$r[Resolution]</td></tr>";
	}
echo '</table>';
echo "=== END OF REPORT===<br>";
?>

</body>
</html>
