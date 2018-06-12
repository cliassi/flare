
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Service")."</th><td> $object->service</td></tr>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Page Number")."</th><td> $object->page_number</td></tr>
		<tr><th>".str("Printable Width")."</th><td> $object->printable_width</td></tr>
		<tr><th>".str("Printable Height")."</th><td> $object->printable_height</td></tr>
		<tr><th>".str("Number Of Columns")."</th><td> $object->number_of_columns</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
	    $filter = "";
	    openFilterForm("get");
	    print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
	    $service = isf("service", "service", $filter, $get, '', true);
    	print str("Service")." ".sop2('service', $service, ['optional'=>true], 'service');					
	    $name = isf("name", "name", $filter, $get);
	    print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "page a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$pages = select("a.*", "page a", "$filter", "ORDER BY service, page_number LIMIT $start, $offset");
  $serviceList = toA("service");
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Service")."</th><th>".str("Name")."</th><th>".str("Page Number")."</th><th>".str("Printable Width")."</th><th>".str("Printable Height")."</th><th>".str("Number Of Columns")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($page = mysqli_fetch_object($pages)){
		print "<tr><td><a href='view/$page->id'>$i</a></td>
			<td>{$serviceList[$page->service]}</td>
	    <td>$page->name</td>
			<td>$page->page_number</td>
			<td>$page->printable_width</td>
			<td>$page->printable_height</td>
			<td>$page->number_of_columns</td>
			<td>".options2("", $page->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(8, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>