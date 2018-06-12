
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Service")."</th><td> $object->service</td></tr>
		<tr><th>".str("Page")."</th><td> $object->page</td></tr>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Offset-x")."</th><td> $object->offset-x</td></tr>
		<tr><th>".str("Offset-y")."</th><td> $object->offset-y</td></tr>
		<tr><th>".str("Width")."</th><td> $object->width</td></tr>
		<tr><th>".str("Height")."</th><td> $object->height</td></tr>
		<tr><th>".str("Language")."</th><td> $object->language</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$pg = isset($get->pg)?$get->pg:1;
	$offset = 50;
	     
  $filter = "";
  openFilterForm("get");
  print "<input type='hidden' name='pg' value='$pg' class='form-control-fluid' />";
  $service = isf("service", "service", $filter, $get, '', true);
	print str("Service")." ".sop2('service', $service, ['optional'=>true], 'service');					
  $page = isf("page", "page", $filter, $get, '', true);
	print str("Page")." ".sop2('page', $page, ['optional'=>true], 'page');					
  $name = isf("name", "name", $filter, $get);
  print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
  closeFilterForm();
	
	$nor = num_rows("a.*", "layout a", "$filter");
	$nop = ceil($nor/$offset);
	$start = ($pg-1)*$offset;
	$layouts = select("a.*", "layout a", "$filter", "LIMIT $start, $offset");
  $serviceList = toA("service");
    	
  $pageList = toA("page");
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Service")."</th><th>".str("Page")."</th><th>".str("Name")."</th><th>".str("Offset X")."</th><th>".str("Offset Y")."</th><th>".str("Width")."</th><th>".str("Height")."</th><th>".str("Language")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($layout = mysqli_fetch_object($layouts)){
		print "<tr><td><a href='view/$layout->id'>$i</a></td>
			<td>{$serviceList[$layout->service]}</td>
	    <td>$layout->page</td>
	    <td>$layout->name</td>
			<td>$layout->offsetx</td>
			<td>$layout->offsety</td>
			<td>$layout->width</td>
			<td>$layout->height</td>
			<td>$layout->language</td>
			<td>".options2("", $layout->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(10, $nop, $nor, $pg-1);
	print "</tfoot>
	</table>";
}
?>