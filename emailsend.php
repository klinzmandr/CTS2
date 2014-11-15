<!DOCTYPE html>
<html>
<head>
<title>Send Email</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();

include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';
include 'Incls/datautils.inc';

$email = isset($_REQUEST['emadr']) ? $_REQUEST['emadr'] : '';
$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : '';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action == '') {
//include 'Incls/vardump.inc';
print <<<pagePart1
<div class="container">
<h3>Send Email to Caller  <a href="callupdatertabbed.php?callnbr=$callnbr" class="btn btn-xs btn-primary"><b>CANCEL</b></a></h3>
<h5>This action will result in an email message to be sent to the email address of $email supplied in call record $callnbr</h5>
<p>This email will be sent FROM the PWC email address of hotline@pacificwildlifecare.org.  Please note that to review responses to this email message you must log into this email account by going to the URL of www.pacificwildlifecare.org/webmail and log in with the user id of 'hotline@pacificwildlifecare.org' with a password of 'wild9453'.</p>
<hr>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	new nicEditor({buttonList : ['fontSize', 'fontFormat', 'left', 'center', 'right', 	'bold','italic','underline','indent', 'outdent', 'ul', 'ol', 'hr', 'forecolor', 
	'bgcolor','link','unlink']}).panelInstance('area1');
});
</script>

<script>
function chkemail(form) {
	//alert("email validation seen");
	var subj = form.subject.value.length;
	var body = document.getElementById("area1").value.length;
	var body = form.area1.value.length;
//	if ((subj == 0) || (body == 0)) {
	if (subj == 0) {
		alert("Subject and/or text body is empty.");
		return false;
		}
	return true;
	}
</script>
To: $email<br />
From: hotline@pacificwildlifecare.org<br />
<br />
<form name="emf" class="form" action="emailsend.php" method="post" onsubmit="return chkemail(this)">
Subject:<br />
<input autofocus type="text" name="subject" size="90" style="width: 500; "  placeholder="Subject"><br />
Message:<br />
<textarea id="area1" name="body" rows="10" cols="90">
<p>Thank you for your recent call to the Pacific Wildlife Care hotline.</p>
<p>You call has been noted with a reference number of <b>CR$callnbr</b>.  Please use this call reference number if you have a question regarding this incident.  For more information about this incident you may respond to this email or place a call to our incident reporting hotline at 805-543-9453.</p>
<p>If you would like to support Pacific Wildlife Care by joining, donating, or volunteering you may obtain more information at the <a href="http://www.pacificwildlifecare.org">Pacific Wildlife Care web site</a>.  Your support is greatly appreciated!</p>
</textarea><br />
<input type="hidden" name="to" value="$email">
<input type="hidden" name="from" value="hotline@pacificwildlifecare.org">
<input type="hidden" name="action" value="send">
<input type="hidden" name="callnbr" value="$callnbr">
<input type="submit" name="Submit" value="Send"><br />
<input type="reset" name="reset" value="Reset Form" />
</form>
<br>
</div>  <!-- container -->
</body></html>

pagePart1;
exit;
}

//include 'Incls/vardump.inc';

print <<<pagePart2
<h3>Email Send Confirmation&nbsp;&nbsp; <a href="callupdatertabbed.php?callnbr=$callnbr" class="btn btn-xs btn-primary">RETURN</a></h3>
pagePart2;
$to = $_REQUEST['to'];
$subject = $_REQUEST['subject'];
$body = stripslashes($_REQUEST['body']);
// create and log message to call history
$notes = 'Email message sent to caller as follows:<br>';
$notes .= 'To: ' . $to . '<br>';
$notes .= 'Subject: ' . $subject . '<br>';
$notes .= 'Message: ' . $body . '<br>';
$notearray = array();
$notearray[CallNbr] = $callnbr;
$notearray[UserID] = $_SESSION['SessionUser'];
$notearray[Notes] = $notes;
// echo '<pre> note '; print_r($notearray); echo '</pre>';
sqlinsert("callslog", $notearray);
// send email message to caller
$from = 'hotline@pacificwildlifecare.org';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= "From: " . $from . "\r\n";
$headers .= "Reply-To: " . $from . "\r\n";
$headers .= "Return-Path: " . $from . "\r\n";   // these two to set reply address
$foption = "-f" . $from;												// notify of undeliverable mail to sender

$mresp = mail($to, $subject, $body, $headers, $foption);
if ($mresp == FALSE) {
	echo "<h4 style=\"color: red;\">ERROR: an error was returned when sending the email message</h4><br />";
	}
else {
	echo "<h4>An email message was successfully sent to $to</h4>";
	echo "Click the RETURN button to return to Call $callnbr<br><br>";
	}
?>

</body>
</html>
