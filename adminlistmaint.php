<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>CTS2 List Maintenance</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
<!-- Form change variable must be global -->
var chgFlag = 0;

function chkchg() {
	if (chgFlag == 0) { return true; }
	var r=confirm("All changes made will be lost.\n\nConfirm by clicking OK. (" + chgFlag + ")");	
	if (r == true) { return true; }
	return false;
	}

function flagChange() {
	chgFlag += 1;
	//alert("something has changed count: " + chgFlag);
	return true;
	}
</script>

<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$file = isset($_REQUEST['file'])? $_REQUEST['file'] : "";
$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$updfile = isset($_REQUEST['updfile'])? $_REQUEST['updfile'] : "";
$ta = isset($_REQUEST['ta'])? $_REQUEST['ta'] : "";

echo "<div class=\"container\">";
if ($action == "update") {
	//echo "update requested for file: $updfile<br>";
	//echo "with the values:<br />"; 
	//echo "<pre>"; echo $ta; echo "</pre><br />";
	//writemaintlist("./config/".$updfile,$ta);
	updatedblist($updfile,$ta);
	echo "<h4>File updated successfully: $updfile</h4>";
	$file = $updfile;
	}

echo "<h2>Admin: List Maintenance Utility</h2>";
//echo "<a onclick=\"return chkchg()\" class=\"btn btn-success\" href=\"index.php\">RETURN</a>";
if ($file == "") {
	echo '<p>Choose a menu option to update a specific list.</p>
	<p>All list (except the Admin Users list, use a free form text file to define the list items used.  Lines that begin with a double slash (//) are provided for comments (which are encouraged.)  The comment lines as well as blank lines are ignored </p>
	<p>Lines are formated into two parts seperated with a colon (:). The first part is used to write into the database and the second is the descriptive text that is displayed in the drop down selection list.  The first part is what is used when searching or creating reports from the database so careful thought should be put into the selection of the terms used.  </p>
	<p>Spaces are important and count even though they are not immediately visable.  For example, to create a &apos;blank&apos; item in the list one would specifify &apos; : &apos; (without the apostrophies, of course.)</p>';
	
	echo "<p>Make sure to save your changes after performaing any updates.</p>";
	}

if ($file == "Locations") {
	echo "<h3>Locations</h3>";
	echo "<form action=\"adminlistmaint.php\" method=\"post\">";
	echo "<textarea name=\"ta\" rows=\"20\" cols=\"100\">";
	echo readdblist('Locations');
	echo "</textarea><br />";	
	echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"updfile\" value=\"$file\">";	
	echo "<input type=\"submit\" name=\"Submit\" value=\"Submit Changes\" />";
	echo "</form>";
	echo '</body></html>';
	exit;
	}

if ($file == "Properties") {
	echo "<h3>Properties</h3>";
	echo "<form action=\"adminlistmaint.php\" method=\"post\">";
	echo "<textarea name=\"ta\" rows=\"20\" cols=\"100\">";
	echo readdblist('Properties');
	echo "</textarea><br />";	
	echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"updfile\" value=\"$file\">";	
	echo "<input type=\"submit\" name=\"Submit\" value=\"Submit Changes\" />";
	echo "</form>";
	echo '</body></html>';
	exit;
	}

if ($file == "Species") {
	echo "<h3>Species</h3>";
	echo "<form action=\"adminlistmaint.php\" method=\"post\">";
	echo "<textarea name=\"ta\" rows=\"20\" cols=\"100\">";
	echo readdblist('Species');
	echo "</textarea><br />";	
	echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"updfile\" value=\"$file\">";	
	echo "<input type=\"submit\" name=\"Submit\" value=\"Submit Changes\" />";
	echo "</form>";
	echo '</body></html>';
	exit;
	}

if ($file == "Reasons") {
	echo "<h3>Reasons</h3>";
	echo "<form action=\"adminlistmaint.php\" method=\"post\">";
	echo "<textarea name=\"ta\" rows=\"20\" cols=\"100\">";
	echo readdblist('Reasons');
	echo "</textarea><br />";	
	echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"updfile\" value=\"$file\">";	
	echo "<input type=\"submit\" name=\"Submit\" value=\"Submit Changes\" />";
	echo "</form>";
	echo '</body></html>';
	exit;
	}

if ($file == "Actions") {
	echo "<h3>Actions</h3>";
	echo "<form action=\"adminlistmaint.php\" method=\"post\">";
	echo "<textarea name=\"ta\" rows=\"20\" cols=\"100\">";
	echo readdblist('Actions');
	echo "</textarea><br />";	
	echo "<input type=\"hidden\" name=\"action\" value=\"update\">";
	echo "<input type=\"hidden\" name=\"updfile\" value=\"$file\">";	
	echo "<input type=\"submit\" name=\"Submit\" value=\"Submit Changes\" />";
	echo "</form>";
	echo '</body></html>';
	exit;
	}


?>

</div>
</body>
</html>
