
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Division")."</th><td> $object->division</td></tr>
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
	    $name = isf("name", "name", $filter, $get);
	    print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "district a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$districts = select("a.*", "district a", "$filter", "LIMIT $start, $offset");
  $divisionList = toA("division");
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Division")."</th><th>".str("Name")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($district = mysqli_fetch_object($districts)){
		print "<tr><td><a href='view/$district->id'>$i</a></td>
			<td>{$divisionList[$district->division]}</td>
	    <td>$district->name</td>
			<td>".options2("", $district->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(4, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>