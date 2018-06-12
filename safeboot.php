<?php
if(session_id()==""){
	session_start();
}
if(!defined("APP")){
	define("APP", $_SESSION["app"]);
}
if(strpos($_SERVER["SCRIPT_FILENAME"], "framework") !== FALSE){
	$base = substr($_SERVER["SCRIPT_FILENAME"], 0, strpos($_SERVER["SCRIPT_FILENAME"], "framework"));
} if(strpos($_SERVER["SCRIPT_FILENAME"], APP) !== FALSE){
	$base = substr($_SERVER["SCRIPT_FILENAME"], 0, strpos($_SERVER["SCRIPT_FILENAME"], APP));
} else{
	$base = substr($_SERVER["SCRIPT_FILENAME"], 0, strpos($_SERVER["SCRIPT_FILENAME"], "framework"));
}

define('BASEURL', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], APP)));
define('APPURL', BASEURL.APP);
$appurl = BASEURL.APP;
$base = "$base".APP;
require_once "$base/framework/core/vendor/rb/rb.php";
require_once "config.php";
require_once "config.local.php";
require_once "defines.php";
require_once 'common.functions.php';
require_once "$base/framework/core/vendor/panacea/rc.inc.php";
require_once "$base/framework/core/vendor/panacea/f.inc.php";