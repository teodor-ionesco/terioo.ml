<?php

session_start();

require_once('bind.php');

App\Login::check();

if(!empty($_POST['name']))
{
	$data["name"] = empty($_POST['name']) ? 'N/A' : $_POST['name'];
	
	if(!empty($_POST['features']) && is_array($_POST['features']))
	{
		foreach($_POST['features'] as $key => $array)
		{
			if(!empty($array['name'])) 
			{
				$data['features'][$key] = $array;
				
				$data['features'][$key]['completed'] = (empty($array['completed'])) ? 0 : 1;
			}
		}
	}
	else
	{
		$data['features'] = 'N/A';
	}
	
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
		
	
		<form action="new-project.php" method="POST">
			<table>
				<tr>
					<td>
						<h2 style="display: inline-block;">New project</h2>	
						<table class="projects">
							<tbody>
								<tr>
									<td style="">Name</td>
									<td><input name="name" type="text" required=""></td>
								</tr>
								<tr>
									<td>State</td>
									<td><input type="button" value="Add feature" onclick="add_feature();"></td>
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
					</td>
					<td style="position:absolute;" id="features-place">
						<h2 style="display: inline-block;">Features</h2>	
					</td>
				</tr>
			</table>
			<input type="submit">
		</form>
	</body>
	<script>
		var gFeaturesCount = 0;

		function add_feature()
		{
			var BR = document.createElement('br');
			
			var TEXT = document.createElement('input');
				TEXT.type = 'text';
				TEXT.name = 'features['+gFeaturesCount+'][name]';
				TEXT.placeholder = 'Input your feature name here';
				
			var CHECKBOX = document.createElement('input');
				CHECKBOX.type = 'checkbox';
				CHECKBOX.name = 'features['+gFeaturesCount+'][completed]';
				CHECKBOX.style = 'display: inline;';
				CHECKBOX.value = 'true';
				
			var DIV = document.createElement('div');
				DIV.style = 'display: inline;';
				DIV.innerHTML = 'Is completed?';
						
			document.getElementById('features-place').appendChild(BR);
			document.getElementById('features-place').appendChild(TEXT);
			document.getElementById('features-place').appendChild(CHECKBOX);
			document.getElementById('features-place').appendChild(DIV);
			
			++gFeaturesCount;
		}
	</script>
</html>