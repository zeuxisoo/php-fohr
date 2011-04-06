<?php
require_once realpath('../kernel/init.php');

$items = array();
$query = $db->query("
	SELECT 
		i.*,
		it.type, 
		id.attack_normal, id.attack_percentage, id.defense_normal, id.defense_percentage, id.magic_defense_normal, id.magic_defense_percentage, id.handle
	FROM ".Table::table("item")." i
	LEFT JOIN ".Table::table("item_type")." it ON it.id = i.item_type_id
	LEFT JOIN ".Table::table("item_detail")." id ON id.item_id = i.id
");
while($row = $db->fetch_array($query)) {
	$id = $row['id']; 
	unset($row['id']);
	$items[$id] = $row;
}
$db->free_result($query);

Cache::set("items", $items);
?>