<?php include("config.php"); ?>
<html><head>
<link href="style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="includes/calendar.css">
<script language="JavaScript" src="includes/calendarcode.js"></script>
</head>
<body>
<?php
/* foreach ($_REQUEST as $key=>$value) {
	echo "Key $key Value $value<br>";
} */
if (isset($_GET['q']))
	$id=$_GET['q'];
else
	$id = false;

date_default_timezone_set("Europe/London");
if ($id) {
	$res = mysql_query("select * from video where id = $id");
	if ($res) {
		if (mysql_num_rows($res) != 1) {
			$rows = mysql_num_rows();
			die("We got $rows instead of exactly 1.");
		}
		$line = mysql_fetch_assoc($res);
		$line['scenes'] = nl2br($line['scenes']);
		
		$yesterday=getdate(time()-86400); // yesterday!
?>
<form method="post" action="views.php" name="dateFrm">
Record a viewing: 
<input type="text" name="day" value="<?=$yesterday['mday']?>" size="2" maxlength="2" class="cal-TextBox">
<input type="text" name="month" value="<?=$yesterday['mon']?>" size="2" maxlength="2" class="cal-TextBox">
<input type="text" name="year" value="<?=$yesterday['year']?>" size="4" maxlength="4" class="cal-TextBox">
<a class="so-BtnLink" href="javascript:calClick();return false;" onmouseover="calSwapImg('BTN_date',
'img_Date_OVER',true);" onmouseout="calSwapImg('BTN_date', 'img_Date_UP',true);"
onclick="calSwapImg('BTN_date', 'img_Date_DOWN');
showCalendar('dateFrm','dteWhen','BTN_date');return false;">
<img src="images/cal_date_up.gif" border="0" alt="Calendar" title=" Calendar " width="22" height="17" align="absmiddle" name="BTN_date"></a>
<input type="hidden" name="video" value="<?=$id?>">
Poor: <input type="radio" name="score" id="1" value="1">
 Ok: <input type="radio" name="score" id="2" value="2" checked>
 Great: <input type="radio" name="score" id="3" value="3">

<input type="submit" value="Record"</form>
<form method="get" action="views.php">

<input type="hidden" name="video" value="<?=$id?>"><input type="submit" value="Viewing History"></form>
<form method="get" action="edit.php">
	<input type="hidden" name="id" value="<?=$line['id']?>">
	<input type="submit" value="Make Changes">
</form>
<table border="1">
<tr><td>Title</td><td colspan="3"><?=$line['title']?>&nbsp;</td>
	<td>ID</td><td><?=$line['id']?>&nbsp;</td></tr>
<tr><td>Series</td><td><?=$line['series']?>&nbsp;</td>
	<td>Subtitle</td><td><?=$line['subtitle']?>&nbsp;</td>
	<td>Country</td><td><?=$line['country']?>&nbsp;</td></tr>
<tr><td>Publisher</td><td><?=$line['publisher']?>&nbsp;</td>
	<td>Medium</td><td><?=$line['medium']?>&nbsp;</td>
	<td>Participants</td><td><?=$line['participants']?>&nbsp;</td>
<tr><td>Sexiness</td><td><?=$line['sexy']?>&nbsp;</td>
	<td>Duration</td><td><?=$line['duration']?>&nbsp;</td>
	<td>Genre</td><td><?=$line['genre']?>&nbsp;</td>
<tr><td>Source</td><td><?=$line['source']?>&nbsp;</td>
	<td>Episodes</td><td><?=$line['episodes']?>&nbsp;</td>
	<td>Date Acquired</td><td><?=$line['date']?>&nbsp;</td></tr>
<tr><td>DVD</td><td  colspan="3"><?=$line['dvd']?>&nbsp;</td>
	<td>Media Server</td><td><?=$line['server']?></tr>
<tr><td>Summary</td><td colspan="5"><?=$line['summary']?>&nbsp;</td></tr>
<tr><td>Comments</td><td colspan="5"><?=$line['comments']?>&nbsp;</td></tr>
<tr><td valign="top">Scenes</td><td colspan="5"><?=$line['scenes']?>&nbsp;</td></tr>
</table>
<?php
	} else echo "No result";
}
?>
<div id="popupcalendar" class="text"></div>	
</body></html
