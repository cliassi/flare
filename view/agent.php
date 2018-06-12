<?php
	$members = select("m.*, a.agent_type", "member m LEFT JOIN publisher_agent a ON(m.id=a.agent)", "m.agent=1 ORDER BY m.name");
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Email")."</th><th>".str("Phone")."</th><th>".str("Agent")."</th><th>".str("ID")."</th><th>Master Agent</th><th>Local Agent</th></tr></thead>
	    <tbody>";
	$i = 1;
	while($member = mysqli_fetch_object($members)){
		print "<tr><td><a href='view/$member->id'>$i</a></td>
			<td>$member->name</td>
			<td>$member->email</td>
			<td>$member->phone</td>
			<td>".($member->agent?"Yes":"No")."</td>
			<td>$member->identity</td>
			<td><input type='checkbox' value='1' id='Master_$member->id' data-id='$member->id' ".($member->agent_type=='Master'?'checked':'')." /></td>
			<td><input type='checkbox' value='1' id='Local_$member->id' data-id='$member->id' ".($member->agent_type=='Local'?'checked':'')." /></td>
			</tr>";
		$i++;
	}
	print "</tbody>
		</table>";
?>
<script type="text/javascript">
	$("input[type='checkbox']").bootstrapToggle({
    on: 'Yes',
    off: 'No'
  }).change(function(){
  	id = $(this).attr("id");
  	id_splitted = id.split("_");
  	id = id_splitted[0];
  	agent = id_splitted[1];
  	type = '';
  	if($(this).prop("checked")){
  		if(id == 'Master'){
  			$("#Local_" + agent).bootstrapToggle('off');
  		} else{
  			$("#Master_" + agent).bootstrapToggle('off');
  		}
	  	master = $("#Master_" + agent).prop("checked");
	  	local = $("#Local_" + agent).prop("checked");
	  	type = '';
	  	if(master == true){
	  		type = 'Master';
	  	} else if(local == true){
	  		type = 'Local';
	  	} else{
	  		type = '';
	  	}
  	} else{
  		master = $("#Master_" + agent).prop("checked");
	  	local = $("#Local_" + agent).prop("checked");
	  	type = '';
	  	if(master == true){
	  		type = 'Master';
	  	} else if(local == true){
	  		type = 'Local';
	  	} else{
	  		type = '';
	  	}
  		console.log(master, local, id_splitted[0], id_splitted[1], type);
  	}
  	$.post("<?php print $appurl; ?>/ajax/publisher_agent.php", {agent: agent, type: type, publisher: <?php print uid(); ?>}, function(data){
  		console.log(data);
  	});
  });
</script>