<?php
// check if there is an active session
if (isset($_SESSION['CTS_SessionUser'])) {
// output page timeout script
echo "
<script src='js/bootstrap-session-timeout.js'></script> 
<script>
$(document).ready(function() { 
  $.sessionTimeout({
      title: 'SESSION TIMEOUT ALERT',
      message: '<h3>Your session is about to expire.</h3>',
      keepAlive: false,
      logoutUrl: 'indexsto.php',
      redirUrl: 'indexsto.php',
      warnAfter:  45*60*1000,
      redirAfter: 60*60*1000,
      countdownMessage: 'Time remaining:',
      countdownBar: true,
      showButtons: false
  });
});
</script>";
  return;
  }
// echo 'indexsto entered with no active session set<br>';

// if not display login form
?>
<script>
function checkform(theForm) {
	var reason = "";
	// $("input").attr("style", "color:white");
  reason += validateUserID();	
	reason += validatePassword();
	if (reason != "") {
  	alert("Some fields need entry:\n" + reason);
  	return false;
		}
	return true;
	}

function validateUserID() {
	var error="";
	var tfld = $("[name='userid']").val().toLowerCase(); 		
	tfld = tfld.replace(/\s+/, "");
  if (tfld == '') {
	  $("[name='userid']").attr("style", "color:pink");
    error = 'User ID not entered.\n';
    }
  else {
  	$("[name='userid']").val(tfld);
    }
  return error;
	}

function validatePassword() {
  var error = "";
  var pfld = $("[name='password']").val().toLowerCase();
  pfld = pfld.replace(/\s+/, "");   // strip out extraneous chars
  if (pfld.length == 0) {
  	$("[name='password']").attr("style", "color:pink");
    error = "You didn't enter a password.\n";
    	}
  else {
    $("[name='password']").val(pfld);
    }
  return error;
	}   

</script>

<div class="container">
	<h1>Call Tracking System</h1>
	<form action="index.php" method='POST' name="form" class="form-signin" onsubmit="return checkform(this)">
	<h2 class="form-signin-heading">Please sign in</h2>
	<input type="text" class="input-block-level" placeholder="User ID" autofocus name="userid" value="" autocomplete="off">
	<input type="text" class="input-block-level" placeholder="Password" name="password" value = "" autocomplete="off"><br><br>
	<button class="btn btn-default btn-small" name='action' value='login' type="submit">Sign in</button>
	</form>
	<a href="admpasswordupd.php"><p>(Change Password)</p></a>
</div> <!-- /container -->
</body>
</html>
