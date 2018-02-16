<!DOCTYPE html>
<html>
<head>
<title>Bulletin Board</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>

<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
?>

<div class="container">
<h2 class="hidden-print">Bulletin Board&nbsp;&nbsp;<a href="bboardupdate.php?action=addnew"><span title="Add New Note" class="glyphicon glyphicon-plus" style="color: blue; font-size: 20px"></span></a></h2>
Filter:<input id="inp" type="text" value="">&nbsp;&nbsp;+&nbsp;&nbsp;
<button id="btnFILTER">Apply Filter</button>&nbsp;&nbsp;
<button id="btnALL">Show All</button>&nbsp;&nbsp;
<script>
$("document").ready( function() {
  $("#X").fadeOut(5000);

// ajax call to display bb note
$("tr").click (function() {
  var id = $(this).attr('id');
  // alert ("row clicked " + id);
  $.post("bboardjson.php",
    {
      bbid: id
    },
  function(data, status){
      // alert("Data: " + data + "\nStatus: " + status);
      $("#mm-modalBody").html(data);
      $("#myModal").modal("show");
      
    });  // end $.post logic 
  });

// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });
  
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

});

function confirmContinue() {
	var r=confirm("This action cannot be reversed.\n\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

<?php
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$seqnbr = isset($_REQUEST['seqnbr'])? $_REQUEST['seqnbr'] : ""; 

if ($action == 'delete') {
	// echo "delete $seqnbr requested<br>";
	$sql = "DELETE FROM `bboard` WHERE `SeqNbr` = '$seqnbr';";
	$rc = doSQLsubmitted($sql);		// returns affected_rows for delete
	if ($rc > 0) 
		$err = "<h4 style=\"color: #FF0000; \">Deletion of note $seqnbr successful</h4>";
	else
		$err = "<h4 style=\"color: #FF0000; \">Error on delete of note $seqnbr</h4>";
  echo "<div id=\"X\">$err</div>";
	}

if ($action == 'update') {
	echo "update $seqnbr requested<br>";
	}

$sql = "SELECT * FROM `bboard` WHERE '1' ORDER BY `DateTime` DESC;";
$res = doSQLsubmitted($sql);
echo '<table class="table table-condensed" border=0>
<tr><th>Title/Topic</th><th>Author</th><th>Last Update</th></tr>';
while ($r = $res->fetch_assoc()) {
  if (preg_match("/newrec/i", $r[UserID])) continue;
  //echo '<pre> bboard '; print_r($r); echo '</pre>';
	echo "<tr id=\"$r[SeqNbr]\" style='cursor: pointer;'><td>$r[Subject]</td><td>$r[UserID]</td><td>$r[DateTime]</td></tr>
	";	
	}
	echo '</table>';

?>
</div>  <!-- container -->

</body>
</html>
