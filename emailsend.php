<?php
session_start();
// echo "MCID: " . $_SESSION['CTS_ActiveCTSMCID'] . '<br>';
$mcid = $_SESSION['CTS_ActiveCTSMCID'];
$email = isset($_REQUEST['emadr']) ? $_REQUEST['emadr'] : ''; // of caller
$volemail = $_SESSION['CTS_VolEmail'];
if ($volemail == '') $volemail = 'hotline@pacificwildlifecare.org';

$crn = isset($_REQUEST['crn']) ? $_REQUEST['crn'] : '';             // WRMD case number
$callnbr = isset($_REQUEST['callnbr']) ? $_REQUEST['callnbr'] : ''; // CTS CR number
$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';          // caller name
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

?>
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
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';
  
if ($name == '') $name = 'PWC Caller';
$to = "$name <$email>";
$specto = htmlentities($to);
if ($action == '') {
?>
<div class="container">
<h3>Send Email to Caller
<span id="helpbtn" title="Email Information" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;
<a href="callupdatertabbed.php?callnbr=<?=$callnbr?>" class="btn btn-danger"><b>CANCEL</b></a></h3>
<div id=help>
<h5>This action will result in an email message to be sent to the email address of <?=$email?> supplied in call record <?=$callnbr?></h5>
<p>This email will be sent FROM the PWC email address of hotline@pacificwildlifecare.org.  Please note that to review responses to this email message you must log into this email account by going to the URL of <a href="https://www.pacificwildlifecare.org/mail" target=_blank>www.pacificwildlifecare.org/mail/</a> and log in with the user id of '<b>hotline@pacificwildlifecare.org</b>' with a password of '<b>hotline9453</b>'.</p>
</div>
<script>
function setup(rep) {
  var val = rep;
  var tar = '#'+rep;
  $("#area1").html($(tar).html());
  var strNewString =  $('#area1').html().replace(/\[callnbr\]/g, "<?=$callnbr?>");
  strNewString = strNewString.replace(/\[name\]/g, "<?=$name?>");
  strNewString = strNewString.replace(/\[crn\]/g, "<?=$crn?>");
	$('#area1').html(strNewString);
	if (strNewString.length < 10) {
	  alert("Reply has not been created.")
	  return false;
    }
  return true;
  }
</script>
<b>Choose and edit a predefined reply or enter your own.</b><br>
<a class="btn btn-info" onclick=setup("Reply1")>Reply 1</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply2")>Reply 2</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply3")>Reply 3</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply4")>Reply 4</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply5")>Reply 5</a>&nbsp;&nbsp;
<a class="btn btn-info" onclick=setup("Reply6")>Reply 6</a><br><br>

<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
  new nicEditor({fullPanel:true}).panelInstance("area1");
  });    

</script>

<script type="text/javascript">
function chkemail(form) {
	var subj = form.subject.value.length;
	if (subj == 0) {
		alert("Subject line is empty.");
		return false;
		}
	var div_val=document.getElementById("area1").innerHTML;
  if(div_val=='<br>'){
    alert("Nothing entered in the email message.");
    return false;
    }
  var pattern = /{.*}/i;
  var tst = pattern.test(div_val);
  if (tst) {
    alert("Replace BOLDED sentence with appropriate verbiage.");
    return false;
    }
  document.getElementById("ta").value =div_val;
  //alert("OK to send");
  return true;
	}
</script>
<script>
$(function() {
  $("#emaddr").val('hotline@pacificwildlifecare.org');
  $("#emchgdef").hide();
  var em = '<?=$volemail?>';
  // var emdefault = 'hotline@pacificwildlifecare.org';
  $("#emchg").click(function() {
    $("#emfrom").text(em);
    $("#emchg").hide();
    $("#emchgdef").show();
    $("#emaddr").val(em);
  });
  $("#emchgdef").click(function() {
    $("#emfrom").text('hotline@pacificwildlifecare.org');
    $("#emchg").show();
    $("#emchgdef").hide();
    $("#emaddr").val('hotline@pacificwildlifecare.org');
  });  
});
</script>
To: <?=$specto?><br />
From: <span id=emfrom>hotline@pacificwildlifecare.org</span>&nbsp;&nbsp;
<button id=emchg>Use your personal email</button>
<button id=emchgdef>Use system default</button>
<br />
<form name="emf" class="form" action="emailsend.php" method="post" onsubmit="return chkemail(this)">
<input type=hidden id=emaddr name=emaddr value=''>
Subject:<br />
<input autofocus type="text" name="subject" size="90" style="width: 500; "  placeholder="Subject"><br />
Message:<br />
<div style="font-size: 16px; background-color:#FFF; padding: 3px; border: 1px solid #000;" id="area1"></div>
<textarea style="display:none;" id="ta" name="body"></textarea><br />
<input type="hidden" name="emadr" value="<?=$email?>">
<input type="hidden" name="from" value="hotline@pacificwildlifecare.org">
<input type="hidden" name="action" value="send">
<input type="hidden" name="callnbr" value="<?=$callnbr?>">
<input type="hidden" name="name" value="<?=$name?>">
<input type="submit" name="Submit" value="Send"><br />
<input type="reset" name="reset" value="Reset Form" />
</form>
<br>
</div>  <!-- container -->
</body></html>

<?php 
echo '<div style="visibility: hidden; " id="Reply1">';
include 'emreplys/emailReply1.inc.php';
echo '</div>
<div style="visibility: hidden; " id="Reply2">';
include 'emreplys/emailReply2.inc.php';
echo '</div>
<div style="visibility: hidden; " id="Reply3">';
include 'emreplys/emailReply3.inc.php';
echo '</div>
<div style="visibility: hidden; " id="Reply4">';
include 'emreplys/emailReply4.inc.php';
echo '</div>
<div style="visibility: hidden; " id="Reply5">';
include 'emreplys/emailReply5.inc.php';
echo '</div>
<div style="visibility: hidden; " id="Reply6">';
include 'emreplys/emailReply6.inc.php';
echo '</div>';

exit;
}   // end if $action == ''

// include 'Incls/vardump.inc.php';

echo "<h3>Email Sending Completed&nbsp;&nbsp; <a href='callupdatertabbed.php?callnbr=$callnbr' class='btn btn-primary'>RETURN</a></h3>";

$subject = $_REQUEST['subject'];
$body = stripslashes($_REQUEST['body']);
// create and log message to call history
$notes = 'Email message sent to caller as follows:<br>';
$notes .= 'To: ' . $specto . '<br>';
$notes .= 'Subject: ' . $subject . '<br>';
$notes .= 'Message: ' . $body . '<br>';
$notearray = array();
$notearray[CallNbr] = $callnbr;
$notearray[UserID] = $_SESSION['CTS_SessionUser'];
$notearray[Notes] = $notes;
// echo '<pre> note '; print_r($notearray); echo '</pre>';
sqlinsert("callslog", $notearray);
// update email sent flag in call record
$where = "`CallNbr`='" . $callnbr . "'";
$updarray[EmailSent] = 'Yes';
sqlupdate("calls", $updarray, $where);

$trans = array("\\" => '', "\n" => '', "\t"=>'', "\r"=>'');
$subject = strtr($subject, $trans);
$message  = strtr($body, $trans);

$list = array(); $msg = array();
$list[] = 'HOTLINE:'.$to;

$msg[] = $_REQUEST['emaddr'];

$msg[] = $subject;
$msg[] = $message;

// echo '<pre> LIST '; print_r($list);echo '</pre>';
// echo '<pre> MSG '; print_r($msg); echo '</pre>';

// NOTE: write the MSG and LIST files out then kick the sender off here
$prefix = date('YmdHis');
$listname = "../MailQ/$prefix.1.LIST";
$msgname  = "../MailQ/$prefix.1.MSG";
file_put_contents($listname, implode("\n", $list));
file_put_contents($msgname, implode("\n", $msg));

// send email message to caller
//echo 'server: ' . $_SERVER['SERVER_NAME'] .'<br>';
//echo 'Message written to the send queue.<br>';
if ($_SERVER['SERVER_NAME'] != 'localhost') {
  echo '<br>Starting sender program at ' . date('r') . '<br>';
  // kick the mailsender routine on its way 
  // cron will automatically schedule every qtr-hour, but this gets it started right now
  // output of command will be in mailsenderlog.txt
  $cmd = '/home/pacwilica/bin/mailsender';
  exec($cmd . " > /home/pacwilica/public_html_apps/mailsenderlog.txt &");
  }
else {
  echo '<h4>Email files written to the test system MailQ directory.</h4>';  
  }

echo "<h4>An email message was successfully queued to be sent to $specto</h4>";

echo "The message may be reviewed by using the <a href=\"rptmaillogviewer.php\" target=_blank>Mail Log Viewer</a> which is also available in the Reports menu<br>
Click the RETURN button to return to Call $callnbr<br><br>";

?>

</body>
</html>
