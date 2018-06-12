<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Upazila Details")."</b></td><tr>
		<tr><td>".str("Division")."</td><td>".selectOption("name='division' id='division' class='form-control selectpicker' data-live-search='true'", 'division', 'name', 'id',$object->division)."</td><td>".space(5)."</td><td>".str("District")."</td><td>".selectOption("name='district' id='district' class='form-control selectpicker' data-live-search='true'", 'district', 'name', 'id',$object->district)."</td></tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td>
	</table>";
closeForm();