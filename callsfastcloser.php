<!DOCTYPE html>
<html>
<head>
<title>Fast Close Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>

<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$user = $_SESSION['CTS_SessionUser'];
$notes = $_REQUEST['notes'];
$r = $_REQUEST['flds'];
$call = $r[CallNbr];

// update the database with the info and close the call
if ($action == 'close') {
  $updarray = $_SESSION['FLDS'];
  $call = $updarray[CallNbr];
	$closedate = date('Y-m-d H:i', strtotime(now));
	$updarray[Status] = 'Closed';
	$updarray[DTClosed] = $closedate;
	$updarray[LastUpdater] = $user;
	$updarray[TimeToResolve] = isset($r['TimeToResolve']) ? $r['TimeToResolve'] : '15';
	$updarray[Resolution] = isset($r['Resolution']) ? $r['Resolution'] : 'Closed with No Action';

// update with final note(s)
  $note = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : '';
	$notearray[CallNbr] = $call;
	$notearray[UserID] = $user;
	$notearray[Notes] = 'Call Fast Closed.';
	if ($note <> '') 
    $notearray[Notes] .= '<br>Closing Comment: ' . $note;

//	echo '<pre> notearray '; print_r($notearray); echo '</pre>';
	sqlinsert("callslog", $notearray);

//	echo "callnbr: $call"; 
//	echo '<pre> updarray'; print_r($updarray); echo '</pre>';
	sqlupdate('calls', $updarray, "`CallNbr` = '$call'");

	echo '<ul><h3 style="color: red; ">Call '.$updarray[CallNbr].' Now Closed</h3>
	<a class="btn btn-primary" href="calls.php?action=MyOpen">My Open Calls</a></ul>';
	exit;
	}
// save all call fields to session var for later
$_SESSION['FLDS'] = $_REQUEST['flds'];
//		echo '<pre> close record '; print_r($r); echo '</pre>';

// now checking for error before allowing close
$errs = '';
if ($r[AnimalLocation] == '') $errs .= 'Missing Animal Location<br>';
if ($r[CallLocation] == '') $errs .= 'Missing Call Location<br>';
if ($r[Property] == '') $errs .= 'Missing Property designation<br>';
if ($r[Species] == '') $errs .= 'Missing Species identification<br>';
if ($r[Name] == '') $errs .= 'No Caller Name has been entered<br>';	
if ($r[Reason] == '') $errs .= 'No Reason provided for the call<br>';	
if ($r[Description] == '') $errs .= 'No Call Description has been provided<br>';
if ($errs != '') {
  $errors = "<h3 style='color: #FF0000; '>Errors in call record needing attention:</h3><ul>$errs</ul>";
  echo '<h3 style="color: #FF0000; ">Return and correct errors.</h3>';
}
	
?>
<script>
$("document").ready(function() {
  var errs = "<?=$errors?>";
  var l = errs.length;
  if (errs.length) {
    $("#CF").hide();
    $("#mm-modalBody").html(errs);
    $("#myModal").modal("show");
    }
$("#CForm").submit(function() {
  // alert("form submit seen");
  var er = "";
  var rb = $(":checked").val();
  if (rb == '') {
    er += "Call Time to Resolve not specified.<br>"; }
  var at = $("#AT").val();
  if (at == '') {
    er += "Call Action Taken not specified.<br>"; }
  var notes = $("textarea").val();
  if (notes.length == 0) {
    er += "No closing comment provided.<br>";}
  if (er.length) {
    er = "<h3 style='color: #FF0000; '>Errors in call record needing attention:</h3><ul>" + er + '</ul>';
    $("#mm-modalBody").html(er);
    $("#myModal").modal("show");
    //alert("error: " + er);
    event.preventDefault();
    return;
    }
  return;
  });  
});
</script>
<div id="CF" class="container">
<h3>Fast Closing Call <?=$call?></h3>

<!-- close form if no errors -->
<form id="CForm" action="callsfastcloser.php" method="post" class="form">
Approx. Time to Resolution:
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<15"><15&nbsp;&nbsp;&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<30"><30&nbsp;&nbsp;&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<45"><45&nbsp;&nbsp;&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="<60"><60&nbsp;&nbsp;&nbsp;
<input class="RB" type="radio" name="flds[TimeToResolve]" value="60+">60+

<br />Action Taken:
	<select id="AT" name="flds[Resolution]" size="1">
	<option value=""></option>
	<?php loaddbselect("Actions"); ?>
</select><br />
Closing Note:<br>
<textarea name="notes" rows="5" cols="80"><?=$notes?></textarea><br>
<input type="hidden" name="call" value="<?=$call?>">
<input type="hidden" name="action" value="close">
<input type="submit" name="submit" value="Close Call" id="CC">
<form>

</div>   <!-- form -->
<br><br>
<a class="btn btn-danger" href="callupdatertabbed.php?callnbr=<?=$call?>">CANCEL</a><br>
</div>

</body>
</html>
