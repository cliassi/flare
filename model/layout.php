<?php 
$fields = ['service','page','name','offsetx','offsety','width','height','language'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

R::store($object); 
?>