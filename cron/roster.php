<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

include($phpbb_root_path . 'guild/includes/constants.' . $phpEx);
include($phpbb_root_path . 'guild/includes/functions.' . $phpEx);
include($phpbb_root_path . 'guild/includes/wowarmoryapi.' . $phpEx);

$armory = new BattlenetArmory($GuildRegion, $GuildRealm);
$armory->setLocale($armoryLocale);
$guild = $armory->getGuild($GuildName);
$members = $guild->getMembers();

echo "<pre>";
print_r($members);
echo "</pre>";

foreach($members as $char)
{
	$character = $char['character'];
	$query = "INSERT INTO
					" . $TableNames['roster'] . "
				SET
					uniquekey = '".generateKey($character['name'], mysql_real_escape_string($character['realm']), "EU") ."',
					name = '".mysql_real_escape_string($character['name'])."',
					realm = '".mysql_real_escape_string($character['realm'])."',
					battlegroup = '".mysql_real_escape_string($character['battlegroup'])."',
					guild = '".mysql_real_escape_string($character['guild'])."',
					guildRealm = '".mysql_real_escape_string($character['guildRealm'])."',
					rank = '".$char['rank']."',
					class = '".$character['class']."', 
					race = '".$character['race']."',
					gender = '".$character['gender']."',
					level = '".$character['level']."',
					achievementPoints = '".$character['achievementPoints']."',
					thumbnail = '".mysql_real_escape_string($character['thumbnail'])."',
					thumbnailURL = '".mysql_real_escape_string($character['thumbnailURL'])."',
					active = '1',
					firstseen = NOW(),
					cache = NOW()
				ON DUPLICATE KEY UPDATE
					realm = '".mysql_real_escape_string($character['realm'])."',
					battlegroup = '".mysql_real_escape_string($character['battlegroup'])."',
					guild = '".mysql_real_escape_string($character['guild'])."',
					guildRealm = '".mysql_real_escape_string($character['guildRealm'])."',
					rank = '".$char['rank']."',
					class = '".$character['class']."', 
					race = '".$character['race']."',
					gender = '".$character['gender']."',
					level = '".$character['level']."',
					achievementPoints = '".$character['achievementPoints']."',
					thumbnail = '".mysql_real_escape_string($character['thumbnail'])."',
					thumbnailURL = '".mysql_real_escape_string($character['thumbnailURL'])."',
					active = '1',
					cache = NOW()
				";
	$result = $db->sql_query($query);
}
$db->sql_freeresult($result);

// Deactivate characters not seen last 12 hours
$query = "UPDATE " . $TableNames['roster'] . " SET active = 0 WHERE cache < DATE_SUB(NOW(),INTERVAL 12 HOUR)";
$result = $db->sql_query($query);
