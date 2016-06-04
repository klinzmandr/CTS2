<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Email Reply Editor</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.css" rel="stylesheet" media="screen">
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

// update reply if reply name is not blank
$ta = $_REQUEST['ta'];
$rfn = isset($_REQUEST['RFN']) ? $_REQUEST['RFN'] : '';
if ($rfn != '') {
  $rfn = 'Incls/email'.$_REQUEST['RFN'].'.inc.php';
  file_put_contents($rfn, $ta);
  }

//echo '<pre>'; print_r($ta); echo '</pre>';
//echo '<pre>'; print_r($rfn); echo '</pre>';
?>

<body>
<script src="js/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	new nicEditor({fullPanel : true,iconsPath : 'js/nicEditorIcons.gif'}).panelInstance('target');
});
</script>

<script>
function setup(txt) {
  var val = txt;
  if (val == "clear") { 
    $("#target").html('<br>'); 
    $("#RFN").val('');
  return; }
  $("#RFN").val(val);
  var tar = '#'+val;
  $("#target").html($(tar).html());
  return;
  }
</script>
<script type="text/javascript">
function moveContent(){
  var div_val=document.getElementById("target").innerHTML;
  document.getElementById("ta").value =div_val;
  }
</script>
<h3>Edit Email Reply Messages</h3>
<p>Select the email reply message, modify the selection in the edit box and click the &apos;SAVE NOW&apos; button when done.</p>

<!-- <div style="font-size: 16px; background-color:#EFF; padding: 3px; border: 2px solid #000; width: 750px; height: 200px" id="target"><?=$ta?></div> -->
<div style="font-size: 16px; background-color:#EFF; padding: 3px; border: 2px solid #000;" id="target"><?=$ta?></div>
<form action="admineditemailreplys.php" method="post" onsubmit="return moveContent()">
<textarea style="display:none;" id="ta" name="ta"></textarea>
<input type="hidden" id="RFN" name="RFN" value="">
<input type=submit name=submit value="SAVE NOW">
</form>

<br>
<a class="btn btn-info" onclick=setup("Reply1")>Edit Reply 1</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply2")>Edit Reply 2</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply3")>Edit Reply 3</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply4")>Edit Reply 4</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply5")>Edit Reply 5</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("clear")>Clear</a><br><br>


<div style="visibility: hidden; " id="Reply1">
<?php include 'Incls/emailReply1.inc.php'; ?>
</div>
<div style="visibility: hidden; " id="Reply2">
<?php include 'Incls/emailReply2.inc.php'; ?>
</div>
<div style="visibility: hidden; " id="Reply3">
<?php include 'Incls/emailReply3.inc.php'; ?>
</div>
<div style="visibility: hidden; " id="Reply4">
<?php include 'Incls/emailReply4.inc.php'; ?>
</div>
<div style="visibility: hidden; " id="Reply5">
<?php include 'Incls/emailReply5.inc.php'; ?>
</div>

</body>
</html>