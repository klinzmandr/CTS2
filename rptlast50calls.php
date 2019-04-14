<?php
session_start();
if (!isset($_SESSION['CTS_SessionUser'])) {
  echo '<h1>SESSION HAS TIMED OUT.</h1>';
  echo '<h3 style="color: red; "><a href="indexsto.php">Log in again</a></h3>';
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
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>

<h3>Last 50 Calls Report  
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;&nbsp;
<a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>  
<div id=help class="hidden-print">
<p>This report will provide a listing of the last 50 calls logged into the database sorted in revers chronological order with the newest call at the top.</p>
<p>All report links open the call in a format ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>
<p>NOTE: columns marked <b style='color:red;'>RED</b> are sortable.</p>
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
echo '<table class="table table-condensed table-hover sortable"><thead>
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th>
<th data-defaultsort=disabled>Description</th>
<th>Resolution</th></tr></thead><tbody>';
while ($r = $res->fetch_assoc()) {
	// echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r['CallNbr'];
	echo "<tr onclick=\"window.location='callroview.php?action=button&call=$callnbr'\" style='cursor: pointer;'>
	<td align=\"center\">$callnbr</td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTPlaced]</td><td>$r[DTOpened]</td><td>$r[OpenedBy]</td><td>$r[Description]</td><td>$r[Resolution]</td></tr>";
	}
echo '</tbody></table>';
echo "=== END OF REPORT===<br>";
?>

</body>
</html>
