
<?php
if($id){
	print "<table class='table table-responsive table-striped table-bordered table-detailed-view'>
		<tr><th>".str("Start Date")."</th><td> $object->start_date</td></tr>
		<tr><th>".str("End Date")."</th><td> $object->end_date</td></tr>
		<tr><th>".str("Days")."</th><td> $object->days</td></tr>
		<tr><th>".str("Publisher")."</th><td> $object->publisher</td></tr>
		<tr><th>".str("Service")."</th><td> $object->service</td></tr>
		<tr><th>".str("Page")."</th><td> $object->page</td></tr>
		<tr><th>".str("Language")."</th><td> $object->language</td></tr>
		<tr><th>".str("Width")."</th><td> $object->width</td></tr>
		<tr><th>".str("Height")."</th><td> $object->height</td></tr>
		<tr><th>".str("Column Size")."</th><td> $object->column_size</td></tr>
		<tr><th>".str("Min Col")."</th><td> $object->min_col</td></tr>
		<tr><th>".str("Max Col")."</th><td> $object->max_col</td></tr>
		<tr><th>".str("Min Col Width")."</th><td> $object->min_col_width</td></tr>
		<tr><th>".str("Max Col Width")."</th><td> $object->max_col_width</td></tr>
		<tr><th>".str("Bw Unit Price")."</th><td> $object->bw_unit_price</td></tr>
		<tr><th>".str("Color Unit Price")."</th><td> $object->color_unit_price</td></tr>
		<tr><th>".str("Min Words")."</th><td> $object->min_words</td></tr>
		<tr><th>".str("Max Words")."</th><td> $object->max_words</td></tr>
		<tr><th>".str("Init Words")."</th><td> $object->init_words</td></tr>
		<tr><th>".str("Additional Word Price")."</th><td> $object->additional_word_price</td></tr>
		<tr><th>".str("Fixed Place Rate")."</th><td> $object->fixed_place_rate</td></tr>
		<tr><th>".str("Bold Text Rate")."</th><td> $object->bold_text_rate</td></tr>
		<tr><th>".str("First Position Rate")."</th><td> $object->first_position_rate</td></tr>
		<tr><th>".str("In Screen Rate")."</th><td> $object->in_screen_rate</td></tr>
		<tr><th>".str("In Box Rate")."</th><td> $object->in_box_rate</td></tr>
		<tr><th>".str("Daily Approval Limit")."</th><td> $object->daily_approval_limit</td></tr>
		<tr><th>".str("Master Agent Commission Bw")."</th><td> $object->master_agent_commission_bw</td></tr>
		<tr><th>".str("Agent Commission Bw")."</th><td> $object->agent_commission_bw</td></tr>
		<tr><th>".str("Master Agent Commission Color")."</th><td> $object->master_agent_commission_color</td></tr>
		<tr><th>".str("Agent Commission Color")."</th><td> $object->agent_commission_color</td></tr>
  </table>";
  back();
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
	    $filter = "";
	    openFilterForm("get");
	    print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";
	    $start_date = isf("start_date", "start_date", $filter, $get);
	    print str("Start Date")." <input type='date' name='start_date' value='$start_date' class='form-control-fluid' /> ";
	    $end_date = isf("end_date", "end_date", $filter, $get);
	    print str("End Date")." <input type='date' name='end_date' value='$end_date' class='form-control-fluid' /> ";
	    $publisher = isf("publisher", "publisher", $filter, $get);
	    print str("Publisher")." <input type='text' name='publisher' value='$publisher' class='form-control-fluid' /> ";
	    $service = isf("service", "service", $filter, $get);
	    print str("Service")." <input type='text' name='service' value='$service' class='form-control-fluid' /> ";
	    $page = isf("page", "page", $filter, $get);
	    print str("Page")." <input type='text' name='page' value='$page' class='form-control-fluid' /> ";
	    closeFilterForm();
	
	$nor = num_rows("a.*", "page_pricing a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$page_pricings = select("a.*", "page_pricing a", "$filter", "LIMIT $start, $offset");
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Start Date")."</th><th>".str("End Date")."</th><th>".str("Days")."</th><th>".str("Publisher")."</th><th>".str("Service")."</th><th>".str("Page")."</th><th>".str("Language")."</th><th>".str("Width")."</th><th>".str("Height")."</th><th>".str("Column Size")."</th><th>".str("Min Col")."</th><th>".str("Max Col")."</th><th>".str("Min Col Width")."</th><th>".str("Max Col Width")."</th><th>".str("Bw Unit Price")."</th><th>".str("Color Unit Price")."</th><th>".str("Min Words")."</th><th>".str("Max Words")."</th><th>".str("Init Words")."</th><th>".str("Additional Word Price")."</th><th>".str("Fixed Place Rate")."</th><th>".str("Bold Text Rate")."</th><th>".str("First Position Rate")."</th><th>".str("In Screen Rate")."</th><th>".str("In Box Rate")."</th><th>".str("Daily Approval Limit")."</th><th>".str("Master Agent Commission Bw")."</th><th>".str("Agent Commission Bw")."</th><th>".str("Master Agent Commission Color")."</th><th>".str("Agent Commission Color")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($page_pricing = mysqli_fetch_object($page_pricings)){
		print "<tr><td><a href='view/$page_pricing->id'>$i</a></td>
			<td>$page_pricing->start_date</td>
			<td>$page_pricing->end_date</td>
			<td>$page_pricing->days</td>
			<td>$page_pricing->publisher</td>
			<td>$page_pricing->service</td>
			<td>$page_pricing->page</td>
			<td>$page_pricing->language</td>
			<td>$page_pricing->width</td>
			<td>$page_pricing->height</td>
			<td>$page_pricing->column_size</td>
			<td>".($page_pricing->min_col?"Yes":"No")."</td>
			<td>".($page_pricing->max_col?"Yes":"No")."</td>
			<td>$page_pricing->min_col_width</td>
			<td>$page_pricing->max_col_width</td>
			<td>$page_pricing->bw_unit_price</td>
			<td>$page_pricing->color_unit_price</td>
			<td>$page_pricing->min_words</td>
			<td>$page_pricing->max_words</td>
			<td>$page_pricing->init_words</td>
			<td>$page_pricing->additional_word_price</td>
			<td>$page_pricing->fixed_place_rate</td>
			<td>$page_pricing->bold_text_rate</td>
			<td>$page_pricing->first_position_rate</td>
			<td>$page_pricing->in_screen_rate</td>
			<td>$page_pricing->in_box_rate</td>
			<td>".($page_pricing->daily_approval_limit?"Yes":"No")."</td>
			<td>$page_pricing->master_agent_commission_bw</td>
			<td>$page_pricing->agent_commission_bw</td>
			<td>$page_pricing->master_agent_commission_color</td>
			<td>$page_pricing->agent_commission_color</td>
			<td>".options2("", $page_pricing->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(32, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>