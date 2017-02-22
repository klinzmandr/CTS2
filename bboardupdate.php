<!DOCTYPE html>
<html>
<head>
<title>BBoard Note Update</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body onchange="flagChange()">
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
echo '<div class="hidden-print">';
include 'Incls/mainmenu.inc.php';
echo '</div>';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$seqnbr = isset($_REQUEST['SeqNbr'])? $_REQUEST['SeqNbr'] : ""; 

if ($action == 'upd') {
	//echo "update action requested for note $seqnbr<br>";
	$notearray = array();
	//echo 'Update action requested.';
	$uri = $_SERVER['QUERY_STRING'];
	parse_str($uri, $notearray);
	unset($notearray[action]);
	unset($notearray[submit]);
	//echo '<pre> note '; print_r($notearray); echo '</pre>';
	$where = "`SeqNbr`='" . $seqnbr . "'";
	//echo '<pre> sql '; print_r($where); echo '<br> notearray ';print_r($notearray); echo '</pre>';
	sqlupdate('bboard',$notearray, $where);	
	
	echo '<script>
$(document).ready(function() {
  $("#X").fadeOut(2000);
});
</script>
<div class="container">
<h3 style="color: red; " id="X">Update Completed.</h3>
</div>';
	}

if ($action == 'addnew') {
	//echo "add a new note<br>";
	$newarray[UserID] = '**NewRec**';
	sqlinsert('bboard', $newarray);
	echo "A new bulletin board note has been added<br><br>Please complete the details or delete it.<br>";
	}

// read note for updating
$sql = "SELECT * FROM `bboard` WHERE `SeqNbr` = '$seqnbr' OR `UserID`	= '**NewRec**';";
$res = doSQLsubmitted($sql);
$r = $res->fetch_assoc();
$seqnbr = $r[SeqNbr];
$userid = $_SESSION['CTS_SessionUser'];

//echo '<pre> db '; print_r($r); echo '</pre>';
print <<<pagePart1
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	new nicEditor({fullPanel:true}).panelInstance('area1');
});
</script>
<script>
function movemsg() {
	//alert("moving text from div to input field");
	var msgtext = document.getElementById('area1').innerHTML
	document.sndform.msg.value = msgtext;
	if (msgtext.length <= 1) {
		alert("No resources text entered."); 
		return false;
		}
	return true;
	}
</script>

<div class="container">

<h3>Update Bulletin Board Note $seqnbr&nbsp;&nbsp;
<a class="btn btn-success" href="bboard.php">RETURN</a></h3>

<form action="bboardupdate.php"  class="form">
<input type="text" name="Subject" value="$r[Subject]" size="80"  placeholder="Note Subject">
<!-- <div style="font-size: 16px; padding: 3px; border: 5px solid #000; width: 800px; height: 400px; " id="area1"> -->
<textarea name="Note" rows="20" cols="90"  id="area1">
$r[Note]
</textarea>
<input type="hidden" name="SeqNbr" value="$seqnbr">
<input type="hidden" name="UserID" value="$userid">
<input type="hidden" name="action" value="upd">
<input type="submit" name="submit" value="submit">
By: $r[UserID] on $r[DateTime]
</form
<br>

</div>  <!-- container -->
pagePart1;

?>

</body>
</html>
