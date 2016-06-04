<!DOCTYPE html>
<html>
<head>
<title>Resource Links</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

print <<<pagePart1
<div class="container">
<h3>Resource Links</h3>
<p>This page will contain a list of all the resource links that are defined</p>

</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
