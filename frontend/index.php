<?php

session_start();

require_once('bind.php');


?>
<html>
	<head>
		<title>Welcome to my website! | terioo | Teodor I.</title>
		<meta charset="UTF-8">
		<style>
			.projects td 
			{
				width: 200px;
			}
			
			p
			{
				font-size: 20px;
			}
			
			a:hover
			{
				color: red;
			}
			
			a:active
			{
				color: green;
			}
		</style>
	</head>
	<body>
		<h2>Hello, stranger! Welcome to my site! :)</h2>
		<p>
			My name is Theodor. My global nickname is 'terioo' or 'teodor-ionesco' <i>(although I like the first one more)</i>.
			<br> 
			I am a passionate full-stack web developer with deep knowledge in PHP. 
			I have recently started developing my skills in C++ as well. 
			<br><br>
			This website is a mostly 'off-CSS' presentation. It contains only my contact information and projects I am currently or have been recently working on.
			Don't be scared about the interface! I can do a lot more than this, be sure of that. Check out my list of projects below and see it for yourself. 😎
		</p>
		<hr>
		<h2>My projects</h2>		
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

	if(!empty($projects))
	{
		foreach($projects as $key => $array)
		{
			print('
					<tr>
						<td>'.$array['name'].'</td>
						<td>'.$array['state'].'</td>
						<td><a href="http://www.github.com/teodor-ionesco/'.$array['github'].'" target="_BLANK">'.$array['github'].'</a></td>
						<td><a href="http://'.$array['website'].'" target="_BLANK">'.$array['website'].'</a></td>
						<td><code>'.$array['start_date'].'</code></td>
						<td><code>'.$array['finish_date'].'</code></td>
					</tr>
			');
		}
	}
	else
	{
		print('
				<tr>
					<td><code>N/A</code></td>
					<td><code>N/A</code></td>
					<td><code>N/A</code></td>
					<td><code>N/A</code></td>
					<td><code>N/A</code></td>
					<td><code>N/A</code></td>
				</tr>
		');
	}
?>
			</tbody>
		</table>		
		<br>
		<hr>
		<h2>Contact information</h2>
		<p>If you need any sort of IT job at low price <i>(or even free of charge)</i>, don't be shy and send me a message. I'd be happy to assist you.</p>
		<table>
			<tbody>
				<tr>
					<td>E-mail:&nbsp;</td>
					<td><a href="mailto:teodor.ionesco@gmail.com" target="_BLANK">teodor.ionesco@gmail.com</a></td>
				</tr>
				<tr>
					<td>Phone:&nbsp;</td>
					<td>+40731704463</td>
				</tr>
				<tr>
					<td>GitHub:&nbsp;</td>
					<td><a href="https://www.github.com/teodor-ionesco" target="_BLANK">teodor-ionesco</a></td>
				</tr>
				<tr>
					<td>Facebook:&nbsp;</td>
					<td><a href="https://www.facebook.com/teodor.ionesco" target="_BLANK">teodor.ionesco</a></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>