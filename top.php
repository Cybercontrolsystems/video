<?php

include("config.php");
// if called with METHOD=POST, commit values. for name, query, id

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = $_POST['id'];
	$name= $_POST['name'];
	$query = $_POST['query'];
	switch ($_POST['mode']) {
	case 'delete':
		$query = "delete from category where id=$id"; break;
	case 'update':
		$query = "update category set name='$name', query='$query' where id = $id"; break;
	case 'insert':
		$query = "insert into category(name, query) values ('$name', '$query')"; break;
	default: die("Invalid _POST['mode'] is ${_POST['mode']}");
	}
	$res = mysql_query($query);
}
?>
<html><head><title>Top Pane</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<!-- Display list of categories, with an Edit and Delete button next to them.
Also a New button.
The edit button calls self with method = GET ? edit=nn or delete=nn
-->
<body class='top' class="main">Select a Category or create a new one ...


<?php
// Was there a query? Did it work?
if (isset($res)) {
	if (!$res)
	echo "SQL status: ", mysql_error($res), $query;
	else
	echo "Update OK<br/>";
}

$res = mysql_query("select id, name from category");
if ($res) {
	while ($row = mysql_fetch_assoc($res)) {
		echo "<a href=\"left.php?q=${row['id']}\" target=\"left\">${row['name']}</a> ";
		echo "<a href=\"top.php?edit=${row['id']}\"><img src=\"images/button_edit.png\" title=\"Edit\"></a> ";
		echo "<a href=\"top.php?delete=${row['id']}\"><img src=\"images/button_drop.png\" title=\"Delete\"></a> ";
	}
}

?>
<br>
<a href="top.php?mode=insert">New Category<img src="images/button_insert.png" title="New"></a> 
<a href="edit.php?id=0" target="main">New Record<img src="images/button_insert.png" title="New"></a>
<a href="allviews.php" target="main">Viewing History<img src="images/b_browse.png"></a>
<br>
<?php
if (isset($_GET['mode']) and $_GET['mode'] == 'insert') {
	echo "<br>New category:";
	$name="Name";
	$query="Select * from video (where ...) (order by ...)";
	$mode="insert";
	?>
	<form method="POST" action="top.php">
	Name: <input type="text" name="name" value="<?=$name?>">
	Query: <input type="text" name="query" size="90" value="<?=$query?>">
	<input type="submit" value="Confirm">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="mode" value="insert">
	</form>
	<?php


}
if (isset($_GET['delete'])) {
	echo "<br>Delete:";
	$id = $_REQUEST['delete'];
	$res = mysql_query("select * from category where id = $id");
	if ($res) {
		$line = mysql_fetch_assoc($res);
		$name = $line['name'];
		$query = $line['query'];
?>
		<form method="POST" action="top.php">
		Name: <?=$name?>
		Query: <?=$query?>
		<input type="submit" value="Confirm">
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="mode" value="delete">
		</form>
<?php
	}
	else echo "Error selecting query to update<br>";
}

if (isset($_GET['edit'])) {
	echo "<br>Update:";
	$id = $_REQUEST['edit'];
	$res = mysql_query("select * from category where id = $id");
	if ($res) {
		$line = mysql_fetch_assoc($res);
		$name = $line['name'];
		$query = $line['query'];
		$id = $line['id'];
	?>
		<form method="POST" action="top.php">
		Name: <input type="text" name="name" value="<?=$name?>">
		Query: <input type="text" name="query" size="90" value="<?=$query?>">
		<input type="submit" value="Update">
		<input type="hidden" name="id" value="<?=$id?>">
		<input type="hidden" name="mode" value="update">
		</form>
<?php
	}
	else echo "Error selecting query to update<br>";
}
?>
<form style="display:inline" method="get" action="left.php" target="left">
Main: <input type="text" name="s" id="s" size="40">
<input type="submit" value="Search">
</form>
<br/>Favourites: 
<input type="button" onclick="javascript:window.open('left.php?q=2', 'left');" value="All">
<input type="button" onclick="javascript:window.open('seriesleft.php', 'left');" value="Shorts">
