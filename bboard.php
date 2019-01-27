<?php
session_start();
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$seqnbr = isset($_REQUEST['seqnbr'])? $_REQUEST['seqnbr'] : ""; 

?>
<!DOCTYPE html>
<html>
<head>
<title>Bulletin Board</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/font-awesome.min.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>

<style>
  .page-break  {
    clear: left;
    display:block;
    page-break-after:always;
    }
</style>

<div class="container">
<h2 class="hidden-print">Bulletin Board
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
&nbsp;&nbsp;<a href="bboardupdate.php?action=addnew"><i title="Add New Note" class="glyphicon glyphicon-plus" style="color: blue; font-size: 20px"></i></a></h2>
<div id="help">
<p>The Bulletin Board is intended to allow communications between users of the CTS2 system. Information about cases as well as other information pertaining to the day to day challenges of working with the public in encouraged to be added tot the Bulletin Board on an ongoing basis.</p>
<p>New notes are added into the database and listed in newest to oldest order so all the latest info is at the top of the Bulletin Board If needed, the administrator can edit an existing note or change its priority to list it at the top of the list if and/or when it becomes necessary.</p>
<p>A couple of guidelines:
<ol>
<li>The CTS2 system is not a public system intended for anyone other than the volunteers supporting PWC. However, the system is hosted on a publicly available service available via the Internet. This means that caution should be used in leaving personal information on the bulletin board itself. It should be noted that caller info including addresses, phone numbers, etc. are in the data base and are not available to anyone other than the approved users</li>
<li>Information on the Bulletin Board is visible to anyone who chooses to view it, but not forwarded to any specific person(s). Please feel free to add any Information of a general nature regarding PWC related situations which you feel would benefit other volunteers.</li>
</ol></p>
<p>Bulletin Board notes are very easy to create and will always carry the name of the person creating them for their entire life the the board. "Old" notes can be edited, bumped (changed in the listing priority) or deleted by the administrator.</p>
</div>
Filter:<input placeholder="Enter search string" id="inp" type="text" value="" autofocus>&nbsp;&nbsp;&nbsp;&nbsp;
<!-- <button id="btnFILTER">Apply Filter</button>&nbsp;&nbsp; -->
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

});

function confirmContinue() {
	var r=confirm("This action cannot be reversed.\n\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

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
echo '<table class="table table-condensed" border=1>
<tr><th>Title/Topic</th><th>Author</th><th>Note#</th><th width="20%">Last Update</th></tr>';
while ($r = $res->fetch_assoc()) {
  if (preg_match("/newrec/i", $r[UserID])) continue;
  //echo '<pre> bboard '; print_r($r); echo '</pre>';
	echo "<tr id=\"$r[SeqNbr]\" style='cursor: pointer;'><td>$r[Subject]</td><td>$r[UserID]</td><td>$r[SeqNbr]</tf><td>$r[DateTime]</td></tr>
	";	
	}
	echo '</table>';

?>
</div>  <!-- container -->

</body>
</html>
