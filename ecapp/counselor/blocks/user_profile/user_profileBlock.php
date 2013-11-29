<?php
class user_profileBlock extends Spine_SuperBlock
{
	//------------------------------------------------------------------------------------
	
	public function displayUserProfile()
	{
		checkAuth();
		$user_data	=	getValueFromAuth('credentials');
		
		$user_data	=	json_decode($user_data, TRUE); 
		
		return $this->displayPhtml('user_profile/views/user_profile_block', array('data' => $user_data));
	}
	
	//------------------------------------------------------------------------------------
	
}