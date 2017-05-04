<!DOCTYPE html>
<html>
<head>
<title>Resources</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<?php
session_start();
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

echo '<div class="container">
<h3>Hotline Volunteer Resources</h3>
<h4>Links will open in a new window.</h4>
<ul>';
include 'Incls/links.inc.php';					// read file with links

echo '</ul></div>';
?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
