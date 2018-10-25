<style>
body { padding-top: 50px; }      <!-- add padding to top of each page for fixed navbar -->
</style>

<script>
<!-- Form change variable must be global -->
var chgFlag = 0;

$(document).ready(function() {
// disable all buttons of class updb  
  $('.updb').prop('disabled', true);
    
  $("#aboutInfo").hide();
  $("#aboutBtn").click(function() {
    $("#mm-modalBody").html($("#aboutInfo").html());
    $("#myModal").modal("show");
    return;
    });
    
// to detect and change on form
var $form = $('form');
var origForm = $form.serialize();   // save all fields values on initial load
 
$('form :input').on('keyup input', function(e) {
  if (e.keyCode == 9) { return; } // ignore tabs
  if ($form.serialize() !== origForm) {         // check for any changes
    chgFlag++;
    $('.updb').prop('disabled', false);    
    $(".updb").css({"background-color": "red", "color":"white"});
    // console.log("chgFlag: "+chgFlag);
    return;
    }
  });

$("form").change(function(){
  if (this.id == "filter") return;  // ignore filter input
  chgFlag += 1; 
  $(".updb").css({"background-color": "red", "color":"black"});
  $('.updb').prop('disabled', false);    
  // setInterval(blink_text, 1000);
  });    
});

function chkchg() {
	if ($form.serialize() !== origForm) {         // check for any changes
  	var r=confirm("All changes made will be lost.\n\nConfirm leaving page by clicking OK.");	
  	if (r == true) { chgFlag = 0; return true; }
    return false;
    }
  }

function blink_text() {
    $('.updb').fadeOut(500);
    $('.updb').fadeIn(500);
  }

</script>

<!-- ========= define main menu bar and choices ======== -->
<div class="hidden-print">
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
</div>

<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="navbar-collapse-1">
  <ul class="nav navbar-nav" style='cursor: pointer;'>
    <li><a onclick="return chkchg()" href="index.php"><b>Home</b></a></li>
		<!-- <li><a onclick="return chkchg()" href="">????</a></li> -->
<?php
// include Admin menu options for special users
$seclevel = isset($_SESSION['CTS_SecLevel']) ? $_SESSION['CTS_SecLevel'] : '';
if ($seclevel == 'admin') {
?> 

<!-- ======== define Admin dropdown ===================== -->
<!-- Menu dropdown for Extended Donor Info pages -->	
  <li class="dropdown">
  <a class="dropdown-toggle" data-toggle="dropdown" role="button"><font color="#FF0000">Admin</font><b class="caret"></b></a>
  	<ul class="dropdown-menu" aria-labelledby="drop2" role="menu">
  		<!-- <li><a onclick="return chkchg()" href="adminbboardmaint.php">Maintain BB</a></li> -->
  		<li><a onclick="return chkchg()" href="adminlistpvcalls.php">List All Calls for a PV</a></li>
  		<li><a onclick="return chkchg()" href="admincloseany.php">Close Any Open Call</a></li>
  		<li><a onclick="return chkchg()" href="adminaddnewuser.php">Maintain Userid &amp; Passwords</a></li>
  		<li><a onclick="return chkchg()" href="adminresourcesmaint.php">Maintain Resource Links</a></li>
  		<li><a onclick="return chkchg()" href="adminformsmaint.php">Maintain Forms &amp; Docs</a></li>
  		<li><a onclick="return chkchg()" href="adminlistmaint.php?file=Locations">Maintain Locations</a></li>
  		<li><a onclick="return chkchg()" href="adminlistmaint.php?file=Properties">Maintain Properties</a></li>
  		<li><a onclick="return chkchg()" href="adminlistmaint.php?file=Species">Maintain Species</a></li>
  		<li><a onclick="return chkchg()" href="adminlistmaint.php?file=Reasons">Maintain Reasons</a></li>
  		<li><a onclick="return chkchg()" href="adminlistmaint.php?file=Actions">Maintain Actions</a></li>
  		<li><a onclick="return chkchg()" href="admineditemailreplys.php">Edit Email Replys</a></li>
  		<li><a onclick="return chkchg()" href="admdeletejdoerecs.php">Delete jdoe records</a></li>
  	</ul>   <!-- ul dropdown-menu -->
  </li>  <!-- li dropdown -->

<?php
}

if ($seclevel != 'guest') {
?>
<!-- ========= define External Systems dropdown ============ -->
<li class="dropdown">
<a id="drop1" class="dropdown-toggle" data-toggle="dropdown" role="button" ><b>External</b><b class="caret"></b></a>
<ul class="dropdown-menu" aria-labelledby="drop1" role="menu">
	<li><a onclick="return chkchg()" href="vmsintro.php" target="_blank">Voice Messages</a></li>
	<li><a onclick="return chkchg()" href="emailintro.php" target="_blank" >Hotline Email</a></li>
	<li><a onclick="return chkchg()" href="wrmdintro.php" target="_blank" >WRMD Case Mgmnt</a></li>
</ul>
</li>

<?php
}
?>

<!-- ========= define Calls dropdown ============= -->
<!-- <li class="dropdown open">  example: to have open on load -->
<li class="dropdown">
<a id="drop1" class="dropdown-toggle" data-toggle="dropdown" role="button" ><b>Calls</b><b class="caret"></b></a>
<ul class="dropdown-menu" aria-labelledby="drop1" role="menu">

<?php
if ($seclevel != 'guest') {
	echo '
	<li><a onclick="return chkchg()" href="calls.php?action=MyOpen">My Open</a></li>
	<li><a onclick="return chkchg()" href="calls.php?action=MyClosed">My Closed</a></li>';
	}
echo	'
  <li><a onclick="return chkchg()" href="calls.php?action=AllOpen">All Open</a></li>';
if ($seclevel != 'guest') {
	echo '
	<li><a onclick="return chkchg()" href="callsaddnew.php">Add New Call</a></li>
	<li><a onclick="return chkchg()" href="callscloser.php">Close A Call</a></li>
	';
	}
?>

	<li><a onclick="return chkchg()" href="callssearch.php">Search All</a></li>
	<!-- <li><a onclick="return chkchg()" href="#">????</a></li> -->
	<!-- <li><a href="#">?</a></li> -->
</ul>
</li>  <!-- class="dropdown" -->

<!-- ============ define Info dropdown ============== -->
<li><a onclick="return chkchg()" href="bboard.php?action=list"><b>BBoard</b></a></li>

<!-- =========== define Resources menu item ========== -->
<li><a onclick="return chkchg()" href="resources.php"><b>Resource Links</b></a></li>

<!-- =========== define Forms menu item ============== -->
<li><a onclick="return chkchg()" href="forms.php"><b>Forms &amp; Docs</b></a></li>

<!-- ========== define reports dropdown ============== -->
<!-- <li class="dropdown open">  example: to have open on load -->
<li class="dropdown">
<a id="drop1" class="dropdown-toggle" data-toggle="dropdown" role="button"><b>Reports</b><b class="caret"></b></a>
<ul class="dropdown-menu" aria-labelledby="drop1" role="menu">
	<li><a href="rptlast50calls.php" target="_blank">Last 50 Calls Report</a></li>
	<li><a href="rptlistcallsfortoday.php" target="_blank">Today&apos;s Calls</a></li>
	<li><a href="rptlistcallsindaterange.php" target="_blank">Calls in Date Range</a></li>
	<li><a href="rptcallsbyhlvindaterange.php" target="_blank">Calls by HLV in Date Range</a></li>
	<li><a href="rpthistoricalcalls.php" target="_blank">Historical Call Report</a></li>
	<li><a href="rptusersbydaterange.php" target="_blank">Users by Date Range</a></li>
	<li><a href="rptmonthlyreport.php" target="_blank">CTS Monthly Report</a></li>
	<li><a href="../charts" target="_blank">PWC Business Charts</a></li>
	<li><a href="rptmaillogviewer.php" target="_blank">Mail Log Viewer</a></li>
	<li class="divider"></li>
	<li><a href="#">Other report(s) added as needed</a></li>
	<li class="divider"></li>
	<li><a id="aboutBtn" href="#myModal" data-toggle="modal" data-keyboard="true">About CTS2</a></li>
</ul>
</li>  <!-- class="dropdown" -->

</ul>		<!-- nav navbar-nav  *the menu bar* -->
</div>  <!--/.nav-collapse -->
</nav>  <!-- class = "navbar" -->
</div> <!-- hidden-print -->
<!-- End mainmenu.inc -->

<!-- =========== ABOUT Modal  ==================== -->  
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
    <div id="mm-modalBody" class="modal-body">
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- end of modal -->
<div id="aboutInfo">
    <h3>About CTS2</h3>
<p>Call Tracking System V2.0 (CTS2) is intended for the use by the Hot Line Volunteers of Pacific Wildlife Care.</p>
<p>CTS2 is offered under the General Public License (GPL) Version 3.  There is no license fee assoicated with the use of this system or any of the components used to develop it.  All improvements or updates made must be made available to the MbrDB community.</p>
</div>

