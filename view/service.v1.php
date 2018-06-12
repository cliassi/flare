<style type="text/css">
	#layout{
		float: right;
    width: 200px;
    min-height: 400px;
    border: #ccc 1px solid;
    margin: 0 10px 0 0;
    padding: 10px;
	}
</style>

<?php
if($id){
	$page = isset($get->page)?$get->page:1;
	$svg = "";
	$service_page = select("id, service, page_number, UNCOMPRESS(layout) layout", "page", "service=$id AND page_number=$page");
	if($service_page){
		$sp = mysqli_fetch_object($service_page);
		if($sp){
			$rects = explode(" ", trim($sp->layout));
			$x = $y = 0;
			$box = 60;
			$svg = 0;
			foreach ($rects as $key => $value) {
				$svg .= "<rect x='$x' y='$y' width='$box' height='$box' stroke='#dddddd' fill='$value' style='stroke-width: 1;'></rect>";
				$y += $box ;
				if($y>600){
					$x += $box;
					$y = 0;
				}
			}
		}
	} else{
		$sp = false;
	}
	$tabs = "";
	$tab_content = "";
	for($i=1; $i<=$object->number_of_pages; $i++){
		$tabs .= "<li".($page == $i?" class='active'":"")."><a href='?page=$i'>Page $i</a></li>";
		$tab_content .= "<div id='page-$i' class='tab-pane fade in ".($page == $i?"active":"")."'></div>";
	}
	print "<ul class='nav nav-pills nav-justified'>$tabs</ul>";
	print "<div class='tab-content'>$tab_content</div>";

	if($svg){
		print "<h3>Current Layout</h3>";
		print "<div class='center'><svg style='height: 600px; width: 600px;'>
			<desc>Created with Snap</desc>
			<defs></defs>
			$svg
		</svg></div>";
	}
	print "<br clear='all'>";
	print "<h3>Create New Layout</h3>";
	print "<div id='tools'>
    <div class='field'>
      <label for='color'>Layout 1</label>
      <input id='color' class='color' type='color' name='color' />
    </div>
    <div class='field'>
      <label for='color'>Layout 2</label>
      <input class='color' type='color' name='color' />
    </div>
    <div class='field'>
      <label for='color'>Layout 3</label>
      <input class='color' type='color' name='color' />
    </div>
    <div class='field'>
      <label for='color'>Layout 4</label>
      <input class='color' type='color' name='color' />
    </div>
    <div class='field'>
      <label for='gridcolor'>Grid</label>
      <input id='gridcolor' type='color' name='gridcolor' />
    </div>
    <div class='field'>
      <label for='paper'>Paper</label>
      <input id='paper' type='color' name='paper' />
    </div>
    <p class='dropperhelp'>Click a box in the graph to select a color.</p>
    <fieldset>
      <div class='field'>
        <legend>Grid</legend>
      </div>
      <div class='field'>
        <label for='w'>Width</label>
        <input id='w' type='text' name='w' /></label>
      </div>
      <div class='field'>
        <label for='h'>Height</label>
        <input id='h' type='text' name='h' />
      </div>
      <div class='field'>
        <label for='box'>Box</label>
        <input id='box' type='text' name='box' />
      </div>
      <div class='field'>
        <input id='update' type='button' name='update' value='Redraw' />
      </div>
      <div class='field'>
        <a class='btn btn-success' href='javascript:save()'>Save</a>
      </div>
    </fieldset>
  </div>";
	print "<div id='container' class='center graph-container'><svg id='graph'></svg></div>";
	// print "<div id='layout'>
	// 		<div class='field'>
	//       <label for='color'>Layout</label>
	//       <input id='color' type='color' name='color' />
	//       <i id='colordrop' class='fa fa-eye-dropper'></i>
	//     </div>
	// 	</div>";
?>
	<script>
    $(document).ready(function() {
      gp = new GraphPaper({w:600, h:600, box:60, draw:'#cccccc', paper:'#ffffff', grid:'#dddddd'});
      // setTimeout(function(){$("#color").spectrum("set", "#ff0000"); gp.updateFill("#ff0000"); console.log("color changed");}, 5000);
    }); 

    function save(){
    	layout = "";
    	$.each($(".graph-container rect"), function(i, e) {
    		layout += $(e).attr('fill') + ' ';
    	});
    	$.post("<?php print $appurl; ?>/ajax/save_layout_box.php", {
    		'service': '<?php print $id; ?>', 
    		'page': '<?php print $page; ?>', 
    		'layout': layout,
    		'width': $("#w").val(),
    		'height': $("#h").val(),
    		'box': $("#box").val()
    	}, function(data){
    		alert("Successfull Save!");
    		console.log(data);
    	});
    	// $.each($(".graph-container rect"), function(i, e) {
    	// 	console.log($(e).attr('fill'));
    	// });
    }
  </script>
<?php
} else{
	//$page = is("page", 1, "", FALSE);
	$page = is("page", 1);
	$offset = 20;
	     
  $filter = "";
  openFilterForm("get");
  print "<input type='hidden' name='page' value='$page' class='form-control-fluid' />";       
  $publisher = isf("publisher", "publisher", $filter, $get, '', true);
  print str("Publihser")." ".sop2('publisher', $publisher, ['optional'=>true]);      
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
    	
  $payment_methodList = toA("payment_method");
    	
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
			<td>{$payment_methodList[$service->payment_method]}</td>
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
<div id="datepicker" data-date="12/03/2012"></div>
<input type="hidden" id="my_hidden_input">
<script type="text/javascript">
  $('#datepicker').datepicker();
$('#datepicker').on('changeDate', function() {
    $('#my_hidden_input').val(
        $('#datepicker').datepicker('getFormattedDate')
    );
});
</script>