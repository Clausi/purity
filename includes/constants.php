<?php

$GuildRegion = "eu";
$GuildRealm = "anetheron";
$GuildName = "purity";
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
$rank = array();
$rank['inaktiv'] = 9;
$rank['friends'] = 8;
$rank['twink'] = 7;
$rank['trial'] = 6;
$rank['member'] = 5;
$rank['epgp'] = 4;
$rank['offitwink'] = 3;
$rank['offi'] = 2;
$rank['gildenrat'] = 1;
$rank['gildenmeister'] = 0;

$rerank = array("Sardra" => $rank['friends']);

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

// Raidzone Ids - comment out raids you don't want to see
$WGPConfig['Raids'] = array(
	6738, // Siege of Orgrimmar
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
