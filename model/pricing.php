<?php 
$fields = ['publisher','service','page','layout','start_date','end_date','price','master_agent_price','agent_price'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

if($function=="add") $object->entry_by = uid();
if($function=="add") $object->entry_time = now();
if($function=="edit") $object->modify_by = uid();
if($function=="edit") $object->modify_time = now();
R::store($object); 
?>