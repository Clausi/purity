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
$newsForum = 8;
$applyForum = 23;
$applyThread = 744;
$leadGroup = 9;

// DB Prefix
$DbPrefix = "guild_";

// DB Tabellennamen
$TableNames = array(
	'roster' => $DbPrefix."roster",
	'guild' => $DbPrefix."info",
	'progress' => $DbPrefix."progress",
	'progressbosses' => $DbPrefix."progressbosses",
	'characterprogress' => $DbPrefix."characterprogress",
);

// Level anzeigen
$showLevel = true;
$showiLevel = true;
$maxLevel = 100;

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
	'offitwink' => 4,
	'raidlead' => 3,
	'klassenleiter' => 99,
	'offi' => 2,
	'gildenrat' => 1,
	'gildenmeister' => 0
);


$rerank = array("Sardra" => $ranks['friends']);

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
	6967, // Blackrock Foundry
	6996, // Highmaul
//	6738, // Siege of Orgrimmar
//	6622, // Throne of Thunder
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
