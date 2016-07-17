<?php include("config.php"); ?>
<html><head><title>Edit Pane</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script>
$(document).ready ( function() { 
	setTimeout(function() {$("#submit").attr("value","Submit")}, 1500)
} );
</script>
</head>
<body>
<?php
function cleanup_time($text)
{
	$output = '';
	for ($i = 0; $i < strlen($text); $i++) {
		$char = $text[$i];
		if ($char >= '0' && $char <= '9')
			$output .= $char;
		else
			$output.= ':';
	}
	return $output;
}
$id = $_REQUEST['id'];
$seriesid = 0;
$seriesid = $_REQUEST['seriesid'];
$seriesname = $_REQUEST['seriesname'];
$submitstatus = "Submit";
// Process POST if it's an update ...
$flag = 'unset';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$title=mysql_escape_string($_POST['title']);
	$girl=mysql_escape_string($_POST['girl']);
	$wearing=mysql_escape_string($_POST['wearing']);
	$series=mysql_escape_string($_POST['series']);
	$action=mysql_escape_string($_POST['action']);
	$duration=cleanup_time($_POST['duration']);
	$date = mysql_escape_string($_POST['date']);
	$flag = mysql_escape_string($_POST['flag']);
	$male = mysql_escape_string($_POST['male']) == "on" ? 1 : 0;
	$female = mysql_escape_string($_POST['female']) == "on" ? 1 : 0;
	$flag = ($flag == 'on') ? 1 : 0;
	if ($id > 0)
	$sql = "update shorts set title='$title', girl='$girl', series='$series', wearing='$wearing', action='$action', duration = '$duration', date = '$date', flag=$flag, male=$male, female=$female "
	. " where id = $id";
	else
		$sql = "insert into shorts (title, girl, series, wearing, action, duration, date, flag, male, female) values ('$title', '$girl', '$series', '$wearing', '$action', '$duration', '$date', $flag, $male, $female)"; 
	$res = mysql_query($sql);
	if ($res)
		$submitstatus = "Updated Ok";
//		$id = mysql_insert_id($res);
	else
		echo "SQL error " . mysql_error();
}

$row = Array();
if ($id > 0) {
	$query = mysql_query("select * from shorts where id = $id");
	if ($query) {
		$row = mysql_fetch_assoc($query);
	}
}

// Get the series name (as long as it's not a new record)
if ($id > 0) {
	$sql = "select series from shorts where id = $id";
	$res = mysql_query($sql);
	if ($res)
		$seriesid = $row['series'];
	$flag = $row['flag'];
}

?>
<form method="post" action="seriesedit.php">
<table>
<tr><td>Series</td><td><select name="series"> 
<?php 
// If 0, new item  Else populate fields from table
	$sql = "select id,seriesname from seriesshorts";
	$res = mysql_query($sql);
	if ($res) {
		while ($seriesrow = mysql_fetch_assoc($res)) {
			if ($seriesrow['id'] == $seriesid) {
				$sel = "selected='yes'";
				$seriesname = $seriesrow['seriesname'];
			}
			else
				$sel = '';
		echo "<option value='${seriesrow['id']}' $sel >${seriesrow['seriesname']}</option>";
		}
	}
?>
</select></td><tr/>
<tr><td>Title</td><td><input type="text" name="title" value="<?= $row['title'] ?>"></td></tr>
<tr><td>Girl</td><td><input type="text" name="girl" value="<?= $row['girl'] ?>"></td></tr>
<tr><td>Wearing</td><td><input type="text" size="100" name="wearing" value="<?= $row['wearing'] ?>"></td></tr>
<tr><td>Doing</td><td><textarea cols="50" rows="6" name="action"><?= $row['action'] ?></textarea></td></tr>
<tr><td>Orgasm</td>
	<td>Male: <input type="checkbox" name="male" <?php if ($row['male']) echo " checked=''" ?>> 
		Female: <input type="checkbox" name="female" <?php if ($row['female']) echo " checked=''" ?>></td></tr>
<tr><td>Duration</td><td><input type="text" name="duration" value="<?=$row['duration']?>" onfocus="this.select()" on mouseup="return false"></td></tr>
<tr><td>Date</td><td><input type="text" id="date" name="date" value="<?= $row['date'] ?>">
	<input type="button"  onclick="setToday('date')" value="Today"></td></tr>

</table>
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="seriesid" value="<?=$seriesid?>">
<input type="submit" id="submit" value="<?=$submitstatus?>">
<br/>Flag? 
<?php
if ($flag == 1)
	echo "<input type='checkbox' name='flag' checked=''>";
else
	echo "<input type='checkbox' name='flag'>";
?>
</form>

<!-- the DONE button.  BUG - not guaranteed to go back to correct series if if has been changed -->
<span class='left'><form method="get" action="seriesmain.php#<?= $id ?>">
<input type="hidden" name="series" value="<?= $seriesid ?>">
<input type="hidden" name="seriesname" value="<?= $seriesname ?>">
<input type="submit" value="Close">
</form></span>

<!-- New one -->
<span class='left'><form method="get" action="seriesedit.php">
<input type="hidden" name="id" value="0">
<input type="hidden" name="seriesid" value ="<?=$series?>">
<input type="submit" value="NEW">
</form></span>
</body>
</html>

