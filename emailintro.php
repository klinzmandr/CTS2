<!DOCTYPE html>
<html>
<head>
<title>Email Message Intro</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';

print <<<pagePart1
<div class="container">
<h3>Email Message System</h3>
<p>A new option has been provided to allow an email confirmation message to be sent to the caller (assuming that an email address has been obtained and entered, of coursel.)  In the event that a message is sent to the caller, that message will contain a 'FROM' address that to facilitate a response being sent.  That response will be sent to 'hotline@pacificwildlifecare.org' and deposited in the inbox of that email system.</p>
<p>Access to the account may be done by clicking the following button and using:<b><ul>userid: hotline@pacificwildlifecare.org<br>
password: hotline9453</b>.</p></ul>
<p>Once logged into the email system, the page may be left open to allow easy navigation between the email system and other tabs/pages of CTS2.</p>
<p>It is recommended that this email account be check at a minimum of once per shift in order to ensure that questions and responses are appropriately handled.</p>
<a class="btn btn-primary" href="http://www.pacwilica.org/mail">EMAIL SYSTEM</a><br /><br />
<a href="javascript:self.close();" class="btn btn-warning"><b>CLOSE</b></a>
</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
