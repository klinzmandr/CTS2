<?php
session_start();
if (!isset($_SESSION['CTS_SessionUser'])) {
  echo '<h1>SESSION HAS TIMED OUT.</h1>';
  echo '<h3 style="color: red; "><a href="indexsto.php">Log in again</a></h3>';
  exit;
  }

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("now"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("now"));

?>
<!DOCTYPE html>
<html>
<head>
<title>CTS Monthly Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<script src="js/bootstrap-sortable.js"></script>

<script>
// initial setup of jquery function(s) for page
$(document).ready( function () {
  // alert ("document ready");
  $("#HLV, #CITY").hide();
  $("#HLVBtn").click( function() {
    $("#HLV").toggle();
    });
  $("#CityBtn").click( function() {
    $("#CITY").toggle();
    });
  $("#ReasonBtn").click( function() {
    $("#REASON").toggle();
    });
  $("#ResolutionBtn").click( function() {
    $("#RESOLUTION").toggle();
    });
  $("#TTRBtn").click( function() {
    $("#TTR").toggle();
    });
});  // end ready function
</script>
<div class="container">
<h3>CTS2 Monthly Report<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;<a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>

<div id="help">
<h3>Monthly Report Explained</h3>
<p>The following sections summarize various data points for the specified date range.</p>
<p>All columns are sortable by clicking on the column heading.</p>

</div>    <!-- help -->

<?php
include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';
?>

<form action="rptmonthlyreport.php" method="post"  id="form">
Start date: <input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit">
</form>

<?php

//include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$sql = "SELECT * FROM `calls` WHERE `DTOpened` BETWEEN '$sd' AND '$ed' ORDER BY `CallNbr` ASC;";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$resarray = array(); $hlvarray = array(); $cityarray = array();
$reasonarray = array(); $ttrarray = array();
// echo "sql: $sql<br>";
$casestoctr = 0;
while ($r = $res->fetch_assoc()) {
  // echo '<pre> r '; print_r($r); echo '</pre>';
  if ($r['OpenedBy'] == '') $r['OpenedBy'] = 'NA';
	$hlvarray[$r['OpenedBy']] += 1;
  if ($r['AnimalLocation'] == '') $r['AnimalLocation'] = 'NA';	
	$cityarray[$r['AnimalLocation']] += 1;
  if ($r['Reason'] == '') $r['Reason'] = 'NA';
	$reasonarray[$r['Reason']] += 1;
  if ($r['Resolution'] == '') $r['Resolution'] = 'NA';
  if (preg_match('/DeliveredToCenter/i',$r['Resolution'])) 
    $casestoctr += 1;
	$resolutionarray[$r['Resolution']] += 1;
  if ($r['TimeToResolve'] == '') $r['TimeToResolve'] = 'NA';
	$ttrarray[$r['TimeToResolve']] += 1;
  }
echo "Total calls for date range from $sd to $ed: $rc,&nbsp;
Delivered to Center: $casestoctr<br>";
  
// echo '<h3 style="color: #FF0000; ">Under Development</h3>';

// echo '<pre> hlv '; print_r($hlvarray); echo '</pre>'; 
$hlvlines = '
<table class="HLV sortable"><thead><tr><th>CTS User</th><th>Calls opened</th></thead><tbody>';
$str1 = '';
if (count($hlvarray) > 0) ksort($hlvarray);
foreach ($hlvarray as $k => $v) {
  $hlvlines .= "<tr><td>$k</td><td>$v</td></tr>";
  $str1 .= "['$k', $v, '$v'],";
  }
$hlvlines .= '</tbody></table>';
$hlvchart = rtrim($str1, ',');

$str2 = '';
if (count($cityarray) > 0) ksort($cityarray);
$citylines = '
<table class="table sortable"><thead><tr><th>Call from City</th><th>Calls opened</th></thead><tbody>';

foreach ($cityarray as $k => $v) {
  $citylines .= "<tr><td>$k</td><td>$v</td></tr>";
  $str2 .= "['$k', $v, '$v'],";
  }
$citylines .= '</tbody></table>';
$citychart = rtrim($str2, ',');

// echo '<pre> reason '; print_r($reasonarray); echo '</pre>';
$str3 = '';
if (count($reasonarray) > 0) ksort($reasonarray);
$reasonlines = '
<table class="table sortable"><thead><tr><th>Calls By Reason Code</th><th>Count</th></thead><tbody>';
foreach ($reasonarray as $k => $v) {
  $reasonlines .= "<tr><td>$k</td><td>$v</td></tr>";
  $str3 .= "['$k', $v],";
  }
$reasonlines .= '</tbody></table>';
$reasonchart = rtrim($str3, ',');

// echo '<pre> resolution '; print_r($resolutionarray); echo '</pre>';
$str4 = ''; 
if (count($resolutionarray) > 0) ksort($resolutionarray);
$resolutionlines = '
<table class="RESOLUTION table sortable"><thead><tr><th>Call by Resolution Code</th><th>Calls opened</th></thead><tbody>';
foreach ($resolutionarray as $k => $v) {
  $resolutionlines .= "<tr><td>$k</td><td>$v</td></tr>";
  $str4 .= "['$k', $v],";
  }
$resolutionlines .= '</tbody></table>';
$resolutionchart = rtrim($str4, ',');
 
// echo '<pre> ttr '; print_r($ttrarray); echo '</pre>';
$str5 = '';
if (count($ttrarray) > 0) ksort($ttrarray);
$ttrlines = '
<table class="table sortable"><thead><tr><th>Call by Time to Resolve</th><th>Calls opened</th></thead><tbody>';
foreach ($ttrarray as $k => $v) {
  $ttrlines .= "<tr><td>$k</td><td>$v</td></tr>";
  $str5 .= "['$k', $v],";
  }
$ttrlines .= '</tbody></table>';
$ttrchart = rtrim($str5, ',');
?>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<!-- HLV chart 1 -->
<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart1);

// Callback that creates and populates a data table,
// instantiates the chart, passes in the data and
// draws it.
function drawChart1() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'HotLineVol');
  data.addColumn('number', 'Count');
  data.addColumn({type:'string', role:'annotation'}); // annotation role col.
  data.addRows(
  [<?=$hlvchart?>]);

  // Set chart options
  var options = {
                  'title':'Hot Line Volunteers Calls Entered',
                  // 'width':600,
                  // 'height':600,
                  'annotations': { alwaysOutside: true } ,
                  'legend': { 'position': "none" },
                  'vAxis': { 'title': 'Hot Line Vols'}
                };

  // Instantiate and draw our chart, passing in some options.
  var chart = new  google.visualization.BarChart(document.getElementById('chart1'));
  chart.draw(data, options);
}
</script>

<h2>Calls by Hot Line Volunteer <button class="btn btn-xs" id=HLVBtn>Data Table</button></h2>
<div id="chart1" style = "width: 550px; height: 600px; margin: 0 auto">NO DATA</div>
<div id="HLV" style="width: 200px; height: auto; margin: 0 auto"><?=$hlvlines?></div>

<!-- City chart 2-->
<script type="text/javascript">
// Load the Visualization API and the corechart package.
// google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart2);

// Callback that creates and populates a data table,
// instantiates the chart, passes in the data and
// draws it.
function drawChart2() {
  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'City');
  data.addColumn('number', 'Count');
  data.addColumn({type:'string', role:'annotation'}); // annotation role col.
  data.addRows(
  [<?=$citychart?>]);

  // Set chart options
  var options = {
                  'title':'Cities',
                  'annotations': { alwaysOutside: true } ,
                  'legend': { 'position': "none" },
                  'width':600,
                  'height':700 
                };

  // Instantiate and draw our chart, passing in some options.
  var chart = new  google.visualization.BarChart(document.getElementById('chart2'));
  chart.draw(data, options);
}
</script>

<h2>Calls by City <button class="btn btn-xs" id="CityBtn">Data Table</button></h2>
<div id="chart2" style="width: 550px; height: 800px; margin: 0 auto">NO DATA</div>
<div id="CITY" style="width: 300px; height: auto; margin: 0 auto"><?=$citylines?></div>

<!-- REASON chart 3-->
<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart3);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart3() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Reason');
  data.addColumn('number', 'Count');
  data.addRows(
  [<?=$reasonchart?>]);

  // Set chart options
  var options = {'title':'Reason Codes',
                 'annotations': { alwaysOutside: true } ,
                 'is3D': true,
                 'pieSliceText':'value',
                 'sliceVisibilityThreshold': .05,
                 'width':600,
                 'height':500};

  // Instantiate and draw our chart, passing in some options.
  var chart = new  google.visualization.PieChart(document.getElementById('chart3'));
  chart.draw(data, options);
}
</script>

<h2>Calls by Reason Code  <button class="btn btn-xs" id="ReasonBtn">Data Table</button></h2>
<table><tr><td>
<div id="REASON" style="width: 300px; height: auto; margin: 0 auto"><?=$reasonlines?></div>
</td><td>
<div id="chart3" style="width: 550px; height: 500px; margin: 0 auto">NO DATA</div>
</td></tr></table>

<!-- Resolution chart 4 -->
<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart4);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart4() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Resolutions');
  data.addColumn('number', 'Count');
  data.addRows(
  [<?=$resolutionchart?>]);

  // Set chart options
  var options = {'title':'Resolution Codes',
                 'is3D': true,
                 'pieSliceText':'value',
                 'sliceVisibilityThreshold': .05,
                 'width':600,
                 'height':350};

  // Instantiate and draw our chart, passing in some options.
  var chart = new  google.visualization.PieChart(document.getElementById('chart4'));
  chart.draw(data, options);
}
</script>

<h2>Calls by Resolution Code  <button class="btn btn-xs" id="ResolutionBtn">Data Table</button></h2>
<table><tr><td>
<div id="RESOLUTION" style="width: 300px; height: auto; margin: 0 auto"><?=$resolutionlines?></div>
</td><td>
<div id="chart4" style="width: 550px; height: 500px; margin: 0 auto">NO DATA</div>
</td></tr></table>

<!-- TimeToResolve chart 5 -->
<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart5);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart5() {

  // Create the data table.
  var data = new google.visualization.DataTable();
  data.addColumn('string', 'Time To Resolve');
  data.addColumn('number', 'Count');
  data.addRows(
  [<?=$ttrchart?>]);

  // Set chart options
  var options = {'title':'Time To Resolve',
                 'pieSliceText':'value',
                 'is3D': true,
                 'sliceVisibilityThreshold': .1,
                 'width':600,
                 'height':350};

  // Instantiate and draw our chart, passing in some options.
  var chart = new  google.visualization.PieChart(document.getElementById('chart5'));
  chart.draw(data, options);
}
</script>

<h2>Calls by Time to Resolve  <button class="btn btn-xs" id="TTRBtn">Data Table</button></span></h2>
<table><tr><td>
<div id="TTR" style="width: 300px; height: auto; margin: 0 auto"><?=$ttrlines?></div>
</td><td>
<div id="chart5" style="width: 550px; height: 350px; margin: 0 auto">NO DATA</div>
</td></tr></table>

</div>  <!-- container -->
</body>
</html>
