<?php

namespace App;

use PDO;
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
				
				$tmp = $prepare -> fetch(PDO::FETCH_ASSOC);
				
				if(!empty($tmp))
				{
					$prepare = $this -> database -> prepare('SELECT *
															FROM features
															WHERE features.pid = :id;');
					$prepare -> execute([':id' => $id]);
					
					$tmp['state'] = $this -> generate_state($tmp['id']);
					$tmp["features"] = $prepare -> fetchAll(PDO::FETCH_ASSOC);
				}
				
				return $tmp;
			}
			catch(PDOException $e)
			{
				die('Exception: '.$e);
			}
		}
		
		throw new Exception('Please input a valid id.');
	}
	
	public function get_project($id)
	{
		if(!empty($id))
		{
			try {
				$prepare = $this -> database -> prepare('SELECT *
														FROM projects
														WHERE projects.id = :id;');
				$prepare -> execute([':id' => $id]);
				
				$tmp = $prepare -> fetch();
				
				if(!empty($tmp))
				{
					$prepare = $this -> database -> prepare('SELECT *
															FROM features
															WHERE features.pid = :id;');
					$prepare -> execute([':id' => $id]);
					
					$tmp['state'] = $this -> generate_state($tmp['id']);
					$tmp['features'] = $prepare -> fetchAll(PDO::FETCH_ASSOC);
				}
				else
				{
					$tmp['features'] = [];
				}
				
				return $tmp;
			}
			catch(PDOException $e)
			{
				die('Exception: '.$e);
			}
		}
		
		throw new Exception('Please input a valid id.');
	}
	
	public function get_projects()
	{
		try {
			$projects = $this -> database -> query('SELECT * FROM projects;') -> fetchAll();
			
			foreach($projects as $key => $array)
			{
				$projects[$key]['features'] = $this -> generate_state($array['id']);
			}
			
			return $projects;
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
				$prepare = $this -> database -> prepare('INSERT INTO projects(name, github, website, start_date, finish_date) 
																		VALUES(:name, :github, :website, :start_date, :finish_date);');
				$prepare -> execute([
					':name' => $data['name'],
					':github' => $data['github'],
					':website' => $data['website'],
					':start_date' => $data['start_date'],
					':finish_date' => $data['finish_date'],
				]);

				$prepare = $this -> database -> prepare('SELECT id
														FROM projects
														WHERE projects.name = :name;');
				$prepare -> execute([
					':name' => $data['name'],
				]);
				
				$ret = $prepare -> fetch();
				
				if(!empty($ret))
				{
					if(is_array($data['features']) && !empty($data['features']))
					{
						$query = null;
						
						foreach($data['features'] as $key => $array)
						{
							$query .= 'INSERT INTO features(pid, name, finished)
													VALUES(:pid, :'.$key.'name, :'.$key.'finished);';
						}
						
						$prepare = $this -> database -> prepare($query);
						
						foreach($data['features'] as $key => $array)
						{
							$prepare -> bindParam(':pid', $ret['id']);
							$prepare -> bindParam(':'.$key.'name', $array['name']);
							$prepare -> bindParam(':'.$key.'finished', $array['completed']);
						}
						
						$prepare -> execute();
					}
					
					return true;
				}
				
				return false;
			} 
			catch (PDOException $e)
			{
				die('Exception: '. $e);
			}
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
														 github = :github,
														 website = :website,
														 start_date = :start_date,
														 finish_date = :finish_date
														WHERE projects.id = :id;");
				$prepare -> execute([
					':id' => $data['id'],
					':name' => $data['name'],
					':github' => $data['github'],
					':website' => $data['website'],
					':start_date' => $data['start_date'],
					':finish_date' => $data['finish_date'],
				]);
				
				foreach($data['features'] as $key => $array)
				{
					$prepare = $this -> database -> prepare("SELECT id
															FROM features
															WHERE features.id = :id;");
					$prepare -> execute([':id' => $key]);
					
					if(!empty($prepare -> fetch()))
					{
						if(!empty($array['name']))
						{
							$prepare = $this -> database -> prepare("UPDATE features
																	SET name = :name,
																		finished = :finished
																	WHERE features.id = :id ;");
							$prepare -> execute([
								':id' => $key,
								':name' => $array['name'],
								':finished' => $array['completed'],
							]);
						}
						else
						{
							$prepare = $this -> database -> prepare("DELETE FROM features
																	WHERE features.id = :id ;");
							$prepare -> execute([
								':id' => $key,
							]);
						}
					}
					else
					{
						$prepare = $this -> database -> prepare("INSERT INTO features(pid, name, finished)
																			VALUES(:pid, :name, :finished);");
						$prepare -> execute([
							':pid' => $data['id'],
							':name' => $array['name'],
							':finished' => $array['completed'],
						]);
					}
				}
				
				return true; 

			} catch (PDOException $e)
			{
				die('Exception: '. $e);
			}
		}
		
		throw new Exception('Please input a valid array data.');
	}
	
	public function delete_project(&$id)
	{
		if(!empty($id))
		{
			try {
				$prepare = $this -> database -> prepare("DELETE FROM projects 
														WHERE projects.id = :id;
														
														DELETE FROM features
														WHERE features.pid = :id");
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
	
	public function generate_state($id)
	{
		$completed = $this -> database -> query("SELECT COUNT(*) AS completed
												FROM features 
												WHERE features.pid = $id
													AND features.finished = 1;") -> fetch()['completed'];
													
		$all = $this -> database -> query("SELECT COUNT(*) AS everything
											FROM features 
											WHERE features.pid = $id;") -> fetch()['everything'];
		
		return [
			'completed' => $completed,
			'all' => $all,
		];
	}
}
