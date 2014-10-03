<!DOCTYPE html>
<html>
<head>
<title>Close Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body onchange="flagChange()">
<?php
session_start();
//include 'Incls/vardump.inc';
include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';
include 'Incls/datautils.inc';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$call = isset($_REQUEST['call']) ? $_REQUEST['call'] : ''; 

// update the database with the info and close the call
if ($action == 'close') {
	$closedate = date('Y-m-d H:i', strtotime(now));
	$updarray[Status] = 'Closed';
	$updarray[DTClosed] = $closedate;
	$updarray[TimeToResolve] =$_REQUEST['ttaken'];
	$updarray[Resolution] = $_REQUEST['resolution'];
	if (strlen($_REQUEST['closingnote']) > 0) {
		$notearray[CallNbr] = $call;
		$notearray[UserID] = $_SESSION['SessionUser'];
		$notearray[Notes] = 'Closing Note: ' . $_REQUEST['closingnote'];
		//echo '<pre> note '; print_r($notearray); echo '</pre>';
		sqlinsert("callslog", $notearray);
		unset($notearray);
		}
	//echo '<pre> upd '; print_r($updarray); echo '</pre>';
	sqlupdate('calls', $updarray, "`CallNbr` = '$call'");
	$action = '';
	}

// do sql query for all open calls for pv and list
$user = $_SESSION['SessionUser'];
if ($action == '') {
	$sql = "SELECT * FROM `calls` 
	WHERE `OpenedBy` = '$user' 
		AND `Status` = 'Open';";
$res = doSQLsubmitted($sql);
echo '<div class="container">
<h3>Close A Call</h3>
<table class="table-condensed">
<tr><th>CallNbr</th><th>Date</th><th>Description</th></tr>';
while ($r = $res->fetch_assoc()) {
	$cn = $r[CallNbr];
	echo "<tr onclick=\"window.location='callscloser.php?action=form&call=$cn';\" style='cursor: pointer;'><td>$cn</td><td>$r[DTOpened]</td><td>$r[Description]</td></tr>";
	}
echo '</table></div><script src="jquery.js"></script><script src="js/bootstrap.min.js"></script>
</body></html>';
exit;
}

// provide the closing form info for call close

// first validate that the call has all the info needed to close
$sql = "SELECT * from `calls` WHERE `CallNbr` = '$call'";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();
//echo '<pre> close record '; print_r($r); echo '</pre>';
$errs = '';
if ($r[AnimalLocation] == '') $errs .= 'Missing Animal Location<br>';
if ($r[CallLocation] == '') $errs .= 'Missing Call Location<br>';
if ($r[Property] == '') $errs .= 'Missing Property designation<br>';
if ($r[Species] == '') $errs .= 'Missing Species identification<br>';
if ($r[Name] == '') $errs .= 'No Caller Name has been entered<br>';
if ($r[Reason] == '') $errs .= 'No Reason provided for the call<br>';
if ($r[Description] == '') $errs .= 'No Call Description has been provided<br>';
//if ($r[] == '') $errs .= '<br>';

if ($action == 'force') $errs = '';	// ignore all errors if force close

if ($errs != '') {
print <<<errMsg
<script>
function confirmContinue() {
	var r=confirm("This action will cause a call to be closed with incomplete information.\\n\\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

Errors have been detected that prevent the call from being immediately closed.<br>
The following errors are being reported:<br>
<ul>$errs</ul>
<br>
<br>
<a onclick="return confirmContinue()" class="btn btn-danger" href="callscloser.php?action=force">CONTINUE ANYWAY</a>
<a class="btn btn-success" href="callupdatertabbed.php?callnbr=$call">Correct Errors</a><br>
<script src="jquery.js"></script><script src="js/bootstrap.min.js"></script>
</body></html>

errMsg;
exit;
}

// get closing info
echo '<div class="container">';
echo "<h3>Closing Call $call</h3>";
print <<<pagePart2
<script type="text/javascript" src="nicEdit.js"></script>
<script type="text/javascript">
	bkLib.onDomLoaded(function() { nicEditors.allTextAreas(); initSelects(this) });
</script>

<form action="callscloser.php" method="post" class="form">
Approx. Time to Resolution:
<input type="radio" name="ttaken" value="15">15&nbsp;
<input type="radio" name="ttaken" value="30">30&nbsp;
<input type="radio" name="ttaken" value="45">45&nbsp;
<input type="radio" name="ttaken" value="60">60&nbsp;
<input type="radio" name="ttaken" value="60+">60+

pagePart2;
echo '</select><br />Action Taken:
<select name="resolution" size="1">
<option value=""></option>';
loaddbselect("Actions");
echo "</select><br />
Closing Note:<br /><textarea name=\"closingnote\" rows=\"5\" cols=\"80\"></textarea>
<br /><br />
<input type=\"hidden\" name=\"call\" value=\"$call\">
<input type=\"hidden\" name=\"action\" value=\"close\">
<input type=\"submit\" name=\"submit\" value=\"Close Call\">
<form><br><br>
<a class=\"btn btn-danger\" href=\"callscloser.php\">CANCEL</a><br>
</div>";
?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
