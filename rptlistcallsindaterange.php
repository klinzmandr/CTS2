<?php
session_start();

if (!isset($_SESSION['CTS_SessionUser'])) {
  echo '<h1>SESSION HAS TIMED OUT.</h1>';
  echo '<h3 style="color: red; "><a href="indexsto.php">Log in again</a></h3>';
  exit;
  }

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-d', strtotime('now'));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime('now'));

?>
<!DOCTYPE html>
<html>
<head>
<title>List Calls in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">

</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<script src="js/bootstrap-sortable.js"></script>

<script>
$(function() {
// adds sign in sorted col header
$.bootstrapSortable({ sign: 'AZ' })
});
</script>

<h3>List Calls In Date Range
<span id="helpbtn" title="Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>

<form action="rptlistcallsindaterange.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit">
</form>
<div id=help>
<h3>Report Explanation</h3>
<p>This report will list all calls within the start and end date range entered.  By default the current month. The default start date is the current date and the default end date is the last day of the month.  By default this will provide a listing of the calls for the current day.</p>
<p>Each call in the date range is listed.  Clicking the corresponding line of a call will display the call in a printer friendly page.</p>
<p>A one line summary is also created to provide a total count of the total number of calls created in the date range, the current count of open calls, the current count of closed calls and the total number of cases delivered to the Center.</p>
</div>
<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';

$sdhms = date("Y-m-d 00:01",strtotime($sd));
$edhms = date("Y-m-d 23:59",strtotime($ed));
$sql = "SELECT * FROM `calls` 
WHERE `Status` != 'New' 
  AND `DTOpened` BETWEEN '$sdhms' AND '$edhms'
ORDER BY `CallNbr` DESC;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// echo "rc: $rc<br>";
$resarray = array(); $countarray = array();
$countarray['Total'] = 0;$countarray['Open'] = 0; $countarray['Closed'] = 0; $countarray['Center'] = 0;  
while ($r = $res->fetch_assoc()) {
	$resarray[$r['CallNbr']] = $r;
	$countarray['Total'] += 1;
	if ($r['Status'] == 'Open') $countarray['Open'] += 1;
	if ($r['Status'] == 'Closed') $countarray['Closed'] += 1;
	if (strpos($r['Resolution'],'Center') > 0) $countarray['Center'] += 1;
	}
$cc = 'Call Counts for Period (Total/Open/Closed/ToCtr): ';
$cc .= $countarray['Total'] . '/';
$cc .= $countarray['Open'] . '/';
$cc .= $countarray['Closed'] . '/';
$cc .= $countarray['Center'] . '<br>';
echo $cc; 
//echo '<pre> year '; print_r($countarray); echo '</pre>';
echo '<table class="sortable table table-condensed"><thead>
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th><th>Resolution</th></tr></thead><tbody>';
foreach ($resarray as $r) {
	// echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r['CallNbr'];
	echo 
"<tr onclick=\"window.location='callroview.php?action=button&call=$callnbr'\" style='cursor: pointer;'>
<td align=\"center\">$callnbr</td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTOpened]</td><td>$r[DTPlaced]</td><td>$r[OpenedBy]</td><td>$r[Description]</td><td>$r[Resolution]</td></tr>";
	}
echo '</tbody></table>';
echo "=== END OF REPORT===<br>";

?>

</body>
</html>
