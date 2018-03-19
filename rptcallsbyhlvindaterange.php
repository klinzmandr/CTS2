<!DOCTYPE html>
<html>
<head>
<title>List Calls in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">

</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="Incls/bootstrap-datepicker-range.inc.php"></script>

<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

print <<<pagePart1
<div class="container">
<h3>List Calls In Date Range&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>

pagePart1;
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
print <<<inputForm

<form action="rptcallsbyhlvindaterange.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="$sd"> to End Date:  
<input type="text" name="ed" id="ed" value="$ed">
<input class="hidden-print" type="submit" name="submit" value="Submit">
</form>

inputForm;

$edhms = date("Y-m-d 23:59:59",strtotime($ed));
$sql = "SELECT * FROM `calls` 
WHERE `DTOpened` BETWEEN '$sd' AND '$edhms'
ORDER BY `CallNbr` DESC;";
//echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$resarray = array(); $countarray = array();
$countarray[Total] = 0;$countarray[Open] = 0; $countarray[Closed] = 0; $countarray[Center] = 0;
$hlvarray = array();
  
while ($r = $res->fetch_assoc()) {
	$countarray[Total] += 1;
	if ($r[Status] == 'Open') {
	  $countarray[Open] += 1;
	  $hlvarray[$r[OpenedBy]][open] += 1;
	  }
	if ($r[Status] == 'Closed') {
	  $countarray[Closed] += 1;
	  $hlvarray[$r[OpenedBy]][closed] += 1;	  
    }
	if (strpos($r[Resolution],'Center') > 0) {
	  $countarray[Center] += 1;
	  $hlvarray[$r[OpenedBy]][center] += 1;
  	}
  $datems = strtotime($r[DTOpened]);
  if ((!isset($hlvarray[$r[OpenedBy]][first])) OR ($hlvarray[$r[OpenedBy]][first] < $datems)) {
    list($hlvarray[$r[OpenedBy]][first], $x) = explode(' ', $r[DTOpened]); 
    //echo 'first DTOpened: '. $r[DTOpened] . " datems: $datems<br>"; 
    }
  if ((!isset($hlvarray[$r[OpenedBy]][last])) OR ($hlvarray[$r[OpenedBy]][last] > $datems)) {
    list($hlvarray[$r[OpenedBy]][last], $x) = explode(' ', $r[DTOpened]);
    //echo 'hlv: ' . $r[OpenedBy] . ' last DTOpened: '. $r[DTOpened] . " datems: $datems<br>"; 
    }
	}
$cc = 'Total Counts for Date Range (Total/Open/Closed/ToCtr): ';
$cc .= $countarray[Total] . '/' . $countarray[Open] . '/' . $countarray[Closed] . '/' . $countarray[Center] . '<br>';
echo $cc; 
$piechartdata = "['Open',$countarray[Open]], ['Closed', $countarray[Closed]]";
//echo '<pre> hlv '; print_r($hlvarray); echo '</pre>';
//exit;
?>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Calls');
  data.addColumn('number', 'Count');
  data.addRows(
  [<?=$piechartdata?>]);
  // [['one',1],['two',2],['three',3]]);

  // Set chart options
  var options = {'title':'Call Distribution',
                 'width':400,
                 'height':300};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById('chart1'));
  chart.draw(data, options);
}
</script>

<!--Div that will hold the pie chart-->
<div id="chart1"></div>

<?php
echo '<table class="table table-condensed">
<tr><th>HLV Id</th><th>Total</th><th>Open</th><th>Closed</th><th>To Ctr</th><th>Earliest Opened</th><th>Last Opened</th></tr>';
foreach ($hlvarray as $k => $r) {
  $count = $r[open] + $r[closed];
  $tot[$k] = $count;
	echo '<td>'.$k.'</td><td>'.$count.'</td><td>'.$r[open].'</td><td>'.$r[closed].'</td><td>'.$r[center].'</td><td>'.$r[first].'</td><td>'.$r[last].'</td></tr>';
	}
echo '</table>';
$str = '';
if (count($tot) > 0) {
  ksort($tot);
  foreach ($tot as $k => $v) {
    $str .= "['$k', $v],";
    }
  $barchartdata = rtrim($str, ','); 
  }
?>
<!--Load the AJAX API-->
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script type="text/javascript">

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Calls');
  data.addColumn('number', 'Count');
  data.addRows(
  [<?=$barchartdata?>]);
  //[['one',1],['two',2],['three',3]]);

  // Set chart options
  var options = {'title':'HLV Call Distribution',
                 'width':600,
                 'height':400};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.BarChart(document.getElementById('chart2'));
  chart.draw(data, options);
}
</script>

<!--Div that will hold the pie chart-->
<div id="chart2"></div>

<br>=== END OF REPORT===<br>
</div>
</body>
</html>
