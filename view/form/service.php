<?php
openForm();
print "<table align='center'>
		<tr>
			<td colspan='5'><b>".str("Service Details")."</b></td>
		<tr>
		<tr>
			<td>".str("Name")."</td>
			<td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td>
			<td>".space(5)."</td>
			<td>".str("Service Type")."</td>
			<td>".selectOption("name='service_type' id='service_type' class='form-control selectpicker' required data-live-search='true'", 'service_type', 'name', 'id',$object->service_type)."</td>
		</tr>
		<tr>
			<td>".str("Frequency")."</td>
			<td>".selectOption("name='frequency' id='frequency' class='form-control selectpicker' required data-live-search='true'", 'service_frequency', 'name', 'id',$object->frequency)."</td>
			<td>".space(5)."</td>
			<td>".str("Number Of Pages")."</td>
			<td><input type='number' name='number_of_pages' id='number_of_pages' value='$object->number_of_pages' required class='form-control number' /></td>
		</tr>
		<tr>
			<td>".str("Payment Term")."</td>
			<td>".selectEnum("name='payment_term' id='payment_term'  class='form-control'", 'service', 'payment_term', $object->payment_term)."</td><td>".space(5)."</td>
			<td>".str("Payment Method")."</td>
			<td>".selectOption("name='payment_method' id='payment_method' class='form-control selectpicker' data-live-search='true'", 'payment_method', 'name', 'id',$object->payment_method)."</td>
		</tr>
		<tr>
			<td>".str("Division")."</td>
			<td>".sop2("division[]", $object->division, ['class'=>'selectfilter', 'attr'=>"data-type='division' data-filter='district'"], "division")."</td>
			<td>".space(5)."</td>
			<td>".str("District")."</td>
			<td>".sop2("district[]", $object->district, ['class'=>'selectfilter', 'attr'=>"data-type='district' data-filter='upazila'", 'extraFields'=>'division'], "district")."</td>
		</tr>
		<tr>
			<td>".str("Upazila")."</td>
			<td>".sop2("upazila[]", $object->upazila, ['extraFields'=>'district'], "upazila")."</td>
			<td>".space(5)."</td>
			<td>".str("Publisher")."</td>
			<td>".sop2("publisher", $object->publisher)."</td>
		</tr>
		<tr>
			<td>".str("Description")."</td>
			<td><textarea name='remarks' class='form-control'></textarea></td>
		</tr>
	</table>";
closeForm();
?>
<script type="text/javascript">
	$(".division").change(divisionChanged);
	function divisionChanged(){
		division = $(".division").val();
		if(division==1){
			$(".district").hide();
		} else{
			$(".district").show();
		}
	}
</script>