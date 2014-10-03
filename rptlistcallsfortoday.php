<!DOCTYPE html>
<html>
<head>
<title>Report Calls for Today</title>
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
<h3>Report Calls For Today    <a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<p>This report merely lists those calls that have been entered since midnight.</p>
<p>NOTE:  All reports open in a new window (or tab) ready for printing (if needed).  Use the 'CLOSE' button to close this window.</p>
</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
