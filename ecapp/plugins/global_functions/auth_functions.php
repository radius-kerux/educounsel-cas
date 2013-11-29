<?php 

function checkAuth()
{
	return	auth::getInstance()->checkAuth('user/login/');
}

//------------------------------------------------------------------------------------

function verifyAuthCredentials()
{
	$role		=	Spine_SessionRegistry::getSession('auth', 'role');
	$user_id	=	isset($_GET['id'])?$_GET['id']:0;
		
	if ( !Auth::getInstance()->verifyCredentials(array( 'user_id'	=>	$user_id)) && $role >= 3)
		header ( 'location: /user/logout' );
}

//------------------------------------------------------------------------------------

function getValueFromAuth ( $index )
{
	return	Spine_SessionRegistry::getSession ('auth', $index);
}

//------------------------------------------------------------------------------------

function doAuth()
{
	$controller	=	str_replace ('Controller', '', Spine_GlobalRegistry::getRegistryValue('route', 'controller'));

	if ($controller !== 'user' || isset($_SESSION['auth']['spine_hash_key']))
	{
		checkAuth();
	}
}

//------------------------------------------------------------------------------------

function storeAuthCredentials($credentials, $destination = '')
{
	auth::getInstance()->storeCredentials($credentials, $destination);
}

//------------------------------------------------------------------------------------

function flushAuth()
{
	auth::getInstance()->flush('user/login');
}

