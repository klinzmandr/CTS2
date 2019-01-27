<?php
session_start();
// include 'Incls/vardump.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));
$cbflds = isset($_REQUEST['cbflds']) ? $_REQUEST['cbflds'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<title>Call Archive Query</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>
<style>
input[type=checkbox] { transform: scale(1.5); }
</style> 

<div class="container">
<h3>Historical Call Archive Report 
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;<a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<div id=help>
<p>This report lists a summary of all calls entered from Jan 1, 2010 to December 31, 2018.</p>
<p>The resulting report is available as a CSV file for download ONLY.</p>
<ol>Perform the following steps to acquire data from the archive.
<li>First a date range between Jan 1, 2010 and Dec 31, 2018 must be selected.</li>
<li>Select the data elements from the archived records.</li>
<li>Download the resulting comma separated variable (CSV) file that is suitable for importing into a spreadsheet program for further manipulation and summarization.</li>
</ol>
NOTE: The Call number and Date/Time Placed columns are always extracted by default.<br><br>
</div>  <!-- help -->

<script>
$(function() {
  // alert("doc load");
$("#cball").change(function() {
  // alert("cball clicked");
  if ($("#cball").is(':checked')) { $('.cb').attr('checked',true); } 
  else { $('.cb').attr('checked',false); }
  });
$("form").submit(function(e) {
  var early = Date.parse("2010-01-01");
  var late  = Date.parse("2018-12-31");
  
  var sd = $("#sd").val(); var sdms = Date.parse(sd);
  var ed = $("#ed").val(); var edms = Date.parse(ed);
  
  if (sdms < early || edms > late) {
    e.preventDefault();
    alert("Start or end date not in range of\nJan 1, 2010 to Dec 31, 2018");
    return;
    }
  var x = $(".cb").is(':checked');    // any checked - true or false
  var y = $('input[type="checkbox"]:checked').length;   // number checked
  if (!x) {
    e.preventDefault();
    alert("No data elements have been selected.");
    return;
    }
  });
});
</script>

<form action="rpthistoricalcalls.php" method="post"  class="form">
Start Date: 
<input type="text" name="sd" id="sd" value="<?=$sd?>"> and End Date:  
<input type="text" name="ed" id="ed" value="<?=$ed?>">
<input type="submit" name="submit" value="Submit"><br>

<div class="container">
<h3>Heading Definitions</h3>
<input id=cball type=checkbox>Check All<br>
<table class="table table-condensed">
<tr><th>Heading</th><th>Definition</th><th>CTS</th><th>CTS2</th></tr>
<!-- <tr><td><input class=cb name=cbflds[] type=checkbox value=Status>&nbsp;Status</td><td>Status of Call (Archive)</td><td>X</td><td>X</td></tr> -->
<tr><td><input style="display: none;" name=cbflds[] type="checkbox" checked value=CallNbr>&nbsp;&nbsp;&nbsp;&nbsp;CallNbr</td><td>Unique call number (NOTE: always extracted by default)</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=DTOpened>&nbsp;DTOpened</td><td>Date call opened</td><td>X</td><td>X</td></tr>
<tr><td><input style="display: none;" class=cb name=cbflds[] type=checkbox checked value=DTPlaced>&nbsp;&nbsp;&nbsp;&nbsp;DTPlaced</td><td>Date call placed (NOTE: always extracted by default)</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=DTClosed>&nbsp;DTClosed</td><td>Date call closed</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=AnimalLocation>&nbsp;AnimalLocation</td><td>Location of animal</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=Property>&nbsp;Property</td><td>Type of property the animal was found on (e.g. business, private, city, state, etc.)</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=Species>&nbsp;Species</td><td>Caller reported species type</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=Resolution>&nbsp;Resolution</td><td>Outcome of the call (i.e. Delivered to center, No Action, Call Referred, etc.)</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=TimeToResolve>&nbsp;TimeToResolve</td><td>Approximate total time to handle call</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=PostcardSent>&nbsp;PostCardSent</td><td>Was a postcard sent (Yes/No)</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=OpenedBy>&nbsp;OpenedBy</td><td>Id of person opening the call</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=Reason>&nbsp;Reason</td><td>Reported reason call was placed by caller</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=CaseRefNbr>&nbsp;CaseRefNbr</td><td>Case reference number assign at the Center</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=LastUpdater>&nbsp;LastUpdater</td><td>Id of person last updating the call</td><td>X</td><td>X</td></tr>
<tr><td><input class=cb name=cbflds[] type=checkbox value=CallLocation>&nbsp;CallLocation</td><td>Location of caller when placing the call (vs animal location)</td><td>X</td><td>X</td></tr>

<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
<input type=submit name=submit value="Submit"><br>
</form>
<?php
if (!isset($_REQUEST['submit'])) exit;

echo '<h3>Displaying first 25 records of result:</h3>';
// echo '<pre> names'; print_r($cbflds); echo '</pre>';
$rownames = implode(", ", $cbflds);
// echo "rownames: $rownames<br>";

$sql = "SELECT $rownames FROM `callsarchive` 
WHERE `DTOpened` BETWEEN '$sd' AND '$ed'
ORDER BY `CallNbr` ASC;";
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo "Total records in output: $rc<br>";
echo "<table border=1>";
echo "<tr><th>" . implode("</th><th>", $cbflds) . "</th></tr>\n";
$csvfile = implode(";", $cbflds) . "\n";
$rescnt = 0;
while ($r = $res->fetch_assoc()) {
	if ($rescnt < 25) echo '<tr><td>' . implode("</td><td>",$r) . '</td></tr>';
  $rescnt++;
	$recordcount++;
	$csvfile .= implode(";", $r) . "\n";
	}
echo "</table>";
file_put_contents("./Downloads/CallArchiveReport.csv", $csvfile);
?>
<a href="./Downloads/CallArchiveReport.csv" download="./Downloads/CallArchiveReport.csv">DOWNLOAD CSV FILE</a>
&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" title="NOTE: Fields separated by semicolon(;)"><span class="glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px"></span></button>

<br>Total records in output: <?=$recordcount?><br><br>=== END OF REPORT===<br></div>
</body>
</html>

