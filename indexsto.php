<?php
session_start();
$lotype = isset($_REQUEST['lo']) ? $_REQUEST['lo'] : 'to';

// include 'Incls/vardump.inc.php';

include_once 'Incls/datautils.inc.php';
$user = $_SESSION['CTS_SessionUser'];
unset($_SESSION['CTS_SessionUser']);
unset($_SESSION['CTS_SecLevel']);

// logout or timeout reset requested
if ($lotype == 'lo') { 
  $title = "Session Logged Out";  
  addlogentry("$user Logged out");	}
else { 
  $title = "Session Timed Out";
  addlogentry("$user Timed out"); }

session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html>
<head>
<title><?=$title?></title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
<script src="jquery.js"></script>
<script>
$(document).ready(function() {
  // alert("document loaded server is <?=$svrname?>");
  window.location.assign('index.php');
});
</script>

<div hidden class="container">
  <h1><?=$title?></h1>
  <p>Your session has been terminated.</p>
  <p>Click the following button to log into the application.</p>
  <a class="btn btn-success" href="index.php">Restart application</a>
</div>
</body>
</html>