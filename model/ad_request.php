<?php 
$fields = ['member','publisher','service','page','date','status','publishing_status','payment_status','updated_by','message','price','tax','service_charge','total','created_at'];

foreach ($fields as $field) {
	if(isset($post->$field) && nn($post->$field)) {
		$object->$field = $post->$field;
	}
}

R::store($object); 
?>