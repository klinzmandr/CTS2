<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Forms and Documentation Administration</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="all">
<link href="css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jsutils.js"></script>
<script src="js/fileinput.min.js"></script>

<?php
// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/seccheck.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action'])? $_REQUEST['action'] : "";
$form =isset($_REQUEST['form'])? $_REQUEST['form'] : "";

$updmsg = "";
$ctslib = "../CTSLibrary/"; // path to forms and doc library

// copy action
if ($action == 'copy') {
  if (isset($_REQUEST['file'])) {
    $file = $_REQUEST['file'];
    $new = $ctslib . $file . ' (copy)';
    $old = $ctslib . $file;
    // echo "new: $new<br>old: $old<br>";
    if (file_exists($new)) {
      addlogentry("File $old already exists");
      $errmsg = "Copy of $old FAILED - new file name already exists.";
      }
    else {
      // echo "old: $old, new: $new<br>";
      if (copy($old, $new)) {
        addlogentry("Copied $old to $new");
        $updmsg = "Copy of $old successful!";
        }
      else {
        addlogentry("Copy of $old FAILED");
        $errmsg = "Copy of $old FAILED!";
        }
      }
    }
  }
 
// rename action
if ($action == 'rename') {
  // include 'Incls/vardump.inc.php';
  // test and reference: https://www.the-art-of-web.com/javascript/escape/
	$old = $ctslib . urldecode($_REQUEST['oldname']);
	$new = $ctslib . rawurldecode($_REQUEST['newname']);
	// echo "old: $old,<br> new: $new<br>";
	if ($stat = rename($old, $new)) {
	  touch($new);   // update file time
	  $updmsg = "File $old renamed to $new";
	  addlogentry("File $old renamed to $new"); 
		}
	else {
	  addlogentry("Rename of $old failed");
		$errmsg = "Rename request FAILED! Stat: $stat<br>
		New name provided already exists OR path name invalid";
		}
	}

// delete action
if ($action == 'delete') {
  //echo '<h4>Delete action requested.</h4>';	
	$deltarget = $ctslib . $form;
	unlink($deltarget);
	addlogentry("Delete of $form successful");
	$updmsg = "Delete of $form successful";
	}

// addnew action
if (count($_FILES)) {
  $errmsg = "";     //initiate the progress message
  // echo '<pre> file '; print_r($_FILES); echo '</pre>';
  for ($i = 0; $i<count($_FILES["files"]["name"]); $i++) {
    $filen = $ctslib.$_FILES["files"]["name"][$i];
    if (file_exists($filen)) {
      $errmsg .= "<b>ERROR:</b> File $filen already exists.  Upload ignored!<br>";
      continue;
      }
    if ($_FILES["files"]["error"][$i] > 0) {
    	$errmsg .= "<b>ERROR:</b> " . $_FILES["files"]["error"][$i] . " on upload of $filen<br>";
    	continue;
    	}
  //  echo "i: $i<br>, name: " . $_FILES["files"]["name"][$i] . '<br>';
  //  echo "tmp_name: " . $_FILES["files"]["tmp_name"][$i] . "<br />";
  //  echo "Size: " . ($_FILES["files"]["size"][$i] / 1024) . " Kb<br>=====<br>";
   	if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $filen)) {
      $updmsg .= "File# ".($i+1)." ($filen) uploaded successfully<br>";
      addlogentry("Upload successful: filen"); 	  
   	  }
    }
  }

if ($errmsg <> '') echo "<h4 style=\"color: red; \" id=\"Xx\">$errmsg</h4>";
if ($updmsg <> '') echo "<span id=\"Xy\"><b>$updmsg</b><br></span>";

?>
<script>
$(document).ready(function() {
  $("#Xx").fadeOut(5000);
  $("#Xy").fadeOut(5000);
  $("#addfile").hide();
  $("#addclick").click(function() {
    $("#addfile").toggle();
  // alert("toggle");
    });
  // alert("doc ready");
  $("#infiles").fileinput({
    'showUpload': true, 
    'previewFileType':'any',
    'maxFileCount': 6
    });
});
</script>

<div class="container">
<h3>Documentation &amp; Forms Directory Maintenance
<span id="helpbtn" title="Help Documentation" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</h3>
<div id=help>
<h3>Help Documentation</h3>
<p>This is a library of reference documents that can be utilized by the volunteers to better serve their community of callers.</p>
<p>All documents have a 3 digit number at the start of the name.  Grouping of all documents in the library is based on the first digit (0-9) of the 3 digit sequence.  The second and third digits will provide sequencing of the documents within a group.</p>
<p>Currently the the names assigned to each group is:</p>
<ol>
	<li>Contacts - contact lists and calendars</li>
	<li>Hotline - reference guides and docmentation</li>
	<li>Rescue - reference guides and documentation</li>
	<li>Dirs & Misc - maps, directions and miscellaneous documents</li>
	<li>Mammals - documentation on capture and handing</li>
	<li>Birds - documentation on capture and handling</li>
	<li>Baby Animals - documentation on capture and handling</li>
	<li>Humane Excl. - information regarding exclusion protocols</li>
	<li>Forms - miscellaneous forms for printing</li>
	<li>System - information about CTS2</li>
</ol>
<p>Changes to the group names requires modification of the listing program and must be done by technical support staff.</p>
<p>Documents may be duplicated by merely making a copy and changing the leading group digit.  However, maintaining multiple copies of the same document may lead to multiple versions of a document to reside in the library.</p>
<p>The three icons preceeding each line of this listing allows for a maintenance action to be performed.  In addition, adding one or more files to the system is done by clicing the 'ADD NEW DOC(S) or FORM(S)' button.  Use the displayed dialogue to browse to and select one or more files to be uploaded.  Use the provided maitenance functions to copy, rename or delete.</p>

</div>
<button id="addclick">ADD NEW DOC(S) or FORM(S)</button>
<div id="addfile">
<form class="form-inline" role="form" action="adminformsmaint.php" method="post" enctype="multipart/form-data">
<input size=25 type="file" name="files[]" id="infiles" multiple />
</form>
</div>

<?php

$forms = scandir($ctslib);

// list contents of forms dir
echo '<table class="table" border="0">';
foreach ($forms as $formname) {
if (($formname == '.') || ($formname == '..')) { continue; }
$urlformname = urlencode($formname);

?>
<tr><td width="15%" align="center">

<a onclick="return chkdel()" href="adminformsmaint.php?action=delete&form=<?=$urlformname?>"><span title="DELETE" class="glyphicon glyphicon-trash" style="color: blue; font-size: 15px"></span></a>
&nbsp;
<a href="#" onclick="return getfld('<?=$urlformname?>')"><span title="RENAME (name starting with period will hide the file without deletion)" class="glyphicon glyphicon-star" style="color: blue; font-size: 15px"></span></a>
&nbsp;
<a href="adminformsmaint.php?action=copy&file=<?=$urlformname?>"><span title="COPY" class="glyphicon glyphicon-tags" style="color: blue; font-size: 15px"></span></a>
</td>
<td>
<a target=_blank href="<?=$ctslib?><?=$formname?>"><?=$formname?></a></td>
</tr>

<?php
}
?>
</table>
=== End of List ===
<script>
function chkdel() {
	var r = confirm("This action permanently deletes the file.  \nThis action CANNOT be reversed. \n\nClick OK to continue.");
	if (r == true) { return true; } 
	else { return false; }	
	}
</script>

</div>  <!-- container -->

<script>
function getfld(OName) {
var urienc = OName;
// var uridec = decodeURIComponent(OName);
var uridec = decodeURIComponent((OName+'').replace(/\+/g, '%20'));
//	if prompt dialog is canceled it exits the script
var newval = prompt("Please enter a NEW name (including the file extension if needed):", uridec);
// new name can not contain /, >, <, |, :, &
if (newval.length > 0) {
  var regex = /[\/><|:&]/gm;
  if (regex.test(newval)) {
    alert("ERROR: New name contains invalid special character(s)\nfor use in a file name.");
    return true;
    }
// if confirm dialog is canceled it returns false
  invalenc = encodeURIComponent(newval);
	$("#HF1").val(urienc);     // old name
	$("#HF2").val(invalenc);   // new name
	$("#NameForm").submit();
	return true;
	}
alert("Rename action cancelled");
return false;
}
</script>

<!-- define form to submit rename info WITHOUT a submit field defined -->
<form method="post" name="NameForm" id=NameForm>
<input type="hidden" id="HF1" name="oldname" value="">
<input type="hidden" id="HF2" name="newname" value="">
<input type="hidden" name="action" value="rename">
</form>
</body>
</html>
