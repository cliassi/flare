<?php
openForm();
print "<table align='center'>
		<tr>
			<td colspan='5'><b>".str("Page Details")."</b></td>
		<tr>
		<tr>
			<td>".str("Service")."</td>
			<td>".selectOption("name='service' id='service' class='form-control selectpicker' data-live-search='true'", 'service', 'name', 'id',$object->service)."</td>
			<td>".space(5)."</td>
			<td>".str("Section Name")."</td>
			<td><input type='text' name='name' id='name' value='$object->name' class='form-control required' /></td>
		</tr>
		<tr>
			<td>".str("Page Number")."</td>
			<td><input type='number' name='page_number' id='page_number' class='form-control required number' value='$object->page_number' min='1' max='$service->number_of_pages' /></td>
			<td>".space(5)."</td>
		</tr>
		<tr>
			<td>".str("Printable Width")."</td>
			<td><input type='text' name='printable_width' id='printable_width' value='$object->printable_width' class='form-control required' /></td>
			<td>".space(5)."</td>
			<td>".str("Printable Height")."</td>
			<td><input type='text' name='printable_height' id='printable_height' value='$object->printable_height' class='form-control required' /></td>
		</tr>
	</table>";
closeForm();
?>

<script type="text/javascript">
	getServicePageNumber();
	function getServicePageNumber(){
		$.post("<?php print $appurl; ?>/ajax/service_page.php", {'id': $("#service").val()}, function (data) {
			console.log(data);
			$("#page_number").attr("max", data);
			value = parseInt($("#page_number").val());
			pages = parseInt(data);
			if(!isNaN(value)){
				if(value > data){
					$("#page_number").val(1);
				}
			}
		});
	}
	
</script>