<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Member Details")."</b></td><tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td><td>".str("Email")."</td><td><input type='text' name='email' id='email' value='$object->email' class='form-control required email' /></td></tr>
		<tr><td>".str("Phone")."</td><td><input type='text' name='phone' id='phone' value='$object->phone' class='form-control required' /></td><td>".space(5)."</td><td>".str("Password")."</td><td><input type='text' name='password' id='password' value='$object->password' class='form-control required' /></td></tr>
		<tr><td>".str("Agent")."?</td><td><input type='checkbox' name='agent' id='agent' ".($object->agent?"checked":"")." class='form-control required number' value='1' /></td><td>".space(5)."</td><td>".str("Identity")."</td><td><input type='text' name='identity' id='identity' value='$object->identity' class='form-control ' /></td></tr>
		<tr><td>".str("Date Of Birth")."</td><td>".dateSelector("date_of_birth", $object->date_of_birth)."</td><td>".space(5)."</td>
	</table>";
closeForm();