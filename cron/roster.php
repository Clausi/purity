<?php

include(dirname(__FILE__) . "/../includes/database.php");
include(dirname(__FILE__) . "/../includes/constants.php");
include(dirname(__FILE__) . "/../includes/functions.php");
include(dirname(__FILE__) . "/../includes/wowarmoryapi.php");


$armory = new BattlenetArmory($GuildRegion, $GuildRealm); 
$guild = $armory->getGuild($GuildName);
$armory->setLocale('de_DE');
/*
// Get guild data
$GuildArray = $guild->getData();

echo "<pre>";
print_r($GuildArray);
echo "</pre>";

// Armory ist erreichbar, wenn ein Array zurückgegeben wurde
if(is_array($GuildArray)) 
{
	// Gilde
	foreach($GuildArray as $key => $value) 
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
			$result = mysql_query($query, $guildsql);
		}
	}
	foreach($GuildArray['members'] as $char)
	{
		$character = $char['character'];
		$query = "INSERT INTO
						" . $TableNames['roster'] . "
					SET
						uniquekey = '".generateKey($character['name'], mysql_real_escape_string($character['realm']), "EU") ."',
						name = '".mysql_real_escape_string($character['name'])."',
						realm = '".mysql_real_escape_string($character['realm'])."',
						rank = '".$char['rank']."',
						class = '".$character['class']."', 
						race = '".$character['race']."',
						gender = '".$character['gender']."',
						level = '".$character['level']."',
						achievementPoints = '".$character['achievementPoints']."',
						thumbnail = '".$character['thumbnail']."', 
						active = '1',
						firstseen = UNIX_TIMESTAMP(NOW()),
						cache = UNIX_TIMESTAMP(NOW())
					ON DUPLICATE KEY UPDATE
						realm = '".mysql_real_escape_string($character['realm'])."',
						rank = '".$char['rank']."',
						class = '".$character['class']."', 
						race = '".$character['race']."',
						gender = '".$character['gender']."',
						level = '".$character['level']."',
						achievementPoints = '".$character['achievementPoints']."',
						thumbnail = '".$character['thumbnail']."', 
						active = '1',
						cache = UNIX_TIMESTAMP(NOW())
					";
		$result = mysql_query($query, $guildsql) or die(mysql_error());
	}
	// Deaktivieren aller Charakter die älter als 1/2 Tag sind
	$query = "UPDATE " . $TableNames['roster'] . " SET active = 0 WHERE cache < '".(time()-(60*60*12))."'";
	$result = mysql_query($query, $guildsql) or die(mysql_error());
}
else
{
	echo "Kein Array";
}
*/