<?php

$dbhost = 'localhost';
$dbname = 'phpbb';
$dbuser = 'phpbb';
$dbpasswd = 'Ma9uPm5h2EnhFsbD';

$guildsql = mysql_connect($dbhost, $dbuser, $dbpasswd);
$guilddb = mysql_select_db($dbname, $guildsql);
