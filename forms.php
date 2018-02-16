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
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();

include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
//include 'Incls/vardump.inc.php';

$fv = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';

$btnarray = array(
  0 => "HLV Ref",
  1 => "Animals",
  2 => "RTV Info",
  3 => "Gen Info",
  4 => "Group 4",
  5 => "Group 5",
  6 => "Group 6",
  7 => "Group 7",
  8 => "Forms",
  9 => "Group 9"
  );
  
$forms = scandir('Forms');

echo '<div class="container">
<h3>Forms & Documentation</h3>
Documents will open in a new tab/window.<br>';

print <<<formPart
<script>
var inp = '';
$(document).ready(function() {
  $("tr").show();
  $("#head").show();
  $("#help").hide();
  
$("#helpclk").click(function() {
  $("#help").toggle();
});

// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });
});
  
$(function(){
  $('#btnFILTER').click(function() { 
    inp = $('#inp').val();
    //console.log(inp);
    if (inp.length > 0) 
      $('tr').hide().filter(':containsNC('+inp+')').show();
      $("#head").show();
      chgFlag = 0;
      });
    $('#btnALL').click(function() {
      $('tr').show();
      $('#inp').val('');
      chgFlag = 0;     
      });
 
  $('#btn0').click(function() { 
    $('tr').hide();  $('.0').toggle(); $("#head").show();});
  $('#btn1').click(function() { 
    $('tr').hide();  $('.1').toggle(); $("#head").show();});
  $('#btn2').click(function() { 
    $('tr').hide();  $('.2').toggle(); $("#head").show();});
  $('#btn3').click(function() { 
    $('tr').hide();  $('.3').toggle(); $("#head").show();});
  $('#btn4').click(function() { 
    $('tr').hide();  $('.4').toggle(); $("#head").show();});
  $('#btn5').click(function() { 
    $('tr').hide();  $('.5').toggle(); $("#head").show();});
  $('#btn6').click(function() { 
    $('tr').hide();  $('.6').toggle(); $("#head").show();});
  $('#btn7').click(function() { 
    $('tr').hide();  $('.7').toggle(); $("#head").show();});
  $('#btn8').click(function() { 
    $('tr').hide();  $('.8').toggle(); $("#head").show();});
  $('#btn9').click(function() { 
    $('tr').hide();  $('.9').toggle(); $("#head").show();});
  });
</script>

formPart;

$count = 0;
foreach ($forms as $formname) {
  if (($formname == '.') || ($formname == '..')) { continue; }
  if (preg_match("/php/i", $formname)) continue;
  $grp = substr($formname,0,1);
  $g[$grp][$count] = $formname;
  $count += 1;
  }

$moddt = filectime("Forms/$formname");
$cd  = date("F d, Y \a\\t H:i:s.", $moddt) . "<br>";

echo '
Filter:<input id="inp" type="text" value="">&nbsp;&nbsp;+&nbsp;&nbsp;
<button id="btnFILTER">Apply Filter</button>&nbsp;&nbsp;
<button id="btnALL">Show All</button>&nbsp;&nbsp;
<span id="helpclk" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
<div id="help">
<p>Click the &quot;Show All&quot; button to list all available documents.  If a key word contained in a document title is entered in the &quot;Filter&quot; box then and clicking the &quot;Apply Filter&quot; will display only those documents containing that character string in their name.  Clicking the &quot;Show None&quot; will clear the contents of the Filter box as well as the results list.</p>
<p>Documents are also grouped based on their document number.  Clicking a button wll list only those documents associated with that button&apos;s label.  Usually these are documents that are associated with that topic.  Documents may be duplicated between subjects when ever it might be appropriate.</p>
</div>
<br>';
foreach ($btnarray as $k => $v) {
  if (isset($g[$k])) {
    echo "<button class=\"btn btn-success btn-xs\" id=\"btn$k\">$v</button>&nbsp;&nbsp;";
    }
  }
  
echo '<br>';

echo "
<table border=0 width=\"90%\">
<tr id=\"head\"><td width=\"70%\"><b>Document Title</b></td><td><b>Date and time last updated</b></td></tr></table>
<table border=0 width=\"90%\">
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
</body>
</html>
