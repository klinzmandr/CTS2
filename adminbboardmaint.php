<!DOCTYPE html>
<html>
<head>
<title>Maintain Bullentin Board</title>
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
<h3>Administration of Bulletin Board</h3>
<p>This page will provide for the administration of the Bulletin Board.  Each PV may add a new note to the bulletine board individually.  The functions provided on this page are for all posted notes regardless of who originated them.  Each note may be individully editted or deleted as needed.</p>

</div>  <!-- container -->
pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
