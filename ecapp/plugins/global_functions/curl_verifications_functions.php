<?php 

/**
 * 
 * Verification is done with the combination of filebased text and session
 */

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
		$role_id				=	Spine_SessionRegistry::getSession('auth', 'role_id');
		$user_id				=	Spine_SessionRegistry::getSession('auth', 'user_id');
		
		$filebase_json_handler			=	 new	filebaseJsonHandler(); //filebaseJsonHandler class dependent
		$filebase_json_handler->path	=	getSitePath($role_id);
		$user_data						=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
		$user_data						=	json_decode($user_data, TRUE);
	
		//assign data for credential validation
		
		if ($user_id == $user_data['user_id'])
		{
			$credential_data['user_id']			=	$user_data['user_id'];
			$credential_data['username']		=	$user_data['username'];
			$credential_data['password']		=	$user_data['password'];
			$credential_data['access_token']	=	$user_data['access_token'];
			$credential_data['role_id']			=	$user_data['role_id'];

			return	$credential_data;
		}
	}
	
	return	FALSE;
}

//------------------------------------------------------------------------------------

function getSitePath($role_id)
{
	if ($role_id == 1)
		return	'counselor/data/user';
	elseif ($role_id == 2)
		return	'admin/data/user';
	elseif ($role_id == 3)
		return	'admin/data/user';
	else 
		return	FALSE;
}


