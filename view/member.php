
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Name")."</th><td> $object->name</td></tr>
		<tr><th>".str("Email")."</th><td> $object->email</td></tr>
		<tr><th>".str("Phone")."</th><td> $object->phone</td></tr>
		<tr><th>".str("Password")."</th><td> $object->password</td></tr>
		<tr><th>".str("Agent")."</th><td> $object->agent</td></tr>
		<tr><th>".str("Identity")."</th><td> $object->identity</td></tr>
		<tr><th>".str("Email Verified")."</th><td> $object->email_verified</td></tr>
		<tr><th>".str("Phone Verified")."</th><td> $object->phone_verified</td></tr>
		<tr><th>".str("Photo")."</th><td> $object->photo</td></tr>
		<tr><th>".str("Date Of Birth")."</th><td> $object->date_of_birth</td></tr>
		<tr><th>".str("Security Question")."</th><td> $object->security_question_1</td></tr>
		<tr><th>".str("Security Question Answer")."</th><td> $object->security_question_answer_1</td></tr>
		<tr><th>".str("Security Question")."</th><td> $object->security_question_2</td></tr>
		<tr><th>".str("Security Question Answer")."</th><td> $object->security_question_answer_2</td></tr>
		<tr><th>".str("Created At")."</th><td> $object->created_at</td></tr>
		<tr><th>".str("Updated At")."</th><td> $object->updated_at</td></tr>
		<tr><th>".str("Deleted At")."</th><td> $object->deleted_at</td></tr>
		<tr><th>".str("Failed Attempt")."</th><td> $object->failed_attempt</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
  $filter = "agent = 0";
  if($function == 'agent'){
  	$filter = "agent = 1";
  }
  openFilterForm("get");
  print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
  $name = isf("name", "name", $filter, $get);
  print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
  $email = isf("email", "email", $filter, $get);
  print str("Email")." <input type='text' name='email' value='$email' class='form-control-fluid' /> ";
  $phone = isf("phone", "phone", $filter, $get);
  print str("Phone")." <input type='text' name='phone' value='$phone' class='form-control-fluid' /> ";
  $identity = isf("identity", "identity", $filter, $get);
  print str("ID")." <input type='text' name='identity' value='$identity' class='form-control-fluid' /> ";
  closeFilterForm();
	
	$nor = num_rows("a.*", "member a", "$filter");
	$nop = ceil($nor/$offset); if($page>$nop) $page=1;

	$start = ($page-1)*$offset;
	$members = select("a.*", "member a", "$filter", "ORDER BY a.name LIMIT $start, $offset");
	print "<hr>";
	if($function == 'agent'){
		print "<div class='frht'><a href='add_agent' class='btn btn-primary'><i class='fa fa-plus'></i> Add or <i class='fa fa-edit'></i> Edit Agent</a></div>";
	}
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Email")."</th><th>".str("Phone")."</th><th>".str("Agent")."</th><th>".str("ID")."</th><th></th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($member = mysqli_fetch_object($members)){
		print "<tr><td><a href='view/$member->id'>$i</a></td>
			<td>$member->name</td>
			<td>$member->email</td>
			<td>$member->phone</td>
			<td>".($member->agent?"Yes":"No")."</td>
			<td>$member->identity</td>
			<td>".options2("", $member->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(7, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>