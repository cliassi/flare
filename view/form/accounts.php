<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>Accounts Details</b></td><tr>
		<tr><td>Name</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td></tr>
		<tr><td>Category</td><td>".sop2("account_category", $object->account_category)."</td></tr>
		<tr><td>Currency</td><td>".selectOption("name='currency' class='form-control'", "country", "currency", "currency", $object->currency, "active=1")."</td></tr>
		<tr><td>Opening Balance</td><td><input type='text' name='opening_balance' id='opening_balance' value='$object->opening_balance' class='form-control required number' /></td></tr>
	</table>";
closeForm();