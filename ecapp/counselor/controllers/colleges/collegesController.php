<?php
class collegesController extends Spine_SuperController
{
	public function main()
	{
	}
	
	//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
		$colleges_array	=	$this->sortCollegesAlphabetically();
		$this->displayPhtml('content', 'colleges/colleges_main', array('colleges' => $colleges_array));
	}
	
	//------------------------------------------------------------------------------------
	
	public function profileAction()
	{
		$college_code		=	$this->getParametersPair('cc')?$this->getParametersPair('cc'):1;
		
		if (!$result = $this->checkCache('college_profile'.$college_code))
		{
			$result	=	$this->checkCache('colleges_listings_data');
			
			$college_list		=	$this->getCollegeList();
			$email_address		=	$this->getCollegeEmailAddress($college_list, $college_code);
			$college_profile	=	$this->getCollegeProfile($college_code);
			
			$result				=	array('college_profile' => $college_profile, 'email_address' => $email_address);
			
			$this->cache('college_profile'.$college_code, $result); //cache result
		}
		
		$this->displayPhtml('content', 'colleges/colleges_profile', $result);
	}
	
	//------------------------------------------------------------------------------------
	
	private function getCollegeList()
	{
		if (verifyCurlToken() != 401)
		{
			$result	=	$this->checkCache('colleges');

			if (!$result)
			{
				$restful_curl	=	new	restfulCurl();
				
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'data-resources/get-colleges-list';
				$restful_curl->postData(array('college_code' => 'college'));
				
				$status	=	$restful_curl->response_code;
				$result	=	$restful_curl->result;

				$this->cache('colleges', $result);
				if ($status	== 200)
					return	$result;
					
				return FALSE;
			}
			return $result;
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function getZipcodes()
	{
		if (verifyCurlToken() != 401)
		{
			$result	=	$this->checkCache('zipcodes');
			
			if (!$result)
			{
				$restful_curl	=	new	restfulCurl();
				
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'data-resources/get-zipcodes';
				$restful_curl->postData(array('college_code' => 'college'));
				
				$status	=	$restful_curl->response_code;
				$result	=	$restful_curl->result;

				$this->cache('zipcodes', $result);
				
				if ($status	== 200)
					return	$result;
					
				return FALSE;
			}
			return $result;
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function getCollegeProfile($college_code)
	{
		if (verifyCurlToken() != 401)
		{
			$restful_curl	=	new	restfulCurl();
			
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'data-resources/get-college-profile';
			$restful_curl->postData(array('college_code' => $college_code));
			
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;
			
			
			if ($status	== 200)
				return	$result;
			
			return	$result;
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function getCollegeEmailAddress($list, $code)
	{
		$list	=	json_decode($list, TRUE);
		foreach ($list as $index => $college)
		{
			if ($college['college_code'] == $code)
			{
				return $college['email_address'];
			}
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function sortCollegeByState()
	{
		$result	=	$this->checkCache('colleges_sorted_by_state');
		
		if (!$result)
		{
			$colleges	=	$this->getCollegeList();
			$colleges	=	json_decode($colleges, TRUE);
	
			$zipcodes	=	$this->getZipcodes();
			$zipcodes	=	json_decode($zipcodes, TRUE);
			
			$result_array	=	array();
			
			foreach ($zipcodes as $zipcode_index => $zipcode)
			{
				$zipcode['zip']	=	(string) zeroFillZip($zipcode['zip']);
				
				foreach ($colleges as $college_index => $college)
				{
					if ($zipcode['zip'] == $college['zipcode'])
					{
						$result_array[$zipcode['state_fullname']][$zipcode['city']][$zipcode['zip']][$college['college_name']]	=	$college;
					}
				}
			}
			$result	=	$result_array;
			$this->cache('colleges_sorted_by_state', $result);
		}
		return	$result;
	}
	
	//------------------------------------------------------------------------------------
	
	private function sortCollegesAlphabetically()
	{
		$colleges_array	=	$this->checkCache('colleges_sorted_alphabetically');
		
		if (!$colleges_array)
		{
			$college_list	=	$this->getCollegeList();
			$college_list	=	json_decode($college_list, TRUE);
			
			foreach ($college_list as $college_list_index => $college_info)
			{
				$college_name	=	$college_info['college_name'];
				$colleges_array[strtoupper($college_name[0])][$college_name]	=	$college_info;
			}
			
			ksort($colleges_array);
			
			$this->cache('colleges_sorted_alphabetically', $colleges_array);
		}
		return	$colleges_array;
	}
}