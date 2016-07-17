<?php include("config.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$date = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
$video = $_REQUEST['video'];
$score=$_POST['score'];
$res = mysql_query("insert into views(`video`, `date`, `score`) values ($video, '$date', $score);");
$num = mysql_affected_rows($res);

}

?>
<html>
<link href="style.css" rel="stylesheet" type="text/css">
<body>
<?php
// foreach ($_REQUEST as $key=>$value) {echo "Key $key Value $value<br>";}
?>
Viewing History:
<?php
$sql = "select count(score) as count, avg(score) as avg, sum(score) as total, max(views.date) as recent, title
	from views, video
	where views.video = $video and video.id = views.video group by title";
$res = mysql_query($sql);
if ($res) {
	if (mysql_num_rows($res) == 0) {
		echo "No previous viewing data for this film";
	} else {
		$line = mysql_fetch_assoc($res);
		foreach ($line as $key=>$value) {
			echo "<br>$key:$value\n";
		}
	}
} else { // SQL Error
	echo "SQL Error: " . mysql_error();
}

$score = array(1=>'poor', 2=>'ok', 3=>'great');

	
$res = mysql_query("select date, score from views where `video` = $video order by `date`");
if ($res) {
	echo "<h3>Viewing History</h3>\n";
	while ($line = mysql_fetch_assoc($res)) {
		echo "${line['date']} -- " . $score[$line['score']] . "<br>\n";
	}
}  else { // SQL Error
	echo "SQL Error: " . mysql_error();
}

?>
<hr>

</body></html
