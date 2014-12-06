<!DOCTYPE html>
<html>
<head>
<title>Add New Admin User</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/vardump.inc';
include 'Incls/mainmenu.inc';
include 'Incls/seccheck.inc';
include 'Incls/datautils.inc';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$recno = isset($_REQUEST['recno']) ? $_REQUEST['recno'] : "";
$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : "";
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
$role = isset($_REQUEST['role']) ? $_REQUEST['role'] : "";
$fullname = isset($_REQUEST['fullname']) ? $_REQUEST['fullname'] : "";
$datejoined = isset($_REQUEST['datejoined']) ? $_REQUEST['datejoined'] : "";
$notes = isset($_REQUEST['notes']) ? $_REQUEST['notes'] : "";

if ($action == "delete") {
	//echo "Delete record number $recno<br>";
	$sql = "DELETE FROM `cts2users` WHERE `SeqNo` = '$recno'";
	$res = doSQLsubmitted($sql);
	}

if ($action == "addnew") {
	//echo "Add new record number for user: $userid, password: $password, role: $role<br>";
	$flds[UserID] = $userid;
	$flds[Password] = $password;
	$flds[Role] = $role;
	$flds[FullName] = $fullname;
	$flds[DateJoined] = $datejoined;
	$flds[Notes] = $notes;
	$res = sqlinsert('cts2users', $flds);
	}

print <<<pagePart1
<div class="container">
<h3>Admin: Add New Administrative User</h3>
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

<form class="form" name="addform" action="adminaddnewuser.php" onsubmit="return checkflds(this)">
New User ID: <input type="text" name="userid" placeholder="User Id">
Password: <input type="text" name="password" value="raptor">
Role: <select name="role">
<option value="">Select a role for the User</option>
<option value="admin">Admin</option>
<option value="user">User</option>
<option value="guest">Guest</option>
</select><br />
Full Name: <input type="text" name="fullname" placeholder="First/Last Name">
Date Joined: <input type="text" name="datejoined" placeholder="MM/DD/YYYY"><br>
Notes:<br /><textarea name="notes" rows="3" cols="50"></textarea><br />
<input type="hidden" name="action" value="addnew">
<input type="submit" name="submit" value="Add New">
</form>
<br />
<a class="btn btn-primary" href="index.php">RETURN</a>
<hr width="50%"><h4>Delete Existing</h4>
pagePart1;

// list exising entries to allow delete of individual rows from DB

$sql = "select * from cts2users ORDER BY `Role` ASC, `UserID` ASC";
$res = doSQLsubmitted($sql);
echo "<table class=\"table-condensed\">";
echo "<tr><th>Delete</th><th>Role</th><th>User ID</th><th>Password</th><th><-----FullName----></th><th>Date Joined</th><th>Notes</th></tr>";
while ($r = $res->fetch_assoc()) {
	//echo "<pre>user: "; print_r($r); echo "</pre>";
	echo "<tr><td align=\"center\"><a href=\"adminaddnewuser.php?action=delete&recno=$r[SeqNo]\"><img src=\"img/b_drop.png\" alt=\"DELETE\" /></a></td><td>$r[Role]</td><td>$r[UserID]</td><td>$r[Password]</td><td>$r[FullName]</td><td>$r[DateJoined]</td><td>$r[Notes]</td></tr>";
	}
echo "</table><br>==== END OF LIST ===<br></div>";

?>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</div>
</body>
</html>
