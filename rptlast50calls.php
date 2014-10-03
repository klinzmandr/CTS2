<!DOCTYPE html>
<html>
<head>
<title>Last 50 Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/seccheck.inc';
//include 'Incls/mainmenu.inc';

print <<<pagePart1
<div class="container">
<h3>Last 50 Calls Report  <a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>  
<p>This report will provide a listing of the last 50 calls logged into the database sorted in chronological order descending with the newest call first.</p>
<p>NOTE:  All reports open in a new window (or tab) ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>
</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
