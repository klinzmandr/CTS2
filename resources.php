<!DOCTYPE html>
<html>
<head>
<title>Resources</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';

echo '<div class="container">
<h3>Hotline Volunteer Resources</h3>
<h4>Links will open in a new window.</h4>';
include 'Incls/links.inc';					// read file with links

echo '</div>';
?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
