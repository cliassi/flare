
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Nid")."</th><td> $object->nid</td></tr>
		<tr><th>".str("Blood Group")."</th><td> $object->blood_group</td></tr>
		<tr><th>".str("Phone")."</th><td> $object->phone</td></tr>
		<tr><th>".str("Premanent Address")."</th><td> $object->premanent_address</td></tr>
		<tr><th>".str("Present Address")."</th><td> $object->present_address</td></tr>
		<tr><th>".str("Passport")."</th><td> $object->passport</td></tr>
		<tr><th>".str("Division")."</th><td> $object->division</td></tr>
		<tr><th>".str("District")."</th><td> $object->district</td></tr>
		<tr><th>".str("Bkash Number")."</th><td> $object->bkash_number</td></tr>
		<tr><th>".str("Bank Account Details")."</th><td> $object->bank_account_details</td></tr>
		<tr><th>".str("Upazila")."</th><td> $object->upazila</td></tr>
		<tr><th>".str("Rank")."</th><td> $object->rank</td></tr>
		<tr><th>".str("Active")."</th><td> $object->active</td></tr>
		<tr><th>".str("User Id")."</th><td> $object->user_id</td></tr>
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
	
	$nor = num_rows("a.*", "writer a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$writers = select("a.*", "writer a", "$filter", "LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Phone")."</th><th>".str("Division")."</th><th>".str("District")."</th><th>".str("Upazila")."</th><th>".str("Rank")."</th><th>".str("Active")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($writer = mysqli_fetch_object($writers)){
		print "<tr><td><a href='view/$writer->id'>$i</a></td>
			<td>$writer->name</td>
			<td>$writer->phone</td>
			<td>$writer->division</td>
			<td>$writer->district</td>
			<td>$writer->upazila</td>
			<td>$writer->rank</td>
			<td>".($writer->active?"Yes":"No")."</td>
			<td>".options2("", $writer->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(9, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>