<?php include("config.php"); ?>
<html>
<!-- Called to edit a Video record
If called  with POST, update the record otherwise display again with fields as input areas
instead of display areas.
If called with ID=0, create a new empty record for editting. -->
<?php
$fields = array("ID" => "id", "Title"=>"title", "Series Number"=>"series", "SubTitle"=>"subtitle",
	"Country"=>"country", "Publisher"=>"publisher", "Medium"=>"medium", "Sexiness"=>"sexy",
	"Duration"=>"duration", "Source"=>"source", "Genre" => "genre", "Episodes"=>"episodes",
	"Participants"=>"participants",
	"Date Acquired"=>"date", "DVD"=>"dvd", "Media Server"=>"server", "Summary"=>"summary", "Comments"=>"comments",
	"Scenes"=>"scenes");
$id=$_REQUEST['id'];
$query='';
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') { //fetch and display
	if ($id == 0) { // insert new record
		$res = mysql_query("insert into video (id) values (0)");
		if ($res) {
			$id = mysql_insert_id();
		} else die ("insert failed");
	} else { // retrieve existing record
		$res = mysql_query("select * from video where id=$id");
		if (mysql_num_rows($res) != 1) {
			die("Returned wrong number of rows");
		}
		$line = mysql_fetch_assoc($res);
	}
} else { // .. must be a POST, so update the database.
	// boring ...
	$query = "Update video set ";
	$trans = array('"' => '&quot;', "'" => '&#039;', '\r\n' => '');
	// Frig - remove leading and trailing \r\n from scenes
	// For some reason, can't remove the last one. maybe a trailing space 
	// there, so have to remove ALL of hem. no harm done.
	$_POST['scenes'] = preg_replace('/^\r\n/', '', $_POST['scenes']);
//	$_POST['scenes'] = preg_replace('/\r\n$/', '', $_POST['scenes']);
	foreach($fields as $key=>$value) {
		$query .= "$value = '" . strtr($_POST[$value], $trans) . "',";
	}
	// echo $query;
	// Have to remove the last comma from the string.
	$query = rtrim($query, ",") . " where id = ${_POST['id']}";
	$res = mysql_query($query);
	if (mysql_errno()) {
		$error = "ERROR -- ". mysql_error();
	}
	$rowsaffected = mysql_affected_rows();
}
// Populate country, genre, publisher, source pulldowns.
$res = mysql_query("select name from country order by name");
if ($res) {
	while ($line = mysql_fetch_assoc($res)) {
		$countries[] = $line['name'];
	}
}
$res = mysql_query("select name from publisher order by name");
if ($res) {
	while ($line = mysql_fetch_assoc($res)) {
		$publishers[] = $line['name'];
	}
}
$res = mysql_query("select name from genre order by name");
if ($res) {
	while ($line = mysql_fetch_assoc($res)) {
		$genres[] = $line['name'];
	}
}
$res = mysql_query("select name from source order by name");
if ($res) {
	while ($line = mysql_fetch_assoc($res)) {
		$sources[] = $line['name'];
	}
}
$medium = array('DVD', 'VHS', 'Digital');
?>
<head><link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script.js">
</script>
</head>
<body>
<?php
if ($error) {
	echo "SQL Error:  $error";
	die ("error Message $error");
}
if ($query) {
// echo $query;
	echo "Changes made successfully. Rows updated: $rowsaffected. Make more or press	<form name='form1' method='get' action='main.php' " . 
		"target=\"main\"><input type=\"hidden\" name=\"q\" value=\"$id\"><input type=\"submit\" value=\"Done\"></form>";
}

if ($id) {
	$res = mysql_query("select * from video where id = $id");
	if ($res) {
		if (mysql_num_rows($res) != 1) {
			$rows = mysql_num_rows($res);
			die("We got $rows rows instead of exactly 1.");
		}
		$line = mysql_fetch_assoc($res);
		echo "Record last updated ${line['updated']}<br>\n";
		echo "<form method=\"post\" action=\"edit.php\"><input type=\"submit\" value=\"Update\" id=\"update\"><table border=\"1\">";
		$rows = 20; 
		foreach($fields as $key=>$value) {
		switch($key) {
			case "ID": echo "<tr><td>ID</td><td>${line['id']}</td></tr><input type=\"hidden\" name=\"id\" value=\"${line['id']}\">"; break;
			case "Scenes": echo "<tr><td>$key</td><td><textarea id=\"$value\" name=\"$value\" rows=\"$rows\" cols=\"60\">${line[$value]}</textarea></tr>"; break;
			case "Medium": echo "<tr><td>$key</td><td><select name=\"medium\">";
				foreach ($medium as $value) {
					if ($value == $line['medium']) $sel = "selected"; else $sel = "";
					echo "<option value=\"$value\" $sel>$value</option>";
				};
				echo "</select></td></tr>"; break;
			case "Genre": echo "<tr><td>$key</td><td><select name=\"genre\">";
				foreach ($genres as $value) {
					if ($value == $line['genre']) $sel = "selected"; else $sel = "";
					echo "<option value=\"$value\" $sel>$value</option>";
				};
				echo "</select></td></tr>"; break;

			case "Country": echo "<tr><td>$key</td><td><select name=\"country\">";
				foreach ($countries as $value) {
					if ($value == $line['country']) $sel = "selected"; else $sel = "";
					echo "<option value=\"$value\" $sel>$value</option>";
				};
				echo "</select></td></tr>"; break;
			case "Publisher": echo "<tr><td>$key</td><td><select name=\"publisher\">";
				foreach ($publishers as $value) {
					if ($value == $line['publisher']) $sel = "selected"; else $sel = "";
					echo "<option value=\"$value\" $sel>$value</option>";
				};
				echo "</select></td></tr>"; break;
			case "Media Server": echo "<tr><td>$key</td>";
				echo "<td><input type=\"text\" name=\"$value\" id=\"$value\" value=\"${line[$value]}\" ";
				echo "size=\"40\" maxlength=\"1000\"><input type=\"button\" value=\"Today\" onclick=\"setToday('server')\"></td></tr>";
				break;
			case "Source": echo "<tr><td>$key</td><td><select name=\"source\">";
				foreach ($sources as $value) {
					if ($value == $line['source']) $sel = "selected"; else $sel = "";
					echo "<option value=\"$value\" $sel>$value</option>";
				};
				echo "</select></td></tr>"; break;
			default: echo "<tr><td>$key</td><td><input type=\"text\" name=\"$value\" id=\"$value\" value=\"${line[$value]}\" size=\"80\" maxlength=\"1000\"></td></tr>";
		}
		echo "\n";
		}
?>
</table>
<input type="submit" value="Update" id="update"></form>
<?php
	} else echo "No result";
}

?>
</body></html>

