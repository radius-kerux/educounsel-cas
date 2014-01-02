<?php
class paymentController extends Spine_SuperController
{
	public function main()
	{
	}
	
// -------------------------------------------------------------------------------------------------------------------

	public function indexAction()
	{
       	$status = verifyCurlToken();
       	
       	if($status !== '401')
			$this->displayPhtml('application_content', 'payment/payment_add_payment');
       	else
       		header("location: /user/logout");
	}
	
// -------------------------------------------------------------------------------------------------------------------

	public function addAction()
	{
		if(!empty($_POST))
		{
	       	$status = verifyCurlToken();
	       	
	       	if($status !== '401')
	       	{
	       		$student_details = array(
			       				"firstname"=>trim(strip_tags($_POST["firstname"])), 
			       				"middlename"=>trim(strip_tags($_POST["middlename"])),
			       				"lastname"=>trim(strip_tags($_POST["lastname"])),
			       				"contactnumber"=>trim(strip_tags($_POST["contactnumber"]))
       			);
	       		
		       	$data = array(
		       				"student_details"=> json_encode($student_details),
		       				"email"=>$_POST["email"],
		       				"or_number"=>$_POST["or_number"]
		       	);
		       	
				$restful_curl =	new	restfulCurl();
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'students/add-student';
	            $restful_curl->postData($data);
				$status	= $restful_curl->response_code;
				$result	= $restful_curl->result;
				
				var_dump($restful_curl);
	       	}
		}
		
		jQuery::getResponse();
	}
}