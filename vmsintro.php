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
include 'Incls/mainmenu.inc';

print <<<pagePart1
<div class="container">
<h3>Voice Message System</h3>
<p>Central to PWC's Hotline Service is the voice message system which answers all calls and records the voice message left by the caller.</p>
<p>Usually, access to this system is done using a regular or cell phone by dialing 543-WILD(9453) and entering #546 during the recorded introduction message.  This provides access to the messages left which can then be listened to, saved and eventually deleted.  Calls left are from members of the public but also messages from other Hotline Volunteers or Rescuer/Transporter.</p>
<p>A web interface is also available to review calls left on the answering service. Click the following button to open a new tab/window which will display the login page of the voice messaging system.  Use the hotline phone number as the user id and the same password to log into this system.  A menu of options is provided.  Click the 'Messages' menu item where new messages are listed at the top.  A message can be demoted to a lower rank or deleted entirely using the appropriate icons. </p>
<a class="btn btn-primary" href="http://www.pw-x.net:8081" target="_blank">MESSAGE SYSTEM</a><br /><br />

<p><b>NOTE: this feature opens in a new window.</b></p>
</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
