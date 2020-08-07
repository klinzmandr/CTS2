<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Resources</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>

<?php
include 'Incls/seccheck.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';

$links = readdblist('Resources');
// echo '<pre>Resources '; print_r($links); echo '</pre>';
?>

<div class="container">
<h3>Hotline Volunteer Resources
<span id="helpbtn" title="Help Documentation" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</h3>
<div id=help><h4>Help Documentation</h4>
<p>Resource links are intended to provide volunteers with information available from other Internet resources.  The links are currently grouped into two categories:  those internal to PWC and links to other wildlife rescue and regulatory organizations.</p>
<p>Clicking a resource link will open a new tab with the resource in it.  This is done to allow browser navigation between the open tabs allowing refereing information to be reviewed while leaving the main CTS2 tab open and available for call retreival and update.</p>
<p>Documents, usually in PDF format, should be uploaded to the &apos;<a href="http://localhost/www/dev/cts2/forms.php">Forms and Documentation</a>&apos; facility.</p>
</div>
<h4>Links will open in a new tab/window.</h4>
<ul>
<?=$links?>
</ul></div>
</body>
</html>
