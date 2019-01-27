<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("now"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("now"));
$ctype = isset($_REQUEST['ctype']) ? $_REQUEST['ctype'] : '';
$dbtab = isset($_REQUEST['dbtab']) ? $_REQUEST['dbtab'] : 'calls';

$retbtn = "";
if (isset($_SESSION['CTS_SessionUser'])) 
  $retbtn = '&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a>';
?>

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
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<script>
$(function() {
  // alert("doc load");
  $("select[name=ctype]").val("<?=$ctype?>");
  var dbvar = "#"+"<?=$dbtab?>";
  $(dbvar).prop("checked", true);

$("#pie").click(function() {
  $("#chart1").toggle();
  });
$("#bar").click(function() {
  $("#chart2").toggle();
  });
$("#tabb").click(function() {
  $("#tab").toggle();
  });

$("form").submit(function(e) {
  //alert("form submit");
  if ($("select[name=ctype]").val() == '') {
    e.preventDefault();
    alert("no chart selected");
    }
  var x = $("#callsarchive").is(':checked'); 
  if ($("#callsarchive").is(':checked')) {
    dorangechk(e);
    return;
    }
  // alert("check date > Jan 1");
  var early = Date.parse("2019-01-01");
  var sd = $("#sd").val(); var sdms = Date.parse(sd);
  var ed = $("#ed").val(); var edms = Date.parse(ed);
  if (sdms < early || edms < early) {
    e.preventDefault();
    alert("Both Start date and End date must be\nafter Jan 1, 2019");
    return; 
    }
  });
});

function dorangechk(e) {
  // alert("checK archive date range");
  var early = Date.parse("2010-01-01");
  var late  = Date.parse("2018-12-31");
  
  var sd = $("#sd").val(); var sdms = Date.parse(sd);
  var ed = $("#ed").val(); var edms = Date.parse(ed);
  
  if (sdms < early || edms > late) {
    e.preventDefault();
    alert("Start or end date not in range\nof Jan 1, 2010 to Dec 31, 2018");
    return;
    }
  return;
}
</script>

<div class="container">
<h3>Chart Calls In Date Range&nbsp;
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span><?=$retbtn?></h3>
<div id=help>
<h4>Report Explanation</h4>
<p>This report is provided to allow a visual representation of the various data points associated with each call within the date range specified.  By default, the date range is for the current month.
<p>Specifically, pie and bar graphs depict the type and counts of:
<ol>
  <li>Weekly Calls in Period - total count of calls opened in each week of the year within the date range entered.</li>  
  <li>Hot Line Volunteers - count based on the id of the user opening the call.</li>
	<li>Animal Location - as identified by the caller.  This is the municipality that the animal was picked up from.</li>
	<li>Property - this represents the type of property that the animal was retrieved from as best as the hot line volunteer can determine.</li>
	<li>Species - This is the non-scientific species of the animal reported to the volunteer.  This is not an exact determination but an attempt to identify the animal as best described by the caller.</li>
	<li>Time To Resolve - estimated time taken to resolve the issue or answer the question as provided by the volunteer.</li>
	<li>Call Reason - reason for the call as reported by the caller to the volunteer.</li>
	<li>Action Taken - count which represents that final action taken for the call as reported by the volunteer based on the best information about the outcome of the call.</li>
</ol></p>
<p><b>CTS Archive Information</b> from the previous version of CTS is available by selection of the 'CTS Archive' button.  In this case the date range must specify a period prior to January 1, 2019.</p>
<p><b>Date Picker TIP</b> - Click in the start or end date field to get the calendar dialog to select a specific date.  Click the header of the date picker calendar dialog once or twice to select a specific month or year before selection of a specific date.</p>
</div>
<form action="rptcallcharts.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> to End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>"><br>
<input type="radio" name="dbtab" id="calls" value="calls" checked>CTS2&nbsp;&nbsp
<input type="radio" name="dbtab" id="callsarchive" value="callsarchive">CTS Archive &nbsp;&nbsp;
<select id=ctype name=ctype>
<option value="">Select data to chart</option>
<option value="week">Weekly Calls in Period</option>
<option value="hlv">Hot Line Volunteers</option>
<option value='loc'>Animal Location</option>
<option value='prop'>Property</option>
<option value='spec'>Species</option>
<option value='ttr'>Time To Resolve</option>
<option value='reas'>Call Reason</option>
<option value='res'>Action Taken</option>
</select>
<input class="hidden-print" type="submit" name="submit" value="Submit">
</form>
<br>
<div class=hidden-print>
<button id = pie href=#>Show/Hide Pie</button>
<button id = bar href=#>Show/Hide Bar</button>
<button id = tabb href=#>Show/Hide Tabular Data</button><br>
</div>
<?php
if ($ctype == '') exit;     // exit if first time through 

$edhms = date("Y-m-d 23:59:59",strtotime($ed));
$sql = "SELECT * FROM `$dbtab` 
WHERE `DTOpened` BETWEEN '$sd' AND '$edhms'
ORDER BY `CallNbr` DESC;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$resarray = array(); $countarray = array();
$countarray[Total] = 0;$countarray[Open] = 0; $countarray[Closed] = 0; $countarray[Center] = 0;
$hlvarray = array(); $animallocation = array(); $hlvs = array();
$property = array(); $species = array(); $resolution = array();
$ttr = array(); $reason = array(); $week = array();
 
while ($r = $res->fetch_assoc()) {
	$countarray[Total] += 1;
	$wnbr = date("W", strtotime($r[DTOpened]));
	$week[$wnbr]++;
	if ($r[AnimalLocation] == '') $animalloction['not entered']++;
	  else $animallocation[$r[AnimalLocation]]++;
	if ($r[Property] == '') $property['not entered']++;
	  else $property[$r[Property]]++;
	if ($r[Species] == '') $specie['not entered']++;
	  else $species[$r[Species]]++;
	if ($r[Resolution] == '') $resolution['not entered']++;
	  else $resolution[$r[Resolution]]++;
	if ($r[OpenedBy] == '') $hlvs['not entered']++;
	  else $hlvs[$r[OpenedBy]]++;
	if ($r[TimeToResolve] == '') $ttr['not entered']++;
	  else $ttr[$r[TimeToResolve]]++;
	if ($r[Reason] == '') $reason['not entered']++;
	  else $reason[$r[Reason]]++;

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

// echo '<pre>hlv '; print_r($hlvarray); echo '</pre>';
// echo '<pre>animallocation '; print_r($animallocation); echo '</pre>';
// echo '<pre>property '; print_r($property); echo '</pre>';
// echo '<pre>species '; print_r($species); echo '</pre>';
// echo '<pre>resolution '; print_r($resolution); echo '</pre>';
// echo '<pre>week '; print_r($week); echo '</pre>';

$ctype = $_REQUEST['ctype'];
if ($ctype == 'hlv') { $charttitle = 'HotLine Volunteers'; $tot = $hlvs; }
if ($ctype == 'loc') { $charttitle = 'Animal Locations'; $tot = $animallocation; }
if ($ctype == 'prop') { $charttitle = 'Property'; $tot = $property; }
if ($ctype == 'spec') {$charttitle = 'Species'; $tot = $species; }
if ($ctype == 'res') { $charttitle = 'Action Taken'; $tot = $resolution; } 
if ($ctype == 'ttr') { $charttitle = 'Time To Resolve'; $tot = $ttr; } 
if ($ctype == 'reas') { $charttitle = 'Call Reason'; $tot = $reason; } 
if ($ctype == 'week') { $charttitle = 'Call Distribution by Week'; $tot = $week; } 

$str = '';
$tab = '<table border=1><tr><th>Data</th><th>Count</th></tr>';
if (count($tot) > 0) {
  ksort($tot);
  foreach ($tot as $k => $v) {
    $str .= "['$k', $v],";
    $tab .= "<tr><td>$k</td><td>$v</td></tr>";
    }
  $chartdata = rtrim($str, ',');
  $tab .= '</table>';
  }

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
  [<?=$chartdata?>]);
  // [['one',1],['two',2],['three',3]]);

  // Set chart options
  var options = {'title':'<?=$charttitle?>',
                 'width':600,
                 'height':350};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.PieChart(document.getElementById('chart1'));
  chart.draw(data, options);
}
</script>

<!--Div that will hold the pie chart-->
<div id="chart1"></div>

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
  [<?=$chartdata?>]);
  //[['one',1],['two',2],['three',3]]);

  // Set chart options
  var options = {'title':'<?=$charttitle?>',
                 'width':600,
                 'height':350};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.BarChart(document.getElementById('chart2'));
  chart.draw(data, options);
}
</script>

<!--Div that will hold the bar chart-->
<div id="chart2"></div>

<div hidden id=tab ><b><?=$charttitle?></b><br><?=$tab?>
<br><br><br>
</div>
</div>
</body>
</html>
