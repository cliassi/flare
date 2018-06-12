<?php
//Beginning of Time
define('BT', '2016-01-01');

//Twilio API
define('TWILIO_SID', 'AC93d76024a5c1ef160619ef24deafb359');
define('TWILIO_TOKEN', '29b6bec1c07831bba992475292f7a211');
define('TWILIO_NUMBER', '+12677536203');
if(function_exists('theme')) theme('sbadmin', false);

//MY SQL
if(!isset($indexloaded)) {
	$safeboot_file = "safeboot.php";
	$safeboot_file = file_exists($safeboot_file)?$safeboot_file:"../$safeboot_file";
	$safeboot_file = file_exists($safeboot_file)?$safeboot_file:"../$safeboot_file";
	$safeboot_file = file_exists($safeboot_file)?$safeboot_file:"../$safeboot_file";
	$safeboot_file = file_exists($safeboot_file)?$safeboot_file:"../$safeboot_file";
	require_once $safeboot_file;
}


$config->db_name = "outsourced";

$c = mysqli_connect($config->db_host, $config->db_user, $config->db_pass, $config->db_name);
