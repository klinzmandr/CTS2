<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Users in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">

</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>

<script>
$(document).ready(function() {
// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });
  
$('#btnFILTER').click(function() { 
  inp = $('#inp').val();
  //console.log(inp);
  if (inp.length > 0) 
    $('tr').hide().filter(':containsNC('+inp+')').show();
    $("#head").show();
    chgFlag = 0;
    });
  
$('#btnALL').click(function() {
  $('tr').show();     
  $('#inp').val('');
  chgFlag = 0;
  });

});

</script>
<?php
//include 'Incls/mainmenu.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/datautils.inc.php';
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("now"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-d', strtotime("now"));
?>

<h3>List Users In Date Range&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>

<form action="rptusersbydaterange.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit">
</form>

<input id="filter" title="Charater string to match" type="text" value="">&nbsp;&nbsp;
<button id="btnALL">Show All</button>&nbsp;&nbsp;

<?php
$edhms = date("Y-m-d 23:59:59",strtotime($ed));
$sql = "SELECT * FROM `log` 
WHERE `DateTime` BETWEEN '$sd' AND '$edhms'
  AND `Text` LIKE '%Logged%' 
ORDER BY `LogID` ASC;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// echo "rc: $rc<br>";
$resarray = array(); $cntarray = array();
while ($r = $res->fetch_assoc()) {
  $dtarray[$r[User]] = $r[DateTime];
	$resarray[$r[User]] = $r[SecLevel];
	$cntarray[$r[User]] += 1;
	}
// echo '<pre> year '; print_r($resarray); echo '</pre>';

echo '<table class="table table-condensed">
<tr id-"head"><th>User ID</th><th>Last Visit Date/Time</th><th>SecLevel</th><th>TotalVisits</th></tr>';
if ($rc > 0) {
  asort($dtarray);
  foreach ($dtarray as $k => $v) {
  	//echo '<pre> year '; print_r($r); echo '</pre>';
  	if ($k == '') continue;
  	$c = $cntarray[$k];
  	echo "<tr><td>$k</td><td>$v</td><td>$resarray[$k]</td><td>$c</td></tr>";
  	}
  echo '</table>';
  echo "=== END OF REPORT===<br>";
  }
else {
  echo "</table><h3>No user activity to report</h3><br><br>=== END OF REPORT===<br>";
  }
?>

</body>
</html>
