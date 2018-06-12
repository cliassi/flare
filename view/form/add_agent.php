<form method="post">
<input type="text" name="key" class="form-control-fluid" placeholder="Email or ID.">
<button class="btn btn-warning">Search</button>
</form>

<?php
if(isset($post->save)){
	$member = R::load("member", $post->member);
	$member->agent = 1;
	R::store($member);
}
if(isset($post->key)){
	$members = select("*", "member", "email='$post->key' OR identity='$post->key'");
	print "<table align='center' class='table table-responsive table-striped'>
	<thead><tr><th>#</th><th>".str("Name")."</th><th>".str("Email")."</th><th>".str("Phone")."</th><th>".str("Agent")."</th><th>".str("ID")."</th><th>Action</th></tr></thead>
	    <tbody>";
	$i = 1;
	while($member = mysqli_fetch_object($members)){
		print "<tr><td><a href='view/$member->id'>$i</a></td>
			<td>$member->name</td>
			<td>$member->email</td>
			<td>$member->phone</td>
			<td>".($member->agent?"Yes":"No")."</td>
			<td>$member->identity</td>
			<td>
				<form method='post'>
					<input type='hidden' name='key' value='$post->key'>
					<input type='hidden' name='member' value='$member->id'>
					<button class='btn btn-success' name='save'>Save as Agent</button>
				</form>
			</td></tr>";
		$i++;
	}
	print "</tbody>
		</table>";
}
?>