<?php 
$fields = ['division','name'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

R::store($object); 
?>