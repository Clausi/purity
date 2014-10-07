<?php

$GLOBALS['wowarmory']['db']['driver'] = 'mysql'; // Dont change. Only mysql supported so far.
$GLOBALS['wowarmory']['db']['hostname'] = '127.0.0.1'; // Hostname of server. 
$GLOBALS['wowarmory']['db']['dbname'] = ''; //Name of your database
$GLOBALS['wowarmory']['db']['username'] = ''; //Insert your database username
$GLOBALS['wowarmory']['db']['password'] = ''; //Insert your database password
// Only use the two below if you have received API keys from Blizzard.
$GLOBALS['wowarmory']['keys']['private'] = ''; // if you have an API key from Blizzard
$GLOBALS['wowarmory']['keys']['public'] = ''; // if you have an API key from Blizzard
include('includes/BattlenetArmory.class.php'); //include the main class 
