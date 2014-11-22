<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$query = "SELECT * FROM ". $TableNames['progress'] ." WHERE active = '1' ORDER BY raidid DESC";
$result = $db->sql_query($query);

while($raid = $db->sql_fetchrow($result)) {
	if($raid['hcbosses'] > 0) $prozentHc = round($raid['hcbosseskilled'] / $raid['hcbosses'] * 100);
	else $prozentHc = 0;
	if($raid['mythicbosses'] > 0) $prozentMythic = round($raid['mythicbosseskilled'] / $raid['mythicbosses'] * 100);
	else $prozentMythic = 0;
	
	$template->assign_block_vars('n_raidprogress', array(
		'RAIDID'		=> $raid['raidid'],
		'RAIDNAME'	=> $raid['raidname'],
		'HCBOSSESKILLED'	=> $raid['hcbosseskilled'],
		'MYTHICBOSSESKILLED' => $raid['mythicbosseskilled'],
		'HCBOSSES'	=> $raid['hcbosses'],
		'MYTHICBOSSES'	=> $raid['mythicbosses'],
		'PROZENTHC'	=> $prozentHc,
		'PROZENTMYTHIC' => $prozentMythic,
	));
	
	$query = "SELECT * FROM ". $TableNames['progressbosses'] ." WHERE raidid = '". $raid['raidid'] ."' ORDER BY killdate, id, bossid, raidid, name";
	$result2 = $db->sql_query($query);
	
	while($boss = $db->sql_fetchrow($result2)) {
		if($boss['mode'] == 2) {
			$template->assign_block_vars('n_raidprogress.n_hckills', array(
				'BOSSNAME' => $boss['name'],
				'KILLDATE' => date('d.m.Y', $boss['killdate']),
			));
		}
		else if ($boss['mode'] == 3) {
			$template->assign_block_vars('n_raidprogress.n_mythickills', array(
				'BOSSNAME' => $boss['name'],
				'KILLDATE' => date('d.m.Y', $boss['killdate']),
			));
		}
	}
	$db->sql_freeresult($result2);
	
}
$db->sql_freeresult($result);
