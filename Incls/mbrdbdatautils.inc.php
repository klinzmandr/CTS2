<?php
// connect to the database for all pages
global $mysqlix;

include "../.DBParamInfo";

// production member database connect
$db = ProdDBName;
$mysqlix = new mysqli("localhost", DBUserName, DBPassword, $db);

if ($mysqlix->connect_errno) {
		$errno = $mysqlix->connect_errno;
    echo "Failed to connect to MySQL: (" . $errno . ") " . $mysqlix->connect_error."<br>";
    }

// auto returns to code following the 'include' statement
// echo "Initial Connection Info: ".$mysqlix->host_info . "<br><br>";

// ------------------ submit sql statement provided by calling script ----------
// submit sql statement provided in call
function dombrSQLsubmitted($sql) { 
global $mysqlix;

$res = $mysqlix->query($sql);
if (substr_compare($sql,"DELETE",0,6,TRUE) == 0) {
	//echo "<br>Delete command seen - return affected_rows<br>";
	$rowsdeleted = $mysqlix->affected_rows;
	//echo "delete count: $rowsdeleted<br>";	
	return($rowsdeleted);
	}

if (!$res) {
    showError($res);
		}
return($res);
}

// --------------------- db configtable utilities --------------------
// 'configtable' column names: CFGId, CfgName, CfgText
// read db table item
function readmbrdblist($listname) {
	$sqldb = "SELECT * FROM `configtable` WHERE `CfgName` = '$listname'";
	$res = dombrSQLsubmitted($sqldb);
	$r = $res->fetch_assoc();
	return($r[CfgText]);
	}

// update db table item
function updatembrdblist($listname,$text) {
	$flds = array();
	$flds[CfgText] = $text;
	$rows = sqlupdate('configtable', $flds, "`CfgName` = '$listname'");
	return($rows);
	}

// insert db configtable item
function insertmbrdblist($listname, $text) {
	$flds = array();
	$flds[CfgName] = $listname;
	$flds[CfgText] = $text;
	$rows = sqlinsert('configtable',$flds);
	return($rows);
	}

// format text blob from db into an array
function formatmbrdbrec($txt) {
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

function loadmbrdbselect($cfglist) {
	$txt = readmbrdblist($cfglist);
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

?>