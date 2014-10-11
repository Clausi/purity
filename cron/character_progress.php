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
if($realm == NULL) $member[$i]['realm'] = $GuildRealm;

$armory = new BattlenetArmory($GuildRegion, $realm);
$armory->setLocale($armoryLocale);
$armory->UTF8(TRUE);
$armory->setCharactersCacheTTL($WGPConfig['Cache']);

$CharacterData = $armory->getCharacter($Character);
$Raidprogress = $CharacterData->getRaidStats('desc');

/*
echo "<pre>";
print_r($Raidprogress);
echo "</pre>";
*/
foreach($Raidprogress as $raid) {
	if($raid['id'] != 0) {
		// progress2 db
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
		$result = mysql_query($query, $guildsql) or die(mysql_error());
		
		// Characterprogress db
		foreach($raid['bosses'] as $boss) {
			$bossname = utf8_decode($boss['name']);
			if($boss['id'] == 0) $bossid = $WGPConfig['noIdBoss'][$bossname];
			else $bossid = $boss['id'];
			$bossname = $db->sql_escape($bossname);
			$query = "SELECT id FROM " . $TableNames['characterprogress'] . " 
					WHERE 
						uniquekey = '".$uniquekey."' AND 
						raidid = '".$raid['id']."' AND 
						bossid = '".$bossid."'
					LIMIT 1";
			$result = mysql_query($query, $guildsql) or die(mysql_error());
			if(mysql_num_rows($result) == 0) {
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
				$insertResult = mysql_query($insertQuery, $guildsql) or die(mysql_error());
			}
			else {
				$row = mysql_fetch_array($result);
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
				$updateResult = mysql_query($updateQuery, $guildsql) or die(mysql_error());
			}
		}
	}
}

$query = "UPDATE " . $TableNames['roster'] . " SET lastupdate = UNIX_TIMESTAMP(NOW()) WHERE uniquekey = '".$uniquekey."'";
$result = mysql_query($query, $guildsql) or die(mysql_error());

//include_once(dirname(__FILE__) ."/combine_progress.php");
