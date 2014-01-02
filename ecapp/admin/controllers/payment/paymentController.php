<?php
class paymentController extends Spine_SuperController
{
	public function main()
	{
	}
	
//------------------------------------------------------------------------------------

	public function indexAction()
	{
		$payments	=	$this->getPayments();
		$this->displayPhtml('payment_content', 'payment/payment_records', array('payments' => $payments));
	}
	
//------------------------------------------------------------------------------------

	public function addApplicantAction()
	{
		$this->displayPhtml('payment_content', 'payment/payment_add_applicant');
	}
	
//------------------------------------------------------------------------------------
	
	public function addApplicantAjaxAction()
	{
		if(!empty($_POST))
		{
	       	$status = verifyCurlToken();
	       	
	       	if($status !== '401')
	       	{
	       		$student_profile	=	array(
	       		'personal_information'	=> array(
			       	'first_name'	=>	$_POST['first_name'],
	       			'middle_name'	=>	$_POST['middle_name'],
	       			'last_name'		=>	$_POST['last_name'])
       			);
       			
       			$contact_info	=	array(
       				'email'				=>	$_POST['email'],
       				'contact_number1'	=>	$_POST['contact_number']
       			);
       			
       			$student_profile['contact_info']	=	$contact_info;
	       		
       			$payment_data	=	array(
		       		'or_number'			=>	$_POST['or_number'],
		       		'credits'			=>	$_POST['credits'],
       				'timestamp'			=>	time(),
       				'applicant_id'		=>	NULL,
       				'amount'			=>	$_POST['amount']
		       	);
       			
		       	//create data here
		       	//for payment
				$restful_curl =	new	restfulCurl();
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'payments/pay';
	            $restful_curl->postData($payment_data);
				$status	= $restful_curl->response_code;
				
				$payment_record_id	=	$restful_curl->result;
				$payment_record_id	=	json_decode($payment_record_id, TRUE);
				$payment_record_id	=	$payment_record_id['payment_record_id'];
				
				if ($payment_record_id)
				{
					$data	=	array(
						'value'			=>	json_encode($student_profile),
						'section'		=>	'profile'
					);
					
					//add applicant
					$restful_curl->application_url	=	DATA_RESOURCE_URL.'applicants/add';
	       			$restful_curl->postData($data);
					$status	= $restful_curl->response_code;
					$result	= $restful_curl->result;
					$result	= json_decode($result, TRUE);
					
					$applicant_id	=	$result['applicant_id'];
					
					//update credits of applicant
					$this->updateApplication($data);
					
					//set new values for data
					$data['section']	=	'credits';
					$data['value']		=	$_POST['credits'];	
					
					//update credits of applicant
					$this->updateApplication($data);
					
					//update payments application ID
					
					$payment_data	=	array(
						'payment_record_id'	=>	$payment_record_id,
						'value'				=>	$applicant_id,
						'section'			=>	'applicant_id'
						);
						
					$restful_curl->application_url	=	DATA_RESOURCE_URL.'payments/single-item-update';
	          	  	$restful_curl->postData($payment_data);
	          	  	
	          	  	//result 
				}
	       	}
		}
		
		jQuery::getResponse();
	}

//------------------------------------------------------------------------------------

	private function updateApplication($data)
	{
		$restful_curl =	new	restfulCurl();
		$restful_curl->application_url	=	DATA_RESOURCE_URL.'applicants/update';
	    $restful_curl->postData($data);
		$status	=	$restful_curl->response_code;
		$result	=	$restful_curl->result;
		
		return	$status;
	}
	
//------------------------------------------------------------------------------------

	private function getPayments()
	{
		//get payment records in the database
	}
}