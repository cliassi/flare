<?php
session_start();
$indexloaded = true;
$base = str_replace("/index.php", "", $_SERVER['SCRIPT_FILENAME']);
define('ROOT', substr($base, 0, strrpos($base, '/')));
define('BASE', $base);
define('APP', str_replace("/", "", str_replace(ROOT, "", BASE)));
define('BASEURL', substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], APP)));

$_SESSION['app'] = APP;

require_once('config.php');
require_once('defines.php');
require_once('framework/core/vendor/panacea/rc.inc.php');
require_once('framework/core/vendor/panacea/f.inc.php');
require_once('framework/core/vendor/rb/rb.php');
require_once('config.local.php');

$themes = new IO("framework/themes");
$mod = new IO("framework/modules");


$get = array(); foreach ($_GET as $key => $value) { $get[$key] = $value; } unset($_GET); $get = (object)$get;
$post = array(); foreach ($_POST as $key => $value) { $post[$key] = $value; } unset($_POST); $post = (object)$post;


$app_mod = new IO("modules");
$controller = 'home';
if(isset($get->q)){
	$params = explode("/", $get->q);
	if($app_mod->exists($params[0])){
		//Load App Module
		$app_module = $params[0];
		$controller = isset($params[1])?$params[1]:false;
		$function = isset($params[2])?$params[2]:false;
		$id = isset($params[3])?$params[3]:false;
		$param1 = isset($params[4])?$params[4]:false;
		$started = true;
		
	} elseif($mod->exists($params[0])){
		//Load Module
		$module = $params[0];
		$controller = isset($params[1])?$params[1]:false;
		$function = isset($params[2])?$params[2]:false;
		$id = isset($params[3])?$params[3]:false;
		$param1 = isset($params[4])?$params[4]:false;
		$started = true;
	} else{
		//Load Controller
		$controller = $params[0];
		$function = isset($params[1])?$params[1]:false;
		$id = isset($params[2])?$params[2]:false;
		$param1 = isset($params[3])?$params[3]:false;
		$started = true;
	}	
} else{
    $started = true;
}

require("framework/themes/".theme()."/index.php");

if(ENV=='DEV'){
	require('framework/core/vendor/panacea/dev.inc.php');
}