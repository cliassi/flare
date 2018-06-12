<?php
if(isset($post->update_status)){
	$object = R::load("ad_request", $post->id);
	if($post->update_status == 'Approve'){
		$object->status = 'Approved';
		R::store($object);
	} elseif ($post->update_status == 'Reject') {
		$object->status = 'Rejected';
		R::store($object);
	}
} elseif(isset($post->update_payment_status)){
	if($post->update_payment_status == 'Mark as Paid'){
		$object = R::load("ad_request", $post->id);
		$object->payment_status = 'Paid';
		R::store($object);
	}
} elseif(isset($post->update_publishing_status)){
	if($post->update_publishing_status == 'Mark as Published'){
		$object = R::load("ad_request", $post->id);
		$object->publishing_status = 'Published';
		R::store($object);
	}
}

if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Member")."</th><td> $object->member</td></tr>
		<tr><th>".str("Publisher")."</th><td> $object->publisher</td></tr>
		<tr><th>".str("Service")."</th><td> $object->service</td></tr>
		<tr><th>".str("Page")."</th><td> $object->page</td></tr>
		<tr><th>".str("Date")."</th><td> $object->date</td></tr>
		<tr><th>".str("Status")."</th><td> $object->status</td></tr>
		<tr><th>".str("Publishing Status")."</th><td> $object->publishing_status</td></tr>
		<tr><th>".str("Payment Status")."</th><td> $object->payment_status</td></tr>
		<tr><th>".str("Updated By")."</th><td> $object->updated_by</td></tr>
		<tr><th>".str("Message")."</th><td> $object->message</td></tr>
		<tr><th>".str("Price")."</th><td> $object->price</td></tr>
		<tr><th>".str("Tax")."</th><td> $object->tax</td></tr>
		<tr><th>".str("Service Charge")."</th><td> $object->service_charge</td></tr>
		<tr><th>".str("Total")."</th><td> $object->total</td></tr>
		<tr><th>".str("Created At")."</th><td> $object->created_at</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = isset($get->page)?$get->page:1;
	$offset = 100;
	     
  $filter = "";
  if(rid()==13){
		$publisher = R::findOne("publisher", "user_id=?", [uid()]);
  	$filter = "publisher = $publisher->id";
	}

  openFilterForm("get");
  print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
  if(rid()!=13){ 
	  $publisher = isf("publisher", "publisher", $filter, $get, '', true);
		print str("Publisher")." ".sop2('publisher', $publisher, ['optional'=>true], 'publisher');
	}			
  $service = isf("service", "service", $filter, $get, '', true);
	print str("Service")." ".sop2('service', $service, ['optional'=>true], 'service');					
  $dateFrom = isset($get->dateFrom)?$get->dateFrom:today();
  $dateTo = isset($get->dateTo)?$get->dateTo:nextYear();
  joinFilter($filter, "`date` BETWEEN '$dateFrom' AND '$dateTo'");
  print str("Date")." <input type='text' name='dateFrom' value='$dateFrom' class='form-control-fluid datepicker' /> to <input type='text' name='dateTo' value='$dateTo' class='form-control-fluid datepicker' /><br>";
  $status = isf("status", "status", $filter, $get);
  print str("Status")." ".selectEnum("name='status' class='form-control-fluid' id='status'", 'ad_request', 'status',$status, array(), true, false, true)." ";
  $publishing_status = isf("publishing_status", "publishing_status", $filter, $get);
  print str("Publishing Status")." ".selectEnum("name='publishing_status' class='form-control-fluid' id='publishing_status'", 'ad_request', 'publishing_status',$publishing_status, array(), true, false, true)." ";
  $payment_status = isf("payment_status", "payment_status", $filter, $get);
  print str("Payment Status")." ".selectEnum("name='payment_status' class='form-control-fluid' id='payment_status'", 'ad_request', 'payment_status',$payment_status, array(), true, false, true)." ";
  closeFilterForm();
	
	$nor = num_rows("a.*", "ad_request a", "$filter");
	$nop = ceil($nor/$offset); if($page > $nop) $page = 1;

	$start = ($page-1)*$offset;
	$ad_requests = select("a.*", "ad_request a", "$filter", "LIMIT $start, $offset");
  $memberList = toA("member");
    	
  $publisherList = toA("publisher");
    	
  $serviceList = toA("service");
    	
  $updated_byList = userList();
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Member")."</th><th>".str("Publisher")."</th><th>".str("Service")."</th><th>".str("Page")."</th><th>".str("Date")."</th><th>".str("Status")."</th><th>".str("Payment Status")."</th><th>".str("Publishing Status")."</th><th>".str("Price")."</th><th>Action</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($ad_request = mysqli_fetch_object($ad_requests)){
		print "<tr><td><a href='view/$ad_request->id'>$i</a></td>
			<td>{$memberList[$ad_request->member]}</td>
	    <td>{$publisherList[$ad_request->publisher]}</td>
	    <td>{$serviceList[$ad_request->service]}</td>
	    <td>$ad_request->page</td>
			<td>$ad_request->date</td>
			<td>$ad_request->status</td>
			<td>$ad_request->payment_status</td>
			<td>$ad_request->publishing_status</td>
	    <td>$ad_request->total</td>
			<td>";
			if($ad_request->publishing_status != 'Published'){
				print "<form method='post'><input type='hidden' name='id' value='$ad_request->id'>";
				if($ad_request->status == 'Pending'){
					print "<button type='submit' name='update_status' class='btn btn-success' value='Approve'>Approve</button> <button type='submit' name='update_status' class='btn btn-danger'>Reject</button>";
				} elseif($ad_request->status == 'Approved' && $ad_request->payment_status != 'Paid'){
					print "<button type='submit' name='update_payment_status' class='btn btn-success' value='Mark as Paid'>Mark as Paid</button> <button type='submit' name='update_status' class='btn btn-danger'>Reject</button>";
				} elseif($ad_request->payment_status == 'Paid'){
					print "<button type='submit' name='update_publishing_status' class='btn btn-success' value='Mark as Published'>Mark as Published</button>";
				}
				print "</form>";
			}
		print "</td>
		</tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(12, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>