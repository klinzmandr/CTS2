<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// initial setup of jquery function(s) for page
$(document).ready(function () {
	alert(" example of action on document load");

// this attaches an event to an object
	$("h3").click(function () {
    alert("example of a click of any header 3 like the page title"); 
    });

  });  // end ready function
</script>


<?php
session_start();
//include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

print <<<pagePart1
<div class="container">
<h3>Page Heading</h3>
<p>Explaination of page.</p>

</div>  <!-- container -->
pagePart1;

?>

</body>
</html>
