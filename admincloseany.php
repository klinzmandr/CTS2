<?php
session_start();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$call = isset($_REQUEST['call']) ? $_REQUEST['call'] : ''; 
$user = $_SESSION['CTS_SessionUser'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Close Any Open Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

// update the database with the info and close the call
if ($action == 'close') {
	$closedate = date('Y-m-d H:i', strtotime(now));
	$updarray['Status'] = 'Closed';
	$updarray['DTClosed'] = $closedate;
	$updarray['TimeToResolve'] = isset($_REQUEST['ttaken']) ? $_REQUEST['ttaken'] : '15';
	if (strlen($updarray['Resolution'] == 0)) 
    $updarray['Resolution'] =  'Admin forced close without resolution';
	if (isset($_REQUEST['AnimalLocation'])) $updarray['AnimalLocation'] = $_REQUEST['AnimalLocation'];
	if (isset($_REQUEST['CallLocation'])) $updarray['CallLocation'] = $_REQUEST['CallLocation'];
	if (isset($_REQUEST['Property'])) $updarray['Property'] = $_REQUEST['Property'];
	if (isset($_REQUEST['Species'])) $updarray['Species'] = $_REQUEST['Species'];
	if (isset($_REQUEST['Name'])) $updarray['Name'] = $_REQUEST['Name'];
	if (isset($_REQUEST['Reason'])) $updarray['Reason'] = $_REQUEST['Reason'];
	if (isset($_REQUEST['Description'])) $updarray['Description'] = $_REQUEST['Description'];
  $updarray['LastUpdater'] = $user;
  $closingnote = isset($_REQUEST['closingnote']) ? $_REQUEST['closingnote'] : '';
  $closingnote = str_replace("\n", "<br>", $closingnote);
  if (strlen($closingnote) == 0) $closingnote = 'Admin closed without comment<br>';
  $notesdiary = isset($_REQUEST['notesdiary']) ? $_REQUEST['notesdiary'] : '';
  $finalnote = '<ul>'. $closingnote . '</ul>' . $notesdiary;
  $updarray['NotesDiary'] = "DateTime: $closedate&nbsp;&nbsp;By: $user $finalnote";
//	echo '<pre> upd '; print_r($updarray); echo '</pre>';
	sqlupdate('calls', $updarray, "`CallNbr` = '$call'");
	$action = '';
	}

// do sql query for all open calls for pv and list
if ($action == '') {
	$sql = "SELECT * FROM `calls`	WHERE `Status` = 'Open';";
	$res = doSQLsubmitted($sql);
//	echo "sql: $sql<br>";
	echo '<div class="container">
<h3>Admin: Close Any Open Call</h3>
<table class="table-condensed">
<tr><th>CallNbr</th><th>Date</th><th>OpenedBy</th><th>Description</th></tr>';
	while ($r = $res->fetch_assoc()) {
		$cn = $r['CallNbr'];
		echo "<tr onclick=\"window.location='admincloseany.php?action=form&call=$cn';\" style='cursor: pointer;'><td>$cn</td><td>$r['DTOpened']</td><td>$r['OpenedBy']</td><td>$r['Description']</td></tr>";
		}
	echo '</table>===== END LIST =====</div></body></html>';
	exit;
	}

// provide the closing form info for call close
$sql = "SELECT * from `calls` WHERE `CallNbr` = '$call';";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();
//		echo "sql: $sql<br>";
$notesdiary = $r['NotesDiary']; // save

// first validate that the call has all the info needed to close
if (($action == 'form') OR ($action == 'force')) {
//	echo "action check: $action<br>";
// if regular close check if call is properly completed	
	if ($action == 'form') {
//		echo '<pre> close record '; print_r($r); echo '</pre>';
		$errs = '';
		if ($r['AnimalLocation'] == '') $errs .= 'Missing Animal Location<br>';
		if ($r['CallLocation'] == '') $errs .= 'Missing Call Location<br>';
		if ($r['Property'] == '') $errs .= 'Missing Property designation<br>';
		if ($r['Species'] == '') $errs .= 'Missing Species identification<br>';
		if ($r['Name'] == '') $errs .= 'No Caller Name has been entered<br>';	
		if ($r['Reason'] == '') $errs .= 'No Reason provided for the call<br>';	
		if ($r['Description'] == '') $errs .= 'No Call Description has been provided<br>';
		}
	if ($errs != '') {
	print <<<errMsg
<script>
function confirmContinue() {
	var r=confirm("This action will cause a call to be closed with incomplete information.\\n\\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>
<div class="container">
<h3>Error(s) when closing a call</h3>
Errors have been detected that prevent the call from being immediately closed.<br>
The following errors are being reported:<br>
<ul>$errs</ul>
<br>
<br>
<a class="btn btn-warning" href="admincloseany.php">CANCEL</a>&nbsp;&nbsp;
<a class="btn btn-danger" href="admincloseany.php?action=force&call=$call" onclick="return confirmContinue()">CONTINUE ANYWAY</a>&nbsp;&nbsp;
<a class="btn btn-success" href="callupdatertabbed.php?callnbr=$call">Correct Errors</a><br><br>
</body></html>

errMsg;
	exit;
	}
	
// no errors so get info to close
	echo '<div class=container>';
	echo "<h3>Admin: Closing Call $call</h3>";
	print <<<pagePart2

	<form action="admincloseany.php" method="post" class="form">
	Approx. Time to Resolution:
	<input type="radio" name="ttaken" value="<15"><15&nbsp;&nbsp;&nbsp;
	<input type="radio" name="ttaken" value="<30"><30&nbsp;&nbsp;&nbsp;
	<input type="radio" name="ttaken" value="<45"><45&nbsp;&nbsp;&nbsp;
	<input type="radio" name="ttaken" value="<60"><60&nbsp;&nbsp;&nbsp;
	<input type="radio" name="ttaken" value="60+">60+

pagePart2;

	if ($action == 'force') {
//		echo 'doing a force close<br>';
// read record to get original info
		$sql = "SELECT * FROM `calls` WHERE `CallNbr` = '$call';";
		$res = doSQLsubmitted($sql);
		$r = $res->fetch_assoc();
//		echo "<br>sql: $sql<br>";
//		echo '<pre> original '; print_r($r); echo '</pre>';
		if ($r['AnimalLocation'] == '') 
			echo "<input type=\"hidden\" name=\"AnimalLocation\" value=\"NA\">";
		if ($r['CallLocation'] == '') 
			echo "<input type=\"hidden\" name=\"CallLocation\" value=\"NA\">";
		if ($r['Property'] == '') 
			echo "<input type=\"hidden\" name=\"Property\" value=\"NA\">";
		if ($r['Species'] == '') 
			echo "<input type=\"hidden\" name=\"Species\" value=\"NA\">";
		if ($r['Name'] == '') 
			echo "<input type=\"hidden\" name=\"Name\" value=\"NA\">";
		if ($r['Reason'] == '') 
			echo "<input type=\"hidden\" name=\"Reason\" value=\"Other\">";
		if ($r['Description'] == '') 
			echo "<input type=\"hidden\" name=\"Description\" value=\"Call force closed by admin with no description\">";
		$closingnote = 'Call force closed by admin.<br>';
		}
  }
?>
</select><br />Action Taken:
<select name="Resolution" size="1">
<option value=""></option>
<?php loaddbselect("Actions"); ?>
</select><br />
Closing Note:<br /><textarea name="closingnote" rows="5" cols="80"><?=$closingnote?></textarea><br /><br />
<input type="hidden" name="call" value="<?=$call?>">
<input type=hidden name=notesdiary value="<?=$notesdiary?>">
<input type="hidden" name="action" value="close">
<input type="submit" name="submit" value="Close Call">
</form>
<br><br>
<a class="btn btn-danger" href="admincloseany.php">CANCEL</a><br>
</div>
</body>
</html>
