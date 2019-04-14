<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>List Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>

<?php
//include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$msg =isset($_REQUEST['msg'])? $_REQUEST['msg'] : ""; 

if ($action == 'update') {
	// echo '<pre> update :'; print_r($_REQUEST['msg']); echo ':</pre>';
	$msg = stripslashes($msg);
	file_put_contents('Incls/links.inc.php', $msg);
	echo '<h3 style="color: red; " id="X">Update Completed.</h3>';
	}

$linksarray = array(); $links = '';
$linksarray = file('Incls/links.inc.php', FILE_IGNORE_NEW_LINES);
foreach ($linksarray as $l) {
  if (strlen($l) == 0) continue;
  $links .= "$l\n";
  }
?>

<script>
$("document").ready( function() {
  $("#X").fadeOut(5000);
  
$("#area1").change(function() {
  alert("area1 changed");
  });
});
</script>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	var myEditor = new nicEditor({buttonList:['fontSize', 'bold', 'italic', 'underline', 'strikeThrough', 'removeformat', 'link',  'unlink', 'xhtml']}).panelInstance('area1');

  myEditor.addEvent('focus', function() {
    // alert( "chgFlag: " + chgFlag); 
    chgFlag +=1;    // block leaving page until changes saved
    });
  });
</script>
<script>
function movemsg() {
	//alert("moving text from div to input field");
	var msgtext = "";
	// msgtext = document.getElementById('area1').innerHTML
	msgtext = $("#area1").html();
	// document.sndform.msg.value = msgtext;
	$("#msg").val(msgtext);
	if (msgtext.length <= 1) {
		alert("No resources text entered."); 
		return false;
		}
	return true;
	}
</script>

<h3>Maintain CTS Resources Lists
<span id="helpbtn" title="Help Documentation" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</h3>
<div id=help>
<p>Use the HTML editor to add or modify the links that are to be presented on the resources page of the system.  Click inside the text area to begin.  Make sure to click the 'Save All Changes' button at the bottom of the edit area once all changes have been made.</p>
<p>NOTE: use the same type of editing functionality found in most all web email composition windows to create bold, italicized or underlined text and links.</p>
<p>To add, update or delete links and text in the editor area do the following steps:
<ol>
	<li>Click inside the edit area to activate the editor buttons.</li>
	NOTE: hover mouse over icons to get description of the editor buttons.
  <li>To add a link:</li>
  	<ol>
    	<li>Place cursor after a line and hit 'Return/Enter' to open a new line.</li>
    	<li>Type in the description of the link.</li>
    	<li>Hi-light the new description and click the 'Add Link' editor button.</li>
    	<li>Fill in URL information for new web location in the pop-up dialog box.</li>
    	<li>Add descriptive text for link.</li>
    	<li>Select Open in 'New Window'.</li>
    	<li>Click 'Submit' of the dialogue box.</li>
    </ol>
	<li>To delete an existing link:</li>
    <ol>
      <li>Place cursor at end of line being deleted.</li>
      <li>Use 'Backspace' key to delete all characters on line.</li> 
      <li>Use 'Backspace' key one more time to delete line.</li>
    </ol>
	<li>To modify the description of a link:</li>
    <ol>
    	<li>Place cursor in the line to be modified.</li>
    	<li>Use left/right arrow keys to position cursor.</li>
    	<li>Use 'Backspace'/'Del' to delete, type new characters on keyboard.</li>
    </ol>	
	<li>To modify a link target URL:</li>
    <ol>
      <li>Place cursor in te line to be modified.</li>
      <li>Click 'Add Link' editor button.</li>
      <li>Modify or replace the URL in the pop up dialogue box.</li>
      <li>Click the 'Submit' button of the dialogue.</li>
    </ol>
  <li><span style="color: red; "><b>IMPORTANT:</b></span> After completion of all changes:</li>
    <ol>
    	<li>Highlight everything in the edit box using Ctrl-A or doing a right mouse button and click 'Select All'</li>
    	<li>Click 'Remove Formatting' editor button.</li>
    	<li>Scroll to bottom of edit box and click the 'Save All Changes' button.</li>
    </ol>
</ol>
NOTE: If you are familiar with HTML formatting code you can use the 'Edit HTML' editor button to make direct changes to the contents of the edit box.</p>
</div>
<form name="sndform" action="adminresourcesmaint.php" method="post" onsubmit="return movemsg()">
<input type="hidden" name="msg" id="msg" value="">
<div id="area1" style="font-size: 16px; padding: 3px; border: 5px solid #000; width: 800px;"><?=$links?></div>
<input type="hidden" name="action" value="update">
<input type="submit" name="submit" value="Save All Changes">
</form>

<br><br><br><br>
</body>
</html>
