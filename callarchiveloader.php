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

echo '
<div class="container">
<h3>Call Archive Loader &nbsp;&nbsp;<a href="javascript:self.close();" class="btn btn-primary"><b>CLOSE</b></a></h3>
<p>Load calls archived in ../cts/Archive directory</p>
<p>All lines starting with double slashes will be ignored.</p>
';

if (!isset($_REQUEST['submit'])) {
  echo '
  <form action="callarchiveloader.php" method="post"  class="form">
  <input type="submit" name="submit" value="Submit">
  </form>
  <h4>The following archive files will be loaded into the CTS2 database callarchive table.</h4>';
  
  $archdir = scandir("../cts/Archive");
  foreach ($archdir as $f) {
    if (is_dir("../cts/Archive/$f")) continue;
    if (substr($f,0,1) == '.') continue;
    echo "$f<br>";
    }
  exit;
  }
// process archive folders  
$archdir = scandir("../cts/Archive");
$filecount = 0; $rcdcount = 0;
foreach ($archdir as $f) {
  if (is_dir("../cts/Archive/$f")) continue;
  if (substr($f,0,1) == '.') continue;
  $fc = file("../cts/Archive/$f", FILE_IGNORE_NEW_LINES);
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
    echo '<pre>upd '; print_r($upd); echo '</pre>';
    $res = sqlinsert('callsarchive', $upd);
    $rcdcount++;
    }
  
  $filecount++;
  rename("../cts/Archive/$f", "../cts/Archive/Done/$f");
  if ($filecount >= 5) break;
  }
?>
<h3>Files processed: <?=$filecount?>, Total rows inserted: <?=$rcdcount?></h3>
<div class="container">
<h3>Heading Definitions of Archive Record</h3>
<table class="table table-condensed">
<tr><th>Heading</th><th>Definition</th><th>CTS</th><th>CTS2</th></tr>
<tr><td>Status</td><td>Status of Call (Open vs Closed)</td><td>X</td><td>X</td></tr>
<tr><td>CallNbr</td><td>Unique call number (CR = CTS, CT = CTS2)</td><td>X</td><td>X</td></tr>
<tr><td>DTOpened</td><td>Date call opened</td><td>X</td><td>X</td></tr>
<tr><td>DTPlaced</td><td>Date call placed (usually the same as prev. col)</td><td>X</td><td>X</td></tr>
<tr><td>DTClosed</td><td>Date call closed</td><td>X</td><td>X</td></tr>
<tr><td>AnimalLocation</td><td>Location (or approx. location) that the animal was picked up</td><td>X</td><td>X</td></tr>
<tr><td>Property</td><td>Type of property the animal was found on (e.g. business, private, city, state, etc.)</td><td>X</td><td>X</td></tr>
<tr><td>Species</td><td>Caller reported species type</td><td>X</td><td>X</td></tr>
<tr><td>Resolution</td><td>Outcome of the call (i.e. Delivered to center, No Action, Call Referred, etc.)</td><td>X</td><td>X</td></tr>
<tr><td>TimeTaken</td><td>Approximate total time to handle call</td><td>X</td><td>X</td></tr>
<tr><td>PostCard</td><td>Was a postcard sent (Yes/No)</td><td>X</td><td>X</td></tr>
<tr><td>OpenedBy</td><td>Id of person opening the call</td><td>X</td><td>X</td></tr>
<tr><td>Reason</td><td>Reported reason call was placed by caller</td><td>S</td><td>X</td></tr>
<tr><td>CtrNbr</td><td>Case reference number assign at the Center</td><td>X</td><td>X</td></tr>
<tr><td>TransHrs</td><td>Transporter hours used to pick up case</td><td>X</td><td></td></tr>
<tr><td>TransMi</td><td>Mileage reported as driven by transporter</td><td>X</td><td></td></tr>
<tr><td>LastUpdater</td><td>Id of person last updating the call</td><td>X</td><td>X</td></tr>
<tr><td>CallLocation</td><td>Location of caller when placing the call (vs animal location)</td><td>X</td><td>X</td></tr>
<tr><td></td><td></td><td></td><td></td></tr>
</table>
</div>
</body>
</html>

