<?php
if(isset($post->update_status)){
	$object = R::load("ad_request", $post->id);
	if($post->update_status == 'Approve'){
		$object->status = 'Approved';
		$object->approved_date = $post->approved_date;
		$object->approved_position = $post->approved_position;
		$object->approved_price = $post->approved_price;
		$object->payment_method = $post->payment_method;
		$object->comment = $post->comment;
		R::store($object);
		insert("inbox", "`ad_request_id`, `message`, `entry_by`", "$post->id, 'Your Request has been Approved.".PHP_EOL."$post->comment', ".uid());
	} elseif ($post->update_status == 'Reject') {
		$object->status = 'Rejected';
		$object->approved_date = $post->approved_date;
		$object->approved_position = $post->approved_position;
		$object->approved_price = $post->approved_price;
		$object->payment_method = $post->payment_method;
		$object->comment = $post->comment;
		R::store($object);
		insert("inbox", "`ad_request_id`, `message`, `entry_by`", "$post->id, 'Your Request has been Rejected.".PHP_EOL."$post->comment', ".uid());
	}
	alert("Successfully updated.");
} elseif(isset($post->update_payment_status)){
	if($post->update_payment_status == 'Mark as Paid'){
		$object = R::load("ad_request", $post->id);
		$object->payment_status = 'Paid';
		R::store($object);
		insert("inbox", "`ad_request_id`, `message`, `entry_by`", "$post->id, 'Payment Received, Thank You', ".uid());
		alert("Successfully updated.");
	}
} elseif(isset($post->update_publishing_status)){
	if($post->update_publishing_status == 'Mark as Published'){
		$object = R::load("ad_request", $post->id);
		$object->publishing_status = 'Published';
		R::store($object);
		insert("inbox", "`ad_request_id`, `message`, `entry_by`", "$post->id, 'Congratulations, You advertisement has been Published.', ".uid());
		alert("Successfully updated.");
	}
}

if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
	<tr>
		<th>".str("Member")."</th>
			<td> $object->member</td>
		</tr>
	<tr>
		<th>".str("Publisher")."</th>
			<td> $object->publisher</td>
		</tr>
	<tr>
		<th>".str("Service")."</th>
			<td> $object->service</td>
		</tr>
	<tr>
		<th>".str("Page")."</th>
			<td> $object->page</td>
		</tr>
	<tr>
		<th>".str("Date")."</th>
			<td> $object->date</td>
		</tr>
	<tr>
		<th>".str("Status")."</th>
			<td> $object->status</td>
		</tr>
	<tr>
		<th>".str("Publishing Status")."</th>
			<td> $object->publishing_status</td>
		</tr>
	<tr>
		<th>".str("Payment Status")."</th>
			<td> $object->payment_status</td>
		</tr>
	<tr>
		<th>".str("Updated By")."</th>
			<td> $object->updated_by</td>
		</tr>
	<tr>
		<th>".str("Message")."</th>
			<td> $object->message</td>
		</tr>
	<tr>
		<th>".str("Price")."</th>
			<td> $object->price</td>
		</tr>
	<tr>
		<th>".str("Tax")."</th>
			<td> $object->tax</td>
		</tr>
	<tr>
		<th>".str("Service Charge")."</th>
			<td> $object->service_charge</td>
		</tr>
	<tr>
		<th>".str("Total")."</th>
			<td> $object->total</td>
		</tr>
	<tr>
		<th>".str("Created At")."</th>
			<td> $object->created_at</td>
		</tr>
	</table>";
	back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = isset($get->page)?$get->page:1;
	$offset = 100;

	$filter = "a.member = m.id AND `status` <> 'Drafting'";
	if(rid()==13){
		$publisher = R::findOne("publisher", "user_id=?", [uid()]);
		$filter .= "publisher = $publisher->id";
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
	$ad_requests = select("a.*, m.name, m.identity, m.email, m.agent", "ad_request a, member m", "$filter", "LIMIT $start, $offset");

	$members = toA("member");
	$publishers = toA("publisher");
	$services = toA("service");
	$users = userList();

	print "<hr>";

	$i = $start + 1;
  print "<div class='panel-group' id='accordion' role='tablist' aria-multiselectable='true'>";
	while($ad_request = mysqli_fetch_object($ad_requests)){
		print "<div class='panel panel-default'>
		<div class='panel-heading' role='tab' id='ad_request-$i'>
      <h4 class='panel-title'>
        <a role='button' data-toggle='collapse' data-parent='#accordion' href='#collapse_$i' aria-controls='collapse_$i'>
          <div class='row'>
          	<div class='col-md-2'>TID-".date("ym", strtotime($ad_request->date))."-".zerofill($ad_request->id, 6)."</div>
          	<div class='col-md-3'><b>Status:</b> <u><i>$ad_request->status</i></u></div>
          	<div class='col-md-3'><b>Payment Status:</b> <u><i>$ad_request->payment_status</i></u></div>
          	<div class='col-md-4'><b>Publishing Status:</b> <u><i>$ad_request->publishing_status</i></u></div>
          </div>
        </a>
      </h4>
    </div>";
		print "<div id='collapse_$i' class='panel-collapse collapse' role='tabpanel' aria-labelledby='headingOne'>
      <div class='panel-body'>";

		print "<form method='post'>
			<input type='hidden' name='id' value='$ad_request->id'>
			<table align='center' class='table table-responsive table-striped'>
			<tbody>";
		// print "<tr>
		// 	<th colspan='3'>TID-".date("ym", strtotime($ad_request->date))."-".zerofill($ad_request->id, 6)."</th>
		// 	<td><b>Status:</b> <u><i>$ad_request->status</i></u></td>
		// 	<td><b>Payment Status:</b> <u><i>$ad_request->payment_status</i></u></td>
		// 	<td><b>Publishing Status:</b> <u><i>$ad_request->publishing_status</i></u></td>
		// </tr>";
		print "<tr>
			<th>".($ad_request->agent?'Agent ID':'Advertiser ID')."</th>
			<th>Service Name</th>
			<th>Requested Date</th>
			<th>Ad Type</th>
			<th>System Price to Receive</th>
			<th>Email</th>
		</tr>
		<tr>
			<td>AID-".zerofill($ad_request->id, 6)."</td>
			<td>{$services[$ad_request->service]}</td>
			<td>".df($ad_request->date)."</td>
			<td>".getName("page_pricing", $ad_request->category)."</td>
			<td>".nf($ad_request->total)."</td>
			<td>$ad_request->email</td>
		</tr>
		<tr>
			<th>".($ad_request->agent?'Agent Name':'Advertiser Name')."</th>
			<th>Page Number</th>
			<th>Approved Date</th>
			<th>Approved Position</th>
			<th>Approved Price</th>
			<th>Payment Methode</th>
		</tr>
		<tr>
			<td>{$members[$ad_request->service]}</td>
			<td>$ad_request->page</td>
			<td><input name='approved_date' class='datepicker form-control' value='$ad_request->date'></td>
			<td nowrap>$ad_request->page - <input type='text' name='approved_position' class='form-control-fluid w80'></td>
			<td><input type='text' name='approved_price' class='form-control' value='$ad_request->total'></td>
			<td>".sop2("payment_method", $ad_request->payment_method)."</td>
		</tr>
		<tr>
			<td>
				<div><input disabled name='fixed_place' value='1' ".($ad_request->fixed_place?'checked':'')." type='checkbox'>Fixed Place</div>
				<div><input disabled name='first_ad' value='1' ".($ad_request->first_ad?'checked':'')." type='checkbox'>First Ad</div>
				<div><input disabled name='bold_text' value='1' ".($ad_request->bold_text?'checked':'')." type='checkbox'>Bold text</div>
				<div><input disabled name='text_in_screen' value='1' ".($ad_request->text_in_screen?'checked':'')." type='checkbox'>Text in Screen</div>
				<div><input disabled name='text_in_box' value='1' ".($ad_request->text_in_box?'checked':'')." type='checkbox'>Text in box</div>
			</td>
			<td colspan='3'><textarea class='form-control' rows='5' placeholder='Content' readonly>$ad_request->message</textarea></td>
			<td rowspan='2' colspan='2'><textarea name='comment' class='form-control' rows='5' placeholder='Editor Comment'></textarea></td>
		</tr>";
		if($ad_request->publishing_status != 'Published'){
			print "<tr>
			<td colspan='3'></td>		
			<td colspan='3' class='right'>";
			if($ad_request->status == 'Pending'){
				print "<button type='submit' name='update_status' class='btn btn-success w150' value='Approve'>Approve</button> <button type='submit' name='update_status' class='btn btn-danger w150'>Reject</button>";
			} elseif($ad_request->status == 'Approved' && $ad_request->payment_status != 'Paid'){
				print "<button type='submit' name='update_payment_status' class='btn btn-success w150' value='Mark as Paid'>Mark as Paid</button> <button type='submit' name='update_status' class='btn btn-danger w150'>Reject</button>";
			} elseif($ad_request->payment_status == 'Paid'){
				print "<button type='submit' name='update_publishing_status' class='btn btn-success w150' value='Mark as Published'>Mark as Published</button>";
			}
			print "
				</td>		
			</tr>";
		}
		print "<tr><th colspan='6' style='border-bottom: solid 3px #595'></th></tr>";
		$i++;
		print "</tbody>
		</table></form>";
		print "</div>
		  </div>
		</div>";
	}
	print "</div>";
}
?>