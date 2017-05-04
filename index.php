<!DOCTYPE html>
<html>
<head>
<title>CTS2 Home Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
//include 'Incls/vardump.inc.php'; 
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if (($action == 'logout')) {
	include_once 'Incls/datautils.inc.php';
//	addlogentry("Logging Out");
	unset($_SESSION['CTS_SessionTimer']);
	unset($_SESSION['CTS_SessionUser']);
	unset($_SESSION['CTS_SecLevel']);
	unset($_SESSION['CTS_TEST_MODE']);
	include 'Incls/seccheck.inc.php';      // present login fields
	}
if ((($action) == 'login')) {
	unset($_SESSION['CTS_TEST_MODE']);
	//echo "login request received<br>";
	$userid = $_REQUEST['userid'];
	$password = $_REQUEST['password'];
	if ($userid != "") {
		include_once 'Incls/datautils.inc.php';	
		$ok = checkcredentials($userid, $password);
		if ($ok) {
			//echo "check of user id and password passed<br>";
			addlogentry("Logged In");
			}
		else {
//			addlogentry("Failed login attempt with password: $password");
			unset($_SESSION['CTS_TEST_MODE']);
			echo "Failed login attempt<br>";
			}
		}
	}

include_once 'Incls/datautils.inc.php';
echo "<div class=\"container\">";

//	if (isset($_SESSION['CTS_TEST_MODE']))
//		echo '<h4 style="color: #FF0000; ">TEST MODE ENABLED - using test database for session</h3>';

if (isset($_SESSION['CTS_SessionUser'])) {
  include_once 'Incls/mainmenu.inc.php';
	echo '<h4>Session user logged in: ' . $_SESSION['CTS_SessionUser'] . '</h4>
	<h5>Security level: ' . $_SESSION['CTS_SecLevel'] . '</h5>
	<form class="form-inline" action="index.php" method="post"  id="xform">
  <h3>Home Page&nbsp  
  <button  class="btn btn-large btn-primary" name="action" value="logout" type="submit" form="xform" class="btn">Logout</button>
  </h3></form>
  <h4 style="color: red; ">Check out the latest bulletins:</h4>
  <ul><table class="table table-condensed">
  <tr><th>Posted</th><th>Title</th><th>Author</th></tr>
  ';
  $sql = "SELECT * FROM `bboard` WHERE '1' ORDER BY `DateTime` DESC LIMIT 0,5;";
  $res = doSQLsubmitted($sql);
  while ($r = $res->fetch_assoc()) {
    echo "<tr><td>$r[DateTime]</td><td>$r[Subject]</td><td>By: $r[UserID]</td>";
    }
  echo '
  </table></ul>';
	}
else {
	echo '<form class="form-inline" action="calls.php" method="post\"  id="yform">
	<h2>Call Tracking System II (CTS2)</h2>
	<h3>Home Page&nbsp  
	<button class="btn btn-large btn-primary" name="action" value="login" type="submit" form="yform" class="btn">Login</button></form></h3>
	</h3>';
	}
?>
<!-- START OF PAGE -->
<p><h4>Welcome!</h4></p>
<p><b>The Call Tracking System is for the exclusive use of Pacific Wildelife Care.  Unauthorized use is prohibitted.</b></p>
<p>Access to all the facilities of the system are provided on the main menu located at the top of each page.</p>

<br>
<b>Instructional Videos</b><br><ul>
	<a href="http://youtu.be/MCX1wAn5lbc" target="_blank">Overview and Main Menu(8:13)</a><br>
	<a href="http://youtu.be/ByaI0rxRlLs" target="_blank">Messages Menu Item(10:41)</a><br>
	<a href="http://youtu.be/EI3XwZVAwYg" target="_blank">Calls Menu Item(14:28)</a>
	</ul><br><br>
<div align="center"><img src="img/PWC1080logo.jpg" width="600" height="96" alt=""></div>
<br />
</div>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
