<?php
// session_start();
include 'Incls/datautils.inc.php';
// include 'Incls/seccheck.inc.php';
// include 'Incls/mainmenu.inc.php';

$sql = 'SELECT * FROM `callslog`';
$res = doSQLsubmitted($sql);
$sizearray = array();
while ($r = $res->fetch_assoc()) {
  $sizearray[$r[CallNbr]] += strlen($r[Notes]);
  }
echo '<pre>'; print_r($sizearray); echo '</pre>';
exit; 
?>
<!DOCTYPE html>
<html>
<head>
<title>Report Call Log Size</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
// initial setup of jquery function(s) for page
$(function(){
	alert(" example of action on document load");

// this attaches an event to an object
	$("h4").click(function () {
    alert("example of a click of any header 4 like the page title"); 
    });

  });  // end ready function
</script>

<div class="container">
<h3>Page Heading
<span id="helpbtn" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
&nbsp;&nbsp;   <a href="javascript:self.close();" class="hidden-print btn btn-primary"><b>CLOSE</b></a>
</h3>
<div id=help>
Explaination of page.
</div>

<h4>Header 4 title to click</h4>
<!-- page contents -->

</div>  <!-- container -->

</body>
</html>
