<?php
openForm('post', true);
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Publisher Details")."</b></td><tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td><td>".str("License No")."</td><td><input type='text' name='license_no' id='license_no' value='$object->license_no' class='form-control ' /></td></tr>
		<tr><td>".str("Logo")."</td><td><input type='file' name='logo' id='logo' value='$object->logo' class='form-control ' /></td><td>".space(5)."</td><td>".str("Address")."</td><td><textarea name='address' id='address' class='form-control '>$object->address</textarea></td></tr>
		<tr><td>".str("Phone")."</td><td><textarea name='phone' id='phone' class='form-control '>$object->phone</textarea></td><td>".space(5)."</td><td>".str("Email")."</td><td><textarea name='email' id='email' class='form-control email'>$object->email</textarea></td></tr>
		<tr><td>".str("Website")."</td><td><input type='text' name='website' id='website' value='$object->website' class='form-control ' /></td><td>".space(5)."</td><td>".str("Contact Person")."</td><td><input type='text' name='contact_person' id='contact_person' value='$object->contact_person' class='form-control ' /></td></tr>
		<tr><td>".str("Contact Person Phone")."</td><td><input type='text' name='contact_person_phone' id='contact_person_phone' value='$object->contact_person_phone' class='form-control ' /></td><td>".space(5)."</td><td>".str("Contact Person Email")."</td><td><input type='text' name='contact_person_email' id='contact_person_email' value='$object->contact_person_email' class='form-control email' /></td></tr>
		<tr><td>".str("Bank Account Details")."</td><td><textarea name='account_details' id='account_details' class='form-control '>$object->account_details</textarea></td><td>".space(5)."</td><td>".str("Description")."</td><td><textarea name='description' id='description' class='form-control '>$object->description</textarea></td></tr>
		<tr><td>".str("Notes")."</td><td><textarea name='notes' id='notes' class='form-control '>$object->notes</textarea></td><td>".space(5)."</td></tr>
		<tr><td colspan='5'><b>".str("User Details")."</b></td><tr>
		<tr><td>Username</td><td><input type='email' name='username' id='username' readonly class='form-control' value='$object->email'></td><td>".space(5)."</td><td>Password</td><td><input type='password' name='password' id='password' class='form-control'></td></tr>
	</table>";
closeForm();
?>
<script type="text/javascript">
	$("#email").keyup(copyEmail).blur(copyEmail);

	function copyEmail(){
		$("#username").val($("#email").val());
	}
</script>