<!DOCTYPE html>
<html>
<head>
<title>Forms and Documentation Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/fileinput.min.js"></script>

<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$form =isset($_REQUEST['form'])? $_REQUEST['form'] : ""; 

$updmsg = "";

// copy action
if ($action == 'copy') {
  if (isset($_REQUEST['file'])) {
    $file = $_REQUEST['file'];
    $new = 'Forms/' . $file . ' (copy)';
    $old = 'Forms/' . $file;
    if (file_exists($new)) {
      addlogentry("File $old already exists");
      $errmsg = "Copy of $old FAILED - new file name already exists.";
      }
    else {
      echo "old: $old, new: $new<br>";
      if (copy($old, $new)) {
        addlogentry("Copied $old to $new");
        $updmsg = "Copy of $old successful!";
        }
      else {
        addlogentry("Copy of $old FAILED");
        $errmsg = "Copy of $old FAILED!";
        }
      }
    }
  }
 
// rename action
if ($action == 'rename') {
	$old = 'Forms/' . $_REQUEST['oldname'];
	$new = 'Forms/' . $_REQUEST['newname'];
	// echo "old: $old, new: $new<br>";
	if ($stat = rename($old, $new)) {
	  $updmsg = "File $old renamed to $new";
	  addlogentry("File $old renamed to $new"); 
		}
	else {
	  addlogentry("Rename of $old failed");
		$errmsg = "Rename request FAILED!<br>
		New name provided already exists OR path name invalid";
		}
	}

// delete action
if ($action == 'delete') {
  //echo '<h4>Delete action requested.</h4>';	
	$deltarget = 'Forms/' . $form;
	unlink($deltarget);
	addlogentry("Delete of $form successful");
	$updmsg = "Delete of $form successful";
	}

// addnew action
if (count($_FILES)) {
  $errmsg = "";     //initiate the progress message
  // echo '<pre> file '; print_r($_FILES); echo '</pre>';
  for ($i = 0; $i<count($_FILES["files"]["name"]); $i++) {
    $filen = 'Forms/'.$_FILES["files"]["name"][$i];
    if (file_exists($filen)) {
      $errmsg .= "<b>ERROR:</b> File $filen already exists.  Upload ignored!<br>";
      continue;
      }
    if ($_FILES["files"]["error"][$i] > 0) {
    	$errmsg .= "Error " . $_FILES["files"]["error"][$i] . " on upload of $filen<br>";
    	continue;
    	}
  //  echo "i: $i<br>, name: " . $_FILES["files"]["name"][$i] . '<br>';
  //  echo "tmp_name: " . $_FILES["files"]["tmp_name"][$i] . "<br />";
  //  echo "Size: " . ($_FILES["files"]["size"][$i] / 1024) . " Kb<br>=====<br>";
   	if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $filen)) {
      $updmsg .= "File# ".($i+1)." ($filen) uploaded successfully<br>"; 	  
   	  }
    }
  }

if ($errmsg <> '') echo "<h4 style=\"color: red; \" id=\"Xx\">$errmsg</h4>";
if ($updmsg <> '') echo "<span id=\"Xy\">$updmsg<br></span>";

?>
<script>
$(document).ready(function() {
  $("#Xx").fadeOut(5000);
  $("#Xy").fadeOut(5000);
  $("#addfile").hide();
  $("#addclick").click(function() {
    $("#addfile").toggle();
  // alert("toggle");
    });
  // alert("doc ready");
  $("#infiles").fileinput({
    'showUpload': true, 
    'previewFileType':'any',
    'maxFileCount': 6
    });
});
</script>

<div class="container">
<h3>Documentation &amp; Forms Directory Maintenance</h3>

<button id="addclick">ADD NEW DOC(S) or FORM(S)</button>
<div id="addfile">
<form class="form-inline" role="form" action="adminformsmaint.php" method="post" enctype="multipart/form-data">
<input size=25 type="file" name="files[]" id="infiles" multiple />
</form>
</div>

<?php

$forms = scandir('Forms');

// list contents of forms dir
echo '<table class="table" border="0">';
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }

print <<<listPart1
<tr><td width="15%" align="center">

<a onclick="return chkdel()" href="adminformsmaint.php?action=delete&form=$formname"><span title="DELETE" class="glyphicon glyphicon-trash" style="color: blue; font-size: 15px"></span></a>
&nbsp;
<a href="#" onclick="return getfld('$formname')"><span title="RENAME (name starting with period will hide the file without deletion)" class="glyphicon glyphicon-star" style="color: blue; font-size: 15px"></span></a>
&nbsp;
<a href="adminformsmaint.php?action=copy&file=$formname"><span title="COPY" class="glyphicon glyphicon-tags" style="color: blue; font-size: 15px"></span></a>
</td>
<td>
<a target=_blank href="Forms/$formname">$formname</a></td>
</tr>

listPart1;
}
?>
</table>
=== End of List ===
<script>
function chkdel() {
	var r = confirm("This action permanently deletes the file.  \nThis action CANNOT be reversed. \n\nClick OK to continue.");
	if (r == true) { return true; } 
	else { return false; }	
	}
</script>

</div>  <!-- container -->

<script>
function getfld(OName) {
var inval = OName;
//	if prompt dialog is canceled it exits the script
var val = prompt("Please enter a NEW name (including the file extension if needed):",inval);
if (val.length > 0) {
// if confirm dialog is canceled it returns false
	document.getElementById("HF1").value = inval;
	document.getElementById("HF2").value = val;
	document.forms["NameForm"].submit();
	return true;
	}
alert("Rename action cancelled");
return false;
}
</script>

<!-- define form to submit rename info WITHOUT a submit field defined -->
<form method="post" name="NameForm">
<input type="hidden" id="HF1" name="oldname" value="">
<input type="hidden" id="HF2" name="newname" value="">
<input type="hidden" name="action" value="rename">
</form>
</body>
</html>
