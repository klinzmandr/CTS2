<!DOCTYPE html>
<html>
<head>
<title>Add New Admin User</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
session_start();
// include 'Incls/vardump.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/datautils.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : ''; 
$flds = $_REQUEST['flds'];
$recno = $_REQUEST['SeqNo'];

if ($recno == '') {
  echo "Record number is missing.<br><br>
  <a class=\"btn btn-danger\" href=\"adminaddnewuser.php\">Return to User Admin</a>";
  exit;
  }

if ($action == "update") {
//	echo "update record number $recno<br>";
//	echo '<pre>update flds '; print_r($flds); echo '</pre>';
	sqlupdate('cts2users', $flds, "`SeqNo` = '$recno'");
	echo '<h3 style="color: red; " id="X">Update Completed.</h3>';
	}
	
$sql = "SELECT * FROM `cts2users` WHERE `SeqNo` = '$recno';";
$res = doSQLsubmitted($sql);
$flds = $res->fetch_assoc();
// echo '<pre>DB flds '; print_r($flds); echo '</pre>';
?>
<script>
$("document").ready( function() {
  $("#ROLE").val("<?=$flds[Role]?>");
  $("#X").fadeOut(5000);
});
function checkflds(form) {
	//alert("validation entered");
	var errcnt = 0;
	if (form.userid.value == "") errcnt +=1;
	if (form.password.value == "") errcnt += 1;
	//if (form.mcid.value == "") errcnt += 1;
	//if (form.role.value == "") errcnt += 1;
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

<div class="container">
<h3>Edit User</h3>

<form class="form" name="editform" action="adminedituser.php" onsubmit="return checkflds(this)">
User ID: <input type="text" name="flds[UserID]" placeholder="User Id" value="<?=$flds[UserID]?>">
Password: <input type="text" name="flds[Password]" value="<?=$flds[Password]?>">
Role: <select id="ROLE" name="flds[Role]">
<option value="">Select a role for the User</option>
<option value="admin">Admin</option>
<option value="user">User</option>
<option value="guest">Guest</option>
</select><br />
Full Name: <input type="text" name="flds[FullName]" placeholder="First/Last Name" value="<?=$flds[FullName]?>">
Email: <input type="text" name="flds[Email]" placeholder="Email Address" value="<?=$flds[Email]?>"> 
Phone: <input type="text" name="flds[Phone]" placeholder="Primary Phone" value="<?=$flds[Phone]?>"><br> 
MCID: <input type="text" name="flds[MCID]" placeholder="MCID" value="<?=$flds[MCID]?>">
Date Joined: <input type="text" name="flds[DateJoined]" placeholder="MM/DD/YYYY" value="<?=$flds[DateJoined]?>"><br>
Notes:<br /><textarea name="flds[Notes]" rows="3" cols="50"><?=$flds[Notes]?></textarea><br />
<input type="hidden" name="SeqNo" value="<?=$recno?>">
<input type="hidden" name="action" value="update">
<input type="submit" name="submit" value="Apply Changes">
</form>
<br />
<a class="btn btn-primary" href="adminaddnewuser.php">Return to User Admin</a>
<hr width="50%">

</div>
</body>
</html>
