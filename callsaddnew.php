<?php
session_start();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<title>Add New Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php 
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

if ($action == '') { ?>
<div class="container">
<h3>Add New Call</h3>
<p>A new call will be added for <?=$_SESSION['CTS_SessionUser']?></p>
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
<?php
  exit; 
}

$addarray = array();
// action contains the type of new record to be added
// add in here the logic for the various call type presets
if ($action == 'new') {
	$addarray['AnimalLocation'] = '';
	$addarray['CallLocation'] = '';
	$addarray['Property'] = '';
	$addarray['Species'] = '';
	$addarray['Reason'] = '';
	$addarray['Organization'] = '';
	}
if ($action == 'odsrva') {
	$addarray['AnimalLocation'] = 'Oceano';
	$addarray['CallLocation'] = 'Oceano';
	$addarray['Property'] = 'State';
	$addarray['Species'] = 'Seabird';
	$addarray['Reason'] = 'AppearsSick';
	$addarray['Organization'] = 'ODSRVA Ranger Station';
	}
if ($action == 'cmc') {
	$addarray['AnimalLocation'] = 'SanLuisObispo93409';
	$addarray['CallLocation'] = 'SanLuisObispo93409';
	$addarray['Property'] = 'State';
	$addarray['Species'] = 'Seabird';
	$addarray['Reason'] = 'AppearsInjured';
	$addarray['Organization'] = 'CA Mens Colony';
	}
if ($action == 'ed') {
	$addarray['AnimalLocation'] = 'NA';
	$addarray['CallLocation'] = 'NA';
	$addarray['Property'] = 'NA';
	$addarray['Species'] = 'NA';
	$addarray['Reason'] = 'EdRequest';
	$addarray['Organization'] = '';
	}
if ($action == 'info') {
	$addarray['AnimalLocation'] = 'NA';
	$addarray['CallLocation'] = 'NA';
	$addarray['Property'] = 'NA';
	$addarray['Species'] = 'NA';
	$addarray['Reason'] = 'Info';
	$addarray['Organization'] = '';
	}
if ($action == 'na') {
	$addarray['AnimalLocation'] = 'NA';
	$addarray['CallLocation'] = 'NA';
	$addarray['Property'] = 'NA';
	$addarray['Species'] = 'NA';
	$addarray['Reason'] = 'Other';
	$addarray['Organization'] = '';
	}

// check to determine if a new records has been added but not used
$user = $_SESSION['CTS_SessionUser'];
$sql = "SELECT * FROM `calls` 
	WHERE `Status` = 'New'
	AND `OpenedBy` = '$user';";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

$nowdt = date('Y-m-d H:i', strtotime('now'));

$addarray['OpenedBy'] = $user;
$addarray['DTOpened'] = $nowdt;
$addarray['DTPlaced'] = '';
$notesdiary = '<ul>New call added</ul>';
$addarray['NotesDiary'] = "DateTime: $nowdt&nbsp;&nbsp;By: $user $notesdiary";
$addarray['Status'] = 'New';

$addarray['LastUpdater'] = $user;
$addarray['ResTOD'] = date("H:i", strtotime('now'));
$addarray['ResBy'] = $user;
$addarray['ResTelephone'] = $_SESSION['CTS_VolTelephone'];


//echo '<pre> addarray '; print_r($addarray); echo '</pre>';
if ($rc == 0) {							// nope - add a new record
//	echo 'inserting new record<br>';
	sqlinsert('calls', $addarray);
	addlogentry("New call inserted");
	}
else {											// one exists, update it instead
	$r = $res->fetch_assoc();
	$where = "CallNbr = '$r[CallNbr]'";
//	echo "where: $where<br>";
	sqlupdate('calls', $addarray, $where);
	addlogentry("New call existed");
	}
?>
<script>
$(function() {
  // alert("form load");
  $("#adder").submit();
});
</script>
<form id=adder action=callupdatertabbed.php>
<input type=hidden name=action value=new>
</form>

</body>
</html>
