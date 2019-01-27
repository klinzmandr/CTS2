<?php
session_start();
// include 'Incls/vardump.inc.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$search = isset($_REQUEST['sstr']) ? $_REQUEST['sstr'] : '';
$xsearch = isset($_REQUEST['xsstr']) ? $_REQUEST['xsstr'] : '';

?>
<!DOCTYPE html>
<html>
<head>
<title>Search Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>
<script>
$(function() {
  var xinp = "<?=$xsearch?>";
  if (xinp.length == 0) {
    $(".tips").show();
    }
$("form").submit(function(e) {
  $("#xsstr").val($("#sstr").val());
  var inp = $("#sstr").val();
  if (inp.length == 0) {
    e.preventDefault();
    alert("Search string entry is empty");
    $("#help").show();
    return;
    }
  $("#help").hide();
  });
});
</script>

<div class="container">
<h3>Search All Calls
<span id="helpbtn" title="Tips and Tricks" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</h3>
<form class="form-inline" action="callssearch.php" method="post">
<input type="text" id=sstr name="sstr" size="50" value="" autofocus placeholder="Enter search string">
<input type="hidden" id=xsstr name="xsstr" value="">
<input type="submit" name="submit" value="Search">
</form>

<div id=help class=tips>
<b>Tips and Tricks:</b><br>
<p>General search of all calls for given search string.</p>
<ol>
  <li>Use the 'Last 50 Calls Report' for listing all calls.</li>
  <li>Use this function to find specific calls by key word/phrase.</li>
  <li>Fields searched: Call Number, Animal Location, Call Location, Property, Species, Resolution, Reason, Organization, Name, Address, City, Email, Description and Opened By.</li>
  <li>The call log is NOT searched.</li>
	<li>Enter a 3-5 character search string.  The longer the string, usually the fewer the results listed.</li>
	<li>Avoid special characters like &lt;, &gt;, :, !, $, %, &apos; and so on.</li>
</ol>
</div>
</div>
</body></html>

<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

if ($search == '') exit;

// $action == search
$sql = "SELECT * FROM `calls` 
	WHERE (`CallNbr` LIKE '%$search%'  
	  OR  `AnimalLocation` LIKE '%$search%' 
		OR  `CallLocation` LIKE '%$search%'
		OR  `Property` LIKE '%$search%'
		OR  `Species` LIKE '%$search%'		
		OR  `Resolution` LIKE '%$search%'
		OR  `Reason` LIKE '%$search%'
		OR  `Organization` LIKE '%$search%'
		OR  `Name` LIKE '%$search%'
		OR  `Address` LIKE '%$search%'
		OR  `City` LIKE '%$search%'
		OR  `EMail` LIKE '%$search%'
		OR  `Description` LIKE '%$search%'
		OR  `OpenedBy` LIKE '%$search%')
ORDER BY `CallNbr` ASC;";
//	 AND 	`Status` = '$status';";
		
// echo "sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo "<div class=\"container\"><h4>Rows matched: $rc</h4>";
if ($rc > 0) {
  echo '<table border="0" class="table table-condensed table-hover">'.$rpthdg;
  while ($r = $res->fetch_assoc()) {
    $status = $r[Status];
  //	echo '<pre>'; print_r($r); echo '</pre>';
  	$callnbr = $r[CallNbr]; $dtopened = $r[DTOpened]; $openedby = $r[OpenedBy];
  	$lastupdater = $r[LastUpdater]; $desc = $r[Description];
  	if ($status == 'Closed') 
  		echo "<tr onclick=\"window.location='callroview.php?call=$callnbr'\" style='cursor: pointer;'>";
  	else
  		echo "<tr onclick=\"window.location='callupdatertabbed.php?action=view&callnbr=$callnbr'\" style='cursor: pointer;'>";
  	echo '<td>'.$callnbr.'</td>
  	<td>'.$status.'</td>
  	<td>'.$dtopened.'</td>
  	<td>'.$openedby.'</td>
  	<td>'.$desc.'</td>
  	</tr>';
  	}
  echo '</table></div>--- END OF LIST ---';
}
?>

</body>
</html>
