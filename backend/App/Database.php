<?php

namespace App;

use PDO;
use PDOException;

class Database
{
	private static $connection;
	
	public static function init()
	{
		if(!empty(self::$connection))
		{
			return self::$connection;
		}
	
		try {
			self::$connection = new PDO('mysql:host='.DB_HOST.';dbname='. DB_NAME, DB_USERNAME, DB_PASSWORD);
			self::$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} 
		catch(PDOException $e) {
			die("PDOException: " . $e -> getMessage());
		}
	
		return self::$connection;
	}
}