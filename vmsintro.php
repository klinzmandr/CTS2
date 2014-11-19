<!DOCTYPE html>
<html>
<head>
<title>VMS Intro</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc';
//include 'Incls/mainmenu.inc';

print <<<pagePart1
<div class="container">
<h3>Voice Message System (VMS)</h3>
<p>Central to PWC's Hotline Service is the voice message system which answers all calls and records the voice message left by the caller.</p>
<p>Usually, access to this system is done using a regular or cell phone by dialing 543-WILD(9453) and entering #546 during the recorded introduction message.  This provides access to the messages left which can then be listened to, saved and eventually deleted.  Calls left are from members of the public but also messages from other Hotline Volunteers or Rescuer/Transporter.</p>
<p>A web interface is also available to review calls left on the answering service. Click the following button to display the login page of the voice messaging system.  <b>Use the hotline phone number as the user id and the same password to log into this system.</b>  A menu of options is provided.  Click the 'Messages' menu item where new messages are listed at the top.  A message can be demoted to a lower rank or deleted entirely using the appropriate icons. </p>
<p>Use the icons for each message for further actions:</p>
<ul>
<li>the date/time link or 'speaker' icon will play the message (NOTE: your browser may have to be configured to do a playback.)</li>
<li>the 'X' icon will DELETE the message (NOTE: this is like hitting the '7' button on your phone.)</li>
<li>the 'down arrow' icon (if message is listed in one of top two lists) will demote the message to the list directly below.</li>
</ul>
<h3>PLEASE NOTE: Use only the 'Messages' menu item of the VMS system.</h3>
<p>Once logged into VMS, the page may be left open to allow easy navigation between the VMS and other tabs/pages of CTS2.</p>
<a class="btn btn-primary" href="http://www.pw-x.net:8081">VMS SYSTEM</a><br /><br />
<a href="javascript:self.close();" class="btn btn-warning"><b>CANCEL</b></a>
</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
