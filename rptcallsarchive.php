<?php
session_start();
if (!isset($_SESSION['CTS_SessionUser'])) {
  echo '<h1>SESSION HAS TIMED OUT.</h1>';
  echo '<h3 style="color: red; "><a href="indexsto.php">Log in again</a></h3>';
  exit;
  }
date_default_timezone_set('America/Los_Angeles');
$ft = isset($_REQUEST['sd']) ? 'true' : 'false';
$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));

// include 'Incls/vardump.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Call Archive Query</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<h3>Historical Call Summary Report &nbsp;&nbsp;<a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<button id=fldsbtn class="btn btn-primary btn-xs">Field Selections</button>
<style>
input[type=checkbox] { transform: scale(1.5); }
</style> 

<script>
$(function() {
  // alert("doc load");
  var ft = '<?=$ft?>';
  if (ft == 'true') {
    // alert("first time");
    $("#flds").hide();
    }
  $("#fldsbtn").click(function() {
    $("#flds").toggle();
  });
  $("#cols").click(function() {
    // alert ("all/none clicked");
    var x = $("#cols:checked").length;
    $(".cols").prop("checked", false);
    if (x) $(".cols").prop("checked", true);
  });
  
  $("form").submit(function(e) {
    // alert("form submitted");
    var x = $(".cols").is(':checked');    // any checked - true (>0) or false (0)
    var y = $('input[type="checkbox"]:checked').length;   // number checked
    if (!x) {
      e.preventDefault();
      alert("No fields have been selected.");
      return;
    }
    
  var early = Date.parse("2010-01-01");
  var sd = $("#sd").val(); var sdms = Date.parse(sd);
  if (sdms < early) {
    e.preventDefault();
    alert("Start date is before Jan 1, 2010");
    return;
    }
  });
});
</script>
<div id=flds class="container">

<p>This report extracts and summarizes all calls within the date range specified.  The default date range is for the previous month.</p>
<p>Data fields are extracted based fields selected. Results must be downloaded and opened a spreadsheet to be viewed.</p>
<h3>Field Definitions</h3>
<table class="table table-condensed">
<form action="rptcallsarchive.php" method="post" class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit">

<tr><th><input type=checkbox id=cols>&nbsp;All/None</th><th>Field Definition</th></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=CallNbr>&nbsp;CallNbr</td><td>Unique call number (CR = CTS, CT = CTS2)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=DTPlaced>&nbsp;DTPlaced</td><td>Date call placed</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=DTOpened>&nbsp;DTOpened</td><td>Date call opened (usually the same as DTPlaced)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=DTClosed>&nbsp;DTClosed</td><td>Date call closed</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=AnimalLocation>&nbsp;AnimalLocation</td><td>Location (or approx. location) that the animal was picked up</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=CallLocation>&nbsp;CallLocation</td><td>Location of caller when placing the call (vs animal location)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=Property>&nbsp;Property</td><td>Type of property the animal was found on (e.g. business, private, city, state, etc.)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=Species>&nbsp;Species</td><td>Caller reported species type</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=Reason>&nbsp;Reason</td><td>Reported reason call was placed by caller</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=Resolution>&nbsp;Resolution</td><td>Outcome of the call (i.e. Delivered to center, No Action, Call Referred, etc.)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=TimeToResolve>&nbsp;TimeToResolve</td><td>Approximate total time to handle call</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=PostcardSent>&nbsp;PostcardSent</td><td>Was a postcard sent (Yes/No)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=EmailSent>&nbsp;EmailSent</td><td>Was a postcard sent (Yes/No)</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=OpenedBy>&nbsp;OpenedBy</td><td>Id of person opening the call</td></tr>
<tr><td><input type=checkbox class=cols name=cols[] value=LastUpdater>&nbsp;LastUpdater</td><td>Id of person last updating the call</td></tr>
<tr><td></td><td></td></tr>
</form>
</table>
</div>

<?php
if ($ft != 'true') exit;

include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$colnames = $_REQUEST['cols'];
$sqlcols = '`' . implode('`, `',$colnames)  . '`';
$collist = implode(', ', $colnames);
// echo "sqlcols: $sqlcols<br>";
 
$sql = "
SELECT $sqlcols  
FROM  `calls` 
WHERE `DTPlaced` BETWEEN '$sd' AND '$ed'
UNION ALL
SELECT $sqlcols
FROM `callsarchive` 
WHERE `DTPlaced` BETWEEN '$sd' AND '$ed';
";

// echo "sql: $sql<br><br><br>";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
//echo "SQL rc: $rc<br>";
if ($rc == 0) {
  echo "<h3>No rows extracted.</h3>";
  exit;
  }

$noquotes = array('CallNbr', 'DTPlaced', 'DTOpened', 'DTClosed');
// echo 'col count: ' . count($colnames) . '<br>';
echo '<div class=container>';
echo "<p>A total of $rc rows of data containing the following columns:<ul>$collist</ul>between the dates of $sd and $ed</p><p>Click the following link to download the CSV file containing these results.</p>

<p><a  title=\"Fields separated by semicolon(;). Text fields are quoted.\" href=\"Downloads/callarchiveresults.csv\" download=\"callarchiveresults.csv\">DOWNLOAD CSV FILE<span class=\"glyphicon glyphicon-info-sign\" style=\"color: blue; font-size: 20px\"></span></a></p>";
$tbl = "<tr><th>" . implode('</th><th>', $colnames) . "</th></tr>";
$csv = '"' . implode('", "',$colnames) . '"' . "\n";
echo "<table>$hdr";
while ($r = $res->fetch_assoc()) {
  // echo '<pre>DB record '; print_r($r); echo '</pre>';
  echo "<tr>";
  for ($i = 0; $i < count($colnames); $i++) {
    $colname = $colnames[$i]; $colval = $r[$colname];
    // echo $colname.': '.$colval.'<br>';
    $tbl .= "<td>$colval</td>";
    if (in_array($colname, $noquotes)) $csv .= $colval . ', ';
    else $csv .= '"' . $colval . '", ';
    }
  $csv = rtrim($csv, ', ');
  $csv .= "\n";
  $tbl .= "</tr>";
  } 
$tbl .= "</table>";
// echo $tbl;
// echo "<pre>$csv</pre>";
file_put_contents('Downloads/callarchiveresults.csv',$csv);
?>

</div>
</body>
</html>

