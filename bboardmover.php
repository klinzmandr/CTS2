<h3>Import of Bulletin Board items from old CTS</h3><br>
<h4>DO NOT USE MORE THAN ONCE WITHOUT TRUNCATING THE DATABASE TABLE!!!!!</h4>
<?php
// read text cts directory
$bb = scandir("../cts/BBNotes");
foreach ($bb as $fn) {
	if (($fn == ".") or ($fn == "..")) { $out = array_shift($bb); continue; }
	$fnarray[] = $fn;
	}
arsort($fnarray);

include 'Incls/datautils.inc.php';

$bbcount = 0;
foreach ($fnarray as $fn) {
  $insarray = array();
	$rawnote = file("../cts/BBNotes/" . $fn);
	$notedt = array_shift($rawnote);    // date time of note
	$insarray[Subject] = rtrim(array_shift($rawnote));    // title
	list($noted,$notet,$noteauthor) = explode(" ",$notedt);
	$dt = date("Y-m-d h:i:s", strtotime($noted . ' ' . $notet));
	$insarray[DateTime] = $dt;
	$insarray[UserID] = rtrim($noteauthor);
	
	$nb = implode($rawnote);
	// echo '<pre>NB: '; print_r($nb); echo '</pre>';
  $nb = preg_replace("/[\r]/i", "", $nb);
  $nb = preg_replace("/[\n]/i", "<br>", $nb);
	// echo '<pre>NEWNB: '; print_r($newnb); echo '</pre>';
	// $insarray[Note] = implode($newnb);
	$insarray[Note] = $nb;
	
  // echo '<pre>'; print_r($insarray); echo '</pre>';
  // $ret = sqlinsert('bboard', $insarray);
  $bbcount++;
  if ($ret != 1) echo "return value: $ret<br>";
	}

echo "BBoard Items added: $bbcount<br>";
		
?>
DONE!
</body>
</html>