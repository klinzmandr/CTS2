<?php
// turn off error reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);
date_default_timezone_set('America/Los_Angeles');

// connect to the database for all pages
global $mysqli;

include "../.CTS2DBParamInfo";

// production database
$db = CTSProdDBName;
$mysqli = new mysqli("localhost", CTSDBUserName, CTSDBPassword, $db);
//echo '<pre> on include load: '; print_r($mysqli); echo '</pre>';
if ($mysqli->connect_errno) {
		$errno = $mysqli->connect_errno;
    echo "Failed to connect to MySQL: (" . $errno . ") " . $mysqli->connect_error."<br>";
    $_SESSION['CTS_DB_ERROR'] = $db;
    }
$_SESSION['CTS_DB_InUse'] = $db;
$pl = 'Page Load ';
if (isset($_SESSION['4log'])) $pl .= 'for '.$_SESSION['4log'];
addlogentry($pl);
unset($_SESSION['4log']);
// auto returns to code following the 'include' statement
// echo "Initial Connection Info: ".$mysqli->host_info . "<br><br>";

// ------------------ submit sql statement provided by calling script ----------
// submit sql statement provided in call
function doSQLsubmitted($sql) { 
global $mysqli;

if (isset($_SESSION['CTS_DB_ERROR'])) { unset($_SESSION['CTS_DB_ERROR']); return(FALSE); }
//echo "sql submitted: ".$sql."<br>";
$res = $mysqli->query($sql);
//echo '<pre> on sql submitted: '; print_r($mysqli); echo '</pre>';

if (substr_compare($sql,"DELETE",0,6,TRUE) == 0) {
	//echo "<br>Delete command seen - return affected_rows<br>";
	$rowsdeleted = $mysqli->affected_rows;
	//echo "delete count: $rowsdeleted<br>";	
	addlogentry($sql);
	return($rowsdeleted);
	}

if (!$res) {
    showError($res);
    addlogentry('ERROR on: ' . $sql);
		}
return($res);
}

// --------- update existing row in table from assoc array provided -------------
function sqlupdate($table, $fields, $where) {
global $mysqli;

$nowdate = date('Y-m-d');					// now date if needed
$sql = "UPDATE `$table` SET ";
$f = ""; 
foreach ($fields as $k => $v) {
	if (strlen($v) > 0) {
		$vv = urldecode($v);
		$vv = addslashes($vv);
		$f .= "`$k`='$vv', ";
		}
	else {
		$f .= "`$k`=NULL, ";
		}
 	}
$f = rtrim($f, ', ');
$sql .= $f . ' WHERE ' . $where;
//echo "Update SQL: $sql<b>";
addlogentry($sql);
$res = $mysqli->query($sql);
$rows = $mysqli->affected_rows;
if (!$res) {
 	showError($res);	
	}
return($rows);
}

// ----------- add new row into table from assoc array-------------
function sqlinsert($table,$fields) {
global $mysqli;

$nowdate = date('Y-m-d');					// now date if needed
$sql = "INSERT INTO $table (";
foreach ($fields as $k => $v) {		// field names for sql statement
	$fieldnames .= "`$k`, ";
	}
foreach ($fields as $k => $v) {		// field values for sql statement
	if (strlen($v) == 0) {
		$fieldvalues .= "NULL, ";
		}
	else {	
		$vv = urldecode($v);
		$vv = addslashes($vv);
		$fieldvalues .= "'$vv', ";
		}
	}
$sql .= rtrim($fieldnames, ', ');
$sql .= ") VALUES (";
$sql .= rtrim($fieldvalues,', ');
$sql .= ");";

$res = $mysqli->query($sql);
$rows = $mysqli->affected_rows;
if (!$res) {
	$err= showError($res);
	return($err);
	}
addlogentry($sql);
//echo "Insert SQL: $sql<br>";
//echo "affected rows: $rows<br>";
return($rows);
}

// --------------------- generalized error display for all DB functions ----------
function showError($res) {
global $mysqli;
	$errno = $mysqli->errno;
	$errmsg = $mysqli->error;
	if ($errno == 1049) {
		$db = $_SESSION['CTS_DB_ERROR'];
		print <<<errNoDB
<div class="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>DB ERROR: database $db is not available</strong>
</div>
errNoDB;
  return(FALSE);
  }
	if ($errno == 1062) {
		$errmsg .= "<br>A record already exists for the unique key provided.";
		}
	print <<<errMsg
<div class="alert">
<button type="button" class="close" data-dismiss="alert">&times;</button>
<strong>DB ERROR $errno</strong>: $errmsg
</div>
errMsg;
  return(FALSE);
	}	

// ------------ add new log entry ----------
function addlogentry($text) {
	global $mysqli;
	if (isset($_SESSION['CTS_DB_ERROR'])) return(FALSE);
	$user = $_SESSION['CTS_SessionUser'];
	$seclevel = $_SESSION['CTS_SecLevel'];
	$str = $_SERVER['HTTP_USER_AGENT'];
	// echo "log str: $str<br>";
	$re1 = '/\((.*?)\)/';      // hw info
  $re2 = '/.+\)(.+) /';      // browser info
  preg_match_all($re1, $str, $matches1, PREG_SET_ORDER, 0);
  preg_match_all($re2, $str, $matches2, PREG_SET_ORDER, 0);
  $svrinfo = $matches1[0][1] . ", " . $matches2[0][1];
  // echo "log svrinfo: $svrinfo<br>";
	$page = $_SERVER['PHP_SELF'];
	$txt = addslashes($text);
	$sql = "INSERT INTO `log` (`Agent`, `User`, `SecLevel`, `Page`, `Text`) VALUES ('$svrinfo', '$user', '$seclevel', '$page', '$txt');";
	// echo "Log: $sql<br>";
	$res = $mysqli->query($sql);
	if (!$res) {
		$errno = $mysqli->errno;
		$errmsg = $mysqli->error;
		echo "LOGGING ERROR: $errno -> $errmsg<br>";
		}
	return($err);
	}

// --------------- text file utils --------------------
function loadlist($listname) {
	$listitems = file("$listname");
	foreach ($listitems as $p) {
		$p = rtrim($p);
		if (strlen($p) <=0) { continue; } 
		if (substr_compare($p,'//',0,2) == 0) { continue; }
		printf("%s",$p);
		}
	return(TRUE);
}

// load text file
function loadmaintlist($listname) {
	$listitems = file("$listname");
	foreach ($listitems as $p) {
		printf("%s",$p);
		}
	return(TRUE);
}

// write text file
function writemaintlist($filename,$content) {
	file_put_contents("$filename", $content);
	}

// --------------------- db configtable utilities ----------------------------------------
// 'configtable' column names: CFGId, CfgName, CfgText
// read db table item
function readdblist($listname) {
	$sqldb = "SELECT * FROM `configtable` WHERE `CfgName` = '$listname'";
	$res = doSQLsubmitted($sqldb);
	$r = $res->fetch_assoc();
	return($r['CfgText']);
	}

// update db table item
function updatedblist($listname,$text) {
	$flds = array();
	$flds['CfgText'] = $text;
	$rows = sqlupdate('configtable', $flds, "`CfgName` = '$listname'");
	return($rows);
	}

// insert db configtable item
function insertdblist($listname, $text) {
	$flds = array();
	$flds['CfgName'] = $listname;
	$flds['CfgText'] = $text;
	$rows = sqlinsert('configtable',$flds);
	return($rows);
	}

// format text blob from db into an array
function formatdbrec($txt) {
	$res = array();
	$lines = explode("\n",$txt); 
	foreach ($lines as $l) {
		if (strlen($l) <= 0) { continue; } 
		if (substr_compare($l,'//',0,2) == 0) { continue; }
		list($tla,$desc) = explode(":", $l);
		$res[$tla] = rtrim($desc);
		//echo "tla:$tla, desc:$desc<br>";
		}
	return($res);
	}

// read and format db configtable row into select item list
function loaddbselect($cfglist) {
	$txt = readdblist($cfglist);
	$lines = explode("\n",$txt);
	foreach ($lines as $l) {
		$l = rtrim($l);
		if (strlen($l) <= 0) { continue; } 
		if (substr_compare($l,'//',0,2) == 0) { continue; }
		list($tla,$desc) = explode(":", $l);
		echo "<option value=$tla>$desc</option>";
		}
	return($listarray); 
	}
// ------------------- calc and return expiration date -----------------
function calcexpirationdate() {
	return(date('Y-m-01', strtotime('-11 months')));									// this is the expiration period
	}

// ------------------ check login credentials --------------------------
function checkcredentials($userid, $password) {
	$sql = "SELECT * FROM `cts2users` WHERE `UserID` = '$userid';";
	// echo "sql: $sql<br>";
	$res = doSQLsubmitted($sql);
	// echo '<pre>'; print_r($res); echo '</pre>';
	$nbrofrows = $res->num_rows;
	if ($nbrofrows == 0) {
		echo '<h3 style="color: red; ">ERROR: Userid not valid</h3>';
		addlogentry("Login attempt by $userid");
		return(false);
		}
	else {
		$res->data_seek(0);
		$r = $res->fetch_assoc();
		}
	
	if (($r['UserID'] == $userid) && ($r['Password'] == $password)) {
		//echo "found match - user: $uid, pw: $pw<br>";
		$_SESSION['CTS_SecLevel'] = $r['Role']; 
		$_SESSION['CTS_SessionUser'] = $userid;
		$_SESSION['CTS_ActiveCTSMCID'] = $r['MCID'];
		$_SESSION['CTS_VolEmail'] = $r['Email'];
		return(true);
		}
	echo '<h3 style="color: red; ">ERROR: Password not valid.</h3>';
	addlogentry("Password failure by $userid");
	return(false);
	}


?>