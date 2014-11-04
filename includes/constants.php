<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$GuildRegion = "eu";
$GuildRealm = "anetheron";
$GuildName = "tenebrae";
$battlenetLocale = 'de';
$armoryLocale = 'de_DE';

// Forum Ids
$newsForum = 2;
$applyForum = 2;
$applyThread = 1;
$leadGroup = 9;

// DB Prefix
$DbPrefix = "guild_";

// DB Tabellennamen
$TableNames = array(
	'roster' => $DbPrefix."roster",
	'guild' => $DbPrefix."info",
	'achievements' => $DbPrefix."achievements",
	'perks' => $DbPrefix."perks",
	'progress' => $DbPrefix."progress",
	'progressbosses' => $DbPrefix."progressbosses",
	'characterprogress' => $DbPrefix."characterprogress",
	'recruitment' => $DbPrefix."recruitment",
	'wowprogress' => $DbPrefix."wowprogress"
);

// Level anzeigen
$showLevel = false;
$showiLevel = true;

// Rang anzeigen
$showRank = false;

// Chars ausschließen
$skip = array("Wilder", "Biny");

// Exmember
$maxExmember = 30;

// Ränge aktuell
$ranks = array(
	'inaktiv' => 9,
	'friends' => 8,
	'twink' => 7,
	'trial' => 6,
	'initiant' => 99,
	'member' => 5,
	'epgp' => 4,
	'offitwink' => 3,
	'raidlead' => 99,
	'klassenleiter' => 99,
	'offi' => 2,
	'gildenrat' => 1,
	'gildenmeister' => 0
);


$rerank = array("Sardra" => $ranks['friends']);

// Ränge 16.02.2014
$rankFeb2014 = array();
$rankFeb2014['friends'] = 5;
$rankFeb2014['twink'] = 4;
$rankFeb2014['trial'] = 3;
$rankFeb2014['member'] = 2;
$rankFeb2014['gildenrat'] = 1;
$rankFeb2014['gildenmeister'] = 0;

// Ränge 15. März 2012
$rankMarch2012 = array();
$rankMarch2012['friends'] = 4;
$rankMarch2012['twink'] = 3;
$rankMarch2012['member'] = 2;
$rankMarch2012['gildenrat'] = 1;
$rankMarch2012['gildenmeister'] = 0;

// Raidprogress
$WGPConfig['Cache'] = 3000; // Update interval
	
// How many chars must have the progress
$WGPConfig['CharacterMatch'] = 7;

$WGPConfig['noIdBoss'] = array();
$WGPConfig['noIdBoss']['Die Steinwache'] = 60047;
$WGPConfig['noIdBoss']['Die Geisterkönige'] = 61421;
$WGPConfig['noIdBoss']['Der Wille des Kaisers'] = 60400;
$WGPConfig['noIdBoss']['Konklave des Windes'] = 45871;
$WGPConfig['noIdBoss']['Das Konklave des Windes'] = 45871;
$WGPConfig['noIdBoss']['Theralion und Valiona'] = 45992;
$WGPConfig['noIdBoss']['Rat der Aszendenten'] = 43735;
$WGPConfig['noIdBoss']['Omnotron-Verteidigungssystem'] = 42180;
$WGPConfig['noIdBoss']['Kanonenschiffsschlacht von Eiskrone'] = 201873;
$WGPConfig['noIdBoss']['Rat des Blutes'] = 37970;
$WGPConfig['noIdBoss']['Bestien von Nordend'] = 34797;
$WGPConfig['noIdBoss']['Fraktionschampions'] = 195631;
$WGPConfig['noIdBoss']["Zwillingsval\'kyr"] = 34497;
$WGPConfig['noIdBoss']['Die Versammlung des Eisens'] = 32857;
$WGPConfig['noIdBoss']['Die vier Reiter'] = 181366;

// Raidzone Ids - comment out raids you don't want to see
$WGPConfig['Raids'] = array(
	6738, // Siege of Orgrimmar
	6622, // Throne of Thunder
//	6125, // Mogu'shan Vaults
//	6297, // Heart of Fear
//	6067, // Terrace of Endless Springs
//	5892, // Dragon Soul
//	5723,	// Firelands
//	5334, // Bastion
//	5094, // Blackwing Descent
//	5638, // Throne
//	5600, // Baradin <-- Buggy
);

// Freeze Raidzones - After new content is released, you can freeze your progress of spezified zones
$WGPConfig['Freeze'] = array(
	6738, // Siege of Orgrimmar
	6622, // Throne of Thunder
	6125, // Mogu'shan Vaults
	6297, // Heart of Fear
	6067, // Terrace of Endless Springs
	5892, // Dragon Soul
	5723, // Firelands
	5334, // Bastion
	5094, // Blackwing Descent
	5638, // Throne
	5600, // Baradin <-- Buggy
);

// Fix raidzones with false Bossnumbers: Id => array(Fixnumber, 0/1 nh/hc/flex)
$WGPConfig['Fix'] = array(
	6622 => array(-1, 0), // Throne of Thunder
);
