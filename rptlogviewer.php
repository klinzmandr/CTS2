<!DOCTYPE html>
<html>
<head>
<title>Log Viewer</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet"> 

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>

<script>
// initial setup of jquery function(s) for page
$(document).ready(function () {
  // alert("on document load.");
  $("#sd").change (function() {
  // alert("setval entered");
  var v = $('#sd').val();   // set end date = start date
  return true;
  });
  $("form").submit (function() {
  var d1 = $('#sd').val(); var d2 = $('#ed').val();
  if ((d1.length == 0) || (d2.length == 0)) {
    alert("Missing a start or end date.");
    return false;
    }
  var d1val = Date.parse(d1); var d2val = Date.parse(d2);
  if (d2val <= d1val) {
    alert("Invalid date range. End date before or same as start date.");
    return false;
    }
  return true;
  });

});  // end ready function
</script>
</head>
<body>

<?php
session_start();
//include 'Incls/vardump.inc.php';


// connect to production database
define("ProdDBName", 'pacwilic_phplist');define("DBUserName", 'pacwilic_drkphp');define("DBPassword", 'gsAHMzyiNoxQd7P4');
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-d 00:00', strtotime('now'));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-d 23:59', strtotime('now'));
$bl = isset($_REQUEST['bl']) ? true : false;

print <<<sendForm
<h3>CTS2 Log Viewer&nbsp;&nbsp;&nbsp;&nbsp;
<a class="btn btn-primary" href="javascript:self.close();">CLOSE</a></h3>

<form action="rptlogviewer.php" method="post">
Start: <input type="text" id="sd" name="sd" value="$sd">
End: <input type="text" id="ed" name="ed" value="$ed">
Filter: <input type="text" name="filter" value="$filter">
<input type="hidden" name="action" value="gen">
<input type="submit" name="submit" value="Submit">
</form>

<script type="text/javascript">
$('#sd').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    todayHighlight: true,
    // todayBtn: true,
    // showMeridian: true,
    minuteStep: 15,
    autoclose: true
});
</script>
<script type="text/javascript">
$('#ed').datetimepicker({
    format: 'yyyy-mm-dd hh:ii',
    todayHighlight: true,
    // todayBtn: true,
    // showMeridian: true,
    minuteStep: 15,
    autoclose: true
});
</script>
sendForm;

include 'Incls/datautils.inc.php';

// echo '<pre>'; print_r($_REQUEST); echo '</pre>';
$sql = "
SELECT * FROM `log` 
WHERE `DateTime` BETWEEN '$sd' AND '$ed' 
AND (`User` LIKE '%$filter%' 
 OR `Page` LIKE '%$filter%' 
 OR `SecLevel` LIKE '%$filter%' 
 OR `Text` LIKE '%$filter%')
ORDER BY `LogID` ASC";
// echo "sql: $sql<br>";

$uures = doSQLsubmitted($sql);
$nr = $uures->num_rows;

$html[] = '
<table border=0 class="table" >
<tr><th>Log Id</th><th>DT Entered</th><th>User</th><th>SecLevel</th><th>Page</th><th>Log Text</th></tr>';
while ($r = $uures->fetch_assoc()) {
    $html[] = "<tr><td>$r[LogID]</td><td>$r[DateTime]</td><td>$r[User]</td><td>$r[SecLevel]</td><td>$r[Page]</td><td>$r[Text]</td></tr>";  
  } 
$html[] = '</table>';

if ($filter == '') $filter = 'NONE';
echo "Start date: $sd, End date: $ed, Filter: $filter, Log entries returned: $nr<br>";
foreach ($html as $v) {
  echo "$v\n";
  }
echo '  
<br>============== END REPORT =================<br><br><br><br>';
?>

</body>
</html>
