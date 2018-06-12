<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Pricing Details")."</b></td><tr>
		<tr><td>".str("Publisher")."</td><td>".selectOption("name='publisher' id='publisher' class='form-control selectpicker' data-live-search='true'", 'publisher', 'name', 'id',$object->publisher)."</td><td>".space(5)."</td><td>".str("Service")."</td><td>".selectOption("name='service' id='service' class='form-control selectpicker' data-live-search='true'", 'service', 'name', 'id',$object->service)."</td></tr>
		<tr><td>".str("Page")."</td><td>".selectOption("name='page' id='page' class='form-control selectpicker' data-live-search='true'", 'page', 'name', 'id',$object->page)."</td><td>".space(5)."</td><td>".str("Layout")."</td><td>".selectOption("name='layout' id='layout' class='form-control selectpicker' data-live-search='true'", 'layout', 'name', 'id',$object->layout)."</td></tr>
		<tr><td>".str("Start Date")."</td><td>".dateSelector("start_date", $object->start_date)."</td><td>".space(5)."</td><td>".str("End Date")."</td><td>".dateSelector("end_date", $object->end_date)."</td></tr>
		<tr><td>".str("Price")."</td><td><input type='text' name='price' id='price' value='$object->price' class='form-control required number' /></td><td>".space(5)."</td><td>".str("Master Agent Price")."</td><td><input type='text' name='master_agent_price' id='master_agent_price' value='$object->master_agent_price' class='form-control number' /></td></tr>
		<tr><td>".str("Agent Price")."</td><td><input type='text' name='agent_price' id='agent_price' value='$object->agent_price' class='form-control number' /></td><td>".space(5)."</td>
	</table>";
closeForm();