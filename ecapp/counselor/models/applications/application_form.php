<?php

class application_form extends Spine_SuperModel
{
	public $user_id;
	public $section;
	public $value;
	public $column;
	
	public function select()
	{
		try
		{
			$connection	=	Spine_DB::connection();
			$sql	=	"SELECT
							*
						FROM
							application_forms
						WHERE 
							user_id = :user_id			
			";
				
			$statement	=	$connection->prepare($sql);
			$statement->bindParam(":user_id", $this->user_id, PDO::FETCH_ASSOC);
			$statement->execute();
			return $statement->fetch(PDO::FETCH_ASSOC);	
		}
		catch (PDOException $e)
		{
			throw new Exception($e);
			die();
		}
	}
	
	//---------------------------------------------------------------------------------------
	
	public function insert()
	{
		try
		{
			$connection	=	Spine_DB::connection();
			$sql	=	"
					INSERT INTO
						application_forms
						(
							$this->section
						)
					VALUES
						(
							:value
						)
			";
				
			$statement	=	$connection->prepare($sql);
			$statement->bindParam(":value", $this->value, PDO::FETCH_ASSOC);
			$statement->execute();
		}
		catch (PDOException $e)
		{
			throw new Exception($e);
			die();
		}
	}
	//---------------------------------------------------------------------------------------
	
	public function update()
	{
		try
		{
			$connection	=	Spine_DB::connection();
			$sql	= "
					UPDATE 
						application_forms
					SET 
						$this->column = :value
			";
				
			$statement	=	$connection->prepare($sql);
			$statement->bindParam(":value", $this->value, PDO::FETCH_ASSOC);
			$statement->execute();
		}
		catch (PDOException $e)
		{
			throw new Exception($e);
			die();
		}
	}
}