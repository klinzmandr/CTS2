<!DOCTYPE html>
<html>
<head>
<title>Add New Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action == '') {
print <<<pagePart1
<div class="container">
<h3>Add New Call</h3>
<p>A new call will be added for $_SESSION[SessionUser]</p>
<p>Choose one of the following:</p>
<ul>
<a class="btn btn-success" href="callsaddnew.php?action=new">BLANK</a>&nbsp;
<a class="btn btn-success" href="callsaddnew.php?action=odsrva">ODSRVA</a>&nbsp;
<a class="btn btn-success" href="callsaddnew.php?action=cmc">CMC</a>&nbsp;
<a class="btn btn-success" href="callsaddnew.php?action=ed">Ed. Req.</a>&nbsp;
<a class="btn btn-success" href="callsaddnew.php?action=info">Info</a>&nbsp;
<a class="btn btn-success" href="callsaddnew.php?action=na">NA</a><br>
<br>
<h4>Preset Fields for:</h4>
<table border="1">
<!-- row 1 -->
<tr>
<td valign="top"><b>BLANK</b></td>
<td>
Animal Location: <br>
Call Location: <br>
Property: <br>
Species: <br>
Reason: 
</td>
<td>&nbsp;</td>
<td valign="top"><b>ODSRVA</b></td>
<td>
Animal Location: Oceano<br>
Call Location: Oceano<br>
Property: State<br>
Species: Seabird<br>
Reason: ApprearsSick<br>
Organization: ODSRVA Ranger Station
</td>
</tr>
<!-- row two -->
<tr>
<td valign="top"><b>CMC</b></td>
<td>
Animal Location: SanLuisObispo<br>
Call Location: SanLuisObispo<br>
Property: State<br>
Species: Seabird<br>
Reason: ApprearsInjured<br>
Organization: CA Mens Colony
</td>
<td>&nbsp;</td>
<td valign="top"><b>Ed. Request</b></td>
<td>
Animal Location: NA<br>
Call Location: NA<br>
Property: NA<br>
Species: NA<br>
Reason: Education Request
</td>
</tr>
<!-- row three -->
<tr>
<td valign="top"><b>Info</b></td>
<td>
Animal Location: NA<br>
Call Location: NA<br>
Property: NA<br>
Species: NA<br>
Reason: Info Request<br>
</td>
<td>&nbsp;</td>
<td valign="top"><b>NA</b></td>
<td>
Animal Location: NA<br>
Call Location: NA<br>
Property: NA<br>
Species: NA<br>
Reason: Other
</td>
</tr>
</table><br>
<p><b>Preset fields can be redefined when call is updated.</b></p>
</ul>
</div>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
pagePart1;
exit;
}
$addarray = array();
// action contains the type of new record to be added
// add in here the logic for the various call type presets
if ($action == 'odsrva') {
	$addarray[AnimalLocation] = 'Oceano';
	$addarray[CallLocation] = 'Oceano';
	$addarray[Property] = 'State';
	$addarray[Species] = 'Seabird';
	$addarray[Reason] = 'AppearsSick';
	$addarray[Organization] = 'ODSRVA Ranger Station';
	}
if ($action == 'cmc') {
	$addarray[AnimalLocation] = 'SanLuisObispo';
	$addarray[CallLocation] = 'SanLuisObispo';
	$addarray[Property] = 'State';
	$addarray[Species] = 'Seabird';
	$addarray[Reason] = 'AppearInjured';
	$addarray[Organization] = 'CA Mens Colony';
	}
if ($action == 'ed') {
	$addarray[AnimalLocation] = 'NA';
	$addarray[CallLocation] = 'NA';
	$addarray[Property] = 'NA';
	$addarray[Species] = 'NA';
	$addarray[Reason] = 'EdRequest';
	}
if ($action == 'info') {
	$addarray[AnimalLocation] = 'NA';
	$addarray[CallLocation] = 'NA';
	$addarray[Property] = 'NA';
	$addarray[Species] = 'NA';
	$addarray[Reason] = 'Info';
	}
if ($action == 'na') {
	$addarray[AnimalLocation] = 'NA';
	$addarray[CallLocation] = 'NA';
	$addarray[Property] = 'NA';
	$addarray[Species] = 'NA';
	$addarray[Reason] = 'Other';
	}

// check to determine if a new records has been added but not used
$currentuser = $_SESSION['SessionUser'];
$sql = "SELECT * FROM `calls` 
	WHERE `Status` = 'New' 
	AND `OpenedBy` = '$currentuser';";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

$addarray[OpenedBy] = $_SESSION['SessionUser'];
$addarray[DTOpened] = date('Y-m-d H:i', strtotime(now));
$addarray[DTPlaced] = date('Y-m-d H:00', strtotime(now));
$addarray[Status] = 'New';
//echo '<pre> addarray '; print_r($addarray); echo '</pre>';
if ($rc == 0) {							// nope - add a new record
//	echo 'inserting new record<br>';
	sqlinsert('calls', $addarray);
	}
else {											// one exists, update it instead
	$r = $res->fetch_assoc();
	$where = "CallNbr = '$r[CallNbr]'";
//	echo "where: $where<br>";
	sqlupdate('calls', $addarray, $where);
	}

print <<<pagePart1
<div class="container">
<h3>New Call Added</h3>
<p>A new $action call has been added for $currentuser</p>

<p>Click the continue button to complete the details of the call.</p>

<a class="btn btn-success" href="callupdatertabbed.php?action=new">CONTINUE</a>&nbsp;
<br><br>
<div>
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
