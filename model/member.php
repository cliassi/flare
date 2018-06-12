<?php 
$fields = ['name','email','phone','identity','photo','date_of_birth','security_question_1','security_question_answer_1','security_question_2','security_question_answer_2','created_at','updated_at','deleted_at'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}
if(nn($post->password)){
	$object->password = md5($post->password);
}

$object->agent = isset($post->agent)?$post->agent:0;
$object->email_verified = isset($post->email_verified)?$post->email_verified:0;
$object->phone_verified = isset($post->phone_verified)?$post->phone_verified:0;
$object->failed_attempt = isset($post->failed_attempt)?$post->failed_attempt:0;
R::store($object); 
?>