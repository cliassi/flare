<?php 
$fields = ['parent_agent','name','nid','blood_group','premanent_address','present_address','passport','agent_id','division','district','upazila','level'];

foreach ($fields as $field) {
	if(isset($post->$field)) {
		$object->$field = $post->$field;
	}
}

$object->active = isset($post->active)?$post->active:0;
R::store($object); 
?>