<?php

print <<<menubar
<!-- Admin menu start -->
<nav class="navbar navbar-default" role="navigation">

<div class="navbar-header">

<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-2">
						<span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
</div>  <!-- navbar-header -->
<div class="collapse navbar-collapse" id="navbar-collapse-2">

<ul class="nav navbar-nav">
<!-- Menu -->

	<li><a href="index.php">Admin Functions-></a></li>
  <li><a href="admdeleterecs.php">Delete Records</a></li>
  <li><a href="admlistmaint.php">Maintain Lists</a></li>
  <li><a href="admtemplatemaint.php">Reminder Templates</a></li>
  <li><a href="admDBJanitor.php">DB Janitor</a></li>
  <!-- <li><a href="#" onclick="return chkchg()">?</a></li> -->

</ul>  <!-- nav navbar-nav -->
</div>  <!--collapse nav-collapse -->

</nav>  <!-- class = "navbar" -->
menubar;
?>