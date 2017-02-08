<!DOCTYPE html>
<html>
<head>
<title>Forms</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<script src="jquery.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();

include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
//include 'Incls/vardump.inc.php';

$fv = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';

$btnarray = array(
  0 => "Group 0",
  1 => "Group 1",
  2 => "Group 2",
  3 => "Group 3",
  4 => "Group 4",
  5 => "Group 5",
  6 => "Group 6",
  7 => "Group 7",
  8 => "Group 8",
  9 => "Group 9"
  );
  
$forms = scandir('Forms');

echo '<div class="container">
<h3>Forms & Documentation</h3>
<h4>Documents will open in a new window.</h4>';

print <<<formPart
<script>
$(document).ready(function() {
  $("tr").hide();
  $("tr:first").show();
  });
$(function(){
 $('#btnALL').click(function() { $('tr').show(); });
 $('#btnNONE').click(function() { $('tr').hide(); $("tr:first").show(); });
 $('#btn0').click(function() { $('tr').hide(); $("tr:first").show(); $('.0').toggle(); });
 $('#btn1').click(function() { $('tr').hide(); $("tr:first").show(); $('.1').toggle(); });
 $('#btn2').click(function() { $('tr').hide(); $("tr:first").show(); $('.2').toggle(); });
 $('#btn3').click(function() { $('tr').hide(); $("tr:first").show(); $('.3').toggle(); });
 $('#btn4').click(function() { $('tr').hide(); $("tr:first").show(); $('.4').toggle(); });
 $('#btn5').click(function() { $('tr').hide(); $("tr:first").show(); $('.5').toggle(); });
 $('#btn6').click(function() { $('tr').hide(); $("tr:first").show(); $('.6').toggle(); });
 $('#btn7').click(function() { $('tr').hide(); $("tr:first").show(); $('.7').toggle(); });
 $('#btn8').click(function() { $('tr').hide(); $("tr:first").show(); $('.8').toggle(); });
 $('#btn9').click(function() { $('tr').hide(); $("tr:first").show(); $('.9').toggle(); });
});
</script>

formPart;

$count = 0;
foreach ($forms as $formname) {
  if (($formname == '.') || ($formname == '..')) { continue; }
  
  $grp = substr($formname,0,1);
  $g[$grp][$count] = $formname;

  $count += 1;
  }

$moddt = filectime("Forms/$formname");
$cd  = date("F d, Y \a\\t H:i:s.", $moddt) . "<br>";

echo '
<button id="btnALL">Show All</button>&nbsp;&nbsp;
<button id="btnNONE">Show None</button>
<br>';
foreach ($btnarray as $k => $v) {
  if (isset($g[$k])) {
    echo "<button class=\"btn btn-xs\" id=\"btn$k\">$v</button>&nbsp;&nbsp;";
    }
  }
  
echo '<br>';

echo "<table border=0 width=\"90%\">
<tr><td width=\"70%\"><b>Document Title</b></td><td><b>Date and time last updated</b></td></tr>
";

foreach ($g as $k => $v) {
  foreach ($v as $formname) {
  echo "<tr class=\"$k\">
  <td><a target=_blank href=\"Forms/$formname\">$formname</a></td>
  <td>$cd</td></tr>
  ";
    }
  }
?>
</div>  <!-- container -->
</table>
===== End of List =====
<br>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
