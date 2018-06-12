<?php 
$fields = ['name','nid','blood_group','phone','premanent_address','present_address','passport','division','district','bkash_number','bank_account_details','upazila','rank','user_id'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}
$object->active = isset($post->active)?$post->active:0;
R::store($object); 
if($function == 'add' || !$object->user_id){
	$user = createUser($post->name, $post->email, $post->password, 'Writer');
} else{
	$user = updateUser($id, $fullname, $username, $password = '');
}
$object->user_id = $user->id;
R::store($object); 
?>