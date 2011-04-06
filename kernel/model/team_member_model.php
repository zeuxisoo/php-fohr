<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Team_Member_Model {

	public static function fetch_team_list($user) {
		global $db;
		
		$characters = array();
		
		$query = $db->query("
			SELECT tm.id, tm.name, tm.gender, c.image_boy, c.image_girl
			FROM ".Table::table("team_member")." tm
			LEFT JOIN ".Table::table("character")." c
			ON tm.character_id = c.id
			WHERE tm.user_id = ".$db->escape($user['id'])."
		");
		while($row = $db->fetch_array($query)) {
			$characters[$row['id']] = array(
				'name' => $row['name'],
				'image' => $row['gender'] == 1 ? $row['image_boy'] : $row['image_girl']
			);
		}
		
		return $characters;
	}

}
?>