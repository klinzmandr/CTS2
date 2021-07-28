<?php
session_start();
$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$notes = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : '';
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

<!-- prevent enter key use inside form -->
<script>
// block use of enter key on input form, 
// shifts focus to next field
$(document).ready(function() {
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
</script>

<!-- date/time picker error div fade error notice -->
<script>
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
});
</script>

<!-- call close processing -->
<script>  
$(function() { 
  $(".fcbut").click(function(e) {
  // alert("fast close click");
  e.preventDefault();
  // check that all required fields entered
  var err = "";
  if ($("#AL").val() == "") err += "Animal Location<br>";  
  if ($("#CL").val() == "") err += "Call Location<br>";  
  if ($("#PT").val() == "") err += "Propery<br>";  
  if ($("#SP").val() == "") err += "Species<br>";  
  if ($("#RE").val() == "") err += "Reason<br>";  
  if ($("#AT").val() == "") err += "Resolution<br>";
  if (($('input:radio:checked').length) == 0)
    err += "Est.TimeToResolve";
  if (err.length > 0) { 
    // alert (err);
    $("#mm-modalBody").html("<center><h2 style=\"color: red; \">A T T E N T I O N!</h2></center><font size=\"2\">The follwing list of required field(s) must be specified in order to close a call.<ul>"+err+"</ul></font>");
    $("#myModal").modal("show");
    return;
    }
  if (!confirm("This will permanently close this call.\n\nClick OK to continue or CANCEL.")) { 
    // alert ("cancel"); 
    return; }
  // else alert("OK");
  var formdata = $("#tf").serialize();  // for data for db
  var diaryentry = $("#notesdiary").html();
  $.post("callupdatercloseJSON.php",
  {
    form: formdata,
    diary: diaryentry
  },
function(data, status){
    // alert("Data: " + data + "\nStatus: " + status);
    window.location.assign("callroview.php?call=<?=$callnbr?>");
  });  // end $.post logic 
  }); // end fcbut logiic
});
</script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$nowdt = date('Y-m-d H:i', strtotime('now')); 
$restod = date("H:i", strtotime('now'));
$errs = isset($_REQUEST['errs']) ? $_REQUEST['errs'] : '';

//echo '<pre>Notes  '; print_r($notearray); echo'</pre>';

// read call record with updates if there were any
$sessionuser = $_SESSION['CTS_SessionUser'];
$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$callnbr';";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();

// parse record fields into page
// echo '<pre>DB record '; print_r($r); echo'</pre>';
$callnbr = $r['CallNbr'];
$_SESSION['4log'] = $callnbr;


?>

<!-- test/report field errors, doc load setup -->
<script type="text/javascript">
// report field errors
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
  });
// set up select lists
$(document).ready(function () { 
	//alert("first the inline function");
	$("#AL").val("<?=$r['AnimalLocation']?>");
	$("#CL").val("<?=$r['CallLocation']?>");
	$("#PT").val("<?=$r['Property']?>");
	$("#SP").val("<?=$r['Species']?>");
	$("#RE").val("<?=$r['Reason']?>");
	$("#AT").val("<?=$r['Resolution']?>");
	$('input[type="radio"][value="<?=$r["TimeToResolve"]?>"]').attr('checked', true);
  
});
</script>

<!-- date field and check boxes init -->
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

<!-- call update processing -->
<script>
$(document).ready(function() {
  $(".subbut").click(function(evt) {
    // alert("form submission to json db update");
    evt.preventDefault();
    var formdata = $("#tf").serialize();  // for data for db
    var full = "<?=$nowdt?>";             // curr date/time
    var diaryentry = $("#notesdiary").html();
    // console.log(diaryentry);
    $.post("callupdaterupddbJSON.php",
      {
        form: formdata,
        diary: diaryentry
      },
    function(data, status){
        // alert("Data: " + data + "\nStatus: " + status);
        window.location.assign("callupdater.php?action=update&callnbr=<?=$callnbr?>");
      });  // end $.post logic

  });
});
</script>

<!-- form definition -->
<div class="container">
<?php if ($action == 'update') echo '<h3 style="color: red; " id="X">Update Completed.</h3>'; 
if ($action == 'sfadd') echo '<h3 style="color: red; " id="X">New Call Added.</h3>'; ?>
<div id="xmsg" hidden></div>
<h3>Call <?=$callnbr?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="subbut btn btn-success" form="tf" /><b>Update Call</b></button>&nbsp;&nbsp;&nbsp;
<?php if (($_SESSION['CTS_SecLevel'] == 'admin') OR 
  ($_SESSION['CTS_SessionUser'] == $r['OpenedBy'])) { 
    echo '<button id="FC1" class="fcbut btn btn-primary">Close Call</button>'; } ?>&nbsp;&nbsp;&nbsp;
<a href="callroview.php?call=<?=$callnbr?>">
<i title="Print View" class="lvr glyphicon glyphicon-print" style="color: blue; font-size: 20px"></i></a></h3>

<!-- define the form plus field validations-->
<form action="" method="post"  class="form" id="tf" name="tf" onsubmit="return chkdtp()">
<input type="hidden" name="action" value="update">
<input type="hidden" name="CallNbr" value="<?=$callnbr?>">

<span title="Date and time call was entered into CTS">Date/Time Call Opened:&nbsp;&nbsp;<?=$r['DTOpened']?></span> &nbsp;&nbsp;&nbsp;
<span title="Date and time that the call was placed on the answering service.">Date/Time Call Placed:&nbsp;&nbsp;<input type="text" id="DP1" name="DTPlaced" value="<?=$r['DTPlaced']?>" style="width: 150px; height: 25px;"></span><br>

Caller Name:<input autofocus id="CN" type="text" name="Name" placeholder="Caller Name" value="<?=$r['Name']?>" size="50"  maxlength="50" /><br>
Phone: <input id="PN" onblur="return checkPhone()" type="tel" name="PrimaryPhone" value="<?=$r['PrimaryPhone']?>" size="12" maxlength="12" placeholder="Phone Number" />


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

E-mail: <input type="text" name="EMail" value="<?=$r['EMail']?>" id="EM" onblur="return chkEMAddr()" placeholder="Email Address">

<br />

Call Description:<input id="CD" name="Description" value="<?=$r['Description']?>" size="50" maxlength="80"/>
<br />
Additional Notes: (check History for prior note entries)<br />
<textarea id="Notes" name="Notes" rows="3" cols="60"></textarea><br>
<input type="hidden" name="Status" value="<?=$r['Status']?>">
<input type="hidden" name="OpenedBy" value="<?=$r['OpenedBy']?>">
<br>
<!-- call details -->
<b>Call Info</b>
<table class="table table-condensed" border=0><tr><td>
Animal Loc: 
<select id="AL" name="AnimalLocation" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select><br>
Call Loc: 
<select id="CL" name="CallLocation" size="1">
<option value=""></option>
<?php loaddbselect("Locations"); ?>
</select><br>
Property: 
<select id="PT" name="Property" size="1">
<option value=""></option>
<?php loaddbselect("Properties"); ?>
</select><br>
Species: 
<select id="SP" name="Species" size="1">
<option value=""></option>
<?php loaddbselect("Species"); ?>
</select><br>
Call&nbsp;Reason: 
<select id="RE" name="Reason" size="1">
<option value=""></option>
<?php loaddbselect("Reasons"); ?>
</select><br>
Est.TimeToResolve: 
<input class="RB" type="radio" name="TimeToResolve" value="<15"><15&nbsp;
<input class="RB" type="radio" name="TimeToResolve" value="<30"><30&nbsp;
<input class="RB" type="radio" name="TimeToResolve" value="<45"><45&nbsp;
<input class="RB" type="radio" name="TimeToResolve" value="<60"><60&nbsp;
<input class="RB" type="radio" name="TimeToResolve" value=">60">60+
<br>
Resolution: 
<select id="AT" name="Resolution" size="1">
<option value=""></option>
<?php loaddbselect("Actions"); ?>
</select>
<input type="hidden" id="restod" name="ResTOD" value="<?=$r['ResTOD']?>">
<input type="hidden" id="resby" name="ResBy" value="<?=$r['ResBy']?>">
<input type="hidden" id="restel" name="ResTelephone" value="<?=$r['ResTelephone']?>">
</td></tr></table>
<b>Caller Info</b>
<table class="table table-condensed" border=0>
<tr><td valign="top">
WRMD Number: <input type="text" name="CaseRefNbr" value="<?=$r['CaseRefNbr']?>" maxlength="8" id="CRN"><br>
Organization: <input type="text" name="Organization" size="50" placeholder="Organization" value="<?=$r['Organization']?>"><br>
Address:<input id="PC" type="text" name="Address" size="50" placeholder="Address Line" value="<?=$r[Address]?>"><br />
City:<input id="CI" data-provide="typeahead" data-items="4" type="text" name="City" placeholder="City" value="<?=$r['City']?>" autocomplete="off" />, 
State:<input id="ST" type="text" name="State" size="2" maxlength="2" value="<?=$r['State']?>"/>  
Zip:<input id="ZI" type="text" name="Zip" size="5" maxlength="10" value="<?=$r['Zip']?>"/>
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

<?php
if ($r['PostcardSent'] == '') {
	echo 'Postcard Sent? <input id="PCChk" onchange="return checkaddr()" type="checkbox" name="PostcardSent" Value="Yes">'; }
else {
	echo "Postcard Sent? ".$r['PostcardSent'] ; }
if ($r['EmailSent'] == '') {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? No<br><br>"; }
else {
	echo "&nbsp;&nbsp;&nbsp;Email Sent? ".$r['EmailSent']."<br>"; }
	
// echo 'seclevel: '.$_SESSION['CTS_SecLevel'].', sessuser: '.$_SESSION['CTS_SessionUser'].', calluser: '.$openedby.'<br>';
$fcbutton = '';
if (($_SESSION['CTS_SecLevel'] == 'admin') OR 
  ($_SESSION['CTS_SessionUser'] == $r['OpenedBy'])) { 
    $fcbutton = '<button id="FC2" class="fcbut btn btn-primary">Close Call</button>'; }
?>
</td></tr></table>

<table class="table" borders=5><tr>
<td>
<div align="center"><button class="subbut btn btn-success" form="tf" /><b>Update Call</b></button></div></td>
<td><?=$fcbutton?></td></tr></table>
</form>

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

<script src="js/bootstrap3-typeahead.js"></script>
<script>
var citylist = <?=$citieslist?>
$('#CI').typeahead({source: citylist})
</script>

<script>
$("document").ready(function () {
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


<!-- output the history log -->
<div class="page-break"></div> <!-- insert page break -->
<h4>Call Notes History (latest first)</h4>
<div id="notesdiary"><?=$r['NotesDiary']?></div>
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
