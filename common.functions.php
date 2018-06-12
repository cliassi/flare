<?php
function broadcast($source, $msg, $receipents = 'NULL', $expiry = 'NULL'){
	if($receipents == ''){
		$receipents = 'NULL';
	}
	$id = insert("inbox", "source, message, member, expiry", "'$source', '$msg', $receipents, '$expiry'");
	if($receipents == 'NULL'){
		insert("inbox_read", "inbox, member", "$id, ".uid());
	}
}
function inboxCount(){
	return num_rows("id", "inbox", "member = ".uid()." OR (member IS NULL AND id NOT IN(SELECT inbox FROM inbox_read WHERE inbox=inbox.id AND member=".uid()."))");
}
function user(){
	return $_SESSION[APP.'_fullname'];
}
function member($id = FALSE){
	if(!$id){
		return user();
	} else{
		$member = R::load("members", $id);
		return $member->name;
	}
}
function miniForm($fields = [], $buttons = ['submit']){
	$form = "<form method='post'>";

	$form .= "</form>";
}

function userMan($object, $fullname, $username, $password, $role){
	$hasRole = R::findOne("sys_role", "r_name=?", [$role]);

	if($hasRole){
		if($object->user_id){
			$user = R::load("sys_user", $object->user_id);
		} else{
			$user = R::dispense("sys_user");
		}
		$user->u_fullname = $fullname;
		$user->u_username = $username;
		if(nn($password)) $user->u_password = md5($password);
		$user->u_date_created = now();
		$user->u_email = $username;
		$user->u_created_by = uid();
		R::store($user);
		if($user){
			insert("sys_user_role", "ur_user_id, ur_role_id", "$user->id, $hasRole->id");
		}
		if(!$object->user_id){
			$object->user = $user;
		}

		R::store($object); 
		return $user; 	
	} else{
		return false;
	}		
}


function makeUser($fullname, $email, $phone, $password, $role){
	$hasRole = R::load("roles", $role);
	if($hasRole){
		$user = R::dispense("sys_user");
		$user->u_fullname = $fullname;
		$user->u_username = $email;
		$user->u_email = $email;
		$user->u_phone = $phone;
		$user->u_password = md5($password);
		$user->u_date_created = now();
		$user->u_created_by = uid();
		R::store($user);
		if($user){
			insert("sys_user_role", "ur_user_id, ur_role_id", "$user->id, $hasRole->id");
		}

		return $user; 	
	} else{
		return false;
	}		
}