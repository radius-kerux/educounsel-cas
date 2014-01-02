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
			$data['role_id']	=	2;
			
			$restful_curl	=	new	restfulCurl();
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/verify';
			$restful_curl->postData($data);
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;

			if ($status == '202')
			{
				$filebase_json_handler	=	 new	filebaseJsonHandler();
				$filebase_json_handler->createTable('user_profile', $result);
				
				$result	=	json_decode($result, TRUE);
				
				$auth_credential['username']		=	$result['username'];
				$auth_credential['password']		=	$result['password'];
				$auth_credential['access_token']	=	$result['access_token'];
				$auth_credential['user_id']			=	$result['user_id'];
				$auth_credential['role_id']			=	$result['role_id'];
				
				$auth_credential					=	json_encode($auth_credential);
				
				storeAuthCredentials(array('credentials' => $auth_credential), 'dashboard/');
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
		//To start Auth within this action
		$data	=	getValueFromAuth('credentials');
		$data	=	json_decode($data, TRUE);
		$data	=	array(
						'user_id'		=> $data['user_id'],
						'password'		=> $data['password'],
						'access_token'	=> $data['access_token'],
						'username'		=> $data['username']
					);
		
		$filebase_json_handler	=	 new filebaseJsonHandler();
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
	
	public function saveProfileAction() //this has to be ajax or...
	{
		if (!empty($_POST))
		{
			//Get profile details in local file base data storage
			$filebase_json_handler	=	 new	filebaseJsonHandler();
			$user_data				=	$filebase_json_handler->selectAll('user_profile');
			$user_data				=	json_decode($user_data, TRUE);
			
			//Check credential validation
			$status	=	verifyCurlToken();
			
			//if credential is valid 
			if ($status !== '401')
			{
				//Arrange user_data, merge existing data with posted data - overwrite data under the same index
				foreach ($_POST as $user_data_item_key => $user_data_item)
				{
					$user_data_item	=	$user_data_item_key == 'password'?hashPassword($user_data_item):$user_data_item;
					$user_data[$user_data_item_key]	=	$user_data_item;
				}
				
				//Send the data to store in the online DB via curl
				$restful_curl	=	new	restfulCurl();
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/update-profile';
				$restful_curl->postData($user_data);
				$status	=	$restful_curl->response_code;
				$result	=	$restful_curl->result;

				//If process is OK
				if ($status == 200)
				{
					//Update the local storage
					$filebase_json_handler->createTable('user_profile', json_encode($user_data));
					
					//Redirect to self
					header('location: /user/edit-profile');
					exit;
				}
			}
			else 
				header('location: /user/logout');
		}
	}
	
	//------------------------------------------------------------------------------------
	//Private Functions
	
	//------------------------------------------------------------------------------------
	//Ajax Functions
}