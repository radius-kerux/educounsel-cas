<?php
class user_profileBlock extends Spine_SuperBlock
{
	//------------------------------------------------------------------------------------
	
	public function displayUserProfile()
	{
		checkAuth();
		
		$filebase_json_handler	=	 new	filebaseJsonHandler();
		$user_data	=	$filebase_json_handler->selectAll('user_profile'.getValueFromAuth('access_token'));
		$user_data	=	json_decode($user_data, TRUE); 
		
		return $this->displayPhtml('user_profile/views/user_profile_block', array('data' => $user_data));
	}
	
	//------------------------------------------------------------------------------------
	
}