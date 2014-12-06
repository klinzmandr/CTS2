<!DOCTYPE html>
<html>
<head>
<title>Forms and Documentation Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<div class="container">
<h1>Forms Directory Maintenance</h1>
<hr>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="95%">
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
session_start();
// include 'Incls/vardump.inc';
include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$form =isset($_REQUEST['form'])? $_REQUEST['form'] : ""; 

if ($action == 'rename') {
	$old = 'Forms/' . $_REQUEST['oldname'];
	$new = 'Forms/' . $_REQUEST['newname'];
//	echo 'Rename request received<br>';
//	echo "oldname: $old, newname: $new<br>";
	if (rename($old, $new)) { 
//		echo "Renamed '$old' to '$new'<br>";
		}
	else { 
		echo "Rename request FAILED!<br>
		New name provided already exists OR path name invalid";
		}
	}

if ($action == 'delete') {
//	echo '<h4>Delete action requested.</h4>';	
	$deltarget = 'Forms/' . $form;
	unlink($deltarget);
	echo "<h4>Form &apos;$form&apos; has been successfully deleted.</h4>";
	}

if ($action == 'addnew') {
	if ($_FILES["file"]["size"] < 1000000) {
  if ($_FILES["file"]["error"] > 0)  {		// check for upload error
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else	{ 		// confirm upload info
//    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
//    echo "Type: " . $_FILES["file"]["type"] . "<br />";
//    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
//    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    if (file_exists("Forms/" . $_FILES["file"]["name"])) {
      echo "ERROR: " . $_FILES["file"]["name"] . " already exists. Upload of new one has failed!";
      }
    else {
      move_uploaded_file($_FILES["file"]["tmp_name"], "Forms/" . $_FILES["file"]["name"]);
      echo "<h3>Upload successful. File stored as: " . "&apos;" . $_FILES["file"]["name"] . '&apos;</h3>';
      }
    }
  }
else {
  echo "ERROR: File size exceeds maximum of 1MB.";
  }
}

$forms = scandir('Forms');

// list contents of forms dir
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
print <<<listPart1
<tr><td width="15%" align="center">
<a id="DF" href="adminformsmaint.php?action=delete&form=$formname">Delete</a>
&nbsp;/&nbsp;
<a href="#" onclick="return getfld('$formname')">Rename</a>
</td>
<td>
<a target=_blank href="Forms/$formname">$formname</a></td></tr>
listPart1;
}
?>
</table>
<script>
$( "#DF" ).click(function() {
	var r = confirm("This action permanently deletes the file.\nThis action CANNOT be reversed.\n\nClick OK to continue.");
	if (r == true) { return true; } 
	else { return false; }
});
</script>
<form action="adminformsmaint.php" method="post" enctype="multipart/form-data">
<!-- <label for="file">or add a new one:&nbsp;</label> -->
<br>OR ADD A NEW ONE
<input size=50 type="file" name="file" id="file" />
<input type="hidden" name="action" value="addnew">
<input type="submit" name="submit" value="Submit" />
</form>

</div>
// rename function and scripts
<script>
function getfld(OName) {
var inval = OName;
//	if prompt dialog is canceled it exits the script
var val = prompt("Please enter a NEW name (including the file extension if needed):",inval);
if (val.length > 0) {
// if confirm dialog is canceled it returns false
	var r = confirm("Confirm rename of "+inval+" to "+val);
	if (r == true) {
		document.getElementById("HF1").value = inval;
		document.getElementById("HF2").value = val;
		document.forms["NameForm"].submit();
  	return true;
		}
	}
alert("Rename action cancelled");
return false;
}
</script>

<!-- define form to submit WITHOUT a submit field defined -->
<form method="post" name="NameForm">
<input type="hidden" id="HF1" name="oldname" value="">
<input type="hidden" id="HF2" name="newname" value="">
<input type="hidden" name="action" value="rename">
</form>

</body>
</html>
