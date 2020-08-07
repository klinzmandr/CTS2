<?php
session_start();

include 'Incls/datautils.inc.php';
// include 'Incls/vardump.inc.php';

// get any input values in $_REQUEST parameters
$nowdatetime = date("M d, Y \a\\t H:i",strtotime('now'));
$nowtime = date("H:i", strtotime('now'));
$startdate = date("Y-m-d",strtotime('now'));
$currdate = isset($_REQUEST['dday']) ? $_REQUEST['dday'] : $startdate;
// $currdate = "2019-05-07";
$currday = date("D", strtotime($currdate));
$daydate = date("M d, Y", strtotime($currdate));
$beforedate = date("Y-m-d", strtotime("$currdate - 1 day"));
$afterdate = date("Y-m-d", strtotime("$currdate + 1 day"));
$dbstart = date("Y-m-d 00:00:01", strtotime($currdate));
$dbend = date("Y-m-d 23:59:59", strtotime($currdate));
$chartstart = date("Y-m-d 00:00:01", strtotime("$currdate -13 days"));
$chartend = date("Y-m-d 23:59:59", strtotime("$currdate"));

$sql = "SELECT * FROM `calls` WHERE `DTPlaced` BETWEEN '$dbstart' AND '$dbend';";
// echo "$sql<br>";

$res = doSQLsubmitted($sql);
$rowcount = $res->num_rows;
// echo "Rows returned: $rowcount<br />";
if (!$rowcount) {
  $resultLines = "<h2>No calls entered for today</h2>";
  }
else {  
$resultLines = '<table class="table sortable"><thead>
<tr id="head"><th>CallNbr</th><th title="Time of last update">TOLU</th><th>HLV</th><th>Status</th><th>Resolution</th><th>AnimalLocation</th><th>Species</th><th>Reason</th><th data-defaultsort="disabled">Description</th></tr>
</thead><tbody>';
while ($r = $res->fetch_assoc()) {
	// $resultLines .= "<pre>".print_r($r,TRUE)."</pre>";
	if ($r['Status'] == 'New') continue;
	$resTOD = '??:??';
	if (strlen($r['ResTOD'] > 0))
    $resTOD = date("H:i", strtotime("$r[ResTOD]"));
	$resultLines .= "<tr class=cn id=$r[CallNbr] style='cursor: pointer;'><td>$r[CallNbr]</td><td>$resTOD</td><td title='$r[ResTelephone]'>$r[ResBy]</td><td>$r[Status]</td><td>$r[Resolution]</td><td>$r[AnimalLocation]</td><td>$r[Species]</td><td>$r[Reason]</td><td>$r[Description]</td></tr>";
  }
$resultLines .= '</tbody></table>';
}

// echo "chartdates: start: $chartstart, end: $chartend<br>";
$sql = "SELECT * FROM `calls` WHERE `DTPlaced` BETWEEN '$chartstart' AND '$chartend';";
// echo "$sql<br>";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// echo "Rows returned: $rc<br />";
while ($r = $res->fetch_assoc()) {
  $dtp = substr($r['DTPlaced'], 0, 10);
  $cdarray[$dtp] += 1;
  }
ksort($cdarray);
// echo "<pre>"; print_r($cdarray); echo "</pre>";
$cda = '';
foreach ($cdarray as $k => $v) {
  $cda .= "['$k', $v], ";
  }
$cda = rtrim($cda, ", ");
// echo "<pre>cda "; print_r($cda); echo "</pre>";

?>
<!DOCTYPE html>
<html>
<head>
<title>CTS Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">

</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<style>
@media print {
    /* on modal open bootstrap adds class "modal-open" to body, so you can handle that case and hide body so it doesn't print with modal*/
    body.modal-open {
        visibility: hidden;
    }

    body.modal-open .modal .modal-header,
    body.modal-open .modal .modal-body {
        visibility: visible; /* make visible modal body and header */
    }
}
</style>
<script>
var INTERVAL = 5;
var secondsLeft = 0;
// initial setup of jquery function(s) for page
$(function(){
  secondsLeft = INTERVAL * 60;
  startCountdownTimer();

$("#refreshButton").click(function () {
  // alert("refresh button clicked");
  // window.location = "ctsquery.php";
  var day = "<?=$currdate?>";
  $("#dday").val(day);
  $("#dateform").submit();
  });
$("#BDAY").click(function() {
  var day = "<?=$beforedate?>";
  // alert("before button click: "+day);
  $("#dday").val(day);
  $("#dateform").submit();
  });
$("#NDAY").click(function() {
  var day = "<?=$startdate?>";
  // alert("now button click: "+day);
  $("#dday").val(day);
  $("#dateform").submit();
  });
$("#ADAY").click(function() {
  var day = "<?=$afterdate?>";
  // alert("after button click: "+day);
  $("#dday").val(day);
  $("#dateform").submit();
  });
});  // end ready function
  
function startCountdownTimer() {
  secondsLeft = secondsLeft - 1;
  if (secondsLeft <=0) {
    secondsLeft = INTERVAL * 60;
    window.location = "ctsquery.php";
    }
  var percentLeft = ((secondsLeft / (INTERVAL*60)) * 100);
  $('.countdown-bar').css('width', percentLeft + '%');
  $('.countdown-holder').text("Time to refresh: "+secondsLeft + "s");
  setTimeout(function() {
    // Call self after one second
    startCountdownTimer();
    }, 1000);
  }
</script>
<script>
$("document").ready(function() {
// ajax call to display call record
$("tr.cn").click (function() {
  var cn = $(this).attr('id');
  if (cn == 'head') return;
  // alert ("row clicked " + cn);
  $.post("ctsqueryjson.php",
    {
      callnbr: cn
    },
  function(data, status) {
      // alert("Data: " + data + "\nStatus: " + status);
      $("#myModalLabel").html('Call Numbmer '+ cn);
      $("#mm-modalBody").html(data);
      $("#myModal").modal("show");   
    });  // end $.post logic 
  });
$("#lqButton").click(function() {
  // alert("log query button click");
    $.post("ctsqueryjsonlq.php",
      { 
      date: '<?=$daydate?>'
      },
  function(data, status) {
      // alert("Data: " + data + "\nStatus: " + status);
      $("#myModalLabel").html('Log Query Results for <?=$daydate?>');
      $("#mm-modalBody").html(data);
      $("#myModal").modal("show");   
    });  // end $.post logic 
  });

$("#chartButton").click(function() {
  $("#chartarea").toggle();
  });

});
</script>
<script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js">
      </script>
<script type = "text/javascript">
      google.charts.load('current', {packages: ['corechart']});  
      </script>

<script language = "JavaScript">
   function drawChart() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date Placed');
      data.addColumn('number', 'Count');
      data.addRows([
         <?=$cda?>
      ]);
         
      // Set chart options
      var options = {
       title : 'Calls Placed By Date',
        hAxis: { slantedText:true, slantedTextAngle:45 },
        'legend': { 'position': "none" },   
        'width':750,
        'height':400	  
      };

      // Instantiate and draw the chart.
      var chart = new google.visualization.ColumnChart(document.getElementById('chartarea'));
      chart.draw(data, options);
   $("#chartarea").hide();
   }
   google.charts.setOnLoadCallback(drawChart);
</script>

<form action="ctsquery.php" method="post"  id="dateform">
<input type=hidden id=dday name=dday value="<?=$currdate?>">
</form>
<table border=0><tr><td> 
<h3>CTS Calls for <?=$currday?>, <?=$daydate?>&nbsp;&nbsp;
<span id="helpbtn" title="Help/Page Information" class="glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;&nbsp;&nbsp;
<span id="BDAY" title="-1 day" class="glyphicon glyphicon-chevron-left" style="color: blue; font-size: 20px"></span>
<span id="NDAY" title="TODAY" class="glyphicon glyphicon-stop" style="color: blue;" ></span>
<span id="ADAY" title="+1 day" class="glyphicon glyphicon-chevron-right" style="color: blue; font-size: 20px"></span></h3></td>
<td valign=center>
&nbsp;&nbsp;&nbsp;<button id="chartButton">14 Day<br>Chart</button>
</td></tr></table>
<div id=help>
<p>This page is designed to query the CTS2 call database and list all calls for the a single day.  The count down is automatically set to refresh every 5 minutes and will automatically be updated TO THE CURRENT DATE at that inverval until the page is closed.</p>
<p>Full details about each call can be obtained by clicking anywhere on the listing line for that call.</p>
<p>A summary listing of those who have logged on and the total number of calls placed by each is available by clicking the &quot;Log Query&quot; button.  The last two columns indicate the number and date/time of the last call entered.  NOTE: all calls entered for the day are counted regardless of when the call was actually placed.</p>
<p>The Time of Last Update (TOLU) column is based on the time of last update of the call record by the HLV.</p>
<p>Previous days can be displayed by clicking the date icons.  The automatic refresh will reset the date to the current day as will the square date icon.</p>
<p>A history of the previous 14 days of calls placed can be viewed by clicking the &quot;14 Day Chart&quot; button.</p>
<p>Hovering the mouse over the HLV name will show the telephone number, if one is registered, for the phone volunteer in case a call back is needed.</p>
<p>Column headings in <font color="#FF0000"><b>RED</b></font> are sortable.</p>
</div>
<!-- https://getbootstrap.com/docs/4.3/components/progress/ -->
<div class="progress" style="height: 5px";>
<div class="progress-bar progress-bar-striped countdown-bar active" role="progressbar" style="min-width: 10px; width: 100%;">
<span class="countdown-holder">xxxxx</span>
</div>
</div>
Calls Placed: <?=$rowcount?>, Last Refresh: <?=$nowtime?>&nbsp;&nbsp;
<button id=refreshButton title="Refresh page for <?=$daydate?>">Refresh</button>
<a class="btn btn-default btn-sm" href="index.php" title="CTS Home Page">CTS Home</a>
<button id=lqButton title="List all logins from day&apos;s log.">Log Query</button>
<div id="chartarea" style="width: auto; height: auto; margin: 0 auto">NO DATA</div>
<div id=lines>
<?=$resultLines?>
</div>
<!-- =========== Modal  ==================== -->  
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
    <div id="mm-modalBody" class="modal-body">
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="js:window.print()">Print</button>

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>
