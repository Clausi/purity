<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

$Roles = array('tank', 'heal', 'damage');

foreach($Roles as $Role) {
	$query = "SELECT COUNT(id) AS num_recruits FROM ". $TableNames['recruitment'] ." WHERE role = '".$Role."' ORDER BY class, urgency, spec, id";
	$result = $db->sql_query($query);

	$count = $db->sql_fetchfield('num_recruits');

	if($count > 0) {
		$template->assign_block_vars('n_recruitment', array(
			'GERMANROLES' => getGermanRole($Role),
			'ROLES'	=> $Role
		));

		while($row = $db->sql_fetchrow($result)) {
			$template->assign_block_vars('n_recruitment.n_classes', array(
				'CLASS' => getGermanClass($row['class']),
				'SPEC'	=> getGermanRole($row['spec'])
			));
		}
	}
}
