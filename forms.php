<?php
session_start();
$fv = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<title>Documentation</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<script>
$(function() {
// adds sign in sorted col header
$.bootstrapSortable({ sign: 'AZ' }) });
</script>

<div class="container">
<h3>Forms & Documentation</h3>
Documents will open in a new tab/window.<br>

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
  // $('#btnFILTER').click(function() { 
  $('#inp').keyup(function() { 
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

<?php
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
// include 'Incls/vardump.inc.php';

$ctslib = "../CTSLibrary/"; // path to forms and doc library

// array key 0-9 corresond to the titles of the buttons defined
$btnarray = array(
  0 => "Contacts",
  1 => "Hotline",
  2 => "Rescue",
  3 => "Dirs & Misc",
  4 => "Mammals",
  5 => "Birds",
  6 => "Baby Animals",
  7 => "Humane Excl",
  8 => "Forms",
  9 => "System"
  );

$forms = scandir($ctslib);
$count = 0;
foreach ($forms as $formname) {
  // if (($formname == '.') || ($formname == '..')) { continue; }
  // if (preg_match("/php/i", $formname)) continue;
  if (preg_match("/^\.|php/i", $formname)) continue;
  $grp = substr($formname,0,1);
  $g[$grp][$count] = $formname;
  $count += 1;
  }
?>
<input placeholder="Enter search string" id="inp" type="text" value="" autofocus title="Enter string to limit the number of rows listed.">&nbsp;&nbsp;
<button id="btnALL">Show All</button>&nbsp;&nbsp;
<span id="helpclk" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
<div id="help">
<p>Enter a key word or short character string and &quot;Apply Filter&quot; to list only those documents with titles containing the target string.  Click the &quot;Show All&quot; button to clear the filter and list all available documents.</p>
<p>Documents are grouped based on the first digit of preceeding document number.  Clicking a button wll list only those documents associated with that button&apos;s label.  Usually these are documents that are associated with that topic.  Documents may be duplicated between subjects when ever it might be appropriate.</p>
</div>
<br><br>
<ul><table border=0><thead>
<tr id="head"><th>Document Title</th><th>Size(KB)</th><th>&nbsp;</th><th>Date/time last updated</th></tr></thead><tbody>

<?php
foreach ($btnarray as $k => $v) {
  if (isset($g[$k])) {
    echo "<button class=\"btn btn-success btn-xs\" id=\"btn$k\">$v</button>&nbsp;&nbsp;";
    }
  }
echo '<br>';



foreach ($g as $k => $v) {
  foreach ($v as $formname) {
  $listingfn = $formname;
  $formname = $ctslib . $formname;
  $moddt = filectime("$formname");
  $cd  = date("m-d-y \a\\t H:i", $moddt);
  $fs = number_format(filesize("$formname")/1000,1);
  $urlformname = urlencode($formname);
  echo "<tr class=\"$k\">";
  // echo "<td><a target=_blank href=\"Forms/$formname\">$formname</a></td>";
  echo "<td><a target=_blank href='formviewer.php?dsp=$urlformname'>$listingfn</a></td>";
  echo "
  <td align=right>$fs</td>
  <td>&nbsp;</td>
  <td>$cd</td></tr>
  ";
    }
  }
?>
</tbody></table></ul>
</div>  <!-- container -->
===== End of List =====
<br>
</body>
</html>
