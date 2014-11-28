<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$gear = $CharacterData->getGear();

if( ! is_array($gear)) 
{
	$query = "UPDATE " . $TableNames['roster'] . " SET lastupdate = NOW() WHERE uniquekey = '".$uniquekey."'";
	$result = $db->sql_query($query);
	trigger_error("No gear array, character not reachable", E_USER_ERROR);
	exit;
}

if($gear['averageItemLevelEquipped'] != '')
{
	$query = "UPDATE " . $TableNames['roster'] . " SET iLevel = '".$gear['averageItemLevelEquipped']."' WHERE uniquekey = '".$uniquekey."'";
	$result = $db->sql_query($query);
	echo "<br />ilvl: ".$gear['averageItemLevelEquipped'];
}

// echo "<pre>";
// print_r($gear);
// echo "</pre>";
