<!DOCTYPE html>
<html>
<head>
<title>Forms and Documentation Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$form =isset($_REQUEST['form'])? $_REQUEST['form'] : ""; 

if ($action == 'rename') {
	$old = 'Forms/' . $_REQUEST['oldname'];
	$new = 'Forms/' . $_REQUEST['newname'];
	if (rename($old, $new)) { 
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
print <<<headPart
<div class="container">
<h1>Documentation &amp; Forms Directory Maintenance</h1>
<form action="adminformsmaint.php" method="post" enctype="multipart/form-data">
<!-- <label for="file">or add a new one:&nbsp;</label> -->
<table border=0><tr><td>
ADD A NEW FORM: </td><td>
<input size=50 type="file" name="file" id="file" /></td><td>
<input type="hidden" name="action" value="addnew"></td><td>
<input type="submit" name="submit" value="Submit" />
</form>
</td></tr></table>


headPart;

$forms = scandir('Forms');

// list contents of forms dir
echo '<table cellpadding="0" cellspacing="0" border="0" align="center" width="95%">';
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
print <<<listPart1
<tr><td width="20%" align="center">
<a onclick="return chkdel()" href="adminformsmaint.php?action=delete&form=$formname">Delete</a>
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
function chkdel() {
	var r = confirm("This action permanently deletes the file.  \nThis action CANNOT be reversed. \n\nClick OK to continue.");
	if (r == true) { return true; } 
	else { return false; }	
	}
</script>

</div>  <!-- container -->
// rename function and scripts
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
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
