<!DOCTYPE html>
<html>
<head>
<title>CTS2 Home Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc';

if ((($_REQUEST['action']) == 'logout')) {
	include 'Incls//datautils.inc';
//	addlogentry("Logging Out");
	unset($_SESSION['SessionTimer']);
	unset($_SESSION['SessionUser']);
	unset($_SESSION['SecLevel']);
	unset($_SESSION['TEST_MODE']);
	include 'Incls/seccheck.inc';
	}
if ((($_REQUEST['action']) == 'login')) {
	unset($_SESSION['TEST_MODE']);
	//echo "login request received<br>";
	$userid = $_REQUEST['userid'];
	$password = $_REQUEST['password'];
	if ($userid != "") {
		include 'Incls/datautils.inc';	
		$ok = checkcredentials($userid, $password);
		if ($ok) {
			//echo "check of user id and password passed<br>";
//			addlogentry("Logged In");
			}
		else {
//			addlogentry("Failed login attempt with password: $password");
			unset($_SESSION['TEST_MODE']);
			echo "Failed login attempt<br>";
			}
		}
	}

include 'Incls/mainmenu.inc';
echo "<div class=\"container\">";

if (isset($_SESSION['SessionUser'])) {
	echo '<h4>Session user logged in: ' . $_SESSION['SessionUser'] . '</h4>';
	echo '<h5>Security level: ' . $_SESSION['SecLevel'] . '</h5>';
	echo "<form class=\"form-inline\" action=\"index.php\" method=\"post\"  id=\"xform\">";
//	if (isset($_SESSION['TEST_MODE']))
//		echo '<h4 style="color: #FF0000; ">TEST MODE ENABLED - using test database for session</h3>';

  echo '<h3>Home Page&nbsp  <button  class="btn btn-large btn-primary" name="action" value="logout" type="submit" form="xform" class="btn">Logout</button></h3></form>';
	}
else {
	echo "<form class=\"form-inline\" action=\"calls.php\" method=\"post\"  id=\"yform\">";
	echo "<h2>Call Tracking System II (CTS2)</h2>";
	echo "
	<table class=\"table table condensed\" border=\"0\">
	<tr><td valign=\"top\">
	<h3>Home Page&nbsp  <button class=\"btn btn-large btn-primary\" name=\"action\" value=\"login\" type=\"submit\" form=\"yform\" class=\"btn\">Login</button></form></h3></td>
	<td><b>Instructional Videos</b><br><ul>
	<a href=\"http://youtu.be/VfYXGxNpEJw\" target=\"_blank\">Overview and Main Menu</a><br>
	<a href=\"http://youtu.be/a-wcRHG4WaA\" target=\"_blank\">Messages Menu Item</a><br>
	<a href=\"http://youtu.be/IOBq-r477OI\" target=\"_blank\">Calls Menu Item</a>
	</ul></td></tr></table>";
	}
//echo "</h3>";

?>
<!-- START OF PAGE -->
<p><h4>Welcome!</h4></p>
<p><b>The Call Tracking System is for the exclusive use of Pacific Wildelife Care.  Unauthorized use is prohibitted.</b></p>
<p>Access to all the facilities of the system are provided on the main menu located at the top of each page.</p>
</div>
<br><br><br>
<div align="center"><img src="img/PWC1080logo.jpg" width="600" height="96" alt=""></div>
<br />

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
