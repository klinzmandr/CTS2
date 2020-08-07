<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('2010-01-01', strtotime("now"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime('now -1 month -1 day'));

$retbtn = "";
if (isset($_SESSION['CTS_SessionUser'])) 
  $retbtn = '&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a>';
?>

<!DOCTYPE html>
<html>
<head>
<title>Chart Calls in Date Range</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>

<script>
$(function() {
  // alert("doc load");
$("form").submit(function(e) {
  //alert("form submit");
  dorangechk(e);
  });
$("#tabb").click(function() {
  $("#tab").toggle();
  });
});

function dorangechk(e) {
  // alert("checK archive date range");
  var early = Date.parse("2010-01-01");  
  var sd = $("#sd").val(); var sdms = Date.parse(sd);
  
  if (sdms < early) {
    e.preventDefault();
    alert("Start entered prior to Jan 1, 2010.");
    return;
    }
  return;
}

</script>

<div class="container">
<h3>Chart Calls History In Date Range&nbsp;
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span><?=$retbtn?></h3>
<div id=help>
<h4>Report Explanation</h4>
<p>This report is provided to allow a visual representation of the history of calls entered within the date range specified.  By default, the date range is for the current month.
<p><b>Date Picker TIP</b> - Click in the start or end date field to get the calendar dialog to select a specific date.  Click the header of the date picker calendar dialog once or twice to select a specific month or year before selection of a specific date.</p>
<p>Any date range is permissable except that the start date value must be after Jan 1, 2010 which is the earliest date for CTS recorded calls.</p>
<p>All calls within the selected range are summarized by month.  The values for the number of calls entered and the number of calls that were delivered to the Clinic are charted.</p>
</div>
<form action="rptcallcharts.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> to End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input class="hidden-print" type="submit" name="submit" value="Submit">
</form>

<?php

$edhms = date("Y-m-d 23:59:59",strtotime($ed));
$sql = "
SELECT C.`CallNbr`, C.`Resolution`, C.`DTOpened`, C.`OpenedBy`
FROM `calls` C
WHERE `DTOpened` BETWEEN '$sd' AND '$edhms'    
UNION ALL
SELECT CA.`CallNbr`, CA.`Resolution`, CA.`DTOpened`, CA.`OpenedBy`
FROM `callsarchive` CA
WHERE `DTOpened` BETWEEN '$sd' AND '$edhms'
ORDER BY `DTOpened` ASC
";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// echo "rc: $rc<br>";

$chartdata = array(); $ymoarray = array(); $rescounter = array();
while ($r = $res->fetch_assoc()) {
	$reccount += 1;
	$dtomy = date("Y-m", strtotime($r['DTOpened']));
	if ($r['Resolution'] == 'CaseDeliveredToCenter') { 
    $countarray['ToCtr'] += 1;
  	$rescounter[$dtomy] += 1;
    } 
	$ymoarray[$dtomy] += 1;
	}
// echo '<pre>'; print_r($ymoarray); echo '</pre>';
$cc = 'Total Counts for Date Range (Total/ToCtr): ';
$cc .= $reccount . '/' . $countarray['ToCtr'] . '<br>';
echo $cc; 

$tab = '<table border=1 class="sortable" align="center"><thead><tr><th>YrMo</th><th>Open</th><th>ToCtr</th></tr></thead><tbody>';
// $chartdata = "['YrMo', 'Open', 'ToCtr'], ";
$chartdata = "";

foreach ($ymoarray as $k => $v) {
  $chartdata .= "['$k', $v, $rescounter[$k]], "; 
  $tab .= "<tr><td>$k</td><td>$v</td><td>$rescounter[$k]</td></tr>";
  }
$tab .= '</tbody></table>';
$chartdata = rtrim($chartdata, ', ');
// echo '<pre>'; print_r($chartdata); echo '</pre>';

$charttitle = "Call History (Calls Opened vs. Calls Delivered to Center)";
// exit;

?>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- Create the chart -->
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
  data.addColumn('string', 'YearMo');
  data.addColumn('number', 'Opened');
  data.addColumn('number', 'ToCtr');
  data.addRows(
  [<?=$chartdata?>]);

  // Set chart options
  var options = {'title':'<?=$charttitle?>',
                 'legend': { 'position': "bottom" },
                 'width':900,
                 'height':400};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.LineChart(document.getElementById('chart1'));
  chart.draw(data, options);
}
</script>

</div>  <!-- container -->
<!--Div that will hold the pie chart-->
<div id="chart1"></div>
<div class="page-break" style="text-align: center; ">
<button id = tabb href=#>Show/Hide Tabular Data</button><br>
<div hidden id=tab ><b>Data Table</b><br>
<?=$tab?>
</div>
</div>  <!-- text-align  -->
<br><br><br>
</body>
</html>
