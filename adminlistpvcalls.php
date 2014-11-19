<!DOCTYPE html>
<html>
<head>
<title>List Calls for a PV</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc';
include 'Incls/datautils.inc';
include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : '';

if ($action == '') {
print <<<pagePart1
<div class="container">
<h3>Admin: Calls for a Phone Volunteer</h3>
<p>This listing is provided to allow the administrator to list all calls for a specific PV.</p>

pagePart1;
$sql = "SELECT `UserID` from `cts2users` WHERE '1' ORDER BY `UserID` ASC;";
$res = doSQLsubmitted($sql);
echo '<form action="adminlistpvcalls.php" method="post"  class="form">
Choose a user: <select onchange="this.form.submit()" name="userid">
<option value=""></option>';
while ($uid = $res->fetch_assoc()) {
	$userid = $uid[UserID];
	//echo '<pre> user '; print_r($uid); echo '</pre>';
	echo "<option value=\"$userid\">$userid</option>";
	}
echo '</select><br>
<input type="hidden" name="action" value="list">
</form>';
echo '</div><script src="jquery.js"></script><script src="js/bootstrap.min.js"></script></body></html>';
exit;
}

// create listing for user selected
//echo "list calls for: $userid<br>";

$sql = "SELECT * FROM `calls` WHERE `OpenedBy` = '$userid' ORDER BY `CallNbr` DESC;";
$res = doSQLsubmitted($sql);
$rows = $res->num_rows;
if ($rows == 0) { echo "no rows found<br>"; };
echo "<div class=\"container\">
<h3>All Calls for $userid</h3>";

echo '<table border="0" class="table table-condensed table-hover">
</tr><th>Call#</th><th>Status</th><th><----DTOpened----></th><th>OpenedBy</th><th>Description</th></tr>';
while ($r = $res->fetch_assoc()) {
	//echo '<pre> user '; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr]; $status = $r[Status]; 
	$dtopened = $r[DTOpened]; $openedby = $r[OpenedBy];
	$lastupdater = $r[LastUpdater]; $desc = $r[Description];
	if ($status == 'Open') {
	echo "<tr onclick=\"window.location='callupdatertabbed.php?action=view&callnbr=$callnbr'\" style='cursor: pointer;'>"; }
	else {
		echo "<tr onclick=\"window.location='callroview.php?call=$callnbr'\" style='cursor: pointer;'>"; }
	echo '<td>'.$callnbr.'</td>
	<td>'.$status.'</td>
	<td>'.$dtopened.'</td>
	<td>'.$openedby.'</td>
	<td>'.$desc.'</td>
	</tr>';
	}
	echo '</table>';

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
