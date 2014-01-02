<?php

class filebaseJsonHandler extends Spine_SuperModel
{
	public $path	=	USER_DATA;
	
	//------------------------------------------------------------------------------------
	
	public function insert($table, $data)
	{
		$table		=	$this->path.'/'.$table;
		if (!file_exists($table))
		{
			fopen($table, 'w');
			$json_data[0]	=	$data;
		}
		else 
		{
			$json_data	=	file_get_contents($table);
			$json_data	=	json_decode($json_data);
			array_push($json_data, $data);
		}
		file_put_contents($table, json_encode($json_data));
	}
	
	//------------------------------------------------------------------------------------
	
	public function update($id, $data, $table)
	{
		$table	=	$this->path.'/'.$table;
		$file	=	fopen($table, 'r+');
		$x		=	0;
		
		foreach ($data as $index => $item)
		{
			if (file_exists($table))
			{
				while (!feof($file))
				{
					$x++;
					if ($id === $x)
					{
						$result	=	preg_replace('/"'.$index.'":"(.*)"$,|}/', $item, fgets($file));
						fwrite($file, $result);
						break;
					}
				}
			}
		}
		fclose($file);
	}
	
	//------------------------------------------------------------------------------------
	
	public function select($table, $fields, $clause)
	{
		$result =	$this->selectAll($table);
		
	}
	
	//------------------------------------------------------------------------------------
	
	public function selectAll($table)
	{
		$result =	array();
		$line	=	'';
		if (file_exists($this->path.'/'.$table.'.sfd'))
		{
			$result	=	file_get_contents($this->path.'/'.$table.'.sfd');
		}
		else 
			$result	=	FALSE;
		return gzdecode($result);
	}
	
	//------------------------------------------------------------------------------------
	
	public function selectbyId($table, $id)
	{
		if (file_exists($this->path.'/'.$table))
		{
			$file	=	file($this->path.'/'.$table);
			return json_decode($file[$id]);
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function delete($table, $id)
	{
		
	}
	
	//------------------------------------------------------------------------------------
	
	public function createTable($name, $data)
	{
		$filename	=	$this->path.'/'.$name.'.sfd';
		if (is_string($data))
			$data	=	gzencode($data);
		elseif (is_array($data) || is_object($data)) 
			$data	=	gzencode(json_encode($data));

		if (file_put_contents($filename, $data))
			return TRUE;
		else 
			return FALSE;
	}
	
	//------------------------------------------------------------------------------------
	
	public function dropTable($name)
	{
		$filename	=	$this->path.'/'.$name.'.sfd';
		
		if (file_exists($filename))
			unlink($filename);
	}
	
	//------------------------------------------------------------------------------------
	
}