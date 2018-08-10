<?php

session_start();

require_once('bind.php');

App\Login::check();

$project = (new App\Admin) -> check_project((!empty($_GET['id']) ? $_GET['id'] : 'whatsoever; doesn\'t matter'));

if(empty($project))
{
	header('Cache-Control: no-cache, no-store');
	header('Location: admin.php?msg=Project does not exist.', true, 301);
	
	die('Redirecting...');
}

if(!empty($_GET['delete']))
{
	(new App\Admin) -> delete_project($_GET['id']);
	
	header('Cache-Control: no-cache, no-store');
	header('Location: admin.php?msg=Project has been deleted.', true, 301);
	
	die('Redirecting...');
}

if(!empty($_POST['name']))
{
	$data['id'] = $_GET['id'];
	$data["name"] = empty($_POST['name']) ? 'N/A' : $_POST['name'];
	$data["state"] = empty($_POST['state']) ? 'N/A' : $_POST['state'];
	$data["github"] = empty($_POST['github']) ? 'N/A' : $_POST['github']; 
	$data['website'] =  empty($_POST['website']) ? 'N/A' : $_POST['website'];
	$data['start_date'] =  empty($_POST['start_date']) ? 'N/A' : $_POST['start_date'];
	$data['finish_date'] =  empty($_POST['finish_date']) ? 'N/A' : $_POST['finish_date'];
	
	if((new App\Admin) -> edit_project($data) !== true) 
	{
		print('<p style="color:red">Backend error occurred.</p>');
	}
	else
	{
		$project = (new App\Admin) -> check_project($_GET['id']);
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
		<h2 style="display: inline-block;">Edit project</h2>	
		<div style="display: inline-block" >
			[<a href="edit-project.php?id=<?php echo $_GET['id']; ?>&delete=true">Delete project</a>]
			[<a href="admin.php">Go back</a>]
		</div>
		
		<form action="edit-project.php?id=<?php echo $_GET['id']; ?>" method="POST">
			<table class="projects">
				<tbody>
					<tr>
						<td style="">Name</td>
						<td><input name="name" type="text" required="" value="<?php echo $project['name']; ?>"></td>
					</tr>
					<tr>
						<td>State</td>
						<td><input name="state" type="text" value="<?php echo $project['state']; ?>"></td>
					</tr>
					<tr>
						<td>GitHub</td>
						<td><input name="github" type="text" value="<?php echo $project['github']; ?>"></td>
					</tr>
					<tr>
						<td>Website</td>
						<td><input name="website" type="text" value="<?php echo $project['website']; ?>"></td>
					</tr>
					<tr>
						<td>Starting date</td>
						<td><input name="start_date" type="text" value="<?php echo $project['start_date']; ?>"></td>
					</tr>
					<tr>
						<td>Finish date</td>
						<td><input name="finish_date" type="text" value="<?php echo $project['finish_date']; ?>"></td>
					</tr>
				</tbody>
			</table>
			<input type="submit">
		</form>
	</body>
</html>