<?php
if(isset($_POST['agent'])){
	extract($_POST);
  require_once("../safeboot.php");
  if($type==''){
  	del("publisher_agent", "agent=$agent AND publisher=$publisher");
  } else{
  	replace("publisher_agent", "agent, publisher, agent_type", "$agent, $publisher, '$type'");
  }
}