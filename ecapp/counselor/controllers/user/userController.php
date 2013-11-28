<?php
class userController extends Spine_SuperController
{
	private	$application_url	=	'http://ec-resource/users/verify/ac/12345';
	//------------------------------------------------------------------------------------
	//Public Functions
	
	public function main()
	{
		$this->displayPhtml('main_content', 'user/user_main');
	}
	
	//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
	}
	
	//------------------------------------------------------------------------------------

	public function loginAction()
	{
		if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']))
		{
			$data['username']	=	$_POST['username'];
			$data['password']	=	$_POST['password'];
			
			$ch				=	curl_init($this->application_url);     
			                                                                 
			curl_setopt($ch, CURLOPT_POST, TRUE);                                                                   
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                                                                      
			
			$result =	curl_exec($ch);
			$status	=	curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if ($status == '202')
			{
				auth::getInstance()->storeCredentials(json_decode($result, true), '');
				header('location: /dashboard/');
			}
			else 
				header('location: /user/login?inv=1');
			exit;
		}
		else
		{
			$this->displayPhtml('user_login', 'user/user_login');
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function logoutAction()
	{
		auth::getInstance()->flush('user/login');
	}
	
	//------------------------------------------------------------------------------------
	//Private Functions
	
	//------------------------------------------------------------------------------------
	//Ajax Functions
}