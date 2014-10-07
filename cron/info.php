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
$armory->UTF8(TRUE);
$guild = $armory->getGuild($GuildName);
$guildData = $guild->getData();

echo "<pre>";
print_r($guildData);
echo "</pre>";

foreach($guildData as $key => $value) 
{
	if($key != "members" && $key != "emblem" && $key != "achievements") 
	{
		$query = "INSERT INTO
						" . $TableNames['guild'] . "
					SET
						name = '".$key."',
						value = '".$value."'
					ON DUPLICATE KEY UPDATE
						value = '".$value."'
					";
		$result = $db->sql_query($query);
	}
}
