<!DOCTYPE html>
<html>
<head>
<title>Report Calls for Today</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
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
<h3>Historical Call Summary Report &nbsp;&nbsp;   <a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<p>This report lists a summary of all calls entered since Jan 1, 2010.  The detailed report is available for download ONLY.</p>

pagePart1;
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
print <<<inputForm
<form action="rpthistoricalcalls.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="$sd"> and End Date:  
<input type="text" name="ed" id="ed" value="$ed">
<input type="submit" name="submit" value="Submit">
</form>

inputForm;

$allcalls = file("docs/CallSummaryReportPage.txt");   // read historical info
$allcallsarray = array();
$allcallsarray[] = rtrim($allcalls[0]); 
$hdr = array_shift($allcalls);
$recordcount = 0;
foreach ($allcalls as $l) {
  $f = explode(';',rtrim($l)); 
  $f[2] = date('m/d/y', strtotime($f[2]));
  $f[3] = date('m/d/y', strtotime($f[3]));
  $f[4] = date('m/d/y', strtotime($f[4]));
  if ((strtotime($f[2]) >= strtotime($sd)) && (strtotime($f[2]) <= strtotime($ed))) {
    $allcallsarray[$f[1]] = implode(';',$f);
    $recordcount++;
    }
  }
//echo '<pre> allcalls '; print_r($allcallsarray); echo '</pre>';
//  exit;
$sql = "SELECT * FROM `calls` 
WHERE `DTOpened` BETWEEN '$sd' AND '$ed'
ORDER BY `CallNbr` ASC;";
//  echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
//echo "SQL rc: $rc<br>";
while ($r = $res->fetch_assoc()) {
	//echo '<pre> year '; print_r($r); echo '</pre>';
	if ($r[DTOpened] != '') $r[DTOpened] = date('m/d/y', strtotime($r[DTOpened]));
  if ($r[DTPlaced] != '') $r[DTPlaced] = date('m/d/y', strtotime($r[DTPlaced]));
  if ($r[DTClosed] != '') $r[DTClosed] = date('m/d/y', strtotime($r[DTClosed]));
  $cn = sprintf("CT%05d", $r[CallNbr]);  
	$allcallsarray[$cn] = "$r[Status];$r[CallNbr];$r[DTOpened];$r[DTPlaced];$r[DTClosed];$r[AnimalLocation];$r[Disposition];$r[Property];$r[CallType];$r[Species];$r[Resolution];$r[TimeToResolve];$r[Postcare];$r[OpenedBy];$r[Reason];$r[CaseRefNbr];$r[TransHrs];$r[Transmi];$r[LastUpdater];$r[CallLocation]";
	$recordcount++;
	}
ksort($allcallsarray);
file_put_contents('docs/CallSummaryReport.csv', implode("\n", $allcallsarray));
//  echo '<pre> All calls '; print_r($allcallsarray); echo '</pre>';
echo "<a href=\"docs/CallSummaryReport.csv\" download=\"CallSummaryReport.csv\">DOWNLOAD CSV FILE</a>";
echo "&nbsp;&nbsp;<button type=\"button\" class=\"btn btn-xs btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Fields separated by semicolon(;)\nText fields are quoted.\"><span class=\"glyphicon glyphicon-info-sign\" style=\"color: blue; font-size: 20px\"></span></button>";

echo "<br>Total records in output: $recordcount<br><br>=== END OF REPORT===<br></div>";
//echo '<pre> All calls '; print_r($allcallsarray); echo '</pre>';
?>
<div class="container">
<h3>Heading Definitions</h3>
<table class="table table-condensed">
<tr><th>Heading</th><th>Definition</th><th>CTS</th><th>CTS2</th></tr>
<tr><td>Status</td><td>Status of Call (Open vs Closed)</td><td>X</td><td>X</td></tr>
<tr><td>CallNbr</td><td>Unique call number (CR = CTS, CT = CTS2)</td><td>X</td><td>X</td></tr>
<tr><td>DTOpened</td><td>Date call opened</td><td>X</td><td>X</td></tr>
<tr><td>DTPlaced</td><td>Date call placed (usually the same as prev. col)</td><td>X</td><td>X</td></tr>
<tr><td>DTClosed</td><td>Date call closed</td><td>X</td><td>X</td></tr>
<tr><td>AnimalLocation</td><td>Location (or approx. location) that the animal was picked up</td><td>X</td><td>X</td></tr>
<tr><td>Disposition</td><td>Disposition of case (assigned by Center)</td><td></td><td></td></tr>
<tr><td>Property</td><td>Type of property the animal was found on (e.g. business, private, city, state, etc.)</td><td>X</td><td>X</td></tr>
<tr><td>CallType</td><td>Nature of the call (i.e. informational, educational, report of injury, etc)</td><td>X</td><td>X</td></tr>
<tr><td>Species</td><td>Caller reported species type</td><td>X</td><td>X</td></tr>
<tr><td>Resolution</td><td>Outcome of the call (i.e. Delivered to center, No Action, Call Referred, etc.)</td><td>X</td><td>X</td></tr>
<tr><td>TimeTaken</td><td>Approximate total time to handle call</td><td>X</td><td>X</td></tr>
<tr><td>PostCard</td><td>Was a postcard sent (Yes/No)</td><td>X</td><td>X</td></tr>
<tr><td>OpenedBy</td><td>Id of person opening the call</td><td>X</td><td>X</td></tr>
<tr><td>Reason</td><td>Reported reason call was placed by caller</td><td>S</td><td>X</td></tr>
<tr><td>CtrNbr</td><td>Case reference number assign at the Center</td><td>X</td><td>X</td></tr>
<tr><td>TransHrs</td><td>Transporter hours used to pick up case</td><td>X</td><td></td></tr>
<tr><td>TransMi</td><td>Mileage reported as driven by transporter</td><td>X</td><td></td></tr>
<tr><td>LastUpdater</td><td>Id of person last updating the call</td><td>X</td><td>X</td></tr>
<tr><td>CallLocation</td><td>Location of caller when placing the call (vs animal location)</td><td>X</td><td>X</td></tr>
<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
</body>
</html>

