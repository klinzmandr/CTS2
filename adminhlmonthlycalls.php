<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>CTS HL Calls Maintenance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
function chkta() {
  var regex = /^\d{4,4}-\d{2,2}:\d{1,5}$/gm;
  var lines = $('[name=ta]').val().split('\n');
  var m = '';
  for (var j = 0; j < lines.length; j++) {
    if (lines[j].substring(0, 2) == '//') continue;
    if (lines[j].length == 0) continue;
    if (lines[j].match(regex) === null) {      
      console.log("linenbr: " +j+", line: "+lines[j]);
      alert("BAD INPUT LINE "+j+"\n\n"+lines[j]+"\n\nFormat: YYYY-DD:ddddd\nwhere: \n  YYYY is 4 digit year\n  Dash ('-')\n  MM is 2 digit month\n  Colon (':')\n  ddddd is 1 to 5 digits.");
      return(false);
      }
    }
  }
</script>
<?php
include 'Incls/datautils.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$file = isset($_REQUEST['file'])? $_REQUEST['file'] : "";
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$updfile = isset($_REQUEST['updfile'])? $_REQUEST['updfile'] : "";
$ta = isset($_REQUEST['ta'])? $_REQUEST['ta'] : "";

echo "<div class='container'>";
if ($action == "update") {
	updatedblist('HLMonthlyCalls', $ta);
	echo "<h4 id='upd'>HL Monthly Calls updated successfully</h4>";
	$file = $updfile;
	}
?>
<script>

$(function() {
  // alert("doc loaded");
  $("#upd").show(); $("#upd").fadeOut(2000);
  
$("#helpbtn").click(function() {
    // alert("help button clicked");
    $("#doc").toggle();
    });
});
</script>
<h2>Admin: HL Monthly Calls Utility&nbsp;&nbsp;<span id="helpbtn" title="Help document" class="glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;<button class='btn btn-danger' onclick='javascript:window.location.assign("index.php");'>DONE</button></h2>
<div id=doc hidden>
<p>This utility allows input of a monthly number which is a count of the total calls made to the PWC hot line service.  This table is input to the chart program that plots monthly call volumes for the organization.</p>
<p>This monthly call count must be derived from the hot line service call history logs usually kept on call services systems.</p>
<p>The format of the individual line is very specific and must be entered exactly as documented.  Automated checks are performed on all lines in the input box to ensure that these lines all confirm to the prescribed formatting rules.  An error message is produced and the update is cancelled until all lines in the input box are valid.</p>
<p>New entries should be entered at the top of list following the comment lines.</p>
<p>All lines that start with double slashes ('//') are ignored.  Blank lines are also ignored.</p>
</div>
<br>
<form action="adminhlmonthlycalls.php" onsubmit="return chkta();" method="post">
<input type="submit" name="Submit" value="Submit Changes" /><br>
<textarea name="ta" rows="15" cols="35">
<?php	echo readdblist('HLMonthlyCalls'); ?>
</textarea><br />
<input type="hidden" name="action" value="update">
</form>

<br><br><br>
</body></html>
</div>
</body>
</html>
