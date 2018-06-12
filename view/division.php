
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
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
	    $name = isf("name", "name", $filter, $get);
	    print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "division a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$divisions = select("a.*", "division a", "$filter", "LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($division = mysqli_fetch_object($divisions)){
		print "<tr><td><a href='view/$division->id'>$i</a></td>
			<td>$division->name</td>
			<td>".options2("", $division->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(3, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>