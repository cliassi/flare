<?php 
$fields = ['service','name','printable_width','printable_height'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

$object->page_number = isset($post->page_number)?$post->page_number:0;
$object->number_of_columns = isset($post->number_of_columns)?$post->number_of_columns:0;
R::store($object); 
?>