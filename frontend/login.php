<?php

session_start();

require_once('bind.php');

if(!empty($_POST['username']) && !empty($_POST['password']))
{
	if(((new App\Login) -> init($_POST['username'], $_POST['password'])) === true)
	{
		$_SESSION['login'] = true;
		
		header('Cache-Control: no-cache, no-store');
		header('Location: admin.php', true, 301);
		
		print('Redirecting...');
	}
	else
	{
		$_SESSION['login'] = false;
		
		print('<p style="color:red;">Username or password is incorrect.</p>');
	}
}

?>

<html>
	<head>
		<title>Login</title>
		<meta charset="UTF-8">
	</head>
	<body>
		<center>
			<h3>Login to admin area.</h3>
			<form action="login.php" method="POST">
				<table>
					<tr>
						<td>Username:&nbsp;</td>
						<td><input type="text" name="username" required="" autofocus=""></td>
					</tr>
					<tr>
						<td>Password:&nbsp;</td>
						<td><input type="password" name="password" required=""></td>
					</tr>
				</table>
				<br>
				<input type="submit">
			</form>
		</center>
	</body>
</html>