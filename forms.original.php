<!DOCTYPE html>
<html>
<head>
<title>Forms</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();

include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$forms = scandir('Forms');


echo '<div class="container">
<h3>Forms & Documentation</h3>
<h4>Documents will open in a new window.</h4>';

//include 'Incls/links.inc.php';					// read file with links

echo "<table>
<tr><th>Document Title</th><th>Date and time last updated</th></tr>";
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
$moddt = filectime("Forms/$formname");
$cd  = date("F d, Y \a\\t H:i:s.", $moddt) . "<br>";
echo "<tr><td width=\"50%\">
<a target=_blank href=\"Forms/$formname\">$formname</a></td><td align=\"center\">$cd</td></tr>";
}
?>
</div>  <!-- container -->
</table>
<br>
<p>Note: All links will open it in a new window.</p><br>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
