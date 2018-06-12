
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Division")."</th><td> $object->division</td></tr>
		<tr><th>".str("District")."</th><td> $object->district</td></tr>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
	    $filter = "";
	    openFilterForm("get");
	    print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
	    $division = isf("division", "division", $filter, $get, '', true);
    	print str("Division")." ".sop2('division', $division, ['optional'=>true], 'division');					
	    $district = isf("district", "district", $filter, $get, '', true);
    	print str("District")." ".sop2('district', $district, ['optional'=>true], 'district');					
	    $name = isf("name", "name", $filter, $get);
	    print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "upazila a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$upazilas = select("a.*", "upazila a", "$filter", "LIMIT $start, $offset");
  $divisionList = toA("division");
    	
  $districtList = toA("district");
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Division")."</th><th>".str("District")."</th><th>".str("Name")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($upazila = mysqli_fetch_object($upazilas)){
		print "<tr><td><a href='view/$upazila->id'>$i</a></td>
			<td>{$divisionList[$upazila->division]}</td>
	    <td>{$districtList[$upazila->district]}</td>
	    <td>$upazila->name</td>
			<td>".options2("", $upazila->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(5, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>