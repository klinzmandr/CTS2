<!DOCTYPE html>
<html>
<head>
<title>Menu Tests</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/seccheck.inc';
include 'Incls/mainmenu.inc';

print <<<pagePart1
<div class="container">
<h3>Test Menus and Navigation</h3>
<p>Testing of menu layouts and page navigation.</p>
<p>Each menu item will display a page which defines the basic function provided by that menu function.</p>
<p>The main menu is to be present on all pages so navigation between the pages is done by selection of a menu item from the main menu bar.  The exception to this are the selections under 'Reports' where each selection will produce a new page especially designed for printing (if needed).  A special 'CLOSE' button is provided for these pages.</p>
<p>NOTE:  The menu item in <font color="#FF0000"><b>RED</b></font> is an administrative menu item and will only be displayed for those users who have been registered as &apos;Administrators&apos; of the system.</p>
</div>

pagePart1;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
