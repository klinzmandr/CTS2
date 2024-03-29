<?php
session_start();
$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$flds = isset($_REQUEST['flds']) ? $_REQUEST['flds'] : '';
$notes = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : '';
$errs = isset($_REQUEST['errs']) ? $_REQUEST['errs'] : '';
$_SESSION['4log'] = $callnbr;
$user = $_SESSION['CTS_SessionUser'];
$seclevel = $_SESSION['CTS_SecLevel'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Call Update</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>

<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>
<script>
// report field
$(function() {
  var er = "<?=$errs?>";
  var errs = "<h3 style='color: #FF0000; '>Errors in call record needing attention:</h3><ul>" + er + '</ul>';
  // var errs = "<?=$errs?>";
  if (er.length) {
    // alert("errs: "+errs);
    $("#mm-modalBody").html(errs);
    $("#myModal").modal("show");
    errs = '';
    }
  
// block use of enter key on input form, shift focus to next field
$('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        var inputs = $(this).parents("form").eq(0).find(":input");
        if (inputs[inputs.index(this) + 1] != null) {
            inputs[inputs.index(this) + 1].focus();
        }
        e.preventDefault();
        return false;
    }
  });
});
// document ready function ============
var SecLevel = '<?=$seclevel?>';  // global var for sec level
$(document).ready(function() {
  $('#DP1').datetimepicker({
      format: 'yyyy-mm-dd hh:ii',
      todayHighlight: true,
      todayBtn: true,
      showMeridian: true,
      autoclose: true
    });
  $("#DP1").focus(function() {
    $("#DP1").datetimepicker("show");
    });
  var dp1 = $("#DP1").val();
  if (dp1.length <= 0) {      // demand date/time placed if empty
    $("#DP1").focus(); 
    }
  else {
    if (SecLevel != 'admin') 
      $("#DP1").prop("disabled", true); // lock field if present
                                        // unless an admin user
    }
  
$("#X").fadeOut(2000);
$("#RE").change(function() {
  const regex = /\*/g;    // check selection for an astrix
  var reason = $("#RE option:selected").text();
  if (regex.exec(reason) != null) {
    $("#mm-modalBody").html("<center><h2 style=\"color: red; \">A T T E N T I O N!</h2></center><font size=\"2\">Selection of this call reason requires that this incident be reported to the CA DFW.<br><br><b>Please contact the Center and report this call so that appropriate follow up steps can be done.</b><br><br>Enter a details in the Additional Notes field to document your actions.</font>");
    $("#myModal").modal("show");
    }
  });
  
$("#FC").click(function(e) {
  e.preventDefault();
  $('#tf').attr('action', 'callsfastcloser.php');
  $("#tf").submit();  
  });

});
</script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

// block attempt to update call with no session user
// caused when user does a 'back' button on browser after logout or timeout
if ($_SESSION['CTS_SessionUser'] == '') {
  addlogentry("callupdater is trying to update a call with no userid");
  echo '<h2>Session has timed out</h2>
  <h3 style="color: #FF0000; "><a href="indexsto.php">Log in again</a></h3>';
  exit;
  }

if ($action == 'sfadd') {
  $xmsg = '<h3 style="color: red; " id="X">Call '.$callnbr.' added</h3>';
	$action = 'view';
  }

$nowdt = date('Y-m-d H:i', strtotime('now')); 
$restod = date("H:i", strtotime('now'));

// apply any fields updated to call record
if ($action == 'update') {
// read call record
  $sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
  $res = doSQLsubmitted($sql);
  $r = $res->fetch_assoc();
  // echo '<pre>existing DB record '; print_r($r); echo'</pre>';
	if (strlen($notes) <= 4) { $notes = 'Call updated'; }
	$notes = str_replace("\n", "<br>", $notes);
  $notesdiary = '<ul>'.$notes.'</ul>'.$flds['NotesDiary']; 
  $flds['NotesDiary'] = "DateTime: $nowdt&nbsp;&nbsp;By: $user $notesdiary";  
	$flds['LastUpdater'] = $user;
    
	$where = "`CallNbr`='" . $callnbr . "'";
//  echo '<pre>sql '; print_r($where); echo '<br>flds ';print_r($flds); echo '</pre>';
	sqlupdate('calls',$flds, $where);

  $xmsg = '<h3 style="color: red; " id="X">Update Completed.</h3>';
	$action = 'view';
	}
//echo '<pre>Notes  '; print_r($notearray); echo'</pre>';

// read call record with updates if there were any
$sessionuser = $_SESSION['CTS_SessionUser'];
$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
if ($action == 'new') {
	$sql = "SELECT * FROM `calls` WHERE `Status` = 'New' AND `OpenedBy` = '$sessionuser';";
	// echo "new call by $sessionuser<br>";
	}
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();

// parse record fields into page
// echo '<pre>DB record '; print_r($r); echo'</pre>';
$callnbr = $r['CallNbr'];
$_SESSION['4log'] = $callnbr;

$status = $r['Status']; 
if ($status == 'New') $status = 'Open';
$dtplaced = $r['DTPlaced']; $dtopened = $r['DTOpened']; $dtclosed = $r['DTClosed']; 
$animallocation = $r['AnimalLocation']; $calllocation = $r['CallLocation']; 
$property = $r['Property']; $species = $r['Species']; 
$reason = $r['Reason']; $resolution = $r['Resolution'];
$timetoresolve = $r['TimeToResolve']; $postcard  = $r['Postcard']; $openedby = $r['OpenedBy'];
$reason = $r['Reason']; $lastlupdater = $r['LastUpdater']; 
$org = $r['Organization']; $name = $r['Name']; $address=$r['Address'];
$city = $r['City']; $state = $r['State']; $zip = $r['Zip']; 
$primaryphone = $r['PrimaryPhone']; 
$email = $r['EMail']; $crn = $r['CaseRefNbr'];
$description = htmlentities($r['Description']);
$pcsent = $r['PostcardSent']; $emsent = $r['EmailSent'];
$notesdiary = $r['NotesDiary'];

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
	$("#AT").val("<?=$resolution?>");
	$('input[type=radio][value=' + "'<?=$timetoresolve?>'" + ']').attr('checked', true);
  
$("#cinfo").click(function() {
  var msg = '<center><h3>Caller Info</h3></center><br><br><ul><pre>';
  var msgcb = "Education/Presentation Request:\n";
  msg += 'CT2 Call Number: ' + "<?=$callnbr?>\n";
  msgcb += 'CT2 Call Number: ' + "<?=$callnbr?>\n";
  msg += 'Date/Time of Call: ' + $("#DP1").val() + "\n";
  msgcb += 'Date/Time of Call: ' + $("#DP1").val() + "\n";
  msg += 'Caller Name: ' + $("#CN").val() + "\n";
  msgcb += 'Caller Name: ' + $("#CN").val() + "\n";
  msg += 'Caller Phone: ' + $("#PN").val() + "\n";
  msgcb += 'Caller Phone: ' + $("#PN").val() + "\n";
  msg += 'Caller Email: ' + $("#EM").val() + "\n";
  msgcb += 'Caller Email: ' + $("#EM").val() + "\n";
  msg += 'Call Description: ' + $("#CD").val() + "\n</pre></ul>";
  msgcb += 'Call Description: ' + $("#CD").val() + "\n";
  $("#mm-modalBody").html(msg);
  $("textarea").val(msgcb);
  $("textarea").select();
  // document.execCommand('copy');
  $("#myModal").modal("show");
  $("textarea").val('');
  });
});
</script>
<script>
function chkdtp() {
	var dtp = $("#DP1").val();
	// if field is diabled ignore date validity check
  if ($("#DP1").is(":disabled")) return true;
	if (dtp.length == 0) {
		alert("Entry for Date/Time Placed is required.");
		$("#DP1").focus();
		return false;
		}
	var dtpcurr = new Date(dtp).getTime() / 1000;
	// var dtprev = new Date('2018-12-31 23:59').getTime()/1000;
	var now = new Date();
	var dtprev = (now.getTime()/1000) - (42 * 24 * 60 * 60); // within 6 weeks
	if (dtpcurr <= dtprev) {
	  if (SecLevel == 'admin') return true;  // accept any date entered
    $("#DP1").val('').focus();
    alert("Invalid date entered for Date/Time Call Placed.\n\nDate and time must be within the last 24 hours.");
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
echo $xmsg;     // show completion msg if any
echo '
<div class="container">
<h3>Call '.$callnbr.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="updb btn btn-success" form="tf" /><b>Update Call</b></button>&nbsp;&nbsp;&nbsp;<a href="callroview.php?call='.$callnbr.'">
<i title="Print View" class="lvr glyphicon glyphicon-print" style="color: blue; font-size: 20px"></i></a></h3>';

?>
<!-- define the form -->
<form action="callupdatertabbed.php" method="post"  class="form" id="tf" name="tf" onsubmit="return chkdtp()">
<input type="hidden" name="action" value="update">
<input type="hidden" name="callnbr" value="<?=$callnbr?>">
<input type="hidden" name="flds[CallNbr]" value="<?=$callnbr?>">

<span title="Date and time call was entered into CTS">Date/Time Call Opened:&nbsp;&nbsp;<?=$dtopened?></span> &nbsp;&nbsp;&nbsp;
<span title="Date and time that the call was placed on the answering service.">Date/Time Call Placed:&nbsp;&nbsp;<input type="text" id="DP1" name="flds[DTPlaced]" value="<?=$dtplaced?>" style="width: 150px; height: 25px;"></span><br>

Caller Name:<input autofocus id="CN" type="text" name="flds[Name]" placeholder="Caller Name" value="<?=$name?>" />
Phone: <input id="PN" onblur="return checkPhone()" type="tel" name="flds[PrimaryPhone]" value="<?=$primaryphone?>" size="12" maxlength="12" placeholder="Phone Number" />

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
	errmsg += "Invalid phone number.  Please include the Area Code.\n\n";
	}
if(!stripped.match(/^[0-9]{10}/))  { 
	errmsg += "Value entered not 7 or 10 digits OR a non-numeric character entered.\n\n";
	}
if (errmsg.length > 0) {
	errmsg += "Valid formats: 123-456-7890 or 123 456 7890 or 123-456-7890 or 1234567890";
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
		alert("ERROR: No email address recorded.  You must UPDATE the call with an email address before sending to it.");		
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

E-mail: <input type="text" name="flds[EMail]" value="<?=$email?>" id="EM" onblur="return chkEMAddr()" placeholder="Email Address">

<!-- <a href="emailsend.php?emadr='<?=$email?>'&callnbr=<?=$callnbr?>&name=<?=$name?>&crn=<?=$crn?>" onclick="return checkemail()"> -->
<a href="#">
<span id="desc" title="(Disabled Send Email to Caller" class="lvr glyphicon glyphicon-envelope" style="color: blue; font-size: 20px">
</span></a>
<br />

Call Description:<input id="CD" name="flds[Description]" value="<?=$description?>" size="60" maxlength="60"/>

<span id="cinfo" title="Copy Contact Info" class="glyphicon glyphicon-briefcase" style="color: blue; font-size: 20px">
</span>

<br />
Additional Notes: (check History for prior note entries)<br />
<textarea name="notes" rows="5" cols="90"></textarea><br>
<input type="hidden" name="flds[NotesDiary]" value="<?=$notesdiary?>">
<input type="hidden" name="flds[Status]" value="<?=$status?>">
<input type="hidden" name="flds[OpenedBy]" value="<?=$openedby?>">

<!-- call details tab -->
<table class="table table-condensed" border=1><tr><td>
<table class="table-condnensed" border=0>
<tr><td>Animal Loc:</td><td>
<select id="AL" name="flds[AnimalLocation]" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select></td></tr><tr><td>Call Loc:</td><td>
<select id="CL" name="flds[CallLocation]" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select></td></tr><tr><td>Property:</td><td>
<select id="PT" name="flds[Property]" size="1">
<option value=""></option>
<?php loaddbselect("Properties"); ?>
</select></td></tr><tr><td>Species:</td>
<td>
<select id="SP" name="flds[Species]" size="1">
<option value=""></option>
<?php loaddbselect("Species"); ?>
</select></td></tr><tr><td>Call&nbsp;Reason:</td>
<td>
<select id="RE" name="flds[Reason]" size="1">
<option value=""></option>
<?php loaddbselect("Reasons"); ?>
</select>
</td></tr>
<tr><td>
Est.TimeToResolve:</td><td>
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<15"><15&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<30"><30&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<45"><45&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<60"><60&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value=">60">60+
</td></tr>
<tr><td>
Action Taken:</td><td>
<select id="AT" name="flds[Resolution]" size="1">
<option value=""></option>
<?php loaddbselect("Actions"); ?>
</select>
<input type="hidden" id="restod" name="flds[ResTOD]" value="<?=$r['ResTOD']?>">
<input type="hidden" id="resby" name="flds[ResBy]" value="<?=$r['ResBy']?>">
<input type="hidden" id="restel" name="flds[ResTelephone]" value="<?=$r['ResTelephone']?>">
<br />
</td></tr>
<!-- <tr><td>Delivered to Center by: </td>
<td>Caller: <input type="radio" name="flds[DeliveredBy]">
RTV:<input type="radio" name="flds['DeliveredBy']">
RTV Name: <input type="text" name="flds['DeliveredByName']" value="<?=$r['DeliveredByName']?>">
</td></tr> -->
</table>
</td>

<!-- <input class="btn btn-success" type="submit" name="submit" value="Update Call" /><hr>
<div align="center">
<br><button class="btn btn-success" form="tf" /><b>Update Call</b></button></div><br>
 -->
<td valign="top">
WRMD Number: <input type="text" name="flds[CaseRefNbr]" value="<?=$crn?>" maxlength="8" id="CRN"><br>
Organization: <input type="text" name="flds[Organization]" size="50" placeholder="Organization" value="<?=$org?>"><br>
Address:<input id="PC" type="text" name="flds[Address]" size="50" placeholder="Address Line" value="<?=$address?>"><br />
City:<input id="CI" data-provide="typeahead" data-items="4" type="text" name="flds[City]" placeholder="City" value="<?=$city?>" autocomplete="off" />, 
State:<input id="ST" type="text" name="flds[State]" size="2" maxlength="2" value="<?=$state?>"/>  
Zip:<input id="ZI" type="text" name="flds[Zip]" size="5" maxlength="10" value="<?=$zip?>"/>
<button id="ZM" href="#myZipModal" data-toggle="modal" data-keyboard="true" type="button" class="btn btn-xs btn-default" data-placement="top" title="Zip Code List"><span class="glyphicon glyphicon-list" style="color: blue; font-size: 20px"></span></button>
<br>

<?php 
$citieslist = createddown();
?>

<script>
$("#AT").change(function() {
  var restod = '<?=$restod?>';
  var resby = '<?=$_SESSION['CTS_SessionUser']?>';
  var restel = '<?=$_SESSION['CTS_VolTelephone']?>';
  var v = $("#AT").val();
  // alert("restod: "+restod+", resby: "+resby+", value: "+v);
  $("#restod").val(restod);
  $("#resby").val(resby);
  $("#restel").val(restel);
});
</script>

<script>
$("document").ready (function() {
  $("#ZM").click (function () {
    var cla = <?=$citieslist?>;
    cla.sort();
    var res = "<center><h3>City and Zip List</h3><table><tr><th>City</th><th>Zip</th></tr>";
    for (i=0; i<cla.length; i++) {
      if(cla[i] == '') continue;    // ignore empty array items
      var parts = cla[i].split(",");
      if (parts[2] == '') continue;  // no zip, no list
      res += "<tr><td>"+parts[0]+"</td><td>"+parts[2]+"</td></tr>";
      }
    res += "</table></center>";
    $("#mm-modalBody").html(res);
    $("#myModal").modal("show");
    });
  });

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
  var sval = $("#AL").val();
  // alert("AL text: "+sval);
  if ( sval.length ){
  	$("#CL").val(sval);
  	return;
  	}
// alert("no value seen");
return;
});
</script>

<?php
if ($pcsent == '') {
	echo 'Postcard Sent? <input id="PCChk" onchange="return checkaddr()" type="checkbox" name="flds[PostcardSent]" Value="Yes">'; }
else {
	echo "Postcard Sent? $pcsent "; }
if ($emsent == '') {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? No<br><br>"; }
else {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? $emsent<br>"; }
	
// echo 'seclevel: '.$_SESSION['CTS_SecLevel'].', sessuser: '.$_SESSION['CTS_SessionUser'].', calluser: '.$openedby.'<br>';
$fcbutton = '';
if (($_SESSION['CTS_SecLevel'] == 'admin') OR 
  ($_SESSION['CTS_SessionUser'] == $openedby)) { 
    $fcbutton = '<button id="FC" class="btn btn-primary">Fast Close</button>'; }
?>
</td></tr></table>

<script src="js/bootstrap3-typeahead.js"></script>

<!-- caller extended details tab -->
<script>
$("document").ready(function () {
$("#CI").keypress(function(e) {
  //Enter key
  if (e.which == 13) { return false; }
});

$("#CI").blur(function() {
//	alert("loadcity");
	var cv = $("#CI").val();
	var cva = cv.split(",");
	$("#CI").val(cva[0]);
	$("#ST").val(cva[1]);
	$("#ZI").val(cva[2]);
	});
	
});
</script>

<script>
var citylist = <?=$citieslist?>
$('#CI').typeahead({source: citylist})
</script>

<!-- <input type="submit" name="submit" value="Update Call" /><hr> -->
<table class="table"><tr>
<td>
<div align="center"><button class="updb btn btn-success" form="tf" /><b>Update Call</b></button></div></td>
<td><?=$fcbutton?></td></tr></table>
</form>

<!-- output the history log -->
<div class="page-break"></div> <!-- insert page break for print of page -->
<h4>Call Notes History (latest first)</h4>
<?=$notesdiary?>
</div>  <!-- container -->';

<?php
//echo '<pre>'; print_r($citieslist); echo '</pre>';
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
