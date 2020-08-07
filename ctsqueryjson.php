<?php
session_start();
// AJAX response code - bootstrap is implemented in the receiving page.
include 'Incls/datautils.inc.php';

$cn = $_REQUEST['callnbr'];
$sql  = "
SELECT * FROM `calls`
WHERE `CallNbr` = '$cn';"; 

// echo "notenbr: $notenbr, sql: $sql<br>";

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$r = $res->fetch_assoc();

$label = "$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";
if (strlen($r['Organization']) > 0) 
	$label = "$r[Organization]<br>$r[Name]<br>$r[Address]<br>$r[City], $r[State]  $r[Zip]";

// echo '<pre>'; print_r($r); echo '</pre>';
?>
<table class="table" border=1 class="table-condensed">
<tr><td colspan=4><b>Call Status: </b><?=$r['Status']?></td></tr>
<tr><td><b>Call Opened By:</b> <?=$r['OpenedBy']?></td>
<td colspan=3><b>Last Updated By:</b> <?=$r['LastUpdater']?></td></tr>
<tr><td colspan="4"><b>Description:</b> <?=$r['Description']?></td></tr>
<tr>
<td><b>Placed @:</b><br><?=$r['DTPlaced']?></td>
<td><b>Entered @:</b><br><?=$r['DTOpened']?></td>
<td colspan=2><b>Closed @:</b><br><?=$r['DTClosed']?></td>
</tr>
<tr><td><b>Animal Location:</b><br>&nbsp;&nbsp;<?=$r['AnimalLocation']?></td>
<td><b>Call Location:</b><br>&nbsp;&nbsp;<?=$r['CallLocation']?></td>
<td><b>Property:</b><br>&nbsp;&nbsp;<?=$r['Property']?></td>
<td><b>Species:</b><br>&nbsp;&nbsp;<?=$r['Species']?></td></tr>
<tr><td colspan=4><b>Reason:</b>&nbsp;&nbsp;<?=$r['Reason']?></td></tr>
<tr><td colspan=4><b>WRMD Ref. Number:</b>&nbsp;&nbsp;<?=$r['CaseRefNbr']?></td></tr>
<tr><td valign="top"><b>Resolution:</b> </td><td colspan="3"><?=$r['Resolution']?> at <?=$r['ResTOD']?> by <?=$r['ResBy']?><br></td></tr>
<tr><td><b>Time to Resolve:</b></td><td colspan="3"><?=$r['TimeToResolve']?> minutes</td></tr></table>

<span style="font-size: larger; color: #FF0000; "><b><font size="+1">Caller Detail</font></b></span>
<table border=0 class="table table-condensed">
<tr><td width="35%" valign="top"><b>Mailing Label:</b></td>
<td bgcolor="#E5E5E5"><?=$label?></td</tr>
<tr><td><b>Organization:</b> </td><td  colspan="3"><?=$r['Organization']?></td></tr>
<tr><td><b>Caller Name:</b> </td><td><?=$r['Name']?></td></tr>
<tr><td><b>Address:</b> </td><td><?=$r['Address']?></td></tr>
<tr><td><b>City, State Zip:</b> </td><td><?=$r['City']?>, <?=$r['State']?>  <?=$r['Zip']?></td></tr>
<tr><td><b>Email Address:</b> <?=$r['EMail']?></td><td><b>Phone Number:</b> <?=$r['PrimaryPhone']?></td></tr>
<tr><td><b>Postcard Sent?</b> <?=$r['PostcardSent']?></td><td><b>Email Sent?</b> <?=$r['EmailSent']?></td></tr>
</table>

<span style="font-size: larger; color: #FF0000; "><b><font size="+1">Call History (latest first)</font></b></span><br>
<?=$r['NotesDiary']?><br>
