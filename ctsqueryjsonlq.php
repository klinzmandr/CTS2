<?php
session_start();
// AJAX response code - bootstrap is implemented in the receiving page.
include 'Incls/datautils.inc.php';
$date = $_REQUEST['date'];
$dbstart = date("Y-m-d 00:00:01", strtotime($date));
$dbend = date("Y-m-d 23:59:59", strtotime($date));
$sql  = "
SELECT * FROM `log`
WHERE `DateTime` BETWEEN '$dbstart' AND '$dbend';"; 

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$r = $res->fetch_assoc();
$userArray = array();
$resultsLines = '';
// $resultsLines .= "Rows extracted: $rc<br>";
while ($r = $res->fetch_assoc()) {
  // $resultsLines .= "<pre>row ".print_r($r,TRUE)."</pre>";
	if (preg_match('/logged in/i', $r['Text'])) {
	  // $resultsLines .= "logged in found\n";
    $userArray[$r['User']]['loggedin'] = $r['DateTime']; 
    $userArray[$r['User']]['loggedincount'] += 1; 
    }
	if (preg_match('/call inserted/i', $r['Text'])) { 
    // $resultsLines .= "new call found\n";
    $userArray[$r['User']]['callinsert'] = $r['DateTime'];
    $userArray[$r['User']]['callinsertcount'] += 1;
    }
  }
// $resultsLines .= "<pre>user ".print_r($userArray,TRUE)."</pre>";

if  (count($userArray) <= 0) {
  $resultslines .= '<tr><td><h2>No users logged in for today.</h2></td></tr>';
  }
else {
  $resultsLines .= '<tr><th>User</th><th>Last login</th><th>Count</th><th>Last call added</th><th>Count</th></tr>';
  foreach ($userArray as $k => $v) {
    // $resultsLines .= "<pre>user $k ".print_r($v,TRUE)."</pre>";
    $resultsLines .= "<tr><td>$k</td><td>$v[loggedin]</td><td>$v[loggedincount]</td><td>$v[callinsert]</td><td>$v[callinsertcount]</td></tr>";
    }
  }

?>
<table class="table table-condensed" border=0 class="table-condensed">
<?=$resultsLines?>
</table>


