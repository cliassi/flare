<?php
if(isset($_POST['service'])){
  require_once("../safeboot.php"); 
  extract($_POST);
  $layout = trim($layout);
  $layout = str_replace("  ", "", $layout);
  // $layout = gzcompress($layout, 1);
 	insert("page", "service, page_number, layout", "$service, $page, COMPRESS('$layout')", false, "ON DUPLICATE KEY UPDATE layout=COMPRESS('$layout')");
 	// print insert("page", "layout='$layout'", "service=$service AND page_number=$page");

 	$cells = explode(" ", $layout);
 	$cols = $width / $box;
 	$rows = $height / $box;

 	$col_count = $row_count = $x = $y = 0;
 	$layouts = [];
 	foreach ($cells as $key => $value) {
 		// print trim($value) == '#ffffff'?'T':'F';
 		if(trim($value) != '#ffffff'){
 			array_push($layouts, [$x, $y]);
 		}

 		$x++;
 		if($x>$cols){
 			$y++;
 			$x = 0;
 		}
 	}
}

var_dump($layouts);
foreach ($layouts as $key => $layout) {
	$w = $layout[0] + 1;
	$h = $layout[1] + 1;
	print "$page: $w x $h".PHP_EOL;
	$name = 'S'.zerofill($service,3)."P".zerofill($page,2)."-"."{$w}_{$h}";
	replace("layout", "service, page, name, offsetx, offsety, width, height", "$service, $page, '$name', $w, $h, $w, $h");
}