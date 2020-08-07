<?php
session_start();
// include 'Incls/vardump.inc.php';
// include 'Incls/datautils.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

// get any input values in $_REQUEST parameters

?>
<!DOCTYPE html>
<html>
<head>
<title>Call Short Form Entry</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" 
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>

<script>
// initial setup of jquery function(s) for page
$(function(){
	// alert(" on document load");
  // set up date picker field
  $('#dp').datetimepicker({
      format: 'yyyy-mm-dd hh:ii',
      todayHighlight: true,
      todayBtn: true,
      showMeridian: true,
      autoclose: true,
    });
  // show chooser calendar on focus of field
  $("#dp").focus(function() {
    $("#dp").datetimepicker("show");
    });

  $("#cpn, #desc").focus(function() {
    // alert("cpn or desc entered");
    var dp = $("#dp").val();
    if (dp.length != 16) {
      $("#dp").focus();
      alert("dp empty");
      }
    });

  $("#dp").blur(function() {
    if ($("#dp").length != 18) {
      // alert("Date must be entered");
      // $("#dp").focus();
      }
    });

  // focus on initial load
  $("#dp").focus();

// this attaches an event to an object
	$("#ceBtn").click(function () {
    // alert("enter call into database");
    $.post("callquickentryJSONcalladd.php",
      {
        dp: '"'+$("#dp").val()+'"',
        cpn: '"'+$("#cpn").val()+'"',
        desc: '"'+$("#desc").val()+'"'
      },
    function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
      });  // end $.post logic 
    $("#Xmsg").html("<h3 style='color: red; '>Call added.</h3>");
    $("#Xmsg").fadeIn(100);
    $("#Xmsg").fadeOut(2000);
    
    $("#dp").val('');
    $("#cpn").val('');
    $("#desc").val('');
    $("#dp").focus();
    });
	$("#coBtn").click(function () {
    // alert("enter call into database and leave open for added details");
    $.post("callquickentryJSONcalladd.php",
      {
        dp: '"'+$("#dp").val()+'"',
        cpn: '"'+$("#cpn").val()+'"',
        desc: '"'+$("#desc").val()+'"'
      },
    function(data, status){
        alert("Data: " + data + "\nStatus: " + status);
      });  // end $.post logic 
    $("#Xmsg").html("<h3 style='color: red; '>Call noted for follow-up.</h3>");
    $("#Xmsg").fadeIn(100);
    $("#Xmsg").fadeOut(2000);
    $("#dp").val('');
    $("#cpn").val('');
    $("#desc").val('');
    $("#dp").focus();
    });

  });  // end ready function
</script>

<div class="container">
<h3>CTS Short Form Call Entry
<span id="helpbtn" title="Help" class="glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px"></span>
&nbsp;&nbsp;   <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a>
</h3>

<div id=help>
<p>This page is provided to allow quick entry of calls into the CTS data base.  The call can be handled in one of two ways:</p>
<ol>
	<li>Entry of the call directly into the CTS database, or</li>
	<li>Entry of a call but leave the call in an 'open' status so that added details and closure can be done later.</li>
	<li>Exit the short form and add call using the long form method.</li>
	<li>Exit the short form and return to the home page.</li>
</ol>
</div>

<div id="Xmsg" hidden></div>

<form>
Please provide:<br>
<input id=dp value='' placeholder="Call date/time placed" title='Date/Time call placed'><br>
<input id=cpn value='' placeholder='Caller Phone'><br>
<textarea id=desc rows="3" cols="40" placeholder='Call Description'></textarea>
</form>
<br>
<button id=ceBtn title="Enter short form call into database">Enter short form call</button><br><br>
<button id=coBtn title="Enter short form call into database and leave open for later entry of added information">Enter short form call and leave open for future update</button><br><br>
<button id=sfBtn title="Use the normal long form for entry of a call">Add new call using long form call entry.</button><br><br>

<br><br><br>
<a href="callquickentry.php">Refresh</a>
</div>  <!-- container -->

</body>
</html>
