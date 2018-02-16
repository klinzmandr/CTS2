<!DOCTYPE html>
<html>
<head>
<title>WRMD</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();
include 'Incls/seccheck.inc.php';
//include 'Incls/mainmenu.inc.php';

print <<<pagePart1
<div class="container">
<h3>Wildlife Rehab Medical Database (WRMD)</h3>
<p>Wildlife Rehabilitation Database (WRMD) is a web-based, online system which can remotely provide reasonably current information on the status and
outcome of the animals admitted to PWC.</p>

<p>Volunteers now have 'view only' access to this program, in order to
check on the status of animals for callers. To access, log into the web
site at: www.wrmd.org</p>

<ul>userid: hotline@pacificwildlifecare.org<br>
password: Raptor1 (it is case sensitive)</ul>

<p>Remember, when providing information to callers, keep it simple, there is
no need to go into detail about medications given or a step by step on the
animal&apos;s recovery, a simple "Its still in treatment" or "I&apos;m sorry, it
didn&apos;t make it" or "It is being home rehabbed" is great. In this case,
less really is more.</p>

<p><b>Please note: always refer inquiries from the media to senior staff members at the Center for follow up.</b></p>
<a class="btn btn-primary" href="http://www.wrmd.org">WRMD SYSTEM</a><br /><br />
<a href="javascript:self.close();" class="btn btn-warning"><b>CANCEL</b></a>
</div>  <!-- container -->
pagePart1;

?>

</body>
</html>
