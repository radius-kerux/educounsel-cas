<?php
class collegesController extends Spine_SuperController
{
	public function main()
	{
	}
	
	//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
		if (!$colleges_array	=	$this->checkCache('colleges_listings_data'))
		{
			$college_list	=	$this->getCollegeList();
			$college_list	=	json_decode($college_list, TRUE);
			
			foreach ($college_list as $college_list_index => $college_info)
			{
				$college_name	=	$college_info['college_name'];
				$colleges_array[strtoupper($college_name[0])][$college_name]	=	$college_info;
			}
			
			ksort($colleges_array);
			
			$this->cache('colleges_listings_data', $colleges_array);
		}
		
		$this->displayPhtml('content', 'colleges/colleges_main', array('colleges' => $colleges_array));
	}
	
	//------------------------------------------------------------------------------------
	
	public function profileAction()
	{
		$college_profile	=	$this->getCollegeProfile();
		$this->displayPhtml('content', 'colleges/colleges_profile', array('college_profile' => $college_profile));
	}
	
	//------------------------------------------------------------------------------------
	
	private function getCollegeList()
	{
		if (verifyCurlToken() != 401)
		{
			//$college_code	=	$this->getParametersPair('code')?$this->getParametersPair('code'):1;
		
			$restful_curl	=	new	restfulCurl();
			
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'data-resources/get-colleges-list';
			$restful_curl->postData(array('college_code' => 'college'));
			
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;
			
			if ($status	== 200)
				return	$result;
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function getCollegeProfile()
	{
		$college_code	=	$this->getParametersPair('cc')?$this->getParametersPair('cc'):1;
		
		if (verifyCurlToken() != 401)
		{
			if (!$result	=	$this->checkCache('college_profile'.$college_code))
			{
				$restful_curl	=	new	restfulCurl();
				
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'data-resources/get-college-profile';
				$restful_curl->postData(array('college_code' => $college_code));
				
				$status	=	$restful_curl->response_code;
				$result	=	$restful_curl->result;
				
				$this->cache('college_profile'.$college_code, $result); //cache result
				
				if ($status	== 200)
					return	$result;
			}
			
			return	$result;
		}
		
		
	}
}