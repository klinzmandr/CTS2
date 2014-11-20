<!DOCTYPE html>
<html>
<head>
<title>Call Update</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<!--<body onload="initSelects(this)" onchange="flagChange()">-->
<body onchange="flagChange()">

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();
//include 'Incls/vardump.inc';
include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';
include 'Incls/datautils.inc';

$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// apply any fields updated to call record
if ($action == 'update') {
	$notearray = array();  $vararray = array();
	//echo 'Update action requested.';
	$uri = $_SERVER['QUERY_STRING'];
	parse_str($uri, $vararray);
	//echo '<pre> var '; print_r($vararray); echo '</pre>';
	unset($vararray[action]);
	unset($vararray[submit]);
	if (strlen($vararray[notes]) > 4) {
		$notearray[CallNbr] = $callnbr;
		$notearray[UserID] = $_SESSION['SessionUser'];
		$notearray[Notes] = $vararray[notes];
		//echo '<pre> note '; print_r($notearray); echo '</pre>';
		sqlinsert("callslog", $notearray);
		unset($notearray);
		}
	unset($vararray[notes]);
	$vararray[LastUpdater] = $_SESSION['SessionUser']; 
	$where = "`CallNbr`='" . $callnbr . "'";
	//echo '<pre> sql '; print_r($where); echo '<br> vararray ';print_r($vararray); echo '</pre>';
	sqlupdate('calls',$vararray, $where);
	$action = 'view';
	}
//echo '<pre>Notes  '; print_r($notearray); echo'</pre>';

// read call and display
$sessionuser = $_SESSION['SessionUser'];
$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
if ($action == 'new') {
	$sql = "SELECT * FROM `calls` WHERE `Status` = 'New' AND `OpenedBy` = '$sessionuser';";
	}
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();
//echo '<pre>DB record '; print_r($r); echo'</pre>';
$callnbr = $r[CallNbr];
$status = $r[Status]; 
if ($status == 'New') $status = 'Open';
$dtopened = $r[DTOpened]; $dtclosed = $r[DTClosed]; $animallocation = $r[AnimalLocation];
$calllocation = $r[CallLocation]; $property = $r[Property]; $species = $r[Species]; 
$reason = $r[Reason]; $resolution = $r[Resolution];
$timetoresolve = $r[TimeToResolve]; $postcard  = $r[Postcard]; $openedby = $r[OpenedBy];
$reason = $r[Reason]; $lastlupdater = $r[LastUpdater]; 
$org = $r[Organization]; $name = $r[Name]; $address=$r[Address];
$city = $r[City]; $state = $r[State]; $zip = $r[Zip]; 
$primaryphone = $r[PrimaryPhone]; 
$email = $r[EMail];
$description = $r[Description];

if ($action == 'new') {
//	echo 'add initial log history record';
	$notearray[CallNbr] = $callnbr;
	$notearray[UserID] = $_SESSION['SessionUser'];
	$notearray[Notes] = 'Call Opened';
//	echo '<pre> note '; print_r($notearray); echo '</pre>';
	sqlinsert("callslog", $notearray);
}

print<<<scriptPart
<script type="text/javascript">
// Runs two functions - on inline defined, the second a stand alone
$(document).ready(function () { 
	//alert("first the inline function");
	$("#AL").val("$animallocation");
	$("#CL").val("$calllocation");
	$("#PT").val("$property");
	$("#SP").val("$species");
	$("#RE").val("$reason");
	});
</script>

scriptPart;

print <<<pagePart1
<div class="container">
<h3>Call $callnbr</h3>
<form class="form" name="tf" action="callupdatertabbed.php">
<input type="hidden" name="action" value="update">
<input type="hidden" name="callnbr" value="$callnbr">
<ul id="myTab" class="nav nav-tabs">
  <li class="active"><a href="#info" data-toggle="tab">Call</a></li>
  <li class=""><a href="#details" data-toggle="tab">Details</a></li>
  <li class=""><a href="#callerext" data-toggle="tab">Caller Extended</a></li>
  <li class=""><a href="#history" data-toggle="tab">History</a></li>
  <li class=""><a href="callroview.php?call=$callnbr"><span title="Print View" class="glyphicon glyphicon-print" style="color: blue; font-size: 20px"></span></a></li></a></li>
</ul>

<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="info">
Date Call Entered:&nbsp;&nbsp;$dtopened
<!-- <input type="text" name="DTOpened" value="$dtopened" size="10" maxlength="10"  placeholder="Date" /> -->
<br />
<script>
function checkphone(fld) {
//alert("validation entered");
var errmsg = "";
var stripped = fld.value.replace(/[\(\)\.\-\ \/]/g, '');
if (stripped.length == 0) {
	fld.style.background = 'White';
	return true;
	}
if (stripped.length == 7)
	stripped = "805" + stripped;
if (stripped.length != 10) { 
	errmsg += "Invalid phone number.  Please include the Area Code.\\n";
	}
if(!stripped.match(/^[0-9]{10}/))  { 
	errmsg += "Value entered not 7 or 10 digits OR a non-numeric character entered.\\n";
	}
if (errmsg.length > 0) {
	errmsg += "\\nValid formats: 123-456-7890 or 123 456 7890 or 123-456-7890 or 1234567890";
	fld.style.background = 'Pink';
	alert(errmsg);
	return false;
	}
var newval = stripped.substr(0,3) + "-" + stripped.substr(3,3) + "-" + stripped.substr(6,4);
fld.value = newval;
fld.style.background = 'White';
return true;
}
</script>

Date Call Closed: $dtclosed<br />
Caller Name:<input autofocus type="text" name="Name" placeholder="Caller Name" value="$name" />
Phone: <input id="PN" onchange="return checkphone(this)" type="text" name="PrimaryPhone" value="$primaryphone" size="12" maxlength="12" placeholder="Phone Number" />
<script>
function checkemail() {
	//var sval = $("#EM").val().length;
	var sval = "$email";
	var len = sval.length;
	if (sval == 0) {
		alert("ERROR: No email address provided");		
		return false;
		}
	return true;
	}
</script>

E-mail: <input type="text" name="EMail" value="$email" id="EM" placeholder="Email Address">
<a href="emailsend.php?emadr=$email&callnbr=$callnbr" onclick="return checkemail()">
<span class="glyphicon glyphicon-envelope" style="color: blue; font-size: 20px">
</span></a>
<br />

Call Description:<input type="text" name="Description" value="$description" size="60"  description="" /><br />
<b>New</b> note: (check History for prior note entries)<br />
<textarea name="notes" rows="5" cols="80"></textarea>
<br /><br />
<input type="hidden" name="Status" value="$status">
<input type="hidden" name="OpenedBy" value="$openedby">
pagePart1;
echo '<input type="submit" name="submit" value="Update Info" />';
echo '</div>  <!-- tab-pane -->';
echo '<div class="tab-pane fade" id="details">
<table class="table-condnensed">';
echo '<tr><td>Animal Location:</td><td>
<select id="AL" name="AnimalLocation" size="1">
<option value=""></option>';
loaddbselect("Locations");
echo '</select></td></tr><tr><td>Call Location:</td><td>
<select id="CL" name="CallLocation" size="1">
<option value=""></option>';
loaddbselect("Locations");
echo '</select></td></tr><tr><td>Property:</td><td>
<select id="PT" name="Property" size="1">
<option value=""></option>';
loaddbselect("Properties");
echo '</select></td></tr><tr><td>Species:</td><td>
<select id="SP" name="Species" size="1">
<option value=""></option>';
loaddbselect("Species");
echo '</select></td></tr><tr><td>Call Reason:</td><td>
<select id="RE" name="Reason" size="1">
<option value=""></option>';
loaddbselect("Reasons");
echo '</select></td></tr>';

echo '</table>
<br /><br />';
echo '<input type="submit" name="submit" value="Update Details" />';
$citieslist = createddown();
if ($state == '') $state = 'CA';
print <<<pagePart3
</div>  <!-- tab-pane -->
<script>
function loadcity() {
	//alert("loadcity");
	var cv = $("#CI").val();
	var cva = cv.split(",");
	$("#CI").val(cva[0]);
	$("#ST").val(cva[1]);
	$("#ZI").val(cva[2]);
	}
</script>
<div class="tab-pane fade" id="callerext">
Organization: <input type="text" name="Organization" size="50" placeholder="Organization" value="$org"><br>
Address:<input type="text" name="Address" size="50" placeholder="Address Line" value="$address"><br />
City:<input id="CI" data-provide="typeahead" data-items="4" type="text" name="City" placeholder="City" value="$city" autocomplete="off" onblur="loadcity()" />, 
State:<input id="ST" type="text" name="State" size="2" maxlength="2" value="$state"/>  
Zip: <input id="ZI" type="text" name="Zip" size="5" maxlength="10" placeholder="Zip" value="$zip"/>
<button href="#myZipModal" data-toggle="modal" data-keyboard="true" type="button" class="btn btn-xs btn-default" data-placement="top" title="Zip Code List"><span class="glyphicon glyphicon-list" style="color: blue; font-size: 20px"></span></button>

<br />
<br>

<script src="js/bootstrap3-typeahead.js"></script>
<script>
var citylist = $citieslist
$('#CI').typeahead({source: citylist})
</script>

<script type="text/javascript" src="nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas(); initSelects(this) });
</script>

pagePart3;
echo '<input type="submit" name="submit" value="Update Extended" />';
echo '</form>';
createddown();
echo '
</div>  <!-- tab-pane -->

<div class="tab-pane fade" id="history">
<h4>Call Notes History (latest first)</h4>';
$sql = "SELECT * FROM `callslog` 
WHERE `UserID` = '$openedby' 
	AND `CallNbr` =  '$callnbr' 
ORDER BY `SeqNbr` DESC;";
$res = doSQLsubmitted($sql);
echo "<table class=\"table-condensed\">";
while ($r = $res->fetch_assoc()) {
	//echo '<pre> notes '; print_r($r); echo '</pre>';
	$dt = date('Y-m-d \a\t H:i',strtotime($r[DateTime]));
	echo "<tr><td>DateTime: $dt&nbsp;&nbsp;By: $r[UserID]<br><ul>$r[Notes]</ul></td></tr>";
	}
echo '</table></div>  <!-- tab-pane -->
</div>  <!-- tab-content -->
</div>  <!-- container -->';

print <<<theZipModal

<!-- =========== Zip Code Modal  ==================== -->  
<div class="modal fade" id="myZipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Cities and Zip Code List</h4>
      </div>
    <div class="modal-body">
   <center><h3>Cities and Zip Codes</h3></center>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="95%">
<tr><td>Arroyo Grande</td><td>93420</td><td>Oceano</td><td>93445</td></tr>
<tr><td>Atascadero</td><td>93422</td><td>Paso Robles</td><td>93446</td></tr>
<tr><td>Avila Beach</td><td>93424</td><td>Pismo Beach</td><td>93449</td></tr>
<tr><td>Cambria</td><td>93428</td><td>San Luis Obispo</td><td>93401</td></tr>
<tr><td>Cayucos</td><td>93430</td><td>San Luis Obispo</td><td>93405</td></tr>
<tr><td>Creston</td><td>93432</td><td>San Miguel</td><td>93451</td></tr>
<tr><td>Grover Beach</td><td>93433</td><td>Santa Margarita</td><td>93453</td></tr>
<tr><td>Guadalupe</td><td>93434</td><td>Santa Maria</td><td>93455</td></tr>
<tr><td>Los Osos</td><td>93402</td><td>Shandon</td><td>93461</td></tr>
<tr><td>Morro Bay</td><td>93442</td><td>Templeton</td><td>93465</td></tr>
<tr><td>Nipomo</td><td>93444</td></tr>
</table>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->

theZipModal;

function createddown() {
	$locs = readdblist('Locations');
	$locsarray = formatdbrec($locs);
	//echo '<pre> location list '; print_r($locsarray); echo '</pre>';
	$locslist = "['" . implode("','", $locsarray) . "'];";
	//echo $locslist;
	return($locslist);
}
?>
<script>
// synchronizes second select list with choice made from the first
$( "#AL" ).change(function() {
//alert("change seen");
var sval = $("#AL").val();
if ( sval.length ){
	//alert("A value for sel1 selected: " + sval);
	$("#CL").val(sval);
	return;
	}
alert("no value seen");
return;
});
</script>

</body>
</html>
