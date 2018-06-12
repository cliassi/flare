<?php 
$fields = ['name','license_no','logo','address','phone','email','website','contact_person','contact_person_phone','contact_person_email','account_details','description','notes'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

R::store($object); 

if(isset($_FILES['logo']['name']) && nn($_FILES['logo']['name'])){
	mkdir2("uploads/publisher/$object->id/");
	$logo = upload($_FILES, "logo_$object->id", "uploads/publisher/$object->id/", 'logo');
	$object->logo = $logo;
	R::store($object); 
}

userMan($object, $object->name, $object->email, $post->password, 'Publisher');
?>