<?php
if(isset($_POST['id'])){
  require_once("../safeboot.php"); 
  print getFieldValue("service", "number_of_pages", "id={$_POST['id']}");
}