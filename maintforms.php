<html>
 <head>
  <title>Forms Directory Maintenance</title>
 </head>
 <body>
<?php 
//echo "<hr>Debug Info: dump of input array POST name and value pairs<br>";
//foreach ($_GET as $key => $value) { echo "Key: $key, Value: $value<br>";  }
//echo "<hr>";
include 'includes/checker.php';
$upw = $_GET['upw'];
$userid = $_GET["userid"];
pwcheck($userid, $upw);

$doFlag = 0; if ($ListMgr) { $doFlag += 1; } if ($SysAdmin) { $doFlag += 1; }
if ($doFlag == 0) {
echo "<div align=\"center\"><img src=\"PWC680logo.jpg\" 
			border=\"0\" alt=\"PWC LOGO\"></div>";
echo "<h2>Your User id is not allowed to access this function</h2><br>";
echo "<a href=\"ctsindex.php?userid=$userid&upw=$upw\">Return to CTS Home Page</a>";
exit(0);
}

$forms = scandir('Forms');
?>
<h1>Forms Directory Maintenance</h1>
<a href="ctsindex.php?userid=<?=$userid?>&upw=<?=$upw?>">Return to CTS Home Page</a><br><hr>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="70%">
<?php
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
echo "<tr><td width=\"15%\" align=\"center\">
<a href=\"maintformsupd.php?userid=$userid&upw=$upw&delete=delete&name=$formname\">Delete</a>
</td><td><a target=_blank href=\"Forms/$formname\">$formname</a></td></tr>";
}
?>

</table>
<br><br><br>
<form action="maintformsupl.php" method="post" enctype="multipart/form-data">
<label for="file">or add a new one:&nbsp;</label>
<input size=50 type="file" name="file" id="file" />
<input type="hidden" name="userid" value="<?=$userid?>">
<input type="hidden" name="upw" value="<?=$upw?>">&nbsp;
<input type="submit" name="submit" value="Submit" />
</form>

<hr>
<a href="ctsindex.php?userid=<?=$userid?>&upw=<?=$upw?>">Return to CTS Home Page</a>
</body>
</html>
