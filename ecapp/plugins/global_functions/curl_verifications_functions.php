<?php 

function verifyCurlToken()
{
	if (class_exists('restfulCurl') && $credentials	=	getUserCredentials()) //restfulCurl class dependent
	{
		$restful_curl					=	new	restfulCurl(); 
		$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/validate-token';
		$restful_curl->postData($credentials);
		
		return	(int) $restful_curl->response_code;
	}
	else 
		return	FALSE;
}

//------------------------------------------------------------------------------------

function getUserCredentials()
{
	if (class_exists('filebaseJsonHandler'))
	{
		$filebase_json_handler	=	 new	filebaseJsonHandler(); //filebaseJsonHandler class dependent
		$user_data				=	$filebase_json_handler->selectAll('user_profile');
		$user_data				=	json_decode($user_data, TRUE);
	
		//assign data for credential validation
		$credential_data['user_id']			=	$user_data['user_id'];
		$credential_data['username']		=	$user_data['username'];
		$credential_data['password']		=	$user_data['password'];
		$credential_data['access_token']	=	$user_data['access_token'];
		
		return	$credential_data;
	}
	else 
		return	FALSE;
}

//------------------------------------------------------------------------------------


