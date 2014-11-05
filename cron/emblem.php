<?php

define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

include($phpbb_root_path . 'guild/includes/constants.' . $phpEx);
include($phpbb_root_path . 'guild/includes/functions.' . $phpEx);
include($phpbb_root_path . 'guild/includes/wowarmoryapi.' . $phpEx);

$armory = new BattlenetArmory($GuildRegion, $GuildRealm);
// $armory->setGuildsCacheTTL(1);
$armory->setLocale($armoryLocale);
// $armory->debug('emblem', true);
$guild = $armory->getGuild($GuildName);
$guildData = $guild->getData();
// echo "<pre>";
// print_r($guildData);
// echo "</pre>";
if( ! is_array($guildData)) 
{
	trigger_error("No guild array, armory not reachable", E_USER_ERROR);
	exit;
}

$guild->showEmblem(false, 215);
$guild->saveEmblem($phpbb_root_path . 'guild/'. $GuildRegion .'_'. $GuildRealm .'_'. $GuildName .'.png');
