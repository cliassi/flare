
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("License No")."</th><td> $object->license_no</td></tr>
		<tr><th>".str("Logo")."</th><td> $object->logo</td></tr>
		<tr><th>".str("Address")."</th><td> $object->address</td></tr>
		<tr><th>".str("Phone")."</th><td> $object->phone</td></tr>
		<tr><th>".str("Email")."</th><td> $object->email</td></tr>
		<tr><th>".str("Website")."</th><td> $object->website</td></tr>
		<tr><th>".str("Contact Person")."</th><td> $object->contact_person</td></tr>
		<tr><th>".str("Contact Person Phone")."</th><td> $object->contact_person_phone</td></tr>
		<tr><th>".str("Contact Person Email")."</th><td> $object->contact_person_email</td></tr>
		<tr><th>".str("Account Details")."</th><td> $object->account_details</td></tr>
		<tr><th>".str("Description")."</th><td> $object->description</td></tr>
		<tr><th>".str("Notes")."</th><td> $object->notes</td></tr>
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
	
	$nor = num_rows("a.*", "publisher a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$publishers = select("a.*", "publisher a", "$filter", "ORDER BY name LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Logo")."</th><th>".str("Phone")."</th><th>".str("Email")."</th><th>".str("Notes")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($publisher = mysqli_fetch_object($publishers)){
		$path = "uploads/publisher/$publisher->id/$publisher->logo";
		print "<tr><td><a href='view/$publisher->id'>$i</a></td>
			<td>$publisher->name</td>
			<td>".(nn($path)&&file_exists($path)?"<img src='../$path' class='w100'>":"")."</td>
			<td>".stripslashes($publisher->phone)."</td>
			<td>".stripslashes($publisher->email)."</td>
			<td>".stripslashes($publisher->notes)."</td>
			<td>".options2("", $publisher->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(7, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>