<?php 
$object = R::dispense("ad_request");
if(isset($id)){
	$object = R::load("ad_request", $id);
}
switch ($function){
	case "view":{
		require("view/$controller.php");
	} break;
	case "approve":{
		if(isset($get->conf)){	
			$object->status = 'Approved';
			R::store($object);
			redir("../view");
		} else{
			?>
			<script type="text/javascript">
				if(confirm("Are you sure you want to completly remove this Ad Request?")){
					location.href = "?conf";
				} else{
					location.href = "../view";	
				}
			</script>
			<?php
		}

	} break;
	case "erase":{
		if(isset($get->conf)){		
			$object = R::load("ad_request", $id);
			R::trash($object);
			redir("../view");
		} else{
			?>
			<script type="text/javascript">
				if(confirm("Are you sure you want to completly remove this Ad Request?")){
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