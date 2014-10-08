<?php

$GLOBALS['wowarmory']['db']['driver'] = 'mysql'; // Dont change. Only mysql supported so far.
$GLOBALS['wowarmory']['db']['hostname'] = '127.0.0.1'; // Hostname of server. 
$GLOBALS['wowarmory']['db']['dbname'] = ''; //Name of your database
$GLOBALS['wowarmory']['db']['username'] = ''; //Insert your database username
$GLOBALS['wowarmory']['db']['password'] = ''; //Insert your database password
// Only use the two below if you have received API keys from Blizzard.
$GLOBALS['wowarmory']['keys']['api'] = ''; // You need the api key from Blizzard. dev.battle.net
$GLOBALS['wowarmory']['keys']['share'] = ''; // Currently unused
include(dirname(__FILE__) . '/wowarmoryapi/BattlenetArmory.class.php'); //include the main class 
