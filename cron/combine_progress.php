<?php
// Fasst Charakterprogress zu einem Gildenprogress zusammen

include_once(dirname(__FILE__) . "/../includes/db.inc.php");
include_once(dirname(__FILE__) . "/../includes/constants.inc.php");
include_once(dirname(__FILE__) . "/../includes/functions.inc.php");

$modes = array("flex", "normal", "heroic");

$query = "SELECT * FROM ".$TableNames['progress']." ORDER BY raidid";
$result = mysql_query($query, $guildsql) or die(mysql_error());

// Get all Killdates and Killcounts
$raidprogress = array();
while($row = mysql_fetch_array($result)) {
	if(in_array($row['raidid'], $WGPConfig['Freeze']) == false) { // Exclude freezed raidzones
		$raidprogress[$row['raidid']] = array();
		$raidprogress[$row['raidid']]['id'] = $row['raidid'];
		$raidprogress[$row['raidid']]['name'] = $row['raidname'];
		$raidprogress[$row['raidid']]['bosses'] = array();
		$charQuery = "SELECT * FROM ".$TableNames['characterprogress']." WHERE raidid = '".$row['raidid']."' ORDER BY id";
		$charResult = mysql_query($charQuery, $guildsql) or die(mysql_error());
		while($charRow = mysql_fetch_array($charResult)) {
			$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']]['id'] = $charRow['bossid'];
			$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']]['name'] = $charRow['bossname'];
			foreach($modes as $mode) {
				if($charRow[$mode.'Kills'] > 0) {
					$day = date("j", $charRow[$mode.'Firstkill']);
					$month = date("n", $charRow[$mode.'Firstkill']);
					$year = date("Y", $charRow[$mode.'Firstkill']);
					$timestampDay = mktime(0, 0, 0, $month, $day, $year);
					if(is_array($raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills']) == false) $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'] = array();
					
					$raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] = $raidprogress[$row['raidid']]['bosses'][$charRow['bossid']][$mode.'Kills'][$timestampDay] + 1;
				}
			}
		}
	}
}
/*
echo "<pre>";
print_r($raidprogress);
echo "</pre>";
*/
// Combine Bosskills
$raidresult = array();
foreach($raidprogress as $raid) {
	$raidresult[$raid['id']] = array();
	$raidresult[$raid['id']]['id'] = $raid['id'];
	$raidresult[$raid['id']]['bosses'] = array();
	foreach($raid['bosses'] as $boss) {
		$raidresult[$raid['id']]['bosses'][$boss['id']] = array();
		$raidresult[$raid['id']]['bosses'][$boss['id']]['id'] = $boss['id'];
		$raidresult[$raid['id']]['bosses'][$boss['id']]['name'] = $boss['name'];
		foreach($modes as $mode) {
			if(is_array($boss[$mode.'Kills']) == true) {
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode] = array();
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] = 0;
				$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count'] = 0;
				
				while($kill = current($boss[$mode.'Kills'])){
					if((key($boss[$mode.'Kills']) < $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] 
							&& $kill >= $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count']) 
							|| $raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] == 0){
						$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['time'] = key($boss[$mode.'Kills']);
						$raidresult[$raid['id']]['bosses'][$boss['id']][$mode]['count'] = $kill;
					}
					next($boss[$mode.'Kills']);
				}
			}
		}
	}
}
/*
echo "<pre>";
print_r($raidresult);
echo "</pre>";
*/

foreach($raidresult as $raid){
	$flexKills = 0;
	$normalKills = 0;
	$heroicKills = 0;
	foreach($raid['bosses'] as $boss) {
		foreach($modes as $mode){
			if($mode == 'normal') $heroic = 0;
			else if($mode == 'flex') $heroic = 2;
			else $heroic = 1;
			if(is_array($boss[$mode]) == true) {
				if($boss[$mode]['count'] >= $WGPConfig['CharacterMatch']) {
					$query = "SELECT * FROM " .$TableNames['progressbosses']. " WHERE bossid = '".$boss['id']."' AND heroic='".$heroic."' LIMIT 1";
					$result = mysql_query($query, $guildsql) or die(mysql_error());
					if(mysql_num_rows($result) == 0) {
						$insertQuery = "INSERT INTO " .$TableNames['progressbosses']. " SET
										bossid = '".$boss['id']."',
										raidid = '".$raid['id']."',
										heroic = '".$heroic."',
										name = '".mysql_real_escape_string($boss['name'])."',
										killdate = '".$boss[$mode]['time']."'";
						$insertResult = mysql_query($insertQuery, $guildsql) or die(mysql_error());
					}
					/* Battle.net Api no longer shows firstkills, only the last kills timestamp is shown, so we no longer update timestamps of kills seen once before
					else {
						$row = mysql_fetch_array($result);
						if($boss[$mode]['time'] < $row['killdate']) {
							$updateQuery = "UPDATE " .$TableNames['progressbosses']. " SET
											raidid = '".$raid['id']."',
											name = '".mysql_real_escape_string($boss['name'])."',
											killdate = '".$boss[$mode]['time']."'
										WHERE
											bossid = '".$boss['id']."' AND
											heroic = '".$heroic."'";
							$updateResult = mysql_query($updateQuery, $guildsql) or die(mysql_error());
						}
					}
					*/
					if($mode == 'normal') $normalKills++;
					else if($mode == 'flex') $flexKills++;
					else $heroicKills++;
				}
			}
		}
	}
	if(in_array($raid['id'], $WGPConfig['Raids']) == true) $active = 1;
	else $active = 0;
	$updateQuery = "UPDATE " .$TableNames['progress']. " SET
						active = '".$active."',
						flexbosseskilled = CASE
							WHEN flexbosseskilled > '".$flexKills."' THEN flexbosseskilled
							ELSE '".$flexKills."'
							END,
						bosseskilled = CASE
							WHEN bosseskilled > '".$normalKills."' THEN bosseskilled
							ELSE '".$normalKills."'
							END,
						hcbosseskilled = CASE
							WHEN hcbosseskilled > '".$heroicKills."' THEN hcbosseskilled
							ELSE '".$heroicKills."'
							END
					WHERE
						raidid = '".$raid['id']."'
					";
	$updateResult = mysql_query($updateQuery, $guildsql) or die(mysql_error());
}

// Akt-/Deaktivieren von Raids
$query = "SELECT raidid FROM " . $TableNames['progress'] . "";
$result = mysql_query($query, $guildsql) or die(mysql_error());
while($row = mysql_fetch_array($result)) {
	if(in_array($row['raidid'], $WGPConfig['Raids']) == true) $active = 1;
	else $active = 0;
	$query2 = "UPDATE " . $TableNames['progress'] . " SET active = '".$active."' WHERE raidid = '". $row['raidid'] ."'";
	$result2 = mysql_query($query2, $guildsql) or die(mysql_error());
}
