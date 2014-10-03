<?php 
//echo "<hr>Debug Info: dump of input array REQUEST name and value pairs<br>";//foreach ($_GET as $key => $value) { echo "Key: $key, Value: $value<br>";  }//echo "<hr>";
include 'includes/checker.php';
$upw = $_GET['upw'];
$userid = $_GET["userid"];
pwcheck($userid, $upw);

$delete = $_GET['delete'];
$name = $_GET['name'];

//check if delete is set and process deletion of named file if so.
unlink("Forms/" . $name);

//output response
print <<<okResponse
<html>
 <head>
  <title>Maintain User List Updater</title>
  <meta http-equiv="refresh" content="3; URL=maintforms.php?userid=$userid&upw=$upw">
 </head>
 <body>
 <div align="center"><img src="PWC680logo.jpg" border="0" alt="PWC LOGO"></div>

<h1>Forms List has been updated.</h1>
<p>The forms list has been updated.</p>
<a href="maintforms.php?userid=$userid&upw=$upw">Return to Maintenace of Forms</a>
</body>
</html>
okResponse;

?>
