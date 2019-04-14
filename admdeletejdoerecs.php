<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Delete Test/Training Records</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action == 'delete') {
//	echo "delete of records action seen<br>";
	$sql = "DELETE FROM `calls` WHERE `OpenedBy` = 'jdoe';";
	$res = doSQLsubmitted($sql);	
	$sql = "DELETE FROM `bboard` WHERE `UserID` = 'jdoe';";
	$res = doSQLsubmitted($sql);
	}

print <<<pagePart1
<div class="container">
<h3>Delete Records for jdoe</h3>
<p>This utility is used to delete all calls records and bulletin board items created by the userid of &apos;jdoe&apos; (the training and testing userid.)</p>

pagePart1;
$sql = "SELECT * FROM `calls` WHERE `OpenedBy` = 'jdoe';";
$res = doSQLsubmitted($sql);
$callscount = $res->num_rows;
//echo "callscount: $callscount<br>";

$sql = "SELECT * FROM `bboard` WHERE `UserID` = 'jdoe';";
$res = doSQLsubmitted($sql);
$bullcount = $res->num_rows;
//echo "logcount: $logcount<br>";
?>

<h4>Currently there are <?=$callscount?> call records and <?=$bullcount?> bulletin board items in the database entered by the userid &apos;jdoe&apos;.</h4>
<h4>Click the CONTINUE button to delete all of these records (if any).</h4>

<a id="CB" class="btn btn-danger" href="admdeletejdoerecs.php?action=delete">CONTINUE</a>
<br><br>

<script>
$( "#CB" ).click(function() {
	var rc = <?=$callscount?>;
	if (rc == 0) {
		alert("There are no test/training records on the database.");
		return false;
		}
	var r = confirm("This action permanently deletes these records from the database.\\nThis action cannot be reversed.\n\nClick OK to delete these records.");
	if (r == true) { return true; } 
	else { return false; }
	});
</script>

</div>  <!-- container -->

</body>
</html>
