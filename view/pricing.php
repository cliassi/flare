
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Publisher")."</th><td> $object->publisher</td></tr>
		<tr><th>".str("Service")."</th><td> $object->service</td></tr>
		<tr><th>".str("Page")."</th><td> $object->page</td></tr>
		<tr><th>".str("Layout")."</th><td> $object->layout</td></tr>
		<tr><th>".str("Start Date")."</th><td> $object->start_date</td></tr>
		<tr><th>".str("End Date")."</th><td> $object->end_date</td></tr>
		<tr><th>".str("Price")."</th><td> $object->price</td></tr>
		<tr><th>".str("Master Agent Price")."</th><td> $object->master_agent_price</td></tr>
		<tr><th>".str("Agent Price")."</th><td> $object->agent_price</td></tr>
		<tr><th>".str("Entry By")."</th><td> $object->entry_by</td></tr>
		<tr><th>".str("Entry Time")."</th><td> $object->entry_time</td></tr>
		<tr><th>".str("Modify By")."</th><td> $object->modify_by</td></tr>
		<tr><th>".str("Modify Time")."</th><td> $object->modify_time</td></tr>
		<tr><th>".str("Trash")."</th><td> $object->trash</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$pg = is("pg", 1);
	$offset = 20;
	     
  $filter = "trash=0";
  openFilterForm("get");
  print "<input type='hidden' name='pg' value='$pg' class='form-control-fluid' />";
  $publisher = isf("publisher", "publisher", $filter, $get, '', true);
	print str("Publisher")." ".sop2('publisher', $publisher, ['optional'=>true], 'publisher');					
  $service = isf("service", "service", $filter, $get, '', true);
	print str("Service")." ".sop2('service', $service, ['optional'=>true], 'service');					
  $page = isf("page", "page", $filter, $get, '', true);
	print str("Page")." ".sop2('page', $page, ['optional'=>true], 'page');					
  $layout = isf("layout", "layout", $filter, $get, '', true);
	print str("Layout")." ".sop2('layout', $layout, ['optional'=>true], 'layout');					
  closeFilterForm();
	$userlist = userList();
	
	$nor = num_rows("a.*", "pricing a", "trash=0".(nn($filter)?" AND $filter":""));
	$nop = ceil($nor/$offset);

	$start = ($pg-1)*$offset;
	$pricings = select("a.*", "pricing a", "trash=0".(nn($filter)?" AND $filter":""), "LIMIT $start, $offset");
  $publisherList = toA("publisher");
    	
  $serviceList = toA("service");
    	
  $pageList = toA("page");
    	
  $layoutList = toA("layout");
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Publisher")."</th><th>".str("Service")."</th><th>".str("Page")."</th><th>".str("Layout")."</th><th>".str("Start Date")."</th><th>".str("End Date")."</th><th>".str("Price")."</th><th>".str("Master Agent Price")."</th><th>".str("Agent Price")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($pricing = mysqli_fetch_object($pricings)){
		print "<tr><td><a href='view/$pricing->id'>$i</a></td>
			<td>{$publisherList[$pricing->publisher]}</td>
	    <td>{$serviceList[$pricing->service]}</td>
	    <td>{$pageList[$pricing->page]}</td>
	    <td>{$layoutList[$pricing->layout]}</td>
	    <td>$pricing->start_date</td>
			<td>$pricing->end_date</td>
			<td>$pricing->price</td>
			<td>$pricing->master_agent_price</td>
			<td>$pricing->agent_price</td>
			<td>".options2("", $pricing->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(11, $nop, $nor, $pg);
	print "</tfoot>
	</table>";
}
?>