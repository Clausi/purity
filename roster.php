<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$query = "SELECT * FROM ".$TableNames['roster']." ORDER BY name";
$result = $db->sql_query($query);

while ($row = $db->sql_fetchrow($result))
{
	if($row['active'] == 0)
	{
		$template->assign_block_vars('n_exMembers', array(
			'NAME' => $row['name'],
			'RANKNAME' => getRank($row['rank'], $ranks),
			'LEVEL'	=> $row['level'],
			'ILEVEL' => $row['iLevel'],
			'CLASS'	=> $row['class'],
			'CLASSNAME' => getGermanClass(getClassById($row['class'])),
			'RACE' => $row['race'],
			'RACENAME' => getGermanRace(getRaceById($row['race'])),
			'GENDER' => $row['gender'],
			'LASTSEEN' => $user->format_date(strtotime($row['cache']), false, true)
		));
	}
	else
	{
		$template->assign_block_vars('n_members', array(
			'NAME' => $row['name'],
			'RANK' => $row['rank'],
			'RANKNAME' => getRank($row['rank'], $ranks),
			'LEVEL'	=> $row['level'],
			'ILEVEL' => $row['iLevel'],
			'CLASS'	=> $row['class'],
			'CLASSNAME' => getGermanClass(getClassById($row['class'])),
			'RACE' => $row['race'],
			'RACENAME' => getGermanRace(getRaceById($row['race'])),
			'GENDER' => $row['gender'],
			'ACHIEVEMENTPOINTS' => $row['achievementPoints'],
			'FIRSTSEEN' => $user->format_date(strtotime($row['firstseen']), false, true)
		));
	}
}

$db->sql_freeresult($result);
