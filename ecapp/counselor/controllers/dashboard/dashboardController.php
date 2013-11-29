<?php
class dashboardController extends Spine_SuperController
{
	public function main()
	{
		$params['events']	=	NULL;
		$this->displayPhtml( 'calendar', 'main/calendar', $params );
	}
	
//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
	}
	
//------------------------------------------------------------------------------------
	
	public function logoutAction()
	{
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
	
	public function testAction()
	{
		$this->displayPhtml('calendar', 'test/test');
	}
	
//------------------------------------------------------------------------------------

	public function sampleTestAction()
	{
		$content	=	file_get_contents($_FILES['file']['tmp_name']);
		$content	=	base64_encode($content);
		dumpData($content);
	}
}