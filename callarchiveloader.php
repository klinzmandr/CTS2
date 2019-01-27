<!DOCTYPE html>
<html>
<head>
<title>Call Archive Query</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/datepicker3.css" rel="stylesheet">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-datepicker-range.js"></script>

<?php
session_start();
// include 'Incls/vardump.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';
include 'Incls/datautils.inc.php';

$maxfiles = 5;

echo '
<div class="container">
<h3>Call Archive Loader &nbsp;&nbsp;<a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<p>Load calls archived in ./Archive directory</p>
<p>All lines starting with double slashes will be ignored.</p>
';

if (!isset($_REQUEST['submit'])) {
  echo '
  <form action="callarchiveloader.php" method="post"  class="form">
  <input type="submit" name="submit" value="Submit">
  </form>
  <h4>The following archive files will be loaded into the CTS2 database callarchive table.</h4>';
  
  $archdir = scandir("./Archive");  $filecount = 0;
  foreach ($archdir as $f) {
    if (is_dir("./Archive/$f")) continue;
    if (substr($f,0,1) == '.') continue;
    echo "$f<br>";
    $filecount++;
    if ($filecount >= $maxfiles) break;
    }
  exit;
  }
  
$starttime = date("M d, Y \a\\t H:i:s", strtotime("now"));

// process archive folders  
$archdir = scandir("./Archive");
$filecount = 0; $rcdcount = 0;
foreach ($archdir as $f) {
  if (is_dir("./Archive/$f")) continue;
  if (substr($f,0,1) == '.') continue;
  $fc = file("./Archive/$f", FILE_IGNORE_NEW_LINES);
  echo "processing: $f<br>";
  foreach ($fc as $l) {
    if (substr($l,0,2) == '//') continue;
    // echo '<pre>arch '; print_r($l); echo '</pre>';
    list(
      $upd[Status],
      $upd[CallNbr],
      $upd[DTOpened],
      $upd[DTPlaced],
      $upd[DTClosed],
      $upd[AnimalLocation],
      $ignoredisposition,
      $upd[Property],
      $idnorecalltype,
      $upd[Species],
      $upd[Resolution],
      $upd[TimeToResolve],
      $upd[PostcardSent],
      $upd[OpenedBy],
      $upd[Reason],
      $upd[CaseRefNbr],
      $ignoretrans,
      $ignoretranshrs,
      $ignoretransmi,
      $upd[LastUpdater],
      $upd[CallLocation]) = explode(';', $l);
      $upd[DTOpened] = date("Y-m-d H:i", strtotime($upd[DTOpened]));
      $upd[DTPlaced] = date("Y-m-d H:i", strtotime($upd[DTPlaced]));
      $upd[DTClosed] = date("Y-m-d H:i", strtotime($upd[DTClosed]));
    // echo '<pre>upd '; print_r($upd); echo '</pre>';
    $res = sqlinsert('callsarchive', $upd);
    $rcdcount++;
    }
  
  $filecount++;
  if (!rename("./Archive/$f", "./Archive/Done/$f")) {
    echo "rename of ./Archive/$f to ./Archive/Done/$f FAILED!";
    }
  if ($filecount >= $maxfiles) break;
  }
$endtime = date("M d, Y \a\\t H:i:s", strtotime("now"));

?>
<h3>Files processed: <?=$filecount?>, Total rows inserted: <?=$rcdcount?></h3>
Start time: <?=$starttime?><br>
  End Time: <?=$endtime?><br><br>
<a href="callarchiveloader.php" class="btn btn-primary">Run Again</a>
</div>
</body>
</html>

