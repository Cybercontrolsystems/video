<?php
define('SERVER', 'localhost:/tmp/mysql.sock');
define('DBNAME', 'video');
define('USERNAME', 'testuser');
define('PASSWORD', '');
$link = mysql_connect(SERVER, USERNAME, PASSWORD) or die ("Can't connect to database server" . mysql_error());
mysql_select_db(DBNAME) or die("Can't select database");

?>
