<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>CTS2 Home Page</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : '';

// NOTE:
// NOTE: (isset($var) && !empty($var)) will be equals to !empty($var)
// http://php.net/manual/en/types.comparisons.php

// echo "show login fields"; include 'Incls/vardump.inc.php'; 
if (!isset($_REQUEST['userid'])) {                // no user id
  if (!isset($_SESSION['CTS_SessionUser'])) {    // and no session id
    // echo "userid empty, no cts_sessionuser<br>"; 
  	include 'Incls/seccheck.inc.php';           // present login fields
  	exit;
  	}
  }

if (!empty($userid)) {
//  echo "check uid/pw"; include 'Incls/vardump.inc.php';  
  include_once 'Incls/datautils.inc.php';
	$password = $_REQUEST['password'];
	$ok = checkcredentials($userid, $password);
	if ($ok) {
		//echo "check of user id and password passed<br>";
		addlogentry("$userid Logged In");
		}
	else {
//			addlogentry("Failed login attempt with password: $password");
//			echo '<h3 style="color: red; ">Failed login attempt</h3>';
		}
	}

echo "<div class=\"container\">";
if (!empty($_SESSION['CTS_SessionUser'])) {
  // echo "show logged in"; include 'Incls/vardump.inc.php';
  include_once 'Incls/datautils.inc.php';
  include 'Incls/seccheck.inc.php';         
  include_once 'Incls/mainmenu.inc.php';
	echo '<h4>Session user logged in: ' . $_SESSION['CTS_SessionUser'] . '</h4>
	<h5>Security level: ' . $_SESSION['CTS_SecLevel'] . '</h5>
	<form class="form-inline" action="indexsto.php?lo=lo" method="post"  id="xform">
  <h3>Home Page&nbsp  
  <button  class="btn btn-large btn-primary" name="action" value="logout" type="submit" form="xform" class="btn">Logout</button>
  </h3></form>
  <h4 style="color: red; ">Check out the last 5 bulletins (<a href="bboard.php">or view all of them</a>)</h4>
  <ul><table class="table table-condensed">
  <tr><th>Posted</th><th>Title</th><th>Author</th></tr>
  ';
  $sql = "SELECT * FROM `bboard` WHERE '1' ORDER BY `DateTime` DESC;";
  $res = doSQLsubmitted($sql);
  while ($r = $res->fetch_assoc()) {
    if (preg_match("/newrec/i", $r['UserID'])) continue;  // ignore newly added
    echo "<tr><td>$r[DateTime]</td><td>$r[Subject]</td><td>By: $r[UserID]</td>";
    $count++; if ($count > 4) break;                    // only list 5
    }
  echo '
  </table></ul>';
	}
else {
	echo '<form class="form-inline" action="index.php" method="post\"  id="yform">
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
	<!-- <a href="https://youtu.be/kVp5n-dqNj0" target="_blank">Overview and Main Menu(14:00))</a><br> -->
	<!-- <a href="https://youtu.be/0tWkY2ICwqE" target="_blank">External Systems Menu Item(12:21)</a><br> -->
	<a href="https://youtu.be/8JIri-aBhXQ" target="_blank">Calls Menu Item(10:06)</a><br>
	<a href="https://youtu.be/Ex6jrTQPsy8" target="_blank">CTS2 Admin Functions (14:06)</a>
	</ul><br><br>
<div align="center"><img src="img/PWC1080logo.jpg" width="600" height="96" alt=""></div>
<br />
</div>
</body>
</html>
