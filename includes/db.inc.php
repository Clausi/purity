<?php

$dbhost = 'localhost';
$dbname = 'dbname';
$dbuser = 'dbuser';
$dbpasswd = 'dbpasswd';

$guildsql = mysql_connect($dbhost, $dbuser, $dbpasswd);
$guilddb = mysql_select_db($dbname, $guildsql);
