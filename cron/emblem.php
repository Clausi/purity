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
$guild->showEmblem();
$guild->saveEmblem($phpbb_root_path . 'guild/'. $GuildRegion .'_'. $GuildRealm .'_'. $GuildName .'.png');
