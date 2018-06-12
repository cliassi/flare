<?php 
$object = R::dispense("writer");
$user = R::dispense("sys_user");
if(isset($id) && is_numeric($id)){
	$object = R::load("writer", $id);
	$user = R::load("sys_user", $object->user_id);
}
switch ($function){
	case "view":{
		require("view/$controller.php");
	} break;
	case "erase":{
		if(isset($get->conf)){		
			$object = R::load("writer", $id);
			R::trash($object);
			redir("../view");
		} else{
			?>
			<script type="text/javascript">
				if(confirm("Are you sure you want to completly remove this Writer?")){
					location.href = "?conf";
				} else{
					location.href = "../view";	
				}
			</script>
			<?php
		}

	} break;
	case "edit":
	case "add":{
		if(isset($post->save)){
			require_once("model/$controller.php");
			redir(($function=='edit'?'../':'')."view");
		}
		require_once("view/form/$controller.php");
	} break;
}