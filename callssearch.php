<!DOCTYPE html>
<html>
<head>
<title>Search Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

if ($action == '') {
print <<<pagePart1
<div class="container">
<h3>Search All Calls</h3>
<p>General search of all calls for given search string.</p>
<h4>Enter search string:</h4>
<form action="callssearch.php" method="post">
Call Status: <input type="radio" name="status" value="Open" checked="checked">Open
&nbsp;&nbsp;&nbsp;<input type="radio" name="status" value="Closed">Closed<br>
<input type="text" name="search" size="50" value="" autofocus placeholder="Search string">
<br><br>
<input type="hidden" name="action" value="search">
<input type="submit" name="submit" value="Submit">
</form><br><br>
<b>Hints:</b><br>

<ol>
	<li>Enter a 3-5 character search string.  The longer the string, usually the fewer the results listed.</li>
	<li>Avoid special characters like &lt;, &gt;, :, !, $, %, &apos; and so on.</li>
</ol>

</div>
</body></html>

pagePart1;
exit;
}

// $action == search
$sql = "SELECT * FROM `calls` 
	WHERE (`AnimalLocation` LIKE '%$search%' 
		OR  `CallLocation` LIKE '%$search%'
		OR  `Property` LIKE '%$search%'
		OR  `Species` LIKE '%$search%'		
		OR  `Resolution` LIKE '%$search%'
		OR  `Reason` LIKE '%$search%'
		OR  `Organization` LIKE '%$search%'
		OR  `Name` LIKE '%$search%'
		OR  `Address` LIKE '%$search%'
		OR  `City` LIKE '%$search%'
		OR  `EMail` LIKE '%$search%'
		OR  `Description` LIKE '%$search%'
		OR  `OpenedBy` LIKE '%$search%')
	AND 	`Status` = '$status';";
		
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo "<div class=\"container\"><h4>Rows matched: $rc</h4>";
echo '<table border="0" class="table table-condensed table-hover">'.$rpthdg;
while ($r = $res->fetch_assoc()) {
//	echo '<pre>'; print_r($r); echo '</pre>';
	$callnbr = $r[CallNbr]; $dtopened = $r[DTOpened]; $openedby = $r[OpenedBy];
	$lastupdater = $r[LastUpdater]; $desc = $r[Description];
	if ($status == 'Closed') 
		echo "<tr onclick=\"window.location='callroview.php?call=$callnbr'\" style='cursor: pointer;'>";
	else
		echo "<tr onclick=\"window.location='callupdatertabbed.php?action=view&callnbr=$callnbr'\" style='cursor: pointer;'>";
	echo '<td>'.$callnbr.'</td>
	<td>'.$dtopened.'</td>
	<td>'.$openedby.'</td>
	<td>'.$desc.'</td>
	</tr>';
	}
echo '</table></div>--- END OF LIST ---';
?>

</body>
</html>
