<?php
class userController extends Spine_SuperController
{
	private	$application_url	=	'http://ec-resource/users/verify/ac/12345';
	//------------------------------------------------------------------------------------
	//Public Functions
	
	public function main()
	{
		$this->displayPhtml('main_content', 'user/user_main');
		
		$action	=	$this->getRoutedAction();
		
		if ($action !== 'loginAction') //
		{
			checkAuth();
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
		header ('location: /user/login');
		exit;
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
				$filebase_json_handler	=	 new	filebaseJsonHandler();
				$filebase_json_handler->createTable('user_profile', $result);
				storeAuthCredentials(array('credentials'=>$result), 'dashboard/');
			}
			else 
				header('location: /user/login?inv=1');
			exit;
		}
		else
		{
			if (checkAuth(FALSE))
			{
				header('location: /dashboard');
				exit;
			}
			
			$this->displayPhtml('content', 'user/user_login');
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function logoutAction()
	{
		 //to start Auth within this action
		$data	=	getValueFromAuth('credentials');
		$data	=	json_decode($data, TRUE);
		$data	=	array(
						'user_id'		=> $data['user_id'],
						'password'		=> $data['password'],
						'access_token'	=> $data['access_token'],
						'username'		=> $data['username']
					);
		
		$filebase_json_handler	=	 new	filebaseJsonHandler();
		$filebase_json_handler->dropTable('user_profile');
		flushAuth();
		
		$restful_curl					=	new	restfulCurl();
		$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/flush-token';
		$restful_curl->postData($data);
	}
	
	//------------------------------------------------------------------------------------
	
	public function editProfileAction()
	{
		$filebase_json_handler	=	 new	filebaseJsonHandler();
		
		$user_data	=	$filebase_json_handler->selectAll('user_profile');
		$user_data	=	json_decode($user_data, TRUE); 
		
		$this->displayPhtml('content', 'user/user_profile', array('user_data' => $user_data));
	}
	
	//------------------------------------------------------------------------------------
	
	public function saveProfileAction() //this has to be ajax
	{
		dumpData($_POST);
	}
	
	//------------------------------------------------------------------------------------
	//Private Functions
	
	//------------------------------------------------------------------------------------
	//Ajax Functions
}