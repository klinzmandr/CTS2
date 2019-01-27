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
<title>Report Calls for Today</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>

<h3>Report Calls For Today &nbsp;&nbsp;&nbsp;
<a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a>
</h3>
<?php
include 'Incls/datautils.inc.php';
//include 'Incls/mainmenu.inc.php';
//include 'Incls/seccheck.inc.php';

$today = date("Y-m-d 00:00:01",strtotime(now));
$sql = "SELECT * FROM `calls` 
WHERE `DTOpened` >= '$today'
ORDER BY `CallNbr` DESC;";
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<table class="table table-condensed">
<tr><th>CallNbr</th><th>Status</th><th>D/T Opened</th><th>D/T Placed</th><th>OpenedBy</th><th>Description</th><th>Resolution</th></tr>';
while ($r = $res->fetch_assoc()) {
	//echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr];
	echo "<tr onclick=\"window.location='callroview.php?action=button&call=$callnbr'\" style='cursor: pointer;'><td>$callnbr</td>";
	// echo "<tr><td><a href=\"callroview.php?action=button&call=$callnbr\">$callnbr</a></td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTOpened]</td><td>$r[DTPlaced]</td><td>$r[OpenedBy]</td><td>$r[Description]</td><td>$r[Resolution]</td></tr>";
	}
echo '</table>';
echo "=== END OF REPORT===<br>";

?>

</body>
</html>
