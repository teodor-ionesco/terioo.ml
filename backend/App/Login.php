<?php

namespace App;

use PDO;
use Exception;
use PDOException;

class Login
{
	public static $public;
	private $database;
	
	public function __construct()
	{
		$this -> database = Database::init();
	}

	public function init($usr, $pss)
	{
		if(!empty($usr) && !empty($pss))
		{
			try {
				$prepare = $this -> database -> prepare("SELECT * 
														FROM users 
														WHERE users.username = :usr ;");
				$prepare -> execute([":usr" => $usr]);
			}
			catch(PDOException $e)
			{
				die('Exception: '. $e -> getMessage());
			}
			
			$row = $prepare -> fetch();

			if(empty($row))
			{
				return false;
			}

			if(!password_verify($pss, $row['password']))
			{
				return false;
			}
			
			return true;
		}
		
		throw new Exception('Please input a valid username and password.');
	}

	public static function check()
	{
		if(empty($_SESSION['login']))
		{
			header('Cache-Control: no-cache, no-store');
			header('Location: login.php', true, 301);
			
			die('Redirecting...');
		}
	}
}