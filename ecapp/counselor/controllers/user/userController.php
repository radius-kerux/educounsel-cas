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
			
			$restful_curl	=	new	restfulCurl();
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/verify';
			$restful_curl->postData($data);
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;

			if ($status == '202')
			{
				storeAuthCredentials(array('credentials'=>$result), 'dashboard/');
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
		checkAuth(); //to start Auth within this action
		$data	=	getValueFromAuth('credentials');
		$data	=	json_decode($data, TRUE);
		$data	=	array(
						'user_id'		=> $data['user_id'],
						'password'		=> $data['password'],
						'access_token'	=> $data['access_token'],
						'username'		=> $data['username']
					);
		flushAuth();
		
		$restful_curl					=	new	restfulCurl();
		$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/flush-token';
		$restful_curl->postData($data);
	}
	
	//------------------------------------------------------------------------------------
	//Private Functions
	
	//------------------------------------------------------------------------------------
	//Ajax Functions
}