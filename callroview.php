<!DOCTYPE html>
<html>
<head>
<title>Closed Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<!-- <link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> -->
<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc';
include 'Incls/datautils.inc';
include 'Incls/seccheck.inc';
echo "<div class=\"hidden-print\">";
include 'Incls/mainmenu.inc';
echo "</div>  <!-- hidden-print -->";

$call = isset($_REQUEST['call']) ? $_REQUEST['call'] : '';
$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$call';";
$res = doSQLsubmitted($sql);
$rows = $res->num_rows;
//echo "call rows: $rows<br>";
$r = $res->fetch_assoc();
//echo '<pre> call '; print_r($r); echo '</pre>';
// <tr><td></td><td>$r[]</td></tr>
$label = "$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";
if (strlen($r[Organization]) > 0) 
	$label = "$r[Organization]<br>$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";
print <<<pagePart1
<div class="container">
<h3>Call $call</h3>
<!-- <a class="btn btn-default btn-xs" href="#">RETURN</a> -->
<span style="font-size: larger; color: #1E90FF; "><b>Call Detail</b></span>
<table border="0" class="table-condensed">
<tr><td><b>Call Status:</b> $r[Status]</td></tr>
<tr><td><b>Call Opened By:</b> $r[OpenedBy]</td>
<td><b>Last Updated By:</b> $r[LastUpdater]</td></tr>
<tr><td colspan="4"><b>Description:</b> $r[Description]<td></tr>
<tr><td><b>Date & Time Entered:</b><br>&nbsp;&nbsp;$r[DTOpened]</td>
<td><b>Date & Time Closed:</b><br>&nbsp;&nbsp;$r[DTClosed]</td></tr>
<tr><td><b>Animal Location:</b><br>&nbsp;&nbsp;$r[AnimalLocation]</td>
<td><b>Call Location:</b><br>&nbsp;&nbsp;$r[CallLocation]</td>
<td><b>Property:</b><br>&nbsp;&nbsp;$r[Property]</td>
<td><b>Species:</b><br>&nbsp;&nbsp;$r[Species]</td></tr>
<tr><td><b>Resolution:</b> </td><td colspan="3">$r[Resolution]</td></tr></table>
<table border="0" class="table-condensed">
<span style="font-size: larger; color: #1E90FF; "><b>Caller Detail</b></span>
<tr><td valign="top"><b>Mailing Label:</b></td>
<td bgcolor="#E6E6FA">$label</td</tr>
<tr><td><b>Organization:</b> </td><td  colspan="3">$r[Organization]</td></tr>
<tr><td><b>Caller Name:</b> </td><td>$r[Name]</td></tr>
<tr><td><b>Address:</b> </td><td>$r[Address]</td></tr>
<tr><td><b>City, State Zip:</b> </td><td>$r[City], $r[State]  $r[Zip]</td></tr>
<tr><td><b>Email Address:</b> $r[EMail]</td><td><b>Phone Number:</b> $r[PrimaryPhone]</td></tr>
</table>

pagePart1;
$sql = "SELECT * FROM `callslog` WHERE `CallNbr` = '$call' ORDER BY `DateTime` DESC;";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
if ($rc > 0) {
	echo '<br><span style="font-size: larger; color: #1E90FF; "><b>Call History (latest first)</b></span>';
	echo "<table class=\"table-condensed\">";
	while ($r = $res->fetch_assoc()) {
		//echo '<pre> notes '; print_r($r); echo '</pre>';
		$dt = date('Y-m-d \a\t H:i',strtotime($r[DateTime]));
		echo "<tr><td>DateTime: $dt&nbsp;&nbsp;By: $r[UserID]<br><ul>$r[Notes]</ul></td></tr>";
		}
	echo '</table>';
	}
?>
</div>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
