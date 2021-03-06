<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
//include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$dbinuse = "DB in use: " . $_SESSION['CTS_DB_InUse'] . "<br>";
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$sd = isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : date('Y-m-01', strtotime("today"));
$ed = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : date('Y-m-t', strtotime("today"));
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '%mbrdb/%';
$testdb = isset($_REQUEST['testdb']) ? $_REQUEST['testdb'] : '';
?>
<!DOCTYPE html>
<html>
<head>
<title>Report on Reports</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body onload="initSelect()">
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>

<script>
$(function initSelect() {
// Initialize a selection list (single valued)
var patt = '<?=$type?>';
//alert("initSelect: pattern: " + patt);
if (patt == "") return;
  $("select").val(patt);
	});
</script>
<h3>Report on Page Usage</h3>
$dbinuse<br>
<!-- <h4>Page useage selected: $type</h4> -->
<form name="inform" action="rpt.php" method="post">

<input type="text" name="from_date" value="<?=$sd?>"  placeholder="Start Date" class="from_date" id="sd">
&nbsp;&nbsp;
<input type="text" name="to_date" value="<?=$ed?>" placeholder="End Date" class="from_date" id="ed">

<input type="hidden" name="action" value="continue">
<select name="type">
<option value=""></option>
<option value="%cts2/%">ALL</option >
<option value="%cts2/rpt%">Reports</option>
<option value="%cts2/call%">Calls</option>
<option value="%cts2/adm%">Administrative</option>
</select>
<input type="hidden" name="testdb" value="$testdb">
&nbsp;&nbsp;<input type="submit" name= "submit" value="Submit">
</form>
<p>This report peruses the system log and isolates all web pages that have been used and summaries their usage by user.</p>

<?php
if ($testdb != '') echo "Using Test Database<br>";
if ($type == '') echo "<a href=\"rpt.php\">No page type selected - RETRY</a><br /><br />";

//echo "sd: $sd, ed: $ed<br />";
// generate the report
$msd = date('Y-m-d 00:00:00', strtotime($sd)); $med = date('Y-m-d 23:59:59', strtotime($ed));
$sql = "SELECT `DateTime`,`User`,`Page`, `Text`
FROM `log`
WHERE  `DateTime` BETWEEN '$msd' AND '$med' 
AND `Page` LIKE '$type' 
AND `Text` LIKE 'Page Load%' 
ORDER BY `DateTime` ASC;";
echo "sql: $sql<br />";

$rc = 0;
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo "Total Pages Used: $rc<br />";
$whatarray = array(); $whoarray = array(); $whotimemin = array(); $whotimemax = array();
while ($r = $res->fetch_assoc()) {
//	echo '<pre> reports '; print_r($r); echo '</pre>';
	$exprpt = explode('/', $r[Page]);
	$exprpt[0] = $r[User];			// debug
	if ($r[User] == '') continue;
	$whatarray[$exprpt[2]] += 1;
	$whoarray[$r[User]] += 1;
	if (($r[DateTime] < $whotimemin[$r[User]]) OR (!isset($whotimemin[$r[User]]))) 
		$whotimemin[$r[User]] = $r[DateTime]; 
	if ($r[DateTime] > $whotimemax[$r[User]]) $whotimemax[$r[User]] = $r[DateTime];
	$comboarray[$exprpt[2]] [$r[User]] += 1;
	$combotimearray[$exprpt[2]][$r[User]] = $r[DateTime];
	$usercountarray[$r[User]] += 1;
	$userarray[$r[User]] [$exprpt[2]] += 1;
	//echo '<pre> reports '; print_r($exprpt); echo '</pre>';
	}

// echo '<pre> User start '; print_r($whotimemin); echo '</pre>';
// echo '<pre> User end '; print_r($whotimemax); echo '</pre>';	
echo '<table border=1 class="table table-condensed"><tr><td valign="top"><h4>Pages Most Used</h4><ul>';
arsort($whatarray);
foreach ($whatarray as $k => $v) {
	echo "$k: $v<br />";
	}
echo '</ul>';
echo '</td><td valign="top"><h4>Page Users</h4><ul>';
arsort($whoarray);

foreach ($whoarray as $k => $v) {
	echo "$k: $v<br />&nbsp;&nbsp;(first: $whotimemin[$k], last: $whotimemax[$k])<br />";
	}
echo '</ul>';
echo '</td></tr><tr><td valign="top"><h4>Pages By User</h4><ul>';
if (count($comboarray) > 0) foreach ($comboarray as $k => $v) {
	echo "$k<br /><ul>";
	foreach ($v as $kk => $vv) {
		echo "$kk -> $vv<br />";
		}
	echo '</ul>';
	}
echo '</ul>';
echo '</td><td valign="top"><h4>Users By Page:</h4><ul>';
if (count($userarray) > 0) foreach ($userarray as $k => $v) {
	echo "$k->$usercountarray[$k]<br /><ul>";
	foreach ($v as $kk => $vv) {
		echo "$kk -> $vv, Last @ " . $combotimearray[$kk][$k] . "<br />";
		}
	echo '</ul>';
	}
echo '</td></tr></table>';

// echo '<pre> what '; print_r($whatarray); echo '</pre>';
// echo '<pre> who '; print_r($whoarray); echo '</pre>';
// echo '<pre> combo '; print_r($comboarray); echo '</pre>';


?>

</body>
</html>
