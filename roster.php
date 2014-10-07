<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$query = "SELECT * FROM ".$TableNames['roster']." WHERE active = '1' ORDER BY name";
$result = $db->sql_query($query);

while ($row = $db->sql_fetchrow($result))
{
	$template->assign_block_vars('n_members', array(
		'NAME' => $row['name'],
		'RANK' => $row['rank'],
		'RANKNAME' => getRank($row['rank'], $ranks),
		'LEVEL'	=> $row['level'],
		'ILEVEL' => $row['iLevel'],
		'CLASS'	=> $row['class'],
		'RACE' => $row['race'],
		'GENDER' => $row['gender'],
		'ACHIEVEMENTPOINTS' => $row['achievementPoints'],
	));
}

$db->sql_freeresult($result);
