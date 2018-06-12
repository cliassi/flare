<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Ad Request Details")."</b></td><tr>
		<tr><td>".str("Member")."</td><td>".selectOption("name='member' id='member' class='form-control selectpicker' data-live-search='true'", 'member', 'name', 'id',$object->member)."</td><td>".space(5)."</td><td>".str("Publisher")."</td><td>".selectOption("name='publisher' id='publisher' class='form-control selectpicker' data-live-search='true'", 'publisher', 'name', 'id',$object->publisher)."</td></tr>
		<tr><td>".str("Service")."</td><td>".selectOption("name='service' id='service' class='form-control selectpicker' data-live-search='true'", 'service', 'name', 'id',$object->service)."</td><td>".space(5)."</td><td>".str("Page")."</td><td><input type='text' name='page' id='page' value='$object->page' class='form-control required number' /></td></tr>
		<tr><td>".str("Date")."</td><td>".dateSelector("date", $object->date)."</td><td>".space(5)."</td><td>".str("Message")."</td><td><textarea name='message' id='message' class='form-control required'>$object->message</textarea></td></tr>
		<tr><td>".str("Price")."</td><td><input type='text' name='price' id='price' value='$object->price' class='form-control required number' /></td><td>".space(5)."</td><td>".str("Tax")."</td><td><input type='text' name='tax' id='tax' value='$object->tax' class='form-control required number' /></td></tr>
		<tr><td>".str("Service Charge")."</td><td><input type='text' name='service_charge' id='service_charge' value='$object->service_charge' class='form-control number' /></td><td>".space(5)."</td><td>".str("Total")."</td><td><input type='text' name='total' id='total' value='$object->total' class='form-control number' /></td></tr>
	</table>";
closeForm();