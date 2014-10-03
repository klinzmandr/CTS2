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

<?php
session_start();
include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$form =isset($_REQUEST['form'])? $_REQUEST['form'] : ""; 

if ($action == 'delete') {
	echo '<h4>Delete action requested.</h4>';	
	$deltarget = 'Forms/' . $form;
	unlink($deltarget);
	echo "<h4>Form $form has been successfully deleted.</h4>";
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
      echo "<h3>Upload successful. Stored in: " . "Forms/" . $_FILES["file"]["name"] . '</h3>';
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
echo "<tr><td width=\"15%\" align=\"center\">
<a href=\"adminformsmaint.php?action=delete&form=$formname\">Delete</a>
</td><td><a target=_blank href=\"Forms/$formname\">$formname</a></td></tr>";
}
?>

</table>

<br>
<form action="adminformsmaint.php" method="post" enctype="multipart/form-data">
<label for="file">or add a new one:&nbsp;</label>
<input size=50 type="file" name="file" id="file" />
<input type="hidden" name="action" value="addnew">
<input type="submit" name="submit" value="Submit" />
</form>
<br><br><br>
</div>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
