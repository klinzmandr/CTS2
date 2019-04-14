<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Specific Lists</title>
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
//include 'Incls/vardump.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/mbrdbdatautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$listname = $_REQUEST['listname'] ? $_REQUEST['listname'] : '';
echo '
<div class="container">
<h3>List Members of Available Mailing List';

if ($action != "") echo ": $listname";
echo '
<a href="javascript:self.close();" class="btn btn-xs btn-primary"><b>CLOSE</b></a></h3>
<form action="vollister.php" method="post">
<select onchange="this.form.submit()" name="listname" size="1">
<option value="">Select List Name</option>';
loadmbrdbselect('EmailLists');
echo '</select>
<input type="hidden" name="action" value="display">
</form>';

if (strlen($action) > 0) {	
	$sql = "SELECT * FROM `members` WHERE `Lists` LIKE '%$listname%';";
	$res = dombrSQLsubmitted($sql);
	$rowcnt = $res->num_rows;
	if ($rowcnt == 0) {
		echo '<h3>There are no volunteers in the list '.$listname.'</h3>
		</body></html>';
		exit;
		}
	
	echo '<table class="table table-condensed">
<tr><th>MCID</th><th>Last Name</th><th>First Name</th><th>City</th><th>Email</th><th>PhoneNumber</th></tr>';
	$emarray = array();
	while($r = $res->fetch_assoc()) {
		//echo '<pre>List members '; print_r($r); echo '</pre>';
		$emarray[] = $r['EmailAddress'];
		echo "<tr><td>$r[MCID]</td>
		<td>$r[LName]</td><td>$r[FName]</td><td>$r[City]</td><td>$r[EmailAddress]</td><td>$r[PrimaryPhone]</td></tr>";	
		}
	echo '</table>';
if ($_SESSION['CTS_SecLevel'] == 'admin') {
	echo '<h3>Comma seperated list of email addresses</h3>';
	$emlist = implode($emarray, ', ');
	echo $emlist;
	}
}
echo '<br>==== END OF LIST ===<br>';
echo '</div>';
	
?>

</body>
</html>
