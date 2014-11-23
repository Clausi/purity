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
//$armory->UTF8(TRUE);
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

// echo "<pre>";
// print_r($Raidprogress);
// echo "</pre>";

foreach($Raidprogress as $raid) {
	if($raid['id'] != 0) {
		// progress db
		// $lfrbosses = $raid['lfr'];
		// $nhbosses = $raid['normal'];
		// $hcbosses = $raid['heroic'];
		// $mythicbosses = $raid['mythic'];
		$lfrbosses = $nhbosses = $hcbosses = $mythicbosses = count($raid['bosses']);

		$query = "INSERT INTO 
					".$TableNames['progress']."
				SET
					raidid = '".$raid['id']."',
					raidname = '".$db->sql_escape($raid['name'])."',
					lfrbosses = '".$lfrbosses."',
					nhbosses = '".$nhbosses."',
					hcbosses = '".$hcbosses."',
					mythicbosses = '".$mythicbosses."'
				ON DUPLICATE KEY UPDATE
					raidname = '".$db->sql_escape($raid['name'])."',
					lfrbosses = '".$lfrbosses."',
					nhbosses = '".$nhbosses."',
					hcbosses = '".$hcbosses."',
					mythicbosses = '".$mythicbosses."'
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
			
			if(empty($boss['lfrKills'])) $boss['lfrKills'] = 0;
			if(empty($boss['normalKills'])) $boss['normalKills'] = 0;
			if(empty($boss['heroicKills'])) $boss['heroicKills'] = 0;
			if(empty($boss['mythicKills'])) $boss['mythicKills'] = 0;
			
			if(!empty($boss['lfrTimestamp'])) $lfrTimestamp = convertTimestamp($boss['lfrTimestamp']);
			else $lfrTimestamp = 0;
			if(!empty($boss['normalTimestamp'])) $normalTimestamp = convertTimestamp($boss['normalTimestamp']);
			else $normalTimestamp = 0;
			if(!empty($boss['heroicTimestamp'])) $heroicTimestamp = convertTimestamp($boss['heroicTimestamp']);
			else $heroicTimestamp = 0;
			if(!empty($boss['mythicTimestamp'])) $mythicTimestamp = convertTimestamp($boss['mythicTimestamp']);
			else $mythicTimestamp = 0;
			
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
							lfrFirstkill = '".$lfrTimestamp."',
							normalKills = '".$boss['normalKills']."',
							normalFirstkill = '".$normalTimestamp."',
							heroicKills = '".$boss['heroicKills']."',
							heroicFirstkill = '".$heroicTimestamp."',
							mythicKills = '".$boss['mythicKills']."',
							mythicFirstkill = '".$mythicTimestamp."'
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
							lfrFirstkill = CASE WHEN (lfrFirstkill < '".$lfrTimestamp."' AND lfrFirstkill > 0) OR '".$lfrTimestamp."' = 0 THEN lfrFirstkill ELSE '".$lfrTimestamp."' END,
							normalKills = '".$boss['normalKills']."',
							normalFirstkill = CASE WHEN (normalFirstkill < '".$normalTimestamp."' AND normalFirstkill > 0) OR '".$normalTimestamp."' = 0 THEN normalFirstkill ELSE '".$normalTimestamp."' END,
							heroicKills = '".$boss['heroicKills']."',
							heroicFirstkill = CASE WHEN (heroicFirstkill < '".$heroicTimestamp."' AND heroicFirstkill > 0) OR '".$heroicTimestamp."' = 0 THEN heroicFirstkill ELSE '".$heroicTimestamp."' END,
							mythicKills = '".$boss['mythicKills']."',
							mythicFirstkill = CASE WHEN (mythicFirstkill < '".$mythicTimestamp."' AND mythicFirstkill > 0) OR '".$mythicTimestamp."' = 0 THEN mythicFirstkill ELSE '".$mythicTimestamp."' END
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

$modes = array("lfr", "normal", "heroic", 'mythic');

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
					if( ! array_key_exists($mode.'Kills', $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']]))
					{
						$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'] = array();
					}
					
					if(is_array($raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills']))
					{
						if( ! isset($raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay]))
						{
							$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] = 0;
						}
						$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] = $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] + 1;
					}
				}
			}
		}
	}
}

echo "<pre>";
print_r($raidprogress);
echo "</pre>";

// Combine Bosskills
$raidresult = array();
foreach($raidprogress as $raid) 
{
	$raidresult[$raid['id']] = array();
	$raidresult[$raid['id']]['id'] = $raid['id'];
	$raidresult[$raid['id']]['bosses'] = array();
	foreach($raid['bosses'] as $boss) 
	{
		$raidresult[$raid['id']]['bosses'][$boss['id']] = array();
		$raidresult[$raid['id']]['bosses'][$boss['id']]['id'] = $boss['id'];
		$raidresult[$raid['id']]['bosses'][$boss['id']]['name'] = $boss['name'];
		foreach($modes as $mode)
		{
			if(array_key_exists($mode.'Kills', $boss))
			{
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode] = array();
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] = 0;
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count'] = 0;
				
				while($kill = current($boss[$mode.'Kills']))
				{
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


// echo "<pre>";
// print_r($raidresult);
// echo "</pre>";


foreach($raidresult as $raid){
	$lfrKills = 0;
	$normalKills = 0;
	$heroicKills = 0;
	$mythicKills = 0;
	foreach($raid['bosses'] as $boss) {
		foreach($modes as $mode){
			switch($mode) {
				case 'lfr':
					$modeId = 0;
				break;
				case 'normal':
					$modeId = 1;
				break;
				case 'heroic':
					$modeId = 2;
				break;
				case 'mythic':
					$modeId = 3;
				break;
			}

			if(array_key_exists($mode, $boss)) {
				if($boss[$mode]['count'] >= $WGPConfig['CharacterMatch']) {
					$query = "SELECT COUNT(id) as count_id FROM " .$TableNames['progressbosses']. " WHERE bossid = '".$boss['id']."' AND mode='".$mode."' LIMIT 1";
					$result = $db->sql_query($query);
					if($db->sql_fetchfield('count_id') == 0) {
						$insertQuery = "INSERT INTO " .$TableNames['progressbosses']. " SET
										bossid = '".$boss['id']."',
										raidid = '".$raid['id']."',
										mode = '".$modeId."',
										name = '".$db->sql_escape($boss['name'])."',
										killdate = '".$boss[$mode]['time']."'";
						$insertResult = $db->sql_query($insertQuery);
					}

					switch($mode) {
						case 'lfr':
							$lfrKills++;
						break;
						case 'normal':
							$normalKills++;
						break;
						case 'heroic':
							$heroicKills++;
						break;
						case 'mythic':
							$mythicKills++;
						break;
					}
				}
			}
		}
	}
	if(in_array($raid['id'], $WGPConfig['Raids']) == true) $active = 1;
	else $active = 0;
	$updateQuery = "UPDATE " .$TableNames['progress']. " SET
						active = '".$active."',
						lfrbosseskilled = CASE
							WHEN lfrbosseskilled > '".$lfrKills."' THEN lfrbosseskilled
							ELSE '".$lfrKills."'
							END,
						nhbosseskilled = CASE
							WHEN nhbosseskilled > '".$normalKills."' THEN nhbosseskilled
							ELSE '".$normalKills."'
							END,
						hcbosseskilled = CASE
							WHEN hcbosseskilled > '".$heroicKills."' THEN hcbosseskilled
							ELSE '".$heroicKills."'
							END,
						mythicbosseskilled = CASE
							WHEN mythicbosseskilled > '".$mythicKills."' THEN mythicbosseskilled
							ELSE '".$mythicKills."'
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
