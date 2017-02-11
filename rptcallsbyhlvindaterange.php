<!DOCTYPE html>
<html>
<head>
<title>List Calls in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">

</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="Incls/bootstrap-datepicker-range.inc.php"></script>

<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

print <<<pagePart1
<div class="container">
<h3>List Calls In Date Range&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>

pagePart1;
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
print <<<inputForm

<form action="rptcallsbyhlvindaterange.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="$sd"> and End Date:  
<input type="text" name="ed" id="ed" value="$ed">
<input class="hidden-print" type="submit" name="submit" value="Submit">
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

$hlvarray = array();
foreach ($resarray as $r) {
  $hlvarray[$r[OpenedBy]][count] += 1;
  $datems = strtotime($r[DTOpened]);
  if ((!isset($hlvarray[$r[OpenedBy]][first])) OR ($hlvarray[$r[OpenedBy]][first] < $datems)) {
    list($hlvarray[$r[OpenedBy]][first], $x) = explode(' ', $r[DTOpened]); 
    //echo 'first DTOpened: '. $r[DTOpened] . " datems: $datems<br>"; 
    }
  if ((!isset($hlvarray[$r[OpenedBy]][last])) OR ($hlvarray[$r[OpenedBy]][last] > $datems)) {
    list($hlvarray[$r[OpenedBy]][last], $x) = explode(' ', $r[DTOpened]);
    //echo 'hlv: ' . $r[OpenedBy] . ' last DTOpened: '. $r[DTOpened] . " datems: $datems<br>"; 
    }
    
  }
// echo '<pre> hlv '; print_r($hlvarray); echo '</pre>';

echo '<table class="table table-condensed">
<tr><th>HLV Id</th><th>Call Count</th><th>Earliest Opened</th><th>Last Opened</th></tr>';
foreach ($hlvarray as $k => $r) {
	echo '<td>'.$k.'</td><td>'.$r[count].'</td><td>'.$r[first].'</td><td>'.$r[last].'</td></tr>';
	}
echo '</table>';
echo "=== END OF REPORT===<br>";

?>
</div>
</body>
</html>
