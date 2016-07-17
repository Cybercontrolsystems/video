<?php include("config.php"); ?>
<html><head><title>Main Pane</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
	$id = intval($_POST['id']);
	$sql = "DELETE FROM shorts WHERE id = $id LIMIT 1";
	$query = mysql_query($sql);
	echo "<div>Deleted record $id</div>";
}

if (isset($_POST['action']) && $_POST['action'] == 'update') {
	$series = intval($_POST['series']);
	$desc = mysql_escape_string($_POST['desc']);
	$sql = "UPDATE `seriesshorts` SET `desc` = \"$desc\" WHERE `id` = $series";
	$query = mysql_query($sql);
	if ($query) echo "UPDATED";
	else {
		echo $sql . " " . mysql_error();
	}
}

if (isset($_REQUEST['search'])) 
{
	$search = $_REQUEST['search'];
	$sql = "select * from shorts where match(title, girl, wearing, action) against ('$search')";
	echo "<h1>Search: $search</h1>";
}
else
{
	$series = $_REQUEST['series'];
	$seriesname = $_REQUEST['seriesname'];
	// Get Description
	$query = mysql_query("SELECT `desc` FROM `seriesshorts` WHERE `id` = $series");
	if ( ! $query) echo "FAILED " . mysql_error();
	$desc = mysql_fetch_array($query)[0];
	echo "<h1>$seriesname</h1>Series " . $series;
	$sql = "select * from shorts where series = $series order by title";
}

$query = mysql_query( $sql );
if ($query) {
	?>
	Rows: <?=mysql_num_rows($query)?><br/>
	
	<form method="post" action="seriesmain.php">
	<textarea cols="50" rows="6" name="desc"><?=$desc?></textarea>
	<input type='hidden' name='action' value='update'>
	<input type='hidden' name='series' value='<?=$series?>'>
	<input type='hidden' name='seriesname' value='<?=$seriesname?>'>
	</br/>
	<input type="submit" value="Update">
	</form>
	<?php
	echo "<table border='1'>";
	while ($row = mysql_fetch_assoc($query)) {
		$class = ($row['flag'] == 1) ? 'class="flag"' : '';
		$id = $row['id'];
		echo "<tr><td  $class>";
		if ($row['title'] == '') {
		?>
		<form method='post' action='seriesmain.php'>
			<input type='hidden' name='action' value='delete'>
			<input type='hidden' name='series' value='<?=$series?>'>
			<input type='hidden' name='seriesname' value='<?=$seriesname?>'>
			<input type='hidden' name='id' value='<?=$id?>'>
			<input type='submit' value='DEL'>
		</form>
		<?php
		}
		else
			echo "<a href='seriesedit.php?id=${row['id']}&seriesname=$seriesname'><img src='images/button_edit.png' title='$id' style='width:20px; height:20px'></a>";
		echo "</td>\n";
		echo "<td><a id='${row['id']}'>${row['title']}</a></td>";
		// Output ?, M or '' for male/female orgasm field.
		if (is_null($row['male']))
			$male = '?';
		else $male = $row['male'] ? 'M' : '';
		if (is_null($row['female']))
			$female = '?';
		else $female = $row['female'] ? 'F' : '';
		echo "<td>$male</td><td>$female</td>";
		echo "<td>${row['girl']}</td>";
		echo "<td>${row['wearing']}</td>";
		echo "<td>${row['action']}</td>";
		if ($row['duration'] == '00:00:00')
			echo "<td class='untimed'>${row['duration']}</td>";
		else
			echo "<td>${row['duration']}</td>";
		echo "<td>${row['date']}</td>";
		// echo "<td>${row['modified']}</td>";
		echo "</tr>";
	}
	echo "</table>";
}
?>
<form method="get" action="seriesedit.php">
<input type="hidden" name="id" value="0">
<input type="hidden" name="seriesid" value ="<?=$series?>">
<input type="submit" value="+">
</form>
</body>
</html>

