<!DOCTYPE html>
<html>
<head>
<title>List Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>


<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$msg =isset($_REQUEST['msg'])? $_REQUEST['msg'] : ""; 

if ($action == 'update') {
	//echo '<pre> update '; print_r($_REQUEST['msg']); echo '</pre>';
	$msg = stripslashes($msg);
	file_put_contents('Incls/links.inc.php', $msg);
	echo '<h3 style="color: red; " id="X">Update Completed.</h3>';
	}

$lists = file_get_contents('Incls/links.inc.php');

print <<<pagePart1
<script>
$("document").ready( function() {
  $("#X").fadeOut(5000);
});
</script>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	// new nicEditor({fullPanel:true}).panelInstance('area1');
	new nicEditor({buttonList:['fontSize','bold','italic','underline','strikeThrough','link','unlink']}).panelInstance('area1');
});
</script>
<script>
function movemsg() {
	//alert("moving text from div to input field");
	var msgtext = document.getElementById('area1').innerHTML
	document.sndform.msg.value = msgtext;
	if (msgtext.length <= 1) {
		alert("No resources text entered."); 
		return false;
		}
	return true;
	}
</script>

<h3>Maintain CTS Resources Lists</h3>
<p>Use the HTML editor to add or modify the links that are to be presented on the resources page of the system.  Click inside the text area to begin.</p>
<p>NOTE: use the same type of editting functionality found in most all web email composition windows to create bold, italicized or underlined text, lists and links.</p>
<form name="sndform" action="adminresourcesmaint.php" method="post" onsubmit="return movemsg()">
<input type="hidden" name="msg" id="msg" value="">
<div id="area1" style="font-size: 16px; padding: 3px; border: 5px solid #000; width: 800px;">
$lists
</div>
<input type="hidden" name="action" value="update">
<input type="submit" name="submit" value="submit">

</form>

pagePart1;

?>

</body>
</html>
