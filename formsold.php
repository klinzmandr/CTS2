<?php 
//echo "<hr>Debug Info: dump of input array POST name and value pairs<br>";
//foreach ($_GET as $key => $value) { echo "Key: $key, Value: $value<br>";  }
//echo "<hr>";
include 'includes/checker.php';
$upw = $_GET['upw'];
$userid = $_GET["userid"];
pwcheck($userid, $upw);

$forms = scandir('Forms');

print <<<updPage
<html>
 <head>
  <title>Forms</title>
 </head>
 <body>
 <div align="center"><img src="PWC680logo.jpg" border="0" alt="PWC LOGO"></div>
<h1>Forms & Documentation Available</h1>
<a href="ctsindex.php?userid=$userid&upw=$upw">Return to CTS Home Page</a>
<br><br>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="90%">

<tr><td><b><u>Online Links:</u></b></td></tr>
<tr><td style="text-indent: 10; ">
<a href="http://my.calendars.net/pwcbod" target="_blank">PWC Event Calendar</a>
</td></tr>
<tr><td style="text-indent: 10; ">
<a href="http://my.calendars.net/pwc_phones" target="_blank">Online Hotline Volunteer Calendar</a>
</td></tr>
<tr><td style="text-indent: 10; ">
<a href="http://my.calendars.net/pwctrans" target="_blank">Online Rescue/Transport Volunteer Calendar</a>
</td></tr>
<tr><td style="text-indent: 10; ">
<a href="http://my.calendars.net/centervols" target="_blank">Online Center Volunteer Calendar</a>
</td></tr>
<tr><td style="text-indent: 10; ">
<a href="http://my.calendars.net/babybirdroom" target="_blank">Baby Bird Room Calendar</a>
</td></tr>
<tr><td style="text-indent: 10; ">
<a href="vollister.php" target="_blank">Volunteer Lists</a>
</td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td><b><u>Documents:</u></b></td><td><u>Updated in CTS on:</u></td></tr>
updPage;
?>
<?php
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
$moddt = filectime("Forms/$formname");
$cd  = date("F d, Y \a\\t H:i:s.", $moddt) . "<br>";
echo "<tr><td align=\"left\" style=\"text-indent: 10; \">
<a target=_blank href=\"Forms/$formname\">$formname</a></td><td>$cd</td></tr>";
}
?>
</table>
<br>
<p>Note: All links will open it in a new window.</p><br>
<a href="ctsindex.php?userid=<?=$userid?>&upw=<?=$upw?>">Return to CTS Home Page</a></body>
</html>

