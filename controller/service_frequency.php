<?php 
$object = R::dispense("service_frequency");
if(isset($id)){
	$object = R::load("service_frequency", $id);
}
switch ($function){
	case "view":{
		require("view/$controller.php");
	} break;
	case "erase":{
		if(isset($get->conf)){		
			$object = R::load("service_frequency", $id);
			R::trash($object);
			redir("../view");
		} else{
			?>
			<script type="text/javascript">
				if(confirm("Are you sure you want to completly remove this Service Frequency?")){
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