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
				$result	=	json_decode($result, TRUE);
				
				$auth_credential['username']		=	$result['username'];
				$auth_credential['password']		=	$result['password'];
				$auth_credential['access_token']	=	$result['access_token'];
				$auth_credential['user_id']			=	$result['user_id'];
				$auth_credential['role_id']			=	$result['role_id'];
				
				$filebase_json_handler				=	 new	filebaseJsonHandler();
				
				$filebase_json_handler->path		=	getSitePath($auth_credential['role_id']);
				$filebase_json_handler->createTable('user_profile'.$result['access_token'], json_encode($result));
				//$auth_credential					=	json_encode($auth_credential);
				
				if ($auth_credential['role_id'] == 1)
					storeAuthCredentials($auth_credential, 'dashboard/');
				elseif ($auth_credential['role_id'] == 2)
					storeAuthCredentials($auth_credential, 'admin/dashboard/');
				elseif ($auth_credential['role_id'] == 3)
					storeAuthCredentials($auth_credential, 'management/dashboard/');
				else
					header('location: /user/login?inv=1');
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
		$filebase_json_handler	=	 new filebaseJsonHandler();
		
		$data	=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
		//$data	=	getValueFromAuth('credentials');
		$data	=	json_decode($data, TRUE);
		$data	=	array(
						'user_id'		=> $data['user_id'],
						'password'		=> $data['password'],
						'access_token'	=> $data['access_token'],
						'username'		=> $data['username']
					);
		
		$filebase_json_handler->dropTable('user_profile'.getValueFromAuth('access_token'));
		flushAuth();
		
		$restful_curl					=	new	restfulCurl();
		$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/flush-token';
		$restful_curl->postData($data);
	}
	
	//------------------------------------------------------------------------------------
	
	public function editProfileAction()
	{
		$filebase_json_handler	=	 new	filebaseJsonHandler();
		
		$user_data	=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
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
			$user_data				=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
			$user_data				=	json_decode($user_data, TRUE);
			
			//Check credential validation
			$status	=	verifyCurlToken();
			
			//if credential is valid 
			if ($status !== '401')
			{
				//Arrange user_data, merge existing data with posted data - overwrite data under the same index
				foreach ($_POST as $user_data_item_key => $user_data_item)
				{
					$user_data_item	=	$user_data_item_key == 'password'?hashPassword($user_data_item):$user_data_item; //if password field, hash the entry
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
					$filebase_json_handler->createTable('user_profile'.getValueFromAuth('access_token'), json_encode($user_data));
					
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
	
	public function saveProfileAjaxAction()
	{
		if (!empty($_POST))
		{
			$post	=	array();
			parse_str($_POST['form_data'], $post); //to parse the serialize data passed via ajax
			$post['photo']	=	$_POST['img_src'];
			//Get profile details in local file base data storage
			$filebase_json_handler	=	 new	filebaseJsonHandler();
			$user_data				=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
			$user_data				=	json_decode($user_data, TRUE);
			
			if (isset($post['old_password']) && isset($post['new_password']) && isset($post['password']))
			{
				$password			=	$user_data['password'];
				$old_password		=	hashPassword($post['old_password']);
				$new_password		=	hashPassword($post['new_password']);
				$confirm_password	=	hashPassword($post['password']);
				
				if ($password === $old_password && $new_password === $confirm_password)
				{
					unset($post['old_password']);
					unset($post['new_password']);
				}
			}
			
			//Check credential validation
			$status	=	verifyCurlToken();
			
			//if credential is valid 
			if ($status !== '401')
			{
				//Arrange user_data, merge existing data with posted data - overwrite data under the same index
				foreach ($post as $user_data_item_key => $user_data_item)
				{
					$user_data_item	=	$user_data_item_key == 'password'?hashPassword($user_data_item):$user_data_item; //if password field, hash the entry
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
					$filebase_json_handler->createTable('user_profile'.getValueFromAuth('access_token'), json_encode($user_data));
					jQuery('#saving_profile_status')->html('Saved');
					jQuery::getResponse();
				}
			}
		}
	}
	
	//------------------------------------------------------------------------------------
	
	public function verifyOldPasswordAction()
	{
		if (!empty($_POST))
		{
			$filebase_json_handler	=	 new	filebaseJsonHandler();
			$user_data				=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
			$user_data				=	json_decode($user_data, TRUE);
			
			$old_password			=	$_POST['old_password'];
			$old_password			=	hashPassword($old_password);
			
			if ($user_data['password'] === $old_password)
			{
				jQuery('#verify_old_password_status')->html('Verified');
			}
			else 
			{
				jQuery('#verify_old_password_status')->html('Not Verified');
			}
		}
		jQuery::getResponse();
	}
}