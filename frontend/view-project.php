<?php

require_once('bind.php');

if(!empty($_GET['id']))
{
	$project = (new App\Admin) -> check_project($_GET['id']);
	
	if(empty($project))
	{
		header('Cache-Control: no-cache, no-store', true);
		header('Location: index.php', true, 301);
		
		die('Redirecting...');
	}
}
else
{
	header('Cache-Control: no-cache, no-store', true);
	header('Location: index.php', true, 301);
	
	die('Redirecting...');
}

?>
<html>
	<head>
		<title>Welcome to my website! | terioo | Teodor I.</title>
		<meta charset="UTF-8">
		<style>
			.projects td 
			{
				width: 120px;
			}
			
			p
			{
				font-size: 20px;
			}
			
			a 
			{
				text-decoration: none;
			}
			
			a:hover
			{
				color: red;
				text-decoration: underline;
			}
			
			a:active
			{
				color: green;
			}
		</style>
	</head>
	
	<body>
		<table>
			<tr>
				<td>
					<h2 style="display: inline-block;">Project details</h2>	
					<div style="display: inline-block" >
						[<a href="index.php">Go back</a>]
					</div>
					
					<table class="projects">
						<tbody>
							<tr>
								<td style="">Name:</td>
								<td><?php echo $project['name']; ?></td>
							</tr>
							<tr>
								<td>State:</td>
								<td><code>Finished <?php echo $project['state']['completed'] . '/' . $project['state']['all']; ?> features</code></td>
							</tr>
							<tr>
								<td>GitHub:</td>
								<td><code><a href="http://github.com/teodor-ionesco/<?php echo $project['github']; ?>" target="_BLANK"><?php echo $project['github']; ?></a></code></td>
							</tr>
							<tr>
								<td>Website:</td>
								<td><code><a href="http://<?php echo $project['website']; ?>" target="_BLANK"><?php echo $project['website']; ?></a></code></td>
							</tr>
							<tr>
								<td>Starting date:</td>
								<td><code><?php echo $project['start_date']; ?></code></td>
							</tr>
							<tr>
								<td>Finish date:</td>
								<td><code><?php echo $project['finish_date']; ?></code></td>
							</tr>
						</tbody>
					</table>
				</td>
				<td style="position:absolute;" id="features-place">
					<h2 style="display: inline-block;">Features</h2>	
					<div style="display: inline;"><i>(checked = completed)</i></div>
					<ul style="position: relative; top: -15px; ">
<?php

if(!empty($project['features']))
{
	foreach($project['features'] as $key => $array)
	{
		print( '
			<li>
			<code style="display: inline;">'.$array['name'].'</code>
		');
		
		if((bool)$array['finished'] === true)
		{
			print('<input readonly="" style="display: inline;" value="true" type="checkbox" checked>');
		}
		else
		{
			print('<input readonly="" style="display: inline;" value="true" type="checkbox" >');
		}
			
		print('
				
			</li>
		');
	}
}
else
{
	print('<br><b><code>N/A</code></b>');
}
?>
					</ul>
				</td>
			</tr>
		</table>
	</body>
	<script>
		var gFeaturesCount = 1500;

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