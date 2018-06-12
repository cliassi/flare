
<?php
	$roles = [
		"0"=> 'Guest',
		"1"=> 'System',
		"2"=> 'System Administrator',
		"3"=> 'Accounts',
		"4"=> 'Admin',
		"5"=> 'Customer Service',
		"6"=> 'Writer',
		"7"=> 'Master Agent',
		"8"=> 'Agent',
		"9"=> 'Sub-Agent',
		"10"=> 'Marketing Agent',
		"11"=> 'Sales Agent',
		"12"=> 'Advertiser',
		"13"=> 'Publisher'
	];
	
	$home = "view/home.".rid().".php";
	if(file_exists($home)){
		require $home;
	} else{
		"<h1 class='animated pulse'>Welcome to Falre, that ignites your cause.</h1>";
	}
?>

