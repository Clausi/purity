<?php

// Eindeutige Id eines Charakters (a la wowhead-tooltips)
function generateKey($name, $realm='Anetheron', $region='EU'){
	$name = strtolower(str_replace(' ', '', $name));
	$realm = strtolower(str_replace(' ', '', $realm));
	$region = strtolower($region);
	return md5($name . $realm . $region);
}


function getNameByUniquekey($uniquekey) {
	global $db, $TableNames;
	$query = "SELECT name FROM ".$TableNames['roster']." WHERE uniquekey = '".$uniquekey."' LIMIT 1";
	$result = $db->sql_query($query);
	$row = mysql_fetch_array($result);
	return $row['name'];
}


function GetRole($char) {
	global $guildsql, $TableNames, $armory;
	$CharacterData = $armory->getCharacter($char);
	$Talents = $CharacterData->getTalents();
	if(count($Talents) > 0) {
		foreach($Talents as $Spec) {
			if($Spec['selected'] == 1) {
				$activerole = $Spec['spec']['role'];
				//echo "\n".$activerole."\n";
				$query = "UPDATE " . $TableNames['roster'] . " SET activerole = '".$activerole."' WHERE uniquekey = '".$uniquekey."'";
				$result = mysql_query($query, $guildsql) or die(mysql_error());
			}
		}
	}
	return $activerole;
}


function getRoleBySpec($spec) {
	$Role = array('tank' => 'tank',
				'balance' => 'damage',
				'feral-tank' => 'tank',
				'all' => 'damage',
				'protection' => 'tank',
				'dd' =>	'damage',
				'any' => 'damage',
				'healer' => 'heal',
				'holy' => 'heal',
				'retribution' => 'damage',
				'elemental' => 'damage',
				'enhancement' => 'damage',
				'restoration' => 'heal',
			);
	return $Role[strtolower($spec)];
}


function getGermanRole($role) {
	$Roles = array(
			'all' 	=> 'alle',
			
			// Tanks
			'tank' 	=> 'Schutz',
			'feral-tank'	=> 'Feral-Tank',
			'protection'	=> 'Schutz',
			
			// Heiler
			'heal' 	=> 'Heilung',
			'healer'	=> 'Heilung',
			
			//Schaden
			'damage'	=> 'Schaden',
			'dd'	=> 'Schaden',
			'feral-dd' => 'Feral-Schaden',
			'elemental'	=> 'Elementar',
			'retribution'	=> 'Vergeltung',
		);
	if(array_key_exists(strtolower($role), $Roles))	return $Roles[strtolower($role)];
	else return $role;
}


function getGermanClass($class) {
	$GermanClass = array('deathknight' => 'Todesritter',
				'druid' => 'Druide',
				'hunter' => 'Jäger',
				'mage' => 'Magier',
				'monk' => 'Mönch',
				'paladin' => 'Paladin',
				'priest' => 'Priester',
				'rogue' => 'Schurke',
				'shaman' => 'Schamane',
				'warlock' => 'Hexenmeister',
				'warrior' => 'Krieger',
			);
	return $GermanClass[strtolower($class)];
}

function getClassById($class) {
	$EnglishClass = array(
				6 => 'deathknight',
				11 => 'druid',
				3 => 'hunter',
				8 => 'mage',
				10 => 'monk',
				2 => 'paladin',
				5 => 'priest',
				4 => 'rogue',
				7 => 'shaman',
				9 => 'warlock',
				1 => 'warrior',
			);
	return $EnglishClass[$class];
}

function getRaceById($race) {
	$EnglishRace = array(
				1 => 'human',
				2 => 'orc',
				3 => 'dwarf',
				4 => 'nightelf',
				5 => 'undead',
				6 => 'tauren',
				7 => 'gnome',
				8 => 'troll',
				10 => 'bloodelf',
				11 => 'draenei',
				24 => 'pandaren',
			);
	return $EnglishRace[$race];
}

function getGermanRace($race) {
	$EnglishRace = array(
				'human' => 'Mensch',
				'orc' => 'Ork',
				'dwarf' => 'Zwerg',
				'nightelf' => 'Nachtelf',
				'undead' => 'Untot',
				'tauren' => 'Trauren',
				'gnome' => 'Gnom',
				'troll' => 'Troll',
				'bloodelf' => 'Blutelf',
				'draenei' => 'Draenei',
				'pandaren' => 'Pandaren',
			);
	return $EnglishRace[$race];
}


// Converttimestamp
function convertTimestamp($timestamp) {
	$timestamp = $timestamp / 1000;
	return $timestamp;
}

// Ränge
function getRank($rank, $ranks) {
	if($rank == $ranks['gildenmeister']) return '<strong>Gildenmeister</strong>';
	switch($rank) {
		case $ranks['member']:
			return 'Member';
		break;
		case $ranks['epgp']:
			return 'Member';
		break;
		case $ranks['inaktiv']:
			return 'Inaktiv';
		break;
		case $ranks['raidlead']:
			return '<strong>Raidlead</strong>';
		break;
		case $ranks['klassenleiter']:
			return '<strong>Klassenleiter</strong>';
		break;
		case $ranks['initiant']:
			return 'Initiant';
		break;
		case $ranks['offi']:
			return 'Offi';
		break;
		case $ranks['twink']:
			return 'Twink';
		break;
		case $ranks['trial']:
			return 'Trial';
		break;
		case $ranks['friends']:
			return 'Friend';
		break;
		case $ranks['offitwink']:
			return 'Offitwink';
		break;
		case $ranks['gildenbank']:
			return 'Gildenbank';
		break;
		default: return 'Unkown';
	}
}
