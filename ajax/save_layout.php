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
 			if(isset($layouts[$value])){
 				array_push($layouts[$value], [$x, $y]);
 			} else{
 				$layouts[$value] = [[$x, $y]];
 			}
 		}

 		$x++;
 		if($x>$cols){
 			$y++;
 			$x = 0;
 		}
 	}
}

// var_dump($layouts);
foreach ($layouts as $key => $layout) {
	$first = current($layout);
	$last = end($layout);
	$w = $last[0] - $first[0] + 1;
	$h = $last[1] - $first[1] + 1;
	$boxes = count($layout);
	print "$boxes: $w x $h".PHP_EOL;
}