<?php

/**
 * 
 * @author Raymond
 *
 */

class restfulCurl
{	
	public	$response_code;
	public	$result;
	public	$application_url;
	public	$access_key		=	'12345';
	public	$url_access_key	=	'/ac/';
	public	$headers;
	
//------------------------------------------------------------------------------------
	
	private	$curl_handle;

//------------------------------------------------------------------------------------
	
	public function postData($data)
	{
		$ch		=	curl_init($this->application_url.$this->url_access_key.$this->access_key);
			                                                                 
		curl_setopt($ch, CURLOPT_POST, TRUE);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                                                                      
		
		$this->result 			=	curl_exec($ch);
		$this->response_code	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);
	}
	
//------------------------------------------------------------------------------------
	
}