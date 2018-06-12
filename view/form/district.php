<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("District Details")."</b></td><tr>
		<tr><td>".str("Division")."</td><td>".selectOption("name='division' id='division' class='form-control selectpicker' data-live-search='true'", 'division', 'name', 'id',$object->division)."</td><td>".space(5)."</td><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td></tr>
	</table>";
closeForm();