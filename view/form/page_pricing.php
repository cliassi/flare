<?php
openForm();
print "<table align='center'>
	<tr>
		<td colspan='5'><b>".str("Page Pricing Details")."</b></td>
	<tr>
	<tr>
		<td>".str("Start Date")."</td>
		<td>".dateSelector("start_date", $object->start_date)."</td>
		<td>".space(5)."</td>
		<td>".str("End Date")."</td>
		<td>".dateSelector("end_date", $object->end_date)."</td>
	</tr>
	<tr>
		<td>".str("Days")."</td>
		<td><input type='text' name='days' id='days' value='$object->days' class='form-control ' /></td>
		<td>".space(5)."</td>
		<td>".str("Publisher")."</td>
		<td><input type='text' name='publisher' id='publisher' value='$object->publisher' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Service")."</td>
		<td><input type='text' name='service' id='service' value='$object->service' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Page")."</td>
		<td><input type='text' name='page' id='page' value='$object->page' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Language")."</td>
		<td>".selectEnum("name='language' id='language'  class='form-control'", 'page_pricing', 'language',$object->language)."</td>
		<td>".space(5)."</td>
		<td>".str("Width")."</td>
		<td><input type='text' name='width' id='width' value='$object->width' class='form-control required' /></td>
	</tr>
	<tr>
		<td>".str("Height")."</td>
		<td><input type='text' name='height' id='height' value='$object->height' class='form-control required' /></td>
		<td>".space(5)."</td>
		<td>".str("Column Size")."</td>
		<td><input type='text' name='column_size' id='column_size' value='$object->column_size' class='form-control required' /></td>
	</tr>
	<tr>
		<td>".str("Min Col")."?</td>
		<td><input type='checkbox' name='min_col' id='min_col' ".($object->min_col?"checked":"")." class='form-control required number' value='1' /></td>
		<td>".space(5)."</td>
		<td>".str("Max Col")."?</td>
		<td><input type='checkbox' name='max_col' id='max_col' ".($object->max_col?"checked":"")." class='form-control required number' value='1' /></td>
	</tr>
	<tr>
		<td>".str("Min Col Width")."</td>
		<td><input type='text' name='min_col_width' id='min_col_width' value='$object->min_col_width' class='form-control required' /></td>
		<td>".space(5)."</td>
		<td>".str("Max Col Width")."</td>
		<td><input type='text' name='max_col_width' id='max_col_width' value='$object->max_col_width' class='form-control required' /></td>
	</tr>
	<tr>
		<td>".str("Bw Unit Price")."</td>
		<td><input type='text' name='bw_unit_price' id='bw_unit_price' value='$object->bw_unit_price' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Color Unit Price")."</td>
		<td><input type='text' name='color_unit_price' id='color_unit_price' value='$object->color_unit_price' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Min Words")."</td>
		<td><input type='text' name='min_words' id='min_words' value='$object->min_words' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Max Words")."</td>
		<td><input type='text' name='max_words' id='max_words' value='$object->max_words' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Init Words")."</td>
		<td><input type='text' name='init_words' id='init_words' value='$object->init_words' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Additional Word Price")."</td>
		<td><input type='text' name='additional_word_price' id='additional_word_price' value='$object->additional_word_price' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Fixed Place Rate")."</td>
		<td><input type='text' name='fixed_place_rate' id='fixed_place_rate' value='$object->fixed_place_rate' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Bold Text Rate")."</td>
		<td><input type='text' name='bold_text_rate' id='bold_text_rate' value='$object->bold_text_rate' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("First Position Rate")."</td>
		<td><input type='text' name='first_position_rate' id='first_position_rate' value='$object->first_position_rate' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("In Screen Rate")."</td>
		<td><input type='text' name='in_screen_rate' id='in_screen_rate' value='$object->in_screen_rate' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("In Box Rate")."</td>
		<td><input type='text' name='in_box_rate' id='in_box_rate' value='$object->in_box_rate' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Daily Approval Limit")."?</td>
		<td><input type='checkbox' name='daily_approval_limit' id='daily_approval_limit' ".($object->daily_approval_limit?"checked":"")." class='form-control required number' value='1' /></td>
	</tr>
	<tr>
		<td>".str("Master Agent Commission Bw")."</td>
		<td><input type='text' name='master_agent_commission_bw' id='master_agent_commission_bw' value='$object->master_agent_commission_bw' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Agent Commission Bw")."</td>
		<td><input type='text' name='agent_commission_bw' id='agent_commission_bw' value='$object->agent_commission_bw' class='form-control required number' /></td>
	</tr>
	<tr>
		<td>".str("Master Agent Commission Color")."</td>
		<td><input type='text' name='master_agent_commission_color' id='master_agent_commission_color' value='$object->master_agent_commission_color' class='form-control required number' /></td>
		<td>".space(5)."</td>
		<td>".str("Agent Commission Color")."</td>
		<td><input type='text' name='agent_commission_color' id='agent_commission_color' value='$object->agent_commission_color' class='form-control required number' /></td>
	</tr>
	</table>";
closeForm();
?>
<script type="text/javascript">
	
</script>