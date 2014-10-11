<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$query = "SELECT * FROM ". $TableNames['progress'] ." WHERE active = '1' ORDER BY raidid DESC";
$result = $db->sql_query($query);

$i = 1;
while($raid = mysql_fetch_array($result)) {

	$prozent = round($raid['bosseskilled'] / $raid['totalbosses'] * 100);
	$prozenthc = round($raid['hcbosseskilled'] / $raid['totalhcbosses'] * 100);
	
	$template->assign_block_vars('raidprogress', array(
		'NORMALNUMBER' => 'n'.$i,
		'HEROICNUMBER' => 'h'.$i,
		'RAIDID'		=> $raid['raidid'],
		'RAIDNAME'	=> $raid['raidname'],
		'BOSSESKILLED'	=> $raid['bosseskilled'],
		'HCBOSSESKILLED' => $raid['hcbosseskilled'],
		'TOTALBOSSES'	=> $raid['totalbosses'],
		'TOTALHCBOSSES'	=> $raid['totalhcbosses'],
		'PROZENT'	=> $prozent,
		'PROZENTHC' => $prozenthc,
	));
	$i++;
	
	$query = "SELECT * FROM ". $TableNames['progressbosses'] ." WHERE raidid = '". $raid['raidid'] ."' ORDER BY killdate, id, bossid, raidid, name";
	$result2 = $db->sql_query($query);
	
	while($boss = mysql_fetch_array($result2)) {
		if($boss['heroic'] == 0) {
			$template->assign_block_vars('raidprogress.normalbosskills', array(
				'BOSSNAME' => $boss['name'],
				'KILLDATE' => date('d.m.Y', $boss['killdate']),
			));
		}
		else if ($boss['heroic'] == 1) {
			$template->assign_block_vars('raidprogress.heroicbosskills', array(
				'BOSSNAME' => $boss['name'],
				'KILLDATE' => date('d.m.Y', $boss['killdate']),
			));
		}
	}
	
	$db->sql_freeresult($result2);
}
$db->sql_freeresult($result);

$template->assign_var('S_RAIDPROGRESS',true);
