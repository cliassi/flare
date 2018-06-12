<?php 
$object = R::dispense("page");
$service = R::dispense("service");
if(isset($id)){
	$object = R::load("page", $id);
	$service = R::load("service", $object->service);
}
switch ($function){
	case "view":{
		require("view/$controller.php");
	} break;
	case "erase":{
		if(isset($get->conf)){		
			$object = R::load("page", $id);
			R::trash($object);
			redir("../view");
		} else{
			?>
			<script type="text/javascript">
				if(confirm("Are you sure you want to completly remove this Page?")){
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