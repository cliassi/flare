<?php
openForm();
print "<table align='center'>
	<tr><td colspan='5'><b>".str("Agent Details")."</b></td><tr>
		<tr><td>".str("Parent Agent")."</td><td>".selectOption("name='parent_agent' id='parent_agent' class='form-control selectpicker' data-live-search='true'", 'agent', 'name', 'id',$object->parent_agent)."</td><td>".space(5)."</td><td>".str("Name")."</td><td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td></tr>
		<tr><td>".str("NID")."</td><td><input type='text' name='nid' id='nid' value='$object->nid' class='form-control required' /></td><td>".space(5)."</td><td>".str("Blood Group")."</td><td>".selectEnum("name='blood_group' id='blood_group'  class='form-control'", 'agent', 'blood_group',$object->blood_group)."</td></tr>
		<tr><td>".str("Premanent Address")."</td><td><textarea name='premanent_address' id='premanent_address' class='form-control required'>$object->premanent_address</textarea></td><td>".space(5)."</td><td>".str("Present Address")."</td><td><textarea name='present_address' id='present_address' class='form-control required'>$object->present_address</textarea></td></tr>
		<tr><td>".str("Passport")."</td><td><input type='text' name='passport' id='passport' value='$object->passport' class='form-control ' /></td><td>".space(5)."</td><td>".str("Agent Id")."</td><td><input type='text' name='agent_id' id='agent_id' value='$object->agent_id' class='form-control required' /></td></tr>
		
		<tr>
			<td>".str("Division")."</td>
			<td>".sop2("division", $object->division, ['class'=>'selectfilter', 'attr'=>"data-type='division' data-filter='district'"])."</td>
			<td>".space(5)."</td>
			<td>".str("District")."</td>
			<td>".sop2("district", $object->district, ['class'=>'selectfilter', 'attr'=>"data-type='district' data-filter='upazila'", 'extraFields'=>'division'])."</td>
		</tr>
		<tr>
			<td>".str("Upazila")."</td>
			<td>".sop2("upazila", $object->upazila, ['extraFields'=>'district'])."</td>
			<td>".space(5)."</td>
			<td>".str("Level")."</td><td>".selectEnum("name='level' id='level'  class='form-control'", 'agent', 'level',$object->level)."</td>
		</tr>
		<tr>
			<td>".str("Active")."?</td>
			<td><input type='checkbox' name='active' id='active' ".($object->active?"checked":"")." class='form-control required number' value='1' /></td>
		</tr>
	</table>";
closeForm();