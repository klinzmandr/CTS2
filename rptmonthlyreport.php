<?php
session_start();

$sd = isset($_REQUEST['sd']) ? $_REQUEST['sd'] : date('Y-m-01', strtotime("previous month"));
$ed = isset($_REQUEST['ed']) ? $_REQUEST['ed'] : date('Y-m-t', strtotime("previous month"));

?>
<!DOCTYPE html>
<html>
<head>
<title>CTS Monthly Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>

<script>
// initial setup of jquery function(s) for page
$(document).ready(function () {
	$("#help").hide();

// this attaches an event to an object
	$(".btn").click(function () {
    $("#help").toggle(); 
    });

  });  // end ready function
</script>
<div class="container">
<h3>CTS2 Summary Report&nbsp;&nbsp;<a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a></h3>
<button class="btn btn-xs">HELP</button>
<div id="help">
<h3>Summary Report Explained</h3>
Lorem ipsum dolor sit amet. Arcu eu proin id velit non urna adipiscing. Vestibulum. Sollicitudin tellus. Non montes montes risus parturient ullamcorper. Mi, vitae at enim. A malesuada lorem. Facilisis parturient, quisque. Mollis proin scelerisque ultrices curabitur ut. Ullamcorper ac, luctus vulputate leo, pretium. At, erat metus nonummy. Nisl nam primis per potenti, ut, euismod nisl, et. Curabitur. Felis. Faucibus class ante nulla, fames. A etiam feugiat hac. Nulla mi curabitur a risus ve porta litora. Tortor eros, ante gravida porttitor dictumst aptent ipsum. Auctor diam, condimentum rhoncus consectetuer feugiat magna etiam ullamcorper. Proin luctus eros erat consequat nascetur, dictum pretium libero erat. Auctor molestie torquent posuere netus cum odio parturient rutrum, mi fames eu pellentesque quam curae nullam.

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
echo "Total calls for date range from $sd to $ed: $rc<br>";
while ($r = $res->fetch_assoc()) {
  // echo '<pre> r '; print_r($r); echo '</pre>'; 
	$resarray[$r[CallNbr]] = $r;
	$hlvarray[$r[OpenedBy]] += 1;
	$cityarray[$r[AnimalLocation]] += 1;
	$reasonarray[$r[Reason]] += 1;
	$resolutionarray[$r[Resolution]] += 1;
	$ttrarray[$r[TimeToResolve]] += 1;
  }
echo '<h3>Under Development</h3>';
echo '<pre> result '; print_r($resarray); echo '</pre>';  
echo '<pre> hlv '; print_r($hlvarray); echo '</pre>';  
echo '<pre> city '; print_r($cityarray); echo '</pre>';  
echo '<pre> reason '; print_r($reasonarray); echo '</pre>';  
echo '<pre> resolution '; print_r($resolutionarray); echo '</pre>';  
echo '<pre> ttr '; print_r($ttrarray); echo '</pre>';  
?>
</div>  <!-- container -->


</body>
</html>
