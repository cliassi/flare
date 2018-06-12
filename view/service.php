<style type="text/css">
	#layout{
		float: right;
    width: 200px;
    min-height: 400px;
    border: #ccc 1px solid;
    margin: 0 10px 0 0;
    padding: 10px;
	}
	.btn-date{
		padding: 4px;
	}
	input[type=checkbox]{
		display: none;
	}
</style>
<?php
$payment_methods = toA("payment_method");
$date = isset($get->date)?$get->date:today();
$end_date = isset($get->date)?$get->date:nextMonth();
$page = isset($get->page)?$get->page:1;

///DELETING PRICING
if(isset($get->delete) && !isset($get->confirm)){
?>
<script type="text/javascript">
	if(confirm("Are you sure you want to delete this pricing?")){
		location.href = "?<?php print "page=$get->page&delete=$get->delete&confirm"; ?>";
	}
</script>
<?php
} elseif(isset($get->delete) && isset($get->confirm)){
	$page_pricing = R::load("page_pricing", $get->delete);
	del("page_pricing_date", "`pricing`=$page_pricing->id");
	R::trash($page_pricing);
	redir("?page=$page&date=$date");
}
if($id){
	$page_info = R::findOne("page", "service=? AND page_number=?", [$object->id, $page]);
	if(!$page_info){
		$page_info = R::dispense("page");
		$page_info->service = $id;
		$page_info->page_number = $page;
		R::store($page_info);
	}
	// dd($post->date);
	if(isset($post->save_page)){
		$page_info->number_of_columns = $post->number_of_column;
		$page_info->printable_width = $post->width;
		$page_info->printable_height = $post->height;
		$page_info->column_size = $post->column_size;
		R::store($page_info);
	}
	if(isset($post->date)){
		if(isset($get->edit)){
			$page_pricing = R::load('page_pricing', $get->edit);
		} else{
			$page_pricing = R::dispense('page_pricing');
		}
		//  else{
		// 	$page_pricing = R::findOne('page_pricing', 'service=? AND page_number=? AND date=?', [$object->id, $page, $date]);
		// }
		// if(!$page_pricing){
		// 	$page_pricing = R::dispense('page_pricing');
		// }
		$fields = ['name', 'publisher', 'service', 'page', 'language', 'width', 'height', 'column_size', 'min_col', 'max_col', 'min_col_width', 'max_col_width', 'bw_unit_price', 'color_unit_price', 'min_words', 'max_words', 'init_words', 'additional_word_price', 'fixed_place_rate', 'bold_text_rate', 'first_position_rate', 'in_screen_rate', 'in_box_rate', 'daily_approval_limit', 'master_agent_commission_bw', 'agent_commission_bw', 'master_agent_commission_color', 'agent_commission_color', 'number_of_column', 'price'];
		foreach ($fields as $field) {
			if(isset($post->$field) && nn($post->$field)) {
				$page_pricing->$field = $post->$field;
			}
		}
		$page_pricing->master_agent_payment_method = implode(",", $post->master_agent_payment_method);
		$page_pricing->agent_payment_method = implode(",", $post->agent_payment_method);
		$page_pricing->advertiser_payment_method = implode(",", $post->advertiser_payment_method);
		$page_pricing->page = $page;
		$page_pricing->publisher = $object->publisher;
		$page_pricing->service = $object->id;
		R::store($page_pricing); 
		del("page_pricing_date", "`pricing`=$page_pricing->id");
		foreach ($post->date as $key => $value) {
			insert("page_pricing_date", "`date`, `pricing`", "'$value', $page_pricing->id");
		}
		redir("?page=$page&date=$date");
	}
	print "<div class='center'>Date <input type'text' name='date' id='date' class='datepicker form-control-fluid' autocomplete='off' value='$date'></div>";
	$tabs = "";
	$tab_content = "";
	for($i=1; $i<=$object->number_of_pages; $i++){
		$tabs .= "<li".($page == $i?" class='active'":"")."><a href='?page=$i&date=$date'>$i</a></li>";
		print "<a href='?page=$i&date=$date' class='btn btn-".($page==$i?'success':'default')."'>$i</a>";
		$tab_content .= "<div id='page-$i' class='tab-pane fade in ".($page == $i?"active":"")."'></div>";
	}
	// print "Select Page: <ul class='nav nav-pills nav-justified'>$tabs</ul>";
	print "<div class='tab-content'>$tab_content</div>";
?>
<hr>
	<div class="panel panel-default">
	  <div class="panel-heading center">Page <?php print $page; ?> Setup (Common Setup One Time) <span> <?php print "<a href='?page=$page&date=$date&edit_page=$page' class='frht'><i class='fa fa-edit'></i></a></span>"; ?></div>
	  <div class="panel-body">
	  	<form method="post">
	  	<table class="table table-border">
	  	<?php if(isset($get->edit_page) || !$page_info->column_size){ ?>
	  		<tr>
	  			<th>Page Number</th><td><?php print $page; ?></td>
	  			<th>Number of Columns</th><td><?php print createSelectOption("name='number_of_column'", 1, 12, $page_info->number_of_columns); ?></td>
	  			<th>Printable Size</th><td><input type="number" name='width' class="form-control-fluid w80" placeholder="W" value="<?php print $page_info->printable_width; ?>"> x <input type="number" name="height" class="form-control-fluid w80" placeholder="H" value="<?php print $page_info->printable_height; ?>"></td>
	  		</tr>
	  		<tr>
	  			<th>Column Size</th><td><input type="number" name="column_size" class="form-control-fluid w80" value="<?php print $page_info->column_size; ?>"></td>
	  			<th></th><td></td>
	  			<th></th><td class='right'><a class='btn btn-danger' href='?<?php print "page=$page&date=$date"; ?>'>Cancel</a><?php print space(4); ?><button name='save_page' class='btn btn-success'>Save</button></td>
	  		</tr>
	  	<?php } else{ ?>
	  		<tr>
	  			<th>Page Number</th><td><?php print $page; ?></td>
	  			<th>Number of Columns</th><td><?php print $page_info->number_of_columns; ?></td>
	  			<th>Printable Size</th><td><?php print $page_info->printable_width; ?> x <?php print $page_info->printable_height; ?></td>
	  		</tr>
	  		<tr>
	  			<th>Column Size</th><td><?php print $page_info->column_size; ?></td>
	  			<th></th><td></td>
	  		</tr>
	  	<?php } ?>
	  	</table>
	  	</form>
	  </div>
	</div>
<?php
if($page_info->column_size){
	print '<form method="post">';
	// $page_pricing = selectp("*", "page_pricing", "page=$page AND service=$object->id AND id IN (SELECT pricing FROM page_pricing_date WHERE `date`='$date')");
	$page_pricing = select("p.*", "page_pricing p, page_pricing_date d", "p.id=d.pricing AND page=$page AND service=$object->id AND d.date='$date'", "GROUP BY d.pricing");
	$i = 1;
	while ($p = mysqli_fetch_object($page_pricing)) {
		print "<div class='panel-group'>
	    <div class='panel panel-success'>
	      <div class='panel-heading center'>
	        <h4 class='panel-title'>
	          <a data-toggle='collapse' href='#collapse_$i'>Category : $p->name</a>
	          <span> <a href='?page=$page&date=$date&delete=$p->id' class='frht'><i class='fa fa-trash'></i>".space(4)."</a>".space(4)."<a href='?page=$page&date=$date&edit=$p->id' class='frht'><i class='fa fa-edit'></i>".space(4)."</a></span>
	        </h4>
	      </div>
	      <div id='collapse_$i' class='panel-collapse collapse'>
	          <div class='panel-body'>
						<table class='table'>";
						$days = "";
						$dates = "";
						$firstDay = firstDay($date);
						$page_pricing_dates = select("GROUP_CONCAT(`date`) dates", "page_pricing_date", "pricing=$p->id");
						$page_pricing_dates = mysqli_fetch_object($page_pricing_dates);
						$selected_dates = explode(",", $page_pricing_dates->dates);
						for($i=1; $i<=lastDay($date); $i++){
							$dt = addDay($i, $firstDay);
							$days .= "<td class='btn-default btn-date'>".substr(date("D", strtotime($dt)),0,1)."</td>";
							if(in_array($dt, $selected_dates)){
								$dates .= "<td class='btn-success btn-date disabled'>$i</td>";
							} else{
								$dates .= "<td class='btn-default btn-date disabled'>$i</td>";	
							}
						}
						print "<tr>$days</tr>";
						print "<tr>$dates</tr>";
						print "</table>
	          <table class='table table-border'>
	            <tr>
	              <th>Min Column</th><td>$p->min_col</td>
	              <th>Min Inch</th><td>$p->min_col_width</td>
	              <th>Price (B & W) / Column / Inch</th><td>$p->bw_unit_price ৳</td>
	            </tr>
	            <tr>
	              <th>Max Column</th><td>$p->max_col</td>
	              <th>Max Inch</th><td>$p->min_col</td>
	              <th>Price (Color) / Column / Inch</th><td>$p->color_unit_price ৳</td>
	            </tr>
	            <tr>
	              <th>Min Number of Words</th><td>$p->min_words</td>
	              <th>Max Number of Words</th><td>$p->max_words</td>
	              <th>First</th><td>$p->init_words words $p->price ৳</td>
	            </tr>
	            <tr>
	            	<th>Language</th><td>".makeSelectOption("name='language' class='form-control'", ['Bengali', 'English', 'Any'])."</td>
	              <th></th><td></td>
	              <th>Next each word</th><td>$p->additional_word_price ৳</td>
	            </tr>
	          </table>
	        </div>
	        <div class='panel-footer'>Additional Setup</div>
	        <div class='panel-body'>
	          <table class='table table-border'>
	            <tr>
	              <th>Fixed Place</th><td>$p->fixed_place_rate %</td>
	              <th>First Ad</th><td>$p->first_position_rate %</td>
	              <th>Approval Limit Per Day</th><td>$p->daily_approval_limit</td>
	            </tr>
	            <tr>
	              <th>Bold Text</th><td>$p->bold_text_rate %</td>
	              <th>Text in Screen</th><td>$p->in_screen_rate %</td>
	              <th>Text in Box</th><td>$p->in_box_rate %</td>
	            </tr>
	          </table>
	        </div>
	        <div class='panel-footer'>Pricing</div>
	        <div class='panel-body'>
	          <table class='table table-border'>
	            <tr><th colspan='8'>Black & White</th></tr>
	            <tr>
	              <th>Master Agent Price</th><td>$p->master_agent_commission_bw %</td>
	              <th>Agent Price</th><td>$p->agent_commission_bw %</td>
	              <th>Advertiser Price</th><td>100 %</td>
	            </tr>
	            <tr><th colspan='8'>Color</th></tr>
	            <tr>
	              <th>Master Agent Price</th><td>$p->master_agent_commission_color %</td>
	              <th>Agent Price</th><td>$p->agent_commission_color %</td>
	              <th>Advertiser Price</th><td>100 %</td>
	            </tr>
	            <tr>
	              <th>Payment Method</th><td>{$payment_methods[$p->master_agent_payment_method]}</td>
	              <th>Payment Method</th><td>{$payment_methods[$p->agent_payment_method]}</td>
	              <th>Payment Method</th><td>{$payment_methods[$p->advertiser_payment_method]}</td>
	            </tr>
	          </table>
	        </div>
	      </div>
	    </div>
	  </div>";
	  $i++;
	}

	if(isset($get->edit)){
		$pricing = R::load("page_pricing", $get->edit);
		$page_pricing_dates = select("GROUP_CONCAT(`date`) dates", "page_pricing_date", "pricing=$pricing->id");
		$page_pricing_dates = mysqli_fetch_object($page_pricing_dates);
		$selected_dates = explode(",", $page_pricing_dates->dates);
	} else{
		$pricing = R::dispense("page_pricing");
		$selected_dates = [];
	}

	print "
	<div class='panel panel-success'>
	  <div class='panel-heading center'>Category Name <input type='text' class='form-control-fluid' name='name' required placeholder='Category Name Here...' value='$pricing->name'></div>
	  <div class='panel-body'>
		<table class='table'>";
		$days = "";
		$dates = "";
		$firstDay = firstDay($date);
		for($i=1; $i<=lastDay($date); $i++){
			$dt = addDay($i, $firstDay);
			$days .= "<td class='btn-default btn-date'>".substr(date("D", strtotime($dt)),0,1)."</td>";
			if(in_array($dt, $selected_dates)){
				$dates .= "<td class='btn-success btn-date'>$i <input type='checkbox' name='date[]' value='$dt' checked></td>";
			} else{
				$dates .= "<td class='btn-default btn-date'>$i <input type='checkbox' name='date[]' value='$dt'></td>";	
			}
		}
		// $days = "";
		// $dates = "";
		// $firstDay = firstDay($date);
		// for($i=1; $i<=lastDay($date); $i++){
		// 	$dt = addDay($i, $firstDay);
		// 	$days .= "<td class='btn-default btn-date'>".substr(date("D", strtotime($dt)),0,1)."</td>";
		// 	$dates .= "<td class='btn-default btn-date'>$i <input type='checkbox' name='date[]' value='$dt'></td>";
		// }
		print "<tr>$days</tr>";
		print "<tr>$dates</tr>";
		print "</table>
	    <table class='table table-border'>
	      <tr>
	        <th>Min Column</th><td><input name='min_col' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->min_col'></td>
	        <th>Min Inch</th><td><input name='min_col_width' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->min_col_width'></td>
	        <th>Price (B & W) / Column / Inch</th><td><input name='bw_unit_price' type='number' class='form-control-fluid w100' placeholder='' value='$pricing->bw_unit_price'></td>
	      </tr>
	      <tr>
	        <th>Max Column</th><td><input name='max_col' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->max_col'></td>
	        <th>Max Inch</th><td><input name='max_col_width' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->max_col_width'></td>
	        <th>Price (Color) / Column / Inch</th><td><input name='color_unit_price' type='number' class='form-control-fluid w100' placeholder='' value='$pricing->color_unit_price'></td>
	      </tr>
	      <tr>
	        <th>Min Number of Words</th><td><input name='min_words' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->min_words'></td>
	        <th>Max Number of Words</th><td><input name='max_words' type='number' class='form-control-fluid w80' placeholder='' value='$pricing->max_words'></td>
	        <th>First <input name='init_words' type='number' class='form-control-fluid w90' placeholder='Words' value='$pricing->init_words'></th>
	        <td> <input name='price' type='number' class='form-control-fluid w100' placeholder='Price' value='$pricing->price'></td>
	      </tr>
	      <tr>
	        <th></th><td></td>
	        <th></th><td></td>
	        <th>Next each word</th><td><input name='additional_word_price' type='number' class='form-control-fluid w100' placeholder='' value='$pricing->additional_word_price'></td>
	      </tr>
	    </table>
	  </div>
	  <div class='panel-heading center'>Additional Setup</div>
	  <div class='panel-body'>
	    <table class='table table-border'>
	      <tr>
	        <th>Fixed Place</th><td><input name='fixed_place_rate' value='$pricing->fixed_place_rate' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>First Ad</th><td><input name='first_position_rate' value='$pricing->first_position_rate' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Approval Limit Per Day</th><td><input name='daily_approval_limit' value='$pricing->daily_approval_limit' type='number' class='form-control-fluid w80' placeholder=''></td>
	      </tr>
	      <tr>
	        <th>Bold Text</th><td><input name='bold_text_rate' value='$pricing->bold_text_rate' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Text in Screen</th><td><input name='in_screen_rate' value='$pricing->in_screen_rate' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Text in Box</th><td><input name='in_box_rate' value='$pricing->in_box_rate' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	      </tr>
	    </table>
	  </div>
	  <div class='panel-heading center'>Pricing</div>
	  <div class='panel-body'>
	    <table class='table table-border'>
	      <tr><th colspan='8'>Black & White</th></tr>
	      <tr>
	        <th>Master Agent Price</th><td><input name='master_agent_commission_bw' value='$pricing->master_agent_commission_bw' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Agent Price</th><td><input name='agent_commission_bw' value='$pricing->agent_commission_bw' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Advertiser Price</th><td>100%</td>
	      </tr>
	      <tr><th colspan='8'>Color</th></tr>
	      <tr>
	        <th>Master Agent Price</th><td><input name='master_agent_commission_color' value='$pricing->master_agent_commission_color' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Agent Price</th><td><input name='agent_commission_color' value='$pricing->agent_commission_color' type='number' class='form-control-fluid w80' placeholder=''> %</td>
	        <th>Advertiser Price</th><td>100%</td>
	      </tr>
	      <tr>
	        <th>Payment Method</th><td>".sop2('master_agent_payment_method[]', explode(",", $pricing->master_agent_payment_method), ['attr'=>'multiple'], 'payment_method')."</td>
	        <th>Payment Method</th><td>".sop2('agent_payment_method[]', explode(",", $pricing->agent_payment_method), ['attr'=>'multiple'], 'payment_method')."</td>
	        <th>Payment Method</th><td>".sop2('advertiser_payment_method[]', explode(",", $pricing->advertiser_payment_method), ['attr'=>'multiple'], 'payment_method')."</td>
	      </tr>
	    </table>
	  </div>
	</div>
	<div class='right'>
		<button class='btn btn-success'>Save & Add More</button> <button class='btn btn-primary'>Save & Exit</button> <a href='?' class='btn btn-danger'>Cancel</a>
	</div>
</form>";
}
?>
<br>
<br>
<?php
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;

  $filter = "";
	if(rid()==13){
		$publisher = R::findOne("publisher", "user_id=?", [uid()]);
  	$filter = "publisher = $publisher->id";
	}
	     
  openFilterForm("get");
  print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";   
  if(rid()!=13){ 
	  $publisher = isf("publisher", "publisher", $filter, $get, '', true);
	  print str("Publihser")." ".sop2('publisher', $publisher, ['optional'=>true]);      
	}
  $name = isf("name", "name", $filter, $get);
  print str("Name")." <input type='text' name='name' value='$name' class='form-control-fluid' /> ";
  $service_type = isf("service_type", "service_type", $filter, $get, '', true);
	print str("Service Type")." ".sop2('service_type', $service_type, ['optional'=>true], 'service_type');					
  $frequency = isf("frequency", "frequency", $filter, $get, '', true);
	print str("Frequency")." ".sop2('frequency', $frequency, ['optional'=>true], 'service_frequency');					
  closeFilterForm();
	
	$nor = num_rows("a.*", "service a", "$filter");
	$nop = ceil($nor/$offset);

	$start = ($page-1)*$offset;
	$services = select("a.*", "service a", "$filter", "LIMIT $start, $offset");
  $service_typeList = toA("service_type");
  $publishers = toA("publisher");
    	
  $frequencyList = toA("service_frequency");
    	
    	
	print "<hr>";
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Publisher")."</th><th>".str("Name")."</th><th>".str("Service Type")."</th><th>".str("Frequency")."</th><th>".str("Number Of Pages")."</th><th>".str("Payment Term")."</th><th>".str("Payment Method")."</th><th>".str("Division")."</th><th>".str("District")."</th><th>".str("Upazila")."</th><th>".str("Layout")."</th><th>".options2("", "", array("add"))."</th></tr></thead>
	    <tbody>";

	$i = $start + 1;
	while($service = mysqli_fetch_object($services)){
		print "<tr><td><a href='view/$service->id'>$i</a></td>
      <td>{$publishers[$service->publisher]}</td>
      <td><a href='view/$service->id'>$service->name</a></td>
			<td>{$service_typeList[$service->service_type]}</td>
	    <td>{$frequencyList[$service->frequency]}</td>
	    <td>$service->number_of_pages</td>
			<td>$service->payment_term</td>
			<td>{$payment_methods[$service->payment_method]}</td>
	    <td>".getFieldValue("division", "GROUP_CONCAT(name SEPARATOR '<br>')", "id IN ($service->division)")."</td>
			<td>".getFieldValue("district", "GROUP_CONCAT(name SEPARATOR '<br>')", "id IN ($service->district)")."</td>
			<td>".getFieldValue("upazila", "GROUP_CONCAT(name SEPARATOR '<br>')", "id IN ($service->upazila)")."</td>
			<td><a href='view/$service->id'>View/Set</a></td>
			<td>".options2("", $service->id, array("edit", "remove","erase"))."</td></tr>";
		$i++;
	}
	print "</tbody>
	<tfoot>";
	print paging(11, $nop, $nor, $page);
	print "</tfoot>
	</table>";
}
?>
<script type="text/javascript">
	$(function(){
		$("#date").change(function(){
			location.href = "?page=<?php print $page;?>&date=" + $(this).val();
		});
		$(".btn-date:not(.disabled)").click(function(){
			if($(this).hasClass("btn-success")){
				$(this).addClass("btn-default");
				$(this).removeClass("btn-success");
				$(this).find("input").removeAttr("checked");
			} else{
				$(this).removeClass("btn-default");
				$(this).addClass("btn-success");
				$(this).find("input").attr("checked", "checked");
			}
		});	
	})
</script>