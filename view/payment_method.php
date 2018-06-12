
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Charge Type")."</th><td> $object->charge_type</td></tr>
		<tr><th>".str("Service Charge")."</th><td> $object->service_charge</td></tr>
		<tr><th>".str("Gst")."</th><td> $object->gst</td></tr>
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
	
	$nor = num_rows("a.*", "payment_method a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$payment_methods = select("a.*", "payment_method a", "$filter", "LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Charge Type")."</th><th>".str("Service Charge")."</th><th>".str("Gst")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($payment_method = mysqli_fetch_object($payment_methods)){
		print "<tr><td><a href='view/$payment_method->id'>$i</a></td>
			<td>$payment_method->name</td>
			<td>$payment_method->charge_type</td>
			<td>$payment_method->service_charge</td>
			<td>$payment_method->gst</td>
			<td>".options2("", $payment_method->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(6, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>