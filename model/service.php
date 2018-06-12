<?php 
$fields = ['name','service_type','frequency','number_of_pages','payment_term','payment_method', 'remarks', 'publisher'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

$object->division = implode(",", $post->division);
$object->district = implode(",", $post->district);
$object->upazila = implode(",", $post->upazila);

// if($funcation == 'add'){
// 	$object->entry_by = uid();
// 	$object->entry_time = now();
// } else{
// 	$object->modify_by = uid();
// 	$object->modify_time = now();
// }

R::store($object); 
?>