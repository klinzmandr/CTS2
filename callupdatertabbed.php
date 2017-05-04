<!DOCTYPE html>
<html>
<head>
<title>Call Update</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
</head>
<!--<body onload="initSelects(this)" onchange="flagChange()">-->
<!-- <body onchange="flagChange()"> -->
<body>
<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="./js/bootstrap-datetimepicker.min.js"></script>
<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// apply any fields updated to call record
if ($action == 'update') {
// read call record
  $sessionuser = $_SESSION['CTS_SessionUser'];
  $sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
  $res = doSQLsubmitted($sql);
  $r = $res->fetch_assoc();
//  echo '<pre>DB record '; print_r($r); echo'</pre>';  
	$notearray = array();  $vararray = array();
	$uri = $_SERVER['QUERY_STRING'];
	parse_str($uri, $vararray);
//	echo '<pre> input var '; print_r($vararray); echo '</pre>';
	unset($vararray[action]);
	unset($vararray[submit]);
	if (strlen($vararray[notes]) <= 4) { $vararray[notes] = 'Call updated'; }
	$notearray[CallNbr] = $callnbr;
	$notearray[UserID] = $_SESSION['CTS_SessionUser'];
	$notearray[Notes] = '';
// add any changes to name, phone number of email address to call log record 
  if ($r[Name] != $vararray[Name]) $notearray[Notes] .= '<br>Name: '.$vararray[Name];
  if ($r[EMail] != $vararray[EMail]) $notearray[Notes] .= '<br>Email: '.$vararray[EMail];
  if ($r[PrimaryPhone] != $vararray[PrimaryPhone])
      $notearray[Notes] .= '<br>Phone: '.$vararray[PrimaryPhone].'<br>';
  if ($r['CaseRefNbr'] != $vararray['CaseRefNbr']) 
      $notearray[Notes] .= '<br>WRMD Ref. Nbr: '.$vararray['CaseRefNbr'].'<br>';
  $notearray[Notes] .= '<br>'.$vararray[notes].'<br>';
//	echo '<pre> new note '; print_r($notearray); echo '</pre>';
// add new call log records
	sqlinsert("callslog", $notearray);
	unset($notearray);
  unset($vararray[notes]);
	
// now write updates to the call itself	
	$vararray[LastUpdater] = $_SESSION['CTS_SessionUser'];
	$cszarray = explode(',',$r[City]);
	if (count($cszarray) == 3) {
    $r[City] = $cszarray[0]; $r[State] = $cszarray[1]; $r[Zip] = $cszarray[2];
    }
	$where = "`CallNbr`='" . $callnbr . "'";
//echo '<pre> sql '; print_r($where); echo '<br> vararray ';print_r($vararray); echo '</pre>';
	sqlupdate('calls',$vararray, $where);
	
  echo '  
<script>
$(document).ready(function() {
  $("#X").fadeOut(2000);
});
</script>
<h3 style="color: red; " id="X">Update Completed.</h3>';

	$action = 'view';
	}
//echo '<pre>Notes  '; print_r($notearray); echo'</pre>';

// read call record with updates if there were any
$sessionuser = $_SESSION['CTS_SessionUser'];
$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
if ($action == 'new') {
	$sql = "SELECT * FROM `calls` WHERE `Status` = 'New' AND `OpenedBy` = '$sessionuser';";
	}
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();

// parse record fields into page
//echo '<pre>DB record '; print_r($r); echo'</pre>';
$callnbr = $r[CallNbr];
$status = $r[Status]; 
if ($status == 'New') $status = 'Open';
$dtplaced = $r[DTPlaced]; $dtopened = $r[DTOpened]; $dtclosed = $r[DTClosed]; 
$animallocation = $r[AnimalLocation]; $calllocation = $r[CallLocation]; 
$property = $r[Property]; $species = $r[Species]; 
$reason = $r[Reason]; $resolution = $r[Resolution];
$timetoresolve = $r[TimeToResolve]; $postcard  = $r[Postcard]; $openedby = $r[OpenedBy];
$reason = $r[Reason]; $lastlupdater = $r[LastUpdater]; 
$org = $r[Organization]; $name = $r[Name]; $address=$r[Address];
$city = $r[City]; $state = $r[State]; $zip = $r[Zip]; 
$primaryphone = $r[PrimaryPhone]; 
$email = $r[EMail]; $crn = $r['CaseRefNbr'];
$description = $r[Description];
$pcsent = $r[PostcardSent]; $emsent = $r[EmailSent];

if ($action == 'new') {
//	echo 'add initial log history record';
	$notearray[CallNbr] = $callnbr;
	$notearray[UserID] = $_SESSION['CTS_SessionUser'];
	$notearray[Notes] = 'Call Opened';
//	echo '<pre> note '; print_r($notearray); echo '</pre>';
	sqlinsert("callslog", $notearray);
}
?>
<script type="text/javascript">
// set up select lists
$(document).ready(function () { 
	//alert("first the inline function");
	$("#AL").val("<?=$animallocation?>");
	$("#CL").val("<?=$calllocation?>");
	$("#PT").val("<?=$property?>");
	$("#SP").val("<?=$species?>");
	$("#RE").val("<?=$reason?>");
	});
</script>
<script>
function chkdtp() {
	var dtp = $("#DP1").val();
	if (dtp.length == 0) {
		alert("Entry for Date/Time Placed is required.");
		return false;
		}
	if (chkEMAddr() == false) {
    return false;
    }
  if (checkPhone() == false) {
    return false;
    } 
	return true;
	}
</script>

<?php
echo '
<div class="container">
<h3>Call '.$callnbr.'&nbsp;&nbsp;&nbsp;<a href="callroview.php?call='.$callnbr.'">
<span title="Print View" class="glyphicon glyphicon-print" style="color: blue; font-size: 20px"></span></a></h3>';

?>
<!-- define the form -->
<form class="form" id="tf" name="tf" action="callupdatertabbed.php" onsubmit="return chkdtp()">
<input type="hidden" name="action" value="update">
<input type="hidden" name="callnbr" value="<?=$callnbr?>">

Date/Time Call Entered:&nbsp;&nbsp;<?=$dtopened?>&nbsp;&nbsp;&nbsp;
Date/Time Call Placed:&nbsp;&nbsp;<input type="text" id="DP1" name="DTPlaced" value="<?=$dtplaced?>" style="width: 150px; height: 25px;"><br>

Caller Name:<input autofocus type="text" name="Name" placeholder="Caller Name" value="<?=$name?>" />
Phone: <input id="PN" onblur="return checkPhone()" type="text" name="PrimaryPhone" value="<?=$primaryphone?>" size="12" maxlength="12" placeholder="Phone Number" />

<script type="text/javascript">
$('#DP1').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    todayHighlight: true,
    // todayBtn: true,
    showMeridian: true,
    autoclose: true
});
</script>
<script>
function checkPhone() {
//alert("validation entered");
if ($("#PN").val().length == 0) {
  $("#PN").attr("style","background-color:white;");
  return true;
  }
var fld = $("#PN").val();
var errmsg = "";
var stripped = fld.replace(/[a-zA-z\(\)\.\-\ \/]/g, '');
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
	//fld.attr().background = 'Pink';
	$("#PN").attr("style","background-color:pink;");
	alert(errmsg);
	return false;
	}
var newval = stripped.substr(0,3) + "-" + stripped.substr(3,3) + "-" + stripped.substr(6,4);
//fld.value = newval;
$("#PN").val(newval);
$("#PN").attr("style","background-color:white;");
return true;
}
</script>

<script>
function checkemail() {
	var sval = $("#EM").val();
	if (sval == "") {
		alert("ERROR: No email address provided");		
		return false;
		}
	return true;
	}
</script>
<script type="text/javascript">
function chkEMAddr() {
  $("#EM").attr("style","background-color:white;");
  var em = $("#EM").val();
  if (em.length == 0) return true;
  var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
  var tst = pattern.test(em);
  if (tst == true) return true;
  $("#EM").attr("style","background-color:pink;");
  alert("Invalid email address has been entered.");
  return false;
  }
</script>    
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
//	bkLib.onDomLoaded(function() { nicEditors.allTextAreas(); initSelects(this) });
bkLib.onDomLoaded(function() {
  new nicEditor({fullPanel:true}).panelInstance("area1");
  });    
</script>

E-mail: <input type="text" name="EMail" value="<?=$email?>" id="EM" onblur="return chkEMAddr()" placeholder="Email Address">
<?php
echo '
<a href="emailsend.php?emadr='.$email.'&callnbr='.$callnbr.'&name='.$name.'&crn='.$crn.'" onclick="return checkemail()">';
?>
<span class="glyphicon glyphicon-envelope" style="color: blue; font-size: 20px">
</span></a>
<br />

Call Description:<input type="text" name="Description" value="<?=$description?>" size="60"  description="" />
<br />
Additional Notes: (check History for prior note entries)<br />
<textarea id="area1" name="notes" rows="5" cols="90"></textarea>
<input type="hidden" name="Status" value="<?=$status?>">
<input type="hidden" name="OpenedBy" value="<?=$openedby?>">

<!-- call details tab -->
<table class="table table-condensed" border=1><tr><td>
<table class="table-condnensed">
<tr><td>Animal Location:</td><td>
<select id="AL" name="AnimalLocation" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select></td></tr><tr><td>Call Location:</td><td>
<select id="CL" name="CallLocation" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select></td></tr><tr><td>Property:</td><td>
<select id="PT" name="Property" size="1">
<option value=""></option>
<?php loaddbselect("Properties"); ?>
</select></td></tr><tr><td>Species:</td><td>
<select id="SP" name="Species" size="1">
<option value=""></option>
<?php loaddbselect("Species"); ?>
</select></td></tr><tr><td>Call Reason:</td><td>
<select id="RE" name="Reason" size="1">
<option value=""></option>
<?php loaddbselect("Reasons"); ?>
</select>
</td>
</table>
</td>

<!-- <input class="btn btn-success" type="submit" name="submit" value="Update Call" /><hr> -->
<div align="center">
<br><button class="btn btn-success" form="tf" /><b>Update Call</b></button></div><br>

<?php $citieslist = createddown(); ?>

<!-- caller extended details tab -->
<script>
function loadcity() {
//	alert("loadcity");
	var cv = $("#CI").val();
	var cva = cv.split(",");
	$("#CI").val(cva[0]);
	$("#ST").val(cva[1]);
	$("#ZI").val(cva[2]);
	}
</script>
<td valign="top">
WRMD Number: <input type="text" name="CaseRefNbr" value="<?=$crn?>" maxlength="8" id="CRN"><br>
Organization: <input type="text" name="Organization" size="50" placeholder="Organization" value="<?=$org?>"><br>
Address:<input id="PC" type="text" name="Address" size="50" placeholder="Address Line" value="<?=$address?>"><br />
City:<input id="CI" data-provide="typeahead" data-items="4" type="text" name="City" placeholder="City" value="<?=$city?>" autocomplete="off" onblur="loadcity()" />, 
State:<input id="ST" type="text" name="State" size="2" maxlength="2" value="<?=$state?>"/>  
Zip: <input id="ZI" type="text" name="Zip" size="5" maxlength="10" value="<?=$zip?>"/>
<button href="#myZipModal" data-toggle="modal" data-keyboard="true" type="button" class="btn btn-xs btn-default" data-placement="top" title="Zip Code List"><span class="glyphicon glyphicon-list" style="color: blue; font-size: 20px"></span></button>
<br>
<script>
function checkaddr() {
	var sval = $("#PC").val();
if ( sval.length == 0) {
		alert("ERROR: No address provided to send postcard.")
		$('#PCChk').prop('checked', false);
		return false;
		}
	return true;
	}
</script>
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

<?php
if ($pcsent == '') {
	echo 'Postcard Sent? <input id="PCChk" onchange="return checkaddr()" type="checkbox" name="PostcardSent" Value="Yes">'; }
else {
	echo "Postcard Sent? $pcsent "; }
if ($emsent == '') {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? No<br><br>"; }
else {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? $emsent<br>"; }
?>

<script src="js/bootstrap3-typeahead.js"></script>
<script>
var citylist = <?=$citieslist?>
$('#CI').typeahead({source: citylist})
</script>
</td></tr></table>

<!-- <input type="submit" name="submit" value="Update Call" /><hr> -->
<div align="center"><button class="btn btn-success" form="tf" /><b>Update Call</b></button></div><hr>
</form>

<!-- output the history log -->
<div class="page-break"></div> <!-- insert page break for print of page -->
<h4>Call Notes History (latest first)</h4>
<table class=\"table-condensed\">
<?php
$sql = "SELECT * FROM `callslog` 
WHERE `CallNbr` =  '$callnbr' 
ORDER BY `SeqNbr` DESC;";
$res = doSQLsubmitted($sql);

while ($r = $res->fetch_assoc()) {
	//echo '<pre> notes '; print_r($r); echo '</pre>';
	$dt = date('Y-m-d \a\t H:i',strtotime($r[DateTime]));
	echo "<tr><td>DateTime: $dt&nbsp;&nbsp;By: $r[UserID]<br><ul>$r[Notes]</ul></td></tr>";
	}
?>
</table>
</div>  <!-- container -->';

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

<?php
// php function to read db locations table and return it
function createddown() {
	$locs = readdblist('Locations');
	$locsarray = formatdbrec($locs);
	//echo '<pre> location list '; print_r($locsarray); echo '</pre>';
	$locslist = "['" . implode("','", $locsarray) . "'];";
	//echo $locslist;
	return($locslist);
}
?>

</body>
</html>
