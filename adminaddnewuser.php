<!DOCTYPE html>
<html>
<head>
<title>Add New User</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
$("document").ready(function() {
  $("#X").fadeOut(5000);
  $("#addnew").hide();
$("#addnewbtn").click (function() { 
  $("#addnew").toggle();
  });
// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });

$("#filterbtn2").click(function() {
  $("#filter").val("");
  $('tr').show();
  chgFlag = 0;
  });
  
$("#filterbtn1").click(function() {
  var filter = $("#filter").val();
  if (filter.length) {
    // alert("filter button clicked:" + filter);
    $('tr').hide().filter(':containsNC('+filter+')').show();
    $("#head").show();
    chgFlag = 0;
    return;
    }
  $('tr').show();
  chgFlag = 0;
  });
});
</script>

<?php
session_start();
//include 'Incls/vardump.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$recno = isset($_REQUEST['SeqNo']) ? $_REQUEST['SeqNo'] : "";
$flds = $_REQUEST['flds'];
if ($action == "delete") {
	$sql = "DELETE FROM `cts2users` WHERE `SeqNo` = '$recno'";
	$res = doSQLsubmitted($sql);
	echo '<h3 style="color: red; " id="X">Delete Completed.</h3>';
	}

if ($action == "addnew") {
	//echo "Add new record number for user: $userid, password: $password, role: $role<br>";
	$res = sqlinsert('cts2users', $flds);
	echo '<h3 style="color: red; " id="X">User Record Added.</h3>';
	}

print <<<pagePart1
<div class="container">
<h3>User Administration</h3>
<button id="addnewbtn">Add New User Record</button>
<b>Filter:</b><input id="filter" value="">
<button id="filterbtn1">Apply filter</button>
<button id="filterbtn2">Show All</button>
<div id="addnew">
<p>Adds new admin user to the registration database.  User id is the email address of the user.  The role that is to be assigned is in the dropdown.  Both of these fields are required.  The default password provided is 'raptor' but any may be specified.  The password can be updated to a personal password by the user when they log in.</p>
<p>Please note that a new userid is needed for each role.</p>

<script>
function checkflds(form) {
	//alert("validation entered");
	var errcnt = 0;
	if (form.userid.value == "") errcnt +=1;
	if (form.password.value == "") errcnt += 1;
	if (form.role.value == "") errcnt += 1;
	if (errcnt > 0) {
		alert ("A required field is missing.");
		return false;
		}
	var tfld = trim(form.userid.value);  // value of field with whitespace trimmed off
	return true;
	}
	
function trim(s)
	{
  return s.replace(/^\s+|\s+$/, '');
	}

</script>
<script>
function chkconf() {  
  if (confirm("Confirm deletion by clicking OK.")) { return true;  }
  return false;
}
</script>
<form class="form" name="addform" action="adminaddnewuser.php" onsubmit="return checkflds(this)">
New User ID: <input type="text" name="flds[UserID]" placeholder="User Id">
Password: <input type="text" name="flds[Password]" value="raptor">
Role: <select name="flds[Role]">
<option value="">Select a role for the User</option>
<option value="admin">Admin</option>
<option value="user">User</option>
<option value="guest">Guest</option>
</select><br />
Full Name: <input type="text" name="flds[FullName]" placeholder="First/Last Name">
Email: <input type="text" name="flds[Email]" placeholder="Email Address"> 
Phone: <input type="text" name="flds[Phone]" placeholder="Primary Phone"><br> 
MCID: <input type="text" name="flds[MCID]" placeholder="MCID">
Date Joined: <input type="text" name="flds[DateJoined]" placeholder="MM/DD/YYYY"><br>
Notes:<br /><textarea name="flds[Notes]" rows="3" cols="50"></textarea><br />
<input type="hidden" name="action" value="addnew">
<input type="submit" name="submit" value="Add New">
</form>
<br />
<a class="btn btn-primary" href="index.php">RETURN</a>
<hr width="50%"><h4>Existing Users</h4>
</div>  <!-- addnew div -->
pagePart1;

// list exising entries to allow delete of individual rows from DB

$sql = "select * from cts2users ORDER BY `Role` ASC, `UserID` ASC";
$res = doSQLsubmitted($sql);
echo "<table border=1 class=\"table\">";
echo "<tr id=\"head\"><th>Action</th><th>Role</th><th>User ID</th><th>MCID</th><th>Password</th><th>Full Name</th>
<th>Email</th>
<th>Phone</th>
<th>Date Joined</th><th>Notes</th></tr>";
while ($r = $res->fetch_assoc()) {
	//echo "<pre>user: "; print_r($r); echo "</pre>";
	echo "<tr>
<td>
<a href=\"adminedituser.php?SeqNo=$r[SeqNo]\"<span title=\"EDIT\" class=\"glyphicon glyphicon-pencil\" style=\"color: blue; font-size: 15px\"></span></a>
<a href=\"adminaddnewuser.php?SeqNo=$r[SeqNo]&action=delete\" onclick=\"return chkconf()\" <span title=\"DELETE\" class=\"glyphicon glyphicon-trash\" style=\"color: blue; font-size: 15px\"></span></a>

</td>
<td>$r[Role]</td><td>$r[UserID]</td><td>$r[MCID]</td><td>$r[Password]</td><td>$r[FullName]</td>
<td>$r[Email]</td>
<td>$r[Phone]</td>
<td>$r[DateJoined]</td><td>$r[Notes]</td></tr>";
	}
echo "</table><br>==== END OF LIST ===<br></div>";

?>
</div>
</body>
</html>
