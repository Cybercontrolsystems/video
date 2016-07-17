<?php include("config.php"); ?>
<html><head><title>Left Pane</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>Short Films</h1>
<?php
// Process post series="new series name"
if ($_POST['series']) {
	echo "POSTING<br>";
	$sql = "insert into seriesshorts (seriesname) values ('${_POST['series']}')";
	$res = mysql_query($sql);
	if ($res) 
		echo "New series created";
	else
		echo mysql_error();
	
}
 // Construct the lists of Series

function countSeries($id) {
	$sql = "select count(id) as num from shorts where series = $id";
	$res = mysql_query($sql);
	if ($res) {
		$row = mysql_fetch_assoc($res);
		return $row['num'];
	}
	else return mysql_error();	
}

// If the male field is NULL, this hasn't been updated
function countUnwatched($id) {
	$sql = "SELECT count(id) AS num FROM shorts WHERE series = $id AND male IS NULL";
	$res = mysql_query($sql);
	if ($res) {
		$row = mysql_fetch_assoc($res);
		return $row['num'];
	}
	else return mysql_error();	
}

$series = mysql_query("select id, seriesname from seriesshorts order by seriesname");
if ($series) {
	while ($row = mysql_fetch_assoc($series)) {
		$seriesname=urlencode($row['seriesname']);
		$numSeries = countSeries($row['id']);
		$numUnwatched = countUnwatched($row['id']);
		echo "<a href='seriesmain.php?series=${row['id']}&seriesname=$seriesname' target='main'>${row['seriesname']} ($numUnwatched/$numSeries)</a><br/>\n";
	}
}

?>
<!-- Add New Series of shorts -->
<form method="post" action="seriesleft.php">
<input type="text" name="series">
<input type="submit" value="+">
</form>
<form method="post" action="seriesmain.php" target="main">
<input type="text" name="search">
<input type="submit" value="?">
</form>
</body>
</html>

