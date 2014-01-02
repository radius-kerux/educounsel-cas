<?php

require_once 'counselor/models/applications/application_form.php';

class applicationsController extends Spine_SuperController
{
	public function main()
	{
		$this->loadModule('htmlRenderer');
	}

	//------------------------------------------------------------------------------------

	public function indexAction()
	{
		//select the profile section on first load
		//$application_form = new application_form();
		//$application_form->user_id = 1;
		//$results = $application_form->select();
		
		$applicant_id	=	1;
		$results = $this->getApplicantInfo($applicant_id);

		if ($results)
		{
			$results	=	json_decode($results, TRUE);
		
			$this->displayPhtml( 'profile_container', 'applications/profile/profile_main', json_decode($results["profile"], TRUE));
			$this->displayPhtml( 'personal_information', 'applications/profile/personal_information');
			$this->displayPhtml( 'address', 		  'applications/profile/address'); 
			$this->displayPhtml( 'contact_details',   'applications/profile/contact_details');
			$this->displayPhtml( 'demographics', 	  'applications/profile/demographics');
			$this->displayPhtml( 'geography', 		  'applications/profile/geography');
			$this->displayPhtml( 'language', 		  'applications/profile/language');
			$this->displayPhtml( 'citizenship', 	  'applications/profile/citizenship');
			$this->includeInPageScript( 'local_bottom_script', 'applications/profile/profile.js' );
	
			
			$this->displayPhtml( 'family_container', 'applications/family/family_main', json_decode($results["family"], TRUE));
			$this->displayPhtml( 'household', 		 'applications/family/household');
			$this->displayPhtml( 'parent1', 		 'applications/family/parent1');
			$this->displayPhtml( 'parent2', 		 'applications/family/parent2');
			$this->displayPhtml( 'legal_guardian', 	  'applications/family/legal_guardian');
			$this->includeInPageScript( 'local_bottom_script', 'applications/family/family.js' );
			
			
			$this->displayPhtml( 'education_container', 'applications/education/education_main', json_decode($results["education"], TRUE));
			$this->displayPhtml( 'school', 	  'applications/education/school');
			$this->displayPhtml( 'other_school', 	  'applications/education/other_school');
			$this->displayPhtml( 'education_interruption', 	  'applications/education/education_interruption');
			$this->displayPhtml( 'college_and_universities', 	  'applications/education/college_and_universities');
			$this->displayPhtml( 'cbo', 	  'applications/education/cbo');
			$this->displayPhtml( 'grades', 	  'applications/education/grades');
			$this->displayPhtml( 'current_year_courses', 	  'applications/education/current_year_courses');
			$this->displayPhtml( 'honors', 	  'applications/education/honors');
			$this->displayPhtml( 'future_plans', 	  'applications/education/future_plans');
			$this->includeInPageScript( 'local_bottom_script', 'applications/education/education.js' );
			
			$this->displayPhtml( 'testing_container', 'applications/testing/testing_main', json_decode($results["testing"], TRUE));
			$this->displayPhtml( 'tests_taken', 	  'applications/testing/tests_taken');
			$this->displayPhtml( 'act_tests', 	  'applications/testing/act_tests');
			$this->displayPhtml( 'sat_tests', 	  'applications/testing/sat_tests');
			$this->displayPhtml( 'sat_subject_tests', 	  'applications/testing/sat_subject_tests');
			$this->displayPhtml( 'ap_subject_tests', 	  'applications/testing/ap_subject_tests');
			$this->displayPhtml( 'ib_subject_tests', 	  'applications/testing/ib_subject_tests');
			$this->displayPhtml( 'senior_secondary_leaving_examination', 	  'applications/testing/senior_secondary_leaving_examination');
			$this->displayPhtml( 'toefl_ibt', 	  'applications/testing/toefl_ibt');
			$this->displayPhtml( 'toefl_paper', 	  'applications/testing/toefl_paper');
			$this->displayPhtml( 'pte_academic_tests', 	  'applications/testing/pte_academic_tests');
			$this->displayPhtml( 'ielst', 	  'applications/testing/ielst');
			$this->includeInPageScript( 'local_bottom_script', 'applications/testing/testing.js' );
			
			
			$this->displayPhtml( 'writings_container', 'applications/writings/writings_main', json_decode($results["writing"], TRUE));
			$this->displayPhtml( 'personal_essay', 	  'applications/writings/personal_essay');
			$this->displayPhtml( 'disciplinary_history', 	  'applications/writings/disciplinary_history');
			$this->displayPhtml( 'additional_information', 	  'applications/writings/additional_information');
			$this->includeInPageScript( 'local_bottom_script', 'applications/writings/writings.js' );
		}
		
	}

	//------------------------------------------------------------------------------------

	public function clearFieldsAction()
	{
		if (verifyCurlToken() != 401)
		{
			$section		=	$_POST["section"];
			$column			=	$_POST["column"];
			$applicant_id	=	$_POST["user_id"];
			$field_names	=	$_POST["field_names"];
				
			//select the section to the database
			//$application_form = new application_form();
			//$application_form->user_id = $user_id;
			//$application_form->column = $column;
			//$result = $application_form->select();
			
			if($result[$column] != NULL)
			{
				$decoded_json =  json_decode($result[$column], TRUE);
				
				foreach($field_names as $fields)
					$decoded_json[$section][$fields] = "";
				
				$application_form->value = json_encode($decoded_json);
				$application_form->update();
			}
			else
				$decoded_json = array();
		}
		
		jQuery::getResponse();
	}
	
	//------------------------------------------------------------------------------------

	public function saveInfoAction()
	{
		$section		= $_POST["section"];
		$field_name		= $_POST["field_name"];
		$value 	 		= $_POST["value"];
		$applicant_id 	= $_POST["user_id"];
		$column			= $_POST["column"];
		
		
		//select the section to the database
		$result = $this->getApplicantInfo($applicant_id);
		$result	=	json_decode($result, TRUE);
		
		if($result[$column] != NULL)
			$decoded_json	=	json_decode($result[$column], TRUE);
		else
			$decoded_json	=	array();
		
		$decoded_json[$section][$field_name]	=	$value;
		$value	=	json_encode($decoded_json);
		
		$this->updateApplicationInfo($applicant_id, $column, $value);
		
		jQuery::getResponse();
	}
	
	//------------------------------------------------------------------------------------
	
	private function getApplicantInfo($applicant_id)
	{
		if (verifyCurlToken() != 401)
		{
			$restful_curl	=	new	restfulCurl();
			
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'applications/get-application-info';
			$restful_curl->postData(array('applicant_id' => $applicant_id));
			
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;
			
			if ($status	== 200)
				return	$result;
			return FALSE;
		}
	}
	
	//------------------------------------------------------------------------------------
	
	private function updateApplicationInfo($applicant_id, $column, $value)
	{
		if (verifyCurlToken() != 401)
		{
			$restful_curl	=	new	restfulCurl();
			
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'applications/update-application-info';
			$restful_curl->postData(array('applicant_id' => $applicant_id, 'column' => $column, 'value' => $value));
			
			$status	=	$restful_curl->response_code;
			$result	=	$restful_curl->result;

			if ($status	== 200)
				return	$result;
			return FALSE;
		}
	}
}