<!DOCTYPE html>
<html>
<head>
<title>Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body onchange="flagChange()">
<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$userid = $_SESSION['CTS_SessionUser'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action == 'MyClosed') {
	$rpthdg = "<tr><th>Call#</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>";
	$hdg = 'My Closed';
	$sql = "SELECT * from `calls` 
	WHERE `Status` = 'Closed' 
		AND `OpenedBy` = '$userid'
	ORDER BY `CallNbr` DESC;";
	}
elseif ($action == 'AllOpen') {
	$rpthdg = "</tr><th>Call#</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>";
	$hdg = 'All Open';
	$sql = "SELECT * from `calls` WHERE `Status` = 'Open';";
	}
	
else {		// gotta be MyCalls then
	$hdg = 'My Open';
	$rpthdg = "<tr><th>Call#</th><th>Date/TimeOpened</th><th>Date/TimePlaced</th><th>OpenedBy</th><th>Description</th></tr>";
	$sql = "SELECT * from `calls` 
		WHERE ( `Status` = 'Open' OR `Status` = 'New'	)
		AND `OpenedBy` = '$userid';";
	}
$res = doSQLsubmitted($sql);
$rows = $res->num_rows;
// if ($rows == 0) { echo "no rows found<br>"; };
echo '<div class="container">
<h3>'.$hdg.' Calls<img id="chgflg" hidden src="img/Cancel__Red.png" width="16" height="16" /></h3>
';
echo '<table border="0" class="table table-condensed table-hover">'.$rpthdg;
while ($r = $res->fetch_assoc()) {
	// echo '<pre>'; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr]; $dtopened = $r[DTOpened]; $dtplaced=$r[DTPlaced]; 
	$openedby = $r[OpenedBy]; $lastupdater = $r[LastUpdater]; $desc = $r[Description];
	if ($action == 'MyClosed') 
		echo "<tr onclick=\"window.location='callroview.php?call=$callnbr'\" style='cursor: pointer;'>";
	else
		echo "<tr onclick=\"window.location='callupdatertabbed.php?action=view&callnbr=$callnbr'\" style='cursor: pointer;'>";
	echo '<td>'.$callnbr.'</td>
	<td>'.$dtopened.'</td>
	<td>'.$dtplaced.'</td>
	<td>'.$openedby.'</td>
	<td>'.$desc.'</td>
	</tr>';
	}
echo '</table><br>==== END OF LIST ====<br>
</div>  <!-- container -->';

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
