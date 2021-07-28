<?php
// provide selection of prior week dates
// relative to today's date

$t0 = date("Y-m-d", strtotime('now'));
$t0e = date("M j, Y", strtotime('now'));
$t1 = date("Y-m-d", strtotime('now - 1 day'));
$t1e = date("M j, Y", strtotime('now - 1 day'));
$t2 = date("Y-m-d", strtotime('now - 2 day'));
$t2e = date("M j, Y", strtotime('now - 2 day'));
$t3 = date("Y-m-d", strtotime('now - 3 day'));
$t3e = date("M j, Y", strtotime('now - 3 day'));
$t4 = date("Y-m-d", strtotime('now - 4 day'));
$t4e = date("M j, Y", strtotime('now - 4 day'));
$t5 = date("Y-m-d", strtotime('now - 5 day'));
$t5e = date("M j, Y", strtotime('now - 5 day'));
$t6 = date("Y-m-d", strtotime('now - 6 day'));
$t6e = date("M j, Y", strtotime('now - 6 day'));
$t7 = date("Y-m-d", strtotime('now - 7 day'));
$t7e = date("M j, Y", strtotime('now - 7 day'));

?>
<option selected value="<?=$t0?>"><?=$t0e?></option>';
<option value="<?=$t1?>"><?=$t1e?></option>
<option value="<?=$t2?>"><?=$t2e?></option>
<option value="<?=$t3?>"><?=$t3e?></option>
<option value="<?=$t4?>"><?=$t4e?></option>
<option value="<?=$t5?>"><?=$t5e?></option>
<option value="<?=$t6?>"><?=$t6e?></option>
<option value="<?=$t7?>"><?=$t7e?>s</option>

