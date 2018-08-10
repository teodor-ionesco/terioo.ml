<?php

session_start();

require_once('bind.php');

App\Login::check();

if(!empty($_POST['name']))
{
	$data["name"] = empty($_POST['name']) ? 'N/A' : $_POST['name'];
	$data["state"] = empty($_POST['state']) ? 'N/A' : $_POST['state'];
	$data["github"] = empty($_POST['github']) ? 'N/A' : $_POST['github']; 
	$data['website'] =  empty($_POST['website']) ? 'N/A' : $_POST['website'];
	$data['start_date'] =  empty($_POST['start_date']) ? 'N/A' : $_POST['start_date'];
	$data['finish_date'] =  empty($_POST['finish_date']) ? 'N/A' : $_POST['finish_date'];
	
	if((new App\Admin) -> new_project($data) === true) 
	{
		header('Cache-Control: no-cache, no-store');
		header('Location: admin.php?msg=Query updated successfully.', true, 301);
		
		die('Redirecting...');
	}
	else
	{
		print('<p style="color:red">Backend error occurred.</p>');
	}
}

?>

<html>
	<head>
		<title>Admin panel - New project</title>
		<meta charset="UTF-8">
		<style>
			.projects td 
			{
				width: 100px;
			}
		</style>
	</head>
	
	<body>
		<h2 style="display: inline-block;">New project</h2>	
		
		<form action="new-project.php" method="POST">
			<table class="projects">
				<tbody>
					<tr>
						<td style="">Name</td>
						<td><input name="name" type="text" required=""></td>
					</tr>
					<tr>
						<td>State</td>
						<td><input name="state" type="text"></td>
					</tr>
					<tr>
						<td>GitHub</td>
						<td><input name="github" type="text"></td>
					</tr>
					<tr>
						<td>Website</td>
						<td><input name="website" type="text"></td>
					</tr>
					<tr>
						<td>Starting date</td>
						<td><input name="start_date" type="text"></td>
					</tr>
					<tr>
						<td>Finish date</td>
						<td><input name="finish_date" type="text"></td>
					</tr>
				</tbody>
			</table>
			<input type="submit">
		</form>
	</body>
</html>