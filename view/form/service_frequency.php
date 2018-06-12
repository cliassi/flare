<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Service Frequency Details")."</b></td><tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td><td>".str("Description")."</td><td><textarea name='description' id='description' class='form-control '>$object->description</textarea></td></tr>
	</table>";
closeForm();