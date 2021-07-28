<?php
session_start();

$userid = $_SESSION['CTS_SessionUser'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

// include 'Incls/vardump.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>Calls</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/bootstrap-sortable.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<script>
$(function() {
// adds sign in sorted col header
  $.bootstrapSortable({ sign: 'AZ' })
  
// determine col's to hide
  var action = "<?=$action?>";
  // alert("table load script action: " + action);
  if (action == 'AllOpen') {
    $('td:nth-child(7),th:nth-child(7)').hide(); }  // resolution col
  if (action == 'MyClosed') {
    $('td:nth-child(3),th:nth-child(3)').hide(); }  // openedby col
  if (action == 'MyOpen') {
    $('td:nth-child(3),th:nth-child(3)').hide();    // openedby col
    $('td:nth-child(7),th:nth-child(7)').hide(); }  // resolution col
});
</script>
<?php
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$rpthdg = "<thead><tr id='head'><th>Call#</th><th>Call Date</th><th>OpenedBy</th><th>CallerName</th><th>CallerPhone</th><th>Call Reason</th><th>Resolution</th></tr></thead>
<tbody>
";
if ($action == 'MyClosed') {
	$hdg = 'My Closed';
	$sql = "SELECT * from `calls` 
	WHERE `Status` = 'Closed' 
		AND `OpenedBy` = '$userid'
	ORDER BY `CallNbr` DESC;";
	}
elseif ($action == 'AllOpen') {
	$hdg = 'All Open';
	$sql = "SELECT * from `calls` WHERE `Status` = 'Open' ORDER BY `CallNbr` DESC;";
	}
	
else {		// gotta be MyCalls then
	$hdg = 'My Open';
	$sql = "SELECT * from `calls` 
		WHERE ( `Status` = 'Open' )
		AND `OpenedBy` = '$userid' ORDER BY `CallNbr` DESC;";
	}
$res = doSQLsubmitted($sql);
$rows = $res->num_rows;
// if ($rows == 0) { echo "no rows found<br>"; };
echo '<div class="container">' . $xmsg . '
<h3>'.$hdg.' Calls<img id="chgflg" hidden src="img/Cancel__Red.png" width="16" height="16" /></h3>
';
?>
<b>Filter: </b>
<script>
$(function() {
  $("#btnALL").click(function() {
    // alert("button ALL click");
    $('tr').show();
    $('#inp').val('');
  });
  
  $.extend($.expr[":"], {
    "containsNC": function(elem, i, match, array) {
    return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
    });
  
  $('#inp').keyup(function() { 
    inp = $('#inp').val();
    // console.log(inp);
    if (inp.length > 0) 
      $('tr').hide().filter(':containsNC('+inp+')').show();
      $("#head").show();
      chgFlag = 0;
      });
});
    
</script>
<input placeholder="Enter search string" id="inp" type="text" value="" autofocus title="Enter string to filter the number of rows listed.">&nbsp;&nbsp;
<button id="btnALL">Show All</button>&nbsp;&nbsp;

<?php
echo '<table id="tabl" border="1" class="table table-condensed table-hover sortable">'.$rpthdg;
while ($r = $res->fetch_assoc()) {
	// echo '<pre>'; print_r($r); echo '</pre>';
	$callnbr = $r['CallNbr']; 
  $dateplaced = substr($r['DTPlaced'],0,10);
	if ($action == 'MyClosed') {
		echo "<tr onclick=\"window.location='callroview.php?call=$callnbr'\" style='cursor: pointer;'>
		"; }
	else {
    echo "<tr onclick=\"window.location='callupdater.php?action=view&callnbr=$callnbr'\" style='cursor: pointer;'>
    "; }

	echo '<td>'.$r['CallNbr'].'</td>';
	echo '<td>'.$dateplaced.'</td>';
	echo '<td>'.$r['OpenedBy'].'</td>';
	echo '<td>'.$r['Name'].'</td>';
	echo '<td>'.$r['PrimaryPhone'].'</td>';
	echo '<td>'.$r['Reason'].'</td>';
	echo '<td>'.$r['Resolution'].'</td>';
  echo	'</tr>
  ';
  
	}
echo '</body></table><br>==== END OF LIST ====<br>
</div>  <!-- container -->';

?>

</body>
</html>
