<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

include($phpbb_root_path . 'guild/includes/constants.' . $phpEx);
include($phpbb_root_path . 'guild/includes/functions.' . $phpEx);
include($phpbb_root_path . 'guild/includes/wowarmoryapi.' . $phpEx);


$query = "SELECT uniquekey, name, realm FROM " . $TableNames['roster'] . " WHERE active = '1' ORDER BY lastupdate ASC LIMIT 1";
$result = $db->sql_query($query);
$row = $db->sql_fetchrow($result);
$Character = $row['name'];
echo $uniquekey = $row['uniquekey'];
echo "<br />";
echo $Character;
echo "<br />";

$realm = $row['realm'];
if($realm == NULL) $realm = $GuildRealm;

echo $realm;
echo "<br />";
echo $GuildRegion;

$armory = new BattlenetArmory($GuildRegion, $realm);
$armory->setLocale($armoryLocale);
$armory->UTF8(TRUE);
$armory->setCharactersCacheTTL($WGPConfig['Cache']);

$CharacterData = $armory->getCharacter($Character);
$Raidprogress = $CharacterData->getRaidStats('desc');

if( ! is_array($Raidprogress)) 
{
	$query = "UPDATE " . $TableNames['roster'] . " SET lastupdate = NOW() WHERE uniquekey = '".$uniquekey."'";
	$result = $db->sql_query($query);
	trigger_error("No raidprogress array, character not reachable", E_USER_ERROR);
	exit;
}

echo "<pre>";
print_r($Raidprogress);
echo "</pre>";

foreach($Raidprogress as $raid) {
	if($raid['id'] != 0) {
		// progress db
		$flexbosses = $raid['totalbosses'];
		$totalbosses = $raid['totalbosses'];
		$totalhcbosses = $raid['totalbosses'];
		if(array_key_exists($raid['id'], $WGPConfig['Fix']) == true) {
			$flexbosses = $flexbosses + $WGPConfig['Fix'][$raid['id']][2];
			$totalbosses = $totalbosses + $WGPConfig['Fix'][$raid['id']][0];
			$totalhcbosses = $totalhcbosses + $WGPConfig['Fix'][$raid['id']][1];
		}
		$query = "INSERT INTO 
					".$TableNames['progress']."
				SET
					raidid = '".$raid['id']."',
					raidname = '".$db->sql_escape($raid['name'])."',
					flexbosses = '".$flexbosses."',
					totalbosses = '".$totalbosses."',
					totalhcbosses = '".$totalhcbosses."'
				ON DUPLICATE KEY UPDATE
					raidname = '".$db->sql_escape($raid['name'])."',
					flexbosses = '".$flexbosses."',
					totalbosses = '".$totalbosses."',
					totalhcbosses = '".$totalhcbosses."'
				";
		$result = $db->sql_query($query);
		
		// Characterprogress db
		foreach($raid['bosses'] as $boss) {
			$bossname = $db->sql_escape($boss['name']);
			if($boss['id'] == 0 || !$boss['id']) $bossid = $WGPConfig['noIdBoss'][$bossname];
			else $bossid = $boss['id'];
			$bossname = $db->sql_escape($bossname);
			$query = "SELECT COUNT(id) AS count_id FROM " . $TableNames['characterprogress'] . " 
					WHERE 
						uniquekey = '".$uniquekey."' AND 
						raidid = '".$raid['id']."' AND 
						bossid = '".$bossid."'
					LIMIT 1";
			$result = $db->sql_query($query);
			
			if(!$boss['lfrKills']) !$boss['lfrKills'] = 0;
			if(!$boss['flexKills']) !$boss['flexKills'] = 0;
			if(!$boss['normalKills']) !$boss['normalKills'] = 0;
			if(!$boss['heroicKills']) !$boss['heroicKills'] = 0;
			
			if($db->sql_fetchfield('count_id') == 0)
			{
				$insertQuery = "INSERT INTO 
							" . $TableNames['characterprogress'] . "
						SET
							uniquekey = '".$uniquekey."',
							raidid = '".$raid['id']."',
							bossid = '".$bossid."',
							bossname = '".$bossname."',
							lfrKills = '".$boss['lfrKills']."',
							lfrFirstkill = '".convertTimestamp($boss['lfrTimestamp'])."',
							flexKills = '".$boss['flexKills']."',
							flexFirstkill = '".convertTimestamp($boss['flexTimestamp'])."',
							normalKills = '".$boss['normalKills']."',
							normalFirstkill = '".convertTimestamp($boss['normalTimestamp'])."',
							heroicKills = '".$boss['heroicKills']."',
							heroicFirstkill = '".convertTimestamp($boss['heroicTimestamp'])."'
						";
				$insertResult = $db->sql_query($insertQuery);
			}
			else {
				$query = "SELECT id FROM " . $TableNames['characterprogress'] . " 
					WHERE 
						uniquekey = '".$uniquekey."' AND 
						raidid = '".$raid['id']."' AND 
						bossid = '".$bossid."'
					LIMIT 1";
				$result = $db->sql_query($query);
				$row = $db->sql_fetchrow($result);
				
				$updateQuery = "UPDATE
							" . $TableNames['characterprogress'] . "
						SET
							raidid = '".$raid['id']."',
							bossid = '".$bossid."',
							bossname = '".$bossname."',
							lfrKills = '".$boss['lfrKills']."',
							lfrFirstkill = CASE WHEN (lfrFirstkill < '".convertTimestamp($boss['lfrTimestamp'])."' AND lfrFirstkill > 0) OR '".convertTimestamp($boss['lfrTimestamp'])."' = 0 THEN lfrFirstkill ELSE '".convertTimestamp($boss['lfrTimestamp'])."' END,
							flexKills = '".$boss['flexKills']."',
							flexFirstkill = CASE WHEN (flexFirstkill < '".convertTimestamp($boss['flexTimestamp'])."' AND flexFirstkill > 0) OR '".convertTimestamp($boss['flexTimestamp'])."' = 0 THEN flexFirstkill ELSE '".convertTimestamp($boss['flexTimestamp'])."' END,
							normalKills = '".$boss['normalKills']."',
							normalFirstkill = CASE WHEN (normalFirstkill < '".convertTimestamp($boss['normalTimestamp'])."' AND normalFirstkill > 0) OR '".convertTimestamp($boss['normalTimestamp'])."' = 0 THEN normalFirstkill ELSE '".convertTimestamp($boss['normalTimestamp'])."' END,
							heroicKills = '".$boss['heroicKills']."',
							heroicFirstkill = CASE WHEN (heroicFirstkill < '".convertTimestamp($boss['heroicTimestamp'])."' AND heroicFirstkill > 0) OR '".convertTimestamp($boss['heroicTimestamp'])."' = 0 THEN heroicFirstkill ELSE '".convertTimestamp($boss['heroicTimestamp'])."' END
						WHERE
							id = '".$row['id']."' AND uniquekey = '".$uniquekey."'
						";
				$updateResult = $db->sql_query($updateQuery);
			}
		}
	}
}

$query = "UPDATE " . $TableNames['roster'] . " SET lastupdate = NOW() WHERE uniquekey = '".$uniquekey."'";
$result = $db->sql_query($query);

// Begin combine progress

$modes = array("flex", "normal", "heroic");

$query = "SELECT * FROM ".$TableNames['progress']." ORDER BY raidid";
$result = $db->sql_query($query);

// Get all Killdates and Killcounts
$raidprogress = array();
while($row = $db->sql_fetchrow($result)) {
	if(in_array($row['raidid'], $WGPConfig['Freeze']) == false) { // Exclude freezed raidzones
		$raidprogress[$row['raidid']] = array();
		$raidprogress[$row['raidid']]['id'] = $row['raidid'];
		$raidprogress[$row['raidid']]['name'] = $row['raidname'];
		$raidprogress[$row['raidid']]['bosses'] = array();
		$charQuery = "SELECT * FROM ".$TableNames['characterprogress']." WHERE raidid = '".$row['raidid']."' ORDER BY id";
		$charResult = $db->sql_query($charQuery);
		while($charRow = $db->sql_fetchrow($charResult)) {
			$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']]['id'] = $charRow['bossid'];
			$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']]['name'] = $charRow['bossname'];
			foreach($modes as $mode) {
				if($charRow[$mode.'Kills'] > 0) {
					$day = date("j", $charRow[$mode.'Firstkill']);
					$month = date("n", $charRow[$mode.'Firstkill']);
					$year = date("Y", $charRow[$mode.'Firstkill']);
					$timestampDay = mktime(0, 0, 0, $month, $day, $year);
					if(is_array($raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills']) == false) $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'] = array();
					
					$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] = $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] + 1;
				}
			}
		}
	}
}

/*
echo "<pre>";
print_r($raidprogress);
echo "</pre>";
*/

// Combine Bosskills
$raidresult = array();
foreach($raidprogress as $raid) {
	$raidresult[$raid['id']] = array();
	$raidresult[$raid['id']]['id'] = $raid['id'];
	$raidresult[$raid['id']]['bosses'] = array();
	foreach($raid['bosses'] as $boss) {
		$raidresult[$raid['id']]['bosses'][$boss['id']] = array();
		$raidresult[$raid['id']]['bosses'][$boss['id']]['id'] = $boss['id'];
		$raidresult[$raid['id']]['bosses'][$boss['id']]['name'] = $boss['name'];
		foreach($modes as $mode) {
			if(is_array($boss[$mode.'Kills']) == true) {
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode] = array();
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] = 0;
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count'] = 0;
				
				while($kill = current($boss[$mode.'Kills'])){
					if((key($boss[$mode.'Kills']) < $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] 
							&& $kill >= $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count']) 
							|| $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] == 0){
						$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] = key($boss[$mode.'Kills']);
						$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count'] = $kill;
					}
					next($boss[$mode.'Kills']);
				}
			}
		}
	}
}
/*
echo "<pre>";
print_r($raidresult);
echo "</pre>";
*/

foreach($raidresult as $raid){
	$flexKills = 0;
	$normalKills = 0;
	$heroicKills = 0;
	foreach($raid['bosses'] as $boss) {
		foreach($modes as $mode){
			if($mode == 'normal') $heroic = 0;
			else if($mode == 'flex') $heroic = 2;
			else $heroic = 1;
			if(is_array($boss[$mode]) == true) {
				if($boss[$mode]['count'] >= $WGPConfig['CharacterMatch']) {
					$query = "SELECT COUNT(id) as count_id FROM " .$TableNames['progressbosses']. " WHERE bossid = '".$boss['id']."' AND heroic='".$heroic."' LIMIT 1";
					$result = $db->sql_query($query);
					if($db->sql_fetchfield('count_id') == 0) {
						$insertQuery = "INSERT INTO " .$TableNames['progressbosses']. " SET
										bossid = '".$boss['id']."',
										raidid = '".$raid['id']."',
										heroic = '".$heroic."',
										name = '".$db->sql_escape($boss['name'])."',
										killdate = '".$boss[$mode]['time']."'";
						$insertResult = $db->sql_query($insertQuery);
					}

					if($mode == 'normal') $normalKills++;
					else if($mode == 'flex') $flexKills++;
					else $heroicKills++;
				}
			}
		}
	}
	if(in_array($raid['id'], $WGPConfig['Raids']) == true) $active = 1;
	else $active = 0;
	$updateQuery = "UPDATE " .$TableNames['progress']. " SET
						active = '".$active."',
						flexbosseskilled = CASE
							WHEN flexbosseskilled > '".$flexKills."' THEN flexbosseskilled
							ELSE '".$flexKills."'
							END,
						bosseskilled = CASE
							WHEN bosseskilled > '".$normalKills."' THEN bosseskilled
							ELSE '".$normalKills."'
							END,
						hcbosseskilled = CASE
							WHEN hcbosseskilled > '".$heroicKills."' THEN hcbosseskilled
							ELSE '".$heroicKills."'
							END
					WHERE
						raidid = '".$raid['id']."'
					";
	$updateResult = $db->sql_query($updateQuery);
}

// Akt-/Deaktivieren von Raids
$query = "SELECT raidid FROM " . $TableNames['progress'] . "";
$result = $db->sql_query($query);
while($row = $db->sql_fetchrow($result)) {
	if(in_array($row['raidid'], $WGPConfig['Raids']) == true) $active = 1;
	else $active = 0;
	$query2 = "UPDATE " . $TableNames['progress'] . " SET active = '".$active."' WHERE raidid = '". $row['raidid'] ."'";
	$result2 = $db->sql_query($query2);
}
