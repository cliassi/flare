<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Writer Details")."</b></td><tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control' required /></td><td>".space(5)."</td><td>".str("NID")."</td><td><input type='text' name='nid' id='nid' value='$object->nid' class='form-control' required /></td></tr>
		<tr><td>".str("Blood Group")."</td><td>".selectEnum("name='blood_group' id='blood_group'  class='form-control'", 'writer', 'blood_group',$object->blood_group)."</td><td>".space(5)."</td>
			<td>".str("Passport")."</td>
			<td><input type='text' name='passport' id='passport' value='$object->passport' class='form-control ' /></td></tr>
		<tr><td colspan='5'><b>".str("Address & Contact Details")."</b></td><tr>
		<tr><td>".str("Premanent Address")."</td><td><textarea name='premanent_address' id='premanent_address' class='form-control' required>$object->premanent_address</textarea></td><td>".space(5)."</td><td>".str("Present Address")."</td><td><textarea name='present_address' id='present_address' class='form-control' required>$object->present_address</textarea></td>
		</tr>
		<tr>
			<td>".str("Division")."</td>
			<td>".sop2("division", $object->division, ['class'=>'selectfilter', 'attr'=>"data-type='division' data-filter='district'"])."</td>
			<td>".space(5)."</td>
			<td>".str("District")."</td>
			<td>".sop2("district", $object->district, ['class'=>'selectfilter', 'attr'=>"data-type='district' data-filter='upazila'", 'extraFields'=>'division'])."</td>
		</tr>
		<tr>
			<td>".str("Upazila")."</td>
			<td>".sop2("upazila", $object->upazila, ['extraFields'=>'district'])."</td>
		</tr>
		<tr>
			<td>".str("Phone")."</td><td><input type='text' name='phone' id='phone' value='$object->phone' class='form-control ' /></td>
			<td>".space(5)."</td>
		</tr>
		<tr><td colspan='5'><b>".str("Account Details")."</b></td><tr>
		<tr>
			<td>".str("Bkash Number")."</td>
			<td><input type='text' name='bkash_number' id='bkash_number' value='$object->bkash_number' class='form-control' required /></td>
			<td>".space(5)."</td>
			<td>".str("Bank Account Details")."</td>
			<td><input type='text' name='bank_account_details' id='bank_account_details' value='$object->bank_account_details' class='form-control' required /></td>
		</tr>
		<tr><td>".str("Rank")."</td><td>".selectEnum("name='rank' id='rank'  class='form-control'", 'writer', 'rank',$object->rank)."</td><td>".space(5)."</td></tr>
		<tr><td colspan='5'><b>".str("Login Credentials")."</b></td><tr>
		<tr><td>".str("Email (Username)")."</td><td><input type='email' name='email' id='email' value='$user->u_username' class='form-control' required /></td><td>".space(5)."</td><td>".str("Password")."</td><td><input type='password' name='password' id='password' value='' min='".($function=='add'?5:0)."' class='form-control' /></td></tr>
	</table>";
closeForm();