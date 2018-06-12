
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Description")."</th><td> $object->description</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
	    $filter = "";
	    openFilterForm("get");
	    print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
	    $name = isf("name", "name", $filter, $get);
	    print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "service_frequency a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$service_frequencys = select("a.*", "service_frequency a", "$filter", "ORDER BY a.name LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Description")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($service_frequency = mysqli_fetch_object($service_frequencys)){
		print "<tr><td><a href='view/$service_frequency->id'>$i</a></td>
			<td>$service_frequency->name</td>
			<td>".stripslashes($service_frequency->description)."</td>
			<td>".options2("", $service_frequency->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(4, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>