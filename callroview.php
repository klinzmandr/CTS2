<?php
session_start();
$call = isset($_REQUEST['call']) ? $_REQUEST['call'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'notset';
// echo "referer: $referer<br>";
$_SESSION['4log'] = $call;

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';

$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$call';";
$res = doSQLsubmitted($sql);
$rows = $res->num_rows;
//echo "call rows: $rows<br>";
$r = $res->fetch_assoc();
//echo '<pre> call '; print_r($r); echo '</pre>';
// <tr><td></td><td>$r[]</td></tr>
$label = "$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";
if (strlen($r['Organization']) > 0) 
	$label = "$r[Organization]<br>$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";
echo '<div class="container">
<table class="table"><tr><td>';

if ($action != '') {
	echo "<br><h1>Call $call&nbsp;&nbsp;<a href=\"$referer\" class=\"btn btn-primary hidden-print\"><b>Return</b></a></h1> ";
	}
else {
  if ($_SESSION['CTS_SecLevel'] == 'admin') {
	  echo "<h1><a href=\"callupdatertabbed.php?action=view&callnbr=$call\">Call $call</a></h1>"; }
	else {
	  echo "<h1>Call $call</h1>"; }
	}
echo '</td><td><img src="https://apps.pacwilica.org/PWC_logo_only.jpg" width="200" alt="PWC logo" style="float: right; "></td></tr></table>';
if ($r['DTClosed'] == '') $end = strtotime('now');
else $end = strtotime($r['DTClosed']);
$start = strtotime($r['DTOpened']);
$duration = number_format(($end - strtotime($r['DTOpened']))/(24*60*60));
?>

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
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
if ($action == '') 
  include 'Incls/mainmenu.inc.php';
?>
<span style="font-size: larger; color: #FF0000; "><b><font size="+1">Call Detail</font></b></span>
<table class="table table-condensed" border=0 class="table-condensed">
<tr><td width="35%"><b>Call Status: </b><?=$r['Status']?></td></tr>
<td><b>Call Opened By:</b> <?=$r['OpenedBy']?></td>
<td><b>Last Updated By:</b> <?=$r['LastUpdater']?></td></tr>
<tr><td colspan="3"><b>Description:</b> <?=$r['Description']?><td></tr>
<tr>
<td title="Date and time call was placed on voice mail."><b>Call Placed: </b><br>&nbsp;&nbsp;<?=$r['DTPlaced']?></td>
<td title="Date and time call was entered into CTS"><b>Date & Time Opened:</b><br>&nbsp;&nbsp;<?=$r['DTOpened']?></td>
<td title="Date and time call was closed in CTS"><b>Date & Time Closed:</b><br>&nbsp;&nbsp;<?=$r['DTClosed']?></td>
<td><b>Duration:</b><br>&nbsp;&nbsp;<?=$duration?> day(s)</td>
</tr>
<tr><td><b>Animal Location:</b><br>&nbsp;&nbsp;<?=$r['AnimalLocation']?></td>
<td><b>Call Location:</b><br>&nbsp;&nbsp;<?=$r['CallLocation']?></td>
<td><b>Property:</b><br>&nbsp;&nbsp;<?=$r['Property']?></td>
<td><b>Species:</b><br>&nbsp;&nbsp;<?=$r['Species']?></td></tr>
<tr><td><b>Reason:</b>&nbsp;&nbsp;<?=$r['Reason']?></td></tr>
<tr><td><b>WRMD Ref. Number:</b>&nbsp;&nbsp;<?=$r['CaseRefNbr']?></td></tr>
<tr><td valign="top"><b>Resolution:</b> </td><td colspan="3"><?=$r['Resolution']?> updated at <?=$r['ResTOD']?> by <?=$r['ResBy']?><br></td></tr>
<tr><td><b>Time to Resolve:</b></td><td><?=$r['TimeToResolve']?> minutes</td></tr></table>

<span style="font-size: larger; color: #FF0000; "><b><font size="+1">Caller Detail</font></b></span>
<table border=0 class="table table-condensed">
<tr><td width="35%" valign="top"><b>Mailing Label:</b></td>
<td bgcolor="#E5E5E5"><?=$label?></td</tr>
<tr><td><b>Organization:</b> </td><td  colspan="3"><?=$r['Organization']?></td></tr>
<tr><td><b>Caller Name:</b> </td><td><?=$r['Name']?></td></tr>
<tr><td><b>Address:</b> </td><td><?=$r['Address']?></td></tr>
<tr><td><b>City, State Zip:</b> </td><td><?=$r['City']?>, <?=$r['State']?>  <?=$r['Zip']?></td></tr>
<tr><td><b>Email Address:</b> <?=$r['EMail']?></td><td><b>Phone Number:</b> <?=$r['PrimaryPhone']?></td></tr>
<tr><td><b>Postcard Sent?</b> <?=$r['PostcardSent']?></td><td><b>Email Sent?</b> <?=$r['EmailSent']?></td></tr>
</table>

<span style="font-size: larger; color: #FF0000; "><b><font size="+1">Call History (latest first)</font></b></span><br>
<?=$r['NotesDiary']?><br>

</div>
</body>
</html>
