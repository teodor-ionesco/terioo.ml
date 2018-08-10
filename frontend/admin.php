<?php

session_start();

require_once('bind.php');

App\Login::check();

if(!empty($_GET['logout']))
{
	session_destroy();
	
	header('Cache-Control: no-cache, no-store');
	header('Location: login.php', true, 301);
	
	die('Redirecting...');
}

if(!empty($_GET['msg']))
{
	print('<div style="color: green"><b>'.$_GET['msg'].'</b></div>');
}

?>

<html>
	<head>
		<title>Admin panel</title>
		<meta charset="UTF-8">
		<style>
			.projects td 
			{
				width: 200px;
			}
		</style>
	</head>
	
	<body>
		<h2 style="display: inline-block;">My projects</h2>	
		<div style="display: inline-block" >
			[<a href="new-project.php">New project</a>]
			[<a href="admin.php?logout=true">Logout</a>]
		</div>

		<table border="1px" class="projects">
			<thead>
				<tr>
					<td><b>Name</b></td>
					<td><b>State</b></td>
					<td><b>GitHub</b></td>
					<td><b>Website</b></td>
					<td><b>Starting date</b></td>
					<td><b>Finish date</b></td>
				</tr>
			</thead>
			<tbody>
<?php

	$projects = (new App\Admin) -> get_projects();

	foreach($projects as $key => $array)
	{
		print("
			<tr>
				<td><a href=\"edit-project.php?id=$array[id]\" title=\"Edit this project's information\">$array[name]</a></td>
				<td>$array[state]</td>
				<td>$array[github]</td>
				<td>$array[website]</td>
				<td><code>$array[start_date]</code></td>
				<td><code>$array[finish_date]</code></td>
			</tr>
		");
	}
?>
		</tbody>
		</table>
	</body>
</html>