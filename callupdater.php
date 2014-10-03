<!DOCTYPE html>
<html>
<head>
<title>Update Call</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
</head>
<body>
<?php
session_start();
//include 'Incls/seccheck.inc';
//include 'Incls/mainmenu.inc';
include 'Incls/datautils.inc';

print <<<formPart1
<div class="container">
<h3>Update Call</h3>
<p>This page will either populate the fields for a new call or display/update the fields for an existing call.</p>
<form class="form" action="callupdater.php">
&nbsp;&nbsp;Date Call Placed:&nbsp;</td><td>
<select name="dtcpm" size="1">
<option value="--" label="--">--</option>
<option value="01" label="01">01</option>
<option value="02" label="02">02</option>
<option value="03" label="03">03</option>
<option value="04" label="04">04</option>
<option value="05" label="05">05</option>
<option value="06" label="06">06</option>
<option value="07" label="07">07</option>
<option value="08" label="08">08</option>
<option value="09" label="09">09</option>
<option value="10" label="10">10</option>
<option value="11" label="11">11</option>
<option value="12" label="12">12</option>
</select>
<select name="dtcpd" size="1">
<option value="--" label="--">--</option>
<option value="01" label="01">01</option>
<option value="02" label="02">02</option>
<option value="03" label="03">03</option>
<option value="04" label="04">04</option>
<option value="05" label="05">05</option>
<option value="06" label="06">06</option>
<option value="07" label="07">07</option>
<option value="08" label="08">08</option>
<option value="09" label="09">09</option>
<option value="10" label="10">10</option>
<option value="11" label="11">11</option>
<option value="12" label="12">12</option>
<option value="13" label="13">13</option>
<option value="14" label="14">14</option>
<option value="15" label="15">15</option>
<option value="16" label="16">16</option>
<option value="17" label="17">17</option>
<option value="18" label="18">18</option>
<option value="19" label="19">19</option>
<option value="20" label="20">20</option>
<option value="21" label="21">21</option>
<option value="22" label="22">22</option>
<option value="23" label="23">23</option>
<option value="24" label="24">24</option>
<option value="25" label="25">25</option>
<option value="26" label="26">26</option>
<option value="27" label="27">27</option>
<option value="28" label="28">28</option>
<option value="29" label="29">29</option>
<option value="30" label="30">30</option>
<option value="31" label="31">31</option>
</select>&nbsp;
<select name="dtcpy" size="1">
<option value="13" label="13">2013</option>
<option value="14" label="14" selected>2014</option>
<option value="15" label="15">2015</option>
</select>
&nbsp;&nbsp;Approx. Time to Resolution:</td><td>
<input type="radio" name="ttaken" value="15">15&nbsp;
<input type="radio" name="ttaken" value="30">30&nbsp;
<input type="radio" name="ttaken" value="45">45&nbsp;
<input type="radio" name="ttaken" value="60">60&nbsp;
<input type="radio" name="ttaken" value="60+">60+
formPart1;

echo '&nbsp;&nbsp;Animal Location:
<select name="coo" size="1">';
loadlist("Lists/Locations.txt");

echo '</select>&nbsp;&nbsp;Call Location:
<select name="cl" size="1">';
loadlist("Lists/Locations.txt");

echo '</select>&nbsp;&nbsp;Property:
<select name="prop" size="1">';
loadlist("Lists/Propertys.txt");

echo '</select>&nbsp;&nbsp;Species:
<select name="species" size="1">';
loadlist("Lists/Species.txt");

echo '</select>&nbsp;&nbsp;Call Reason:<br><font size="-2">&nbsp;&nbsp;* Req. state and/or federal reporting</font>
<select name="reason" size="1">';
loadlist("Lists/CallReasons.txt");

echo '</select>&nbsp;&nbsp;Action:
<select onchange="checkresol()" name="resol" size="1">';
loadlist("Lists/Actions.txt");
echo '</select>';

print <<<pageEnd
<form>
</div>  <!-- container -->
pageEnd;

?>

<script src="jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
