<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Payment Method Details")."</b></td><tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td><td>".str("Charge Type")."</td><td>".selectEnum("name='charge_type' id='charge_type'  class='form-control'", 'payment_method', 'charge_type',$object->charge_type)."</td></tr>
		<tr><td>".str("Service Charge")."</td><td><input type='text' name='service_charge' id='service_charge' value='$object->service_charge' class='form-control required number' /></td><td>".space(5)."</td><td>".str("Gst")."</td><td><input type='text' name='gst' id='gst' value='$object->gst' class='form-control required number' /></td></tr>
	</table>";
closeForm();