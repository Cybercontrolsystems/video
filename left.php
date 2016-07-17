<?php include("config.php"); ?>
<html><head><title>Left Pane</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
if (isset($_REQUEST['q'])) // For predefined Category
	$id = $_REQUEST['q'];
else $id = false; 
if (isset($_REQUEST['s']))
	$str = $_REQUEST['s'];
else $str = false; // For Search
if ($id) {
	$res = mysql_query("select * from category where id = $id");
	if ($res) { $query = mysql_fetch_assoc($res);
		$title = $query['name'];
		$query = $query['query'];
//		echo "Query is $query<br>";
	}
	else {
		echo "Error- could not retrieve query<br>";
	}
	// Get the matching titles
	$res2 = mysql_query($query);
	$rows = mysql_num_rows($res2);
	echo "<span class=\"heading\">$title</span> <br>$rows results:<br><table>";
	while ($line = mysql_fetch_assoc($res2)) {
		$series = $line['series'];
		$text = $line['summary'] . ' -- ' . $line['comments'];
		$title = $line['title'];
		if ($title == '')
			$title = "TITLE NOT SET";
		echo "<tr><td class=\"left\"><a href=\"main.php?q=${line['id']}\" target=\"main\" title=\"$text\">$title
			$series ${line['subtitle']}</a></td></tr>";
	}
	echo "</table>\n";
} else if ($str) {
	// echo "Called with search = $str<br>";
	$res = mysql_query("select id, title, summary, comments from video where match(summary,comments,scenes) against (\"$str\")");
	if ($res) {
		$rows = mysql_num_rows($res);
		echo "<span class=\"heading\">$title</span> <br>$rows results:<br><table>";
		while ($line = mysql_fetch_assoc($res)) {
			$text = $line['summary'] . ' -- ' . $line['comments'];
			echo "<tr><td class='left'><a href=\"main.php?q=${line['id']}\" target='main' ";
			echo "title='$text'>${line['title']} </a></tr>";
		}
		echo "</table>\n"; 
	}
	else {
		echo "No rows matched \"$str\"<br>";
	$errno = mysql_errno();
	$errstr = mysql_error();
	echo "errno = $errno Error = $errstr";
	}

}// no q= value supplied.
{ echo "Select a Category in the pane above to see a list of matching results here.";
}
?>
</body></html>
