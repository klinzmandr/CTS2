<!DOCTYPE html>
<html>
<head>
<title>Call Archive Query</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<h3>Historical Call Summary Report &nbsp;&nbsp;<a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<button id=helpbtn class="btn btn-primary btn-xs">HELP</button>

<?php
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
?>

<form action="rptcallsarchive.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit">
</form>

<div id=help class="container">
<h3>Explaination</h3>
<p>This report extracts and summarizes all calls within the date range specified.  The default date range is for the previous month.</p>
<p>Calls are summarized based on the options selected.</p>
<h3>Heading Definitions</h3>
<table class="table table-condensed">
<tr><th>Heading</th><th>Field Definition</th><th>CTS</th><th>CTS2</th></tr>
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

<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$sql = "SELECT * FROM `calls` `callsarchive`
WHERE `DTOpened` BETWEEN '$sd' and '$ed'
ORDER BY `DTOpened` ASC;";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
//echo "SQL rc: $rc<br>";
while ($r = $res->fetch_assoc()) {
  echo '<pre>'; print_r($r); echo '</pre>';
  
  }

?>
==== END OF REPORT ====<br>
</body>
</html>

