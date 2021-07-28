<?php
session_start();
$_SESSION['4log'] = $callnbr;
$user = $_SESSION['CTS_SessionUser'];
$seclevel = $_SESSION['CTS_SecLevel'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';

?>

<!DOCTYPE html>
<html>
<head>
<title>Call Add</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet"
media="screen">
</head>
<body>
<div class=container>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<style>
@media print {
    /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body so it doesn't print with modal*/
    body.modal-open {
        visibility: hidden;
    }

    body.modal-open .modal .modal-header,
    body.modal-open .modal .modal-body {
        visibility: visible; /* make visible modal body and header */
    }
}
</style>

<!-- block use of enter key -->
<script>
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
 
var SecLevel = '<?=$seclevel?>';  // global var for sec level

</script>

<!-- alert if reason code contains an asterix -->
<script>
$(document).ready(function() {
$("#X").fadeOut(3000);

$("#RE").change(function() {
  const regex = /\*/g;    // check drop down selection for an astrix
  var reason = $("#RE option:selected").text();
  if (regex.exec(reason) != null) {
    $("#mm-modalBody").html("<font size=\"2\">Selection of this call reason requires that this incident be reported to the CA DFW.<br><b>Please contact the Center and report this call so that appropriate follow up steps can be done.</b><br></font>");
    $("#myModalLabel").html("<h2>A T T E N T I O N!</h2>");
    $("#myModalLabel").css("color","red");
    $("#myModal").modal("show");
    }
  });
});
</script>

<!-- display documentation -->
<script>
$(function(evt) {
  $("#docbtn").click(function() {
  // alert("doc button clicked");
  $.post("calladdshortdocJSON.php",
    {
      name: "admpw",
      city: "bateluer"
    },
  function(data, status){
    // alert("Data: " + data + "\nStatus: " + status);
    $("#myModalLabel").html("Documentation on Call Short Form Entry");
    $("#myModalLabel").css("color","blue");
    $("#mm-modalBody").html(data);
    $("#myModal").modal("show");
    });  // end $.post logic 
  });

$("#SCC").click(function(evt) {
  // alert("add new clicked");
  evt.preventDefault();
  $("#hider").prop("hidden", false);
  $("#showbuts").prop("hidden", true);
  });
});
</script>

<!-- process form submit -->
<script>
$(function() {
$(".subbut").click(function(evt) {
  evt.preventDefault();
  var errmsg = "";                  // field validation error msgs
  var butid = $(this).attr('id');   // get id of button used
  // alert("sub button " + butid + " clicked");
  $("input,select").attr("style","background-color:white;");
  if ($("#PN").val().length <= 0) {
    errmsg += "Caller phone number missing<br>";
    $("#PN").attr("style","background-color:pink;");
    }
  if ($("#RE").val().length <= 0) {
    errmsg += "Call reason missing<br>";
    $("#RE").attr("style","background-color:pink;");
    }
  if ($("#CL").val().length <= 0) {
    errmsg += "Call location missing<br>";
    $("#CL").attr("style","background-color:pink;");
    }
  
  if (butid != 'SLMO') {
    if ($("#RES").val().length <= 0) {
      errmsg += "Call resolution missing<br>";
      $("#RES").attr("style","background-color:pink;");
      }
    }
  
  if (errmsg.length > 0) {
    $("#myModalLabel").html("Error! Missing Fields");
    $("#myModalLabel").css("color","red");
    $("#mm-modalBody").html(errmsg);
    $("#myModal").modal("show");
    return false;
    }

  if (butid == 'SLMO') {      // save and list my open button used
    $("#ACTION").val('Open');
    }
  else {                      // save and close call button used
    $("#ACTION").val('Closed');
    }
  var seldate = $("#date").val();
  // setup to call json module
  $.post("calladdshortdbJSON.php",
    { 
      CN: $("#CN").val(),
      PN: $("#PN").val(),
      EM: $("#EM").val(),
      CD: $("#CD").val(),
      RE: $("#RE").val(),
      CR: $("#RES").val(),
      CL: $("#CL").val(),
      AD: $("#AD").val(),
      CI: $("#CI").val(),
      ST: $("#ST").val(),
      ZI: $("#ZI").val(),
      ACTION: $("#ACTION").val(),
      NOWDT: $("#date").val()
    },
  function(data, status) {
    if (data.length > 6) {    // response should be new call#
      alert("Data: " + data + "\nStatus: " + status);
      return;
      }
    if (butid == 'SLMO') {      // save and list my open button used
    window.location.assign("callupdater.php?action=sfadd&callnbr="+data); 
      }
    else {                      // reload form to add another
      window.location.assign('calladdshortform.php?action=calladded&callnbr='+data);
      } 
    });  // end $.post logic 
  });
});  
</script>

<!-- phone number info display -->
<script>
$(function() {
  // alert("pn info request");
  $("#pnlookup").click(function() {
    // alert("phone number call info display");
    if ($("#CC").val() == 0) return;
    var msg = "Accumulated information <br>for phone number "+$("#PN").val()+"<ul>Number of calls: "+$("#CC").val()+"<br>";
    msg += "Latest Prior Call Number: "+$("#LC").val() + "<br>";
    msg += "Last Caller Name: "+$("#CN").val() + "<br>";
    msg += "Last Caller Address: "+$("#AD").val() + "<br>";
    msg += "Last Caller City: "+$("#CI").val() + "<br>";
    msg += "Last Caller State: "+$("#ST").val() + "<br>";
    msg += "Last Caller ZIP: "+$("#ZI").val() + "<br>";
    msg += "Last Caller Email: "+$("#EM").val() + "<br>";
    msg += "</ul>Information displayed is accumulated from the from all previous calls.<br><br>This information has been automatically entered into this call&apos;s details and can be modified assuming the call is left OPEN."; 
    $("#myModalLabel").html("<h2>Caller Information</h2>");
    $("#myModalLabel").css("color","red");
    $("#mm-modalBody").html(msg);
    $("#myModal").modal("show");

  });
});
</script>

<!-- validate phone number -->
<script>
function checkPhone() {
//alert("validation entered");
if ($("#PN").val().length == 0) {
  $("#PN").attr("style","background-color:white;");
  $("#PN").focus();
  return true;
  }
var fld = $("#PN").val();
var errmsg = "";
var stripped = fld.replace(/[a-zA-z\(\)\.\-\ \/]/g, '');
if (stripped.length == 7)
	stripped = "805" + stripped;
if(!stripped.match(/^[0-9]{10}/))  { 
	errmsg += "Value entered not 7 or 10 digits OR a non-numeric character entered.<br><br>";
	}
if (errmsg.length > 0) {
	$("#PN").focus();
	errmsg += "Valid phone numbers are a 7 or 10 number.  An area code of 805 is assumed if a 7 digit number is entered.  Any of the following formats are acceptable: <br> <ol><li>1234567</li><li>1234567890</li><li>123-456-7890</li><li>123 456 7890</li><li>123.456.7890</li></ol>";
	$("#PN").attr("style","background-color:pink;");
  $("#myModalLabel").html("Invalid phone number");
  $("#myModalLabel").css("color","red");
  $("#mm-modalBody").html(errmsg);
  $("#myModal").modal("show");
	return true;
	}
var newval = stripped.substr(0,3) + "-" + stripped.substr(3,3) + "-" +
stripped.substr(6,4);
//fld.value = newval;
$("#PN").val(newval);
$("#PN").attr("style","background-color:white;");

// check database for previous use of number
// console.log("PN: " + $("#PN").val());
if ($("#PN").val().length <= 0) return;
$.post("calladdshortpnlookupJSON.php",
  { 
    PN: $("#PN").val()
  },
function(data, status) {
  // alert(data);
  var res=data.split(':');
  // console.log(res);
  $("#CC").val(res[0]);
  $("#CN").val(res[1]);
  $("#AD").val(res[2]);
  $("#CI").val(res[3]);
  $("#ST").val(res[4]);
  $("#ZI").val(res[5]);
  $("#EM").val(res[6]);
  $("#LC").val(res[7]);
  if (res[1].length > 0) {
    $("#pnlookup").show(); }
  });  // end $.post logic 
}
</script>

<!-- validate email address -->
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

$nowdt = date('Y-m-d H:i', strtotime('now')); 
$nowymd = date('Y-m-d', strtotime('now')); 
$dtnow = strtotime('now'); 

if ($action == 'calladded') echo "<h3 style='color: red; ' id='X'>Call $callnbr successfully added.</h3>"; 

?>

<!-- define input form -->
<h3>Add New Call&nbsp;&nbsp;<span id="docbtn" title="Short Form Documentation"
class="glyphicon glyphicon-info-sign" style="color: blue; font-size: 25px"></span></h3>

<!-- define the short form -->
<form method="post" id="TF" name="tf">
<table cellpadding="5" cellspacing="0">
<tr hidden><td>
<input id="CC" type=text value='0'>
<input id="AD" type=text value=''>
<input id="CI" type=text value=''>
<input id="ST" type=text value=''>
<input id="ZI" type=text value=''>
<input id="LC" type=text value=''>
</td></tr>
<tr><td>
Date: <select id='date'>
<?php include "Incls/DateDropdown.php"; ?>
</select>
</td></tr>
<tr><td>
<table><tr><td>
<input id="PN" name="pn" onblur="return checkPhone()" type="tel" value="" size="15" maxlength="12" placeholder="Phone Number" />
</td><td hidden id="pnlookup">&nbsp;&nbsp;
<span class="glyphicon glyphicon-question-sign" title="Caller information from previous calls." style="color: green; font-size: 25px"></span>
</td></tr></table>
</td></tr>
<tr><td>
<input id="CN" name="cn" type="text" placeholder="Caller Name" value="" />
</td></tr>

<tr><td>
<input type="text" value="" id="EM" name="em" onblur="return chkEMAddr()" placeholder="Email Address">
</td></tr>
<tr><td>
<input id="CD" name="cd" placeholder="Call Description" title="Enter short description (60 chars max)" value="" size="45" maxlength="60"/>
</td></tr>
<tr><td>
<select id="RE" name="re" placeholder="Reason for Call"
size="1">
<option value="">Call Reason</option>
<?php loaddbselect("Reasons"); ?>
</select>
<input type=hidden id=ACTION name="action" value="">
</td></tr>
<tr><td>
<select id="CL" name="cl">
<option value="">Call Location</option>
<?php loaddbselect("Locations"); ?>
</select>
</td></tr>
<tr id="showbuts"><td>
<button id=SLMO class="subbut">Add and Update New Call</button><br><br>
<button id=SCC>Add and Close New Call</button>
</td></tr>
<tr hidden id="hider"><td>
<select id="RES" id="res">
<option value="">Call Resolution</option>
<?php loaddbselect("Actions"); ?>
</select><br><br>
<button class="subbut btn btn-success" id=CONT>Resolve, close and add another</button>
</td></tr>
</table>
</form>

</div>
</body>
</html>
