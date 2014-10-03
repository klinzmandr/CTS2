<?php
$filter = isset($_REQUEST['filter']) ? $_REQUEST['filter'] : "";
print <<<menu
<!-- Start utilmenu.inc -->
<nav class="navbar navbar-default" role="navigation">

<div class="container">

<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse-3">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
</div>  <!-- navbar-header -->

<div class="collapse navbar-collapse" id="navbar-collapse-3">

<ul class="nav navbar-nav">
<!-- Menu -->

	<li><a href="index.php" onclick="return chkchg()">Return</a></li>
  <li><a href="admaddnewuser.php">Admin Users</a></li>
  <li><a href="admlistmaint.php?file=mctypes.txt" onclick="return chkchg()">Mbr Types</a></li>
  <li><a href="admlistmaint.php?file=purposes.txt" onclick="return chkchg()">Funding Purposes</a></li>
  <li><a href="admlistmaint.php?file=programs.txt" onclick="return chkchg()">Fund Programs</a></li>	
  <li><a href="admlistmaint.php?file=campaigns.txt" onclick="return chkchg()">Fund Campaigns</a></li>
	<li><a href="admlistmaint.php?file=correspondencetypes.txt" onclick="return chkchg()">Corr Types</a></li>

</ul>  <!-- nav navbar-nav -->
</div>  <!--/.nav-collapse -->
</div>  <!-- container -->

</nav>  <!-- nav -->
<!-- End utilmenu.inc -->

menu;
?>