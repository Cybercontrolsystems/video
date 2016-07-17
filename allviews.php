<?php include("config.php");
?>
<html>
<link href="style.css" rel="stylesheet" type="text/css">
<body>
<?php
// foreach ($_REQUEST as $key=>$value) {echo "Key $key Value $value<br>";}
?>
<?php
$score = array(1=>'poor', 2=>'ok', 3=>'great');
$res = mysql_query("select views.date as date, score, title from views, video 
	where video.id = views.video order by `date` desc");
if ($res) {
	echo "<h2>All Viewing History</h2>\n<table border=\"0\">";
	while ($line = mysql_fetch_assoc($res)) {
		echo "<tr><td>${line['date']}</td><td>" . $score[$line['score']] . "</td><td>${line['title']}</td></tr>\n";
		}
	echo "</table>\n";
}  else { // SQL Error
	echo "SQL Error: " . mysql_error();
}

?>
<hr>

</body></html
