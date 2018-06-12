<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Layout Details")."</b></td><tr>
		<tr><td>".str("Service")."</td><td>".selectOption("name='service' id='service' class='form-control selectpicker' data-live-search='true'", 'service', 'name', 'id',$object->service)."</td><td>".space(5)."</td><td>".str("Page")."</td><td>".selectOption("name='page' id='page' class='form-control selectpicker' data-live-search='true'", 'page', 'name', 'id',$object->page)."</td></tr>
		<tr><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td><td>".space(5)."</td><td>".str("Offset X")."</td><td><input type='text' name='offsetx' id='offsetx' value='$object->offsetx' class='form-control required' /></td></tr>
		<tr><td>".str("Offset Y")."</td><td><input type='text' name='offsety' id='offsety' value='$object->offsety' class='form-control required' /></td><td>".space(5)."</td><td>".str("Width")."</td><td><input type='text' name='width' id='width' value='$object->width' class='form-control required' /></td></tr>
		<tr><td>".str("Height")."</td><td><input type='text' name='height' id='height' value='$object->height' class='form-control required' /></td><td>".space(5)."</td><td>".str("Language")."</td><td>".selectEnum("name='language' id='language'  class='form-control'", 'layout', 'language',$object->language)."</td></tr>
	</table>";
closeForm();