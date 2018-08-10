<?php

namespace App;

use Exception;
use PDOException;

class Admin
{
	private $database;
	
	public function __construct()
	{
		$this -> database = Database::init();
	}
	
	public function check_project($id)
	{
		if(!empty($id))
		{
			try {
				$prepare = $this -> database -> prepare('SELECT *
														FROM projects
														WHERE projects.id = :id;');
				$prepare -> execute([':id' => $id]);
				
				return $prepare -> fetch();
			}
			catch(PDOException $e)
			{
				die('Exception: '.$e);
			}
		}
		
		throw new Exception('Please input a valid id.');
	}
	
	public function get_project($name)
	{
		if(!empty($name))
		{
			try {
				$prepare = $this -> database -> prepare('SELECT *
														FROM projects
														WHERE projects.name = :name;');
				$prepare -> execute([':name' => $name]);
				
				return ($prepare -> fetch());
			}
			catch(PDOException $e)
			{
				die('Exception: '.$e);
			}
		}
		
		throw new Exception('Please input a valid name.');
	}
	
	public function get_projects()
	{
		try {
			return (($this -> database -> query('SELECT * 
												FROM projects;')) -> fetchAll());
		}
		catch(PDOException $e)
		{
			die('Exception: '.$e);
		}
	}
	
	public function new_project(&$data)
	{
		if(!empty($data) && is_array($data))
		{
			try {
				$prepare = $this -> database -> prepare('INSERT INTO projects(name, state, github, website, start_date, finish_date) 
																		VALUES(:name, :state, :github, :website, :start_date, :finish_date);');
				$prepare -> execute([
					':name' => $data['name'],
					':state' => $data['state'],
					':github' => $data['github'],
					':website' => $data['website'],
					':start_date' => $data['start_date'],
					':finish_date' => $data['finish_date'],
				]);
			} catch (PDOException $e)
			{
				die('Exception: '. $e);
			}
			
			return true;
		}
		
		throw new Exception('Please input a valid array data.');
	}
	
	public function edit_project(&$data)
	{
		if(!empty($data) && is_array($data))
		{
			try {
				$prepare = $this -> database -> prepare("UPDATE projects 
														SET projects.name = :name,
														 state = :state,
														 github = :github,
														 website = :website,
														 start_date = :start_date,
														 finish_date = :finish_date
														WHERE projects.id = :id;");
				$prepare -> execute([
					':id' => $data['id'],
					':name' => $data['name'],
					':state' => $data['state'],
					':github' => $data['github'],
					':website' => $data['website'],
					':start_date' => $data['start_date'],
					':finish_date' => $data['finish_date'],
				]);
			} catch (PDOException $e)
			{
				die('Exception: '. $e);
			}
			
			return true;
		}
		
		throw new Exception('Please input a valid array data.');
	}
	
	public function delete_project(&$id)
	{
		if(!empty($id))
		{
			try {
				$prepare = $this -> database -> prepare("DELETE FROM projects 
														WHERE projects.id = :id;");
				$prepare -> execute([
					':id' => $id,
				]);
			} catch (PDOException $e)
			{
				die('Exception: '. $e);
			}
			
			return true;
		}
		
		throw new Exception('Please input a valid id.');
	}
}