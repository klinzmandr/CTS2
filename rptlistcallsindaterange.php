<!DOCTYPE html>
<html>
<head>
<title>List Calls in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/datepicker3.css" rel="stylesheet">

</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

print <<<pagePart1
<h3>List Calls In Date Range&nbsp;&nbsp; <a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>

pagePart1;
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
print <<<inputForm

<form action="rptlistcallsindaterange.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="$sd"> and End Date:  
<input type="text" name="ed" id="ed" value="$ed">
<input type="submit" name="submit" value="Submit">
</form>

inputForm;

$today = date("Y-m-d 00:00:01",strtotime(now));
$sql = "SELECT * FROM `calls` 
WHERE `DTOpened` BETWEEN '$sd' AND '$ed'
ORDER BY `CallNbr` DESC;";
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$resarray = array(); $countarray = array();
$countarray[Total] = 0;$countarray[Open] = 0; $countarray[Closed] = 0; $countarray[Center] = 0;  
while ($r = $res->fetch_assoc()) {
	$resarray[$r[CallNbr]] = $r;
	$countarray[Total] += 1;
	if ($r[Status] == 'Open') $countarray[Open] += 1;
	if ($r[Status] == 'Closed') $countarray[Closed] += 1;
	if (strpos($r[Resolution],'Center') > 0) $countarray[Center] += 1;
	}
$cc = 'Call Counts for Period (Total/Open/Closed/ToCtr): ';
$cc .= $countarray[Total] . '/';
$cc .= $countarray[Open] . '/';
$cc .= $countarray[Closed] . '/';
$cc .= $countarray[Center] . '<br>';
echo $cc; 
//echo '<pre> year '; print_r($countarray); echo '</pre>';
echo '<table class="table table-condensed">
<tr><th>CallNbr</th><th>Status</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>';
foreach ($resarray as $r) {
	//echo '<pre> year '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr];
	echo "<tr><td align=\"center\">$callnbr</td>";
	//echo "<tr><td>$callnbr</td>";
	echo "<td>$r[Status]</td><td>$r[DTOpened]</td><td>$r[DTPlaced]</td><td>$r[OpenedBy]</td><td>$r[Description]</td></tr>";
	}
echo '</table>';
echo "=== END OF REPORT===<br>";

?>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="Incls/bootstrap-datepicker-range.inc.php"></script>

</body>
</html>
