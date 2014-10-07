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


// Converttimestamp
function convertTimestamp($timestamp) {
	$timestamp = $timestamp / 1000;
	return $timestamp;
}


// Ränge
function getRang($rang, $raenge) {
	if($rang == $raenge['gildenmeister'] || $rang == $raenge['gildenrat'] || $rang == $raenge['gildenmama']) return '<b>Gildenrat</b>';
	switch($rang) {
		case $raenge['member']:
			return 'Member';
		break;
		case $raenge['raidlead']:
			return '<b>Raidlead</b>';
		break;
		case $raenge['klassenleiter']:
			return '<b>Klassenleiter</b>';
		break;
		case $raenge['initiant']:
			return 'Initiant';
		break;
		case $raenge['offi']:
			return 'Offi';
		break;
		case $raenge['twink']:
			return 'Twink';
		break;
		case $raenge['trial']:
			return 'Trial';
		break;
		case $raenge['friends']:
			return 'Friend';
		break;
		case $raenge['gildenbank']:
			return 'Gildenbank';
		break;
		default: return 'Unkown';
	}
}
