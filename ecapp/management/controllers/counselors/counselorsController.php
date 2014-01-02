<?php
class counselorsController extends Spine_SuperController
{
	public function main()
	{
	}
	
// -------------------------------------------------------------------------------------------------------------------

	public function indexAction()
	{
       	$status = verifyCurlToken();

       	
       	if($status !== '401')
       	{
	       	$session_details = Spine_SessionRegistry::getSession("auth");
	       	$credentials = json_decode($session_details["credentials"]);
	       	
	       	$data["role_id"] = $credentials->role_id;
	       	
			$restful_curl =	new	restfulCurl();
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/get-counselors';
            $restful_curl->postData($data);
			$status	= $restful_curl->response_code;
			$result	= $restful_curl->result;
			
			$counselors = is_array(json_decode($result))? json_decode($result):array();
			
			$this->displayPhtml('application_content', 'counselors/counselors_listing', array("counselors"=>$counselors));
       	}
       	else
       		header("location: /user/logout");
	}
	
// -------------------------------------------------------------------------------------------------------------------
	
	public function editAction()
	{
		$counselor_id = $this->getParametersPair("counselor");
		
		if($counselor_id !== FALSE)
		{
       		$status = verifyCurlToken();
       		
       		if($status !== '401')
       		{
				$data = array("role_id" => 1, "user_id" => $counselor_id);
				
				$restful_curl =	new	restfulCurl();
				$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/get-detail';
	            $restful_curl->postData($data);
				$status	= $restful_curl->response_code;
				$result	= $restful_curl->result;
				
				if($status == "200")
				{
					$page_details = json_decode($result);
					$page_details->contact_details = json_decode($page_details->contact_details);
					$page_details->action = "edit";
					
					unset($page_details->password);
					$this->displayPhtml('application_content', 'counselors/counselors_profile', array("page_details"=>$page_details));
					$this->displayPhtml('popup_dialog', 'counselors/counselors_popups', array("page_details"=>$page_details));
				}
				else
					header("location: /");
       		}
			else
				header("location: /user/logout");
		}
		else
			header("location: /");
	}
	
// -------------------------------------------------------------------------------------------------------------------
	
	public function addAction()
	{
		$page_details = new stdClass();
		$page_details->action= "add";
		$this->displayPhtml('application_content', 'counselors/counselors_profile', array("page_details"=>$page_details));
		$this->displayPhtml('popup_dialog', 'counselors/counselors_popups', array("page_details"=>$page_details));
	}
	
// -------------------------------------------------------------------------------------------------------------------
	
	public function searchAction()
	{
        $status	=	verifyCurlToken();
        
        if($status !== '401')
        {
			$restful_curl =	new	restfulCurl();
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/search-counselors';
	        $restful_curl->postData(array("keyword"=>trim(strip_tags($_POST["keyword"])), "role_id"=>2));
			$status	= $restful_curl->response_code;
			$result	= json_decode($restful_curl->result);
			
			$content = "";
			
			foreach (range('A', 'Z') as $char)
			{
				$content .= '<div id="counselorsListing">';
				$content .= '<h3>'.$char.'</h3>';
				$content .= '<ul style="list-style: none;">';

				foreach ($result as $counselor)
				{
					$counselor_name = ucwords($counselor->counselor_name);
					if($counselor_name[0] == $char)
						$content .= '<li><a href="/counselors/edit/counselor/'.$counselor->user_id.'">'.$counselor_name.'</a></li>';
				}
				$content .= '</ul>';
				$content .= '</div>';
			}
			
			jQuery("div#counselorsListing")->empty();
			jQuery("div#counselorsListing")->append($content);
        }
        
        jQuery::getResponse();
	}
	
// -------------------------------------------------------------------------------------------------------------------
	
	public function saveAction()
	{
        jQuery("span.required")->hide();
        $status	=	verifyCurlToken();
		
        if(isset($_POST["user_id"]))
			$counselor_details = $this->checkFields($_POST, $_POST["user_id"]);
        else
			$counselor_details = $this->checkFields($_POST);
		
		unset($_POST);
        if ($status !== '401')
        {
			if($this->checker == "0")
			{
				$restful_curl =	new	restfulCurl();
				
				if(!isset($counselor_details["user_id"]))
					$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/add-counselor';
				else
					$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/update-counselor';
				
	            $restful_curl->postData($counselor_details);
				$status	= $restful_curl->response_code;
				$result	= $restful_curl->result;
	            
				if($result == "Success" AND $status == "200")
					jQuery("div#saveProfile")->show()->hide(3000);
			}
			else
			{
				jQuery("input[name=password]")->val("");
				jQuery("input[name=repassword]")->val("");
			}
			
			jQuery::getResponse();
        }
	}
	
// -------------------------------------------------------------------------------------------------------------------
	private $checker = 0;
	
	private function checkFields($post, $is_edit = FALSE)
	{
		$counselor_details = array();
		$to_encode = array();
		
        //clean post
        foreach ($post as $key=>$field)
        {
        	if(stripos("email", $key) !== FALSE)
	        	$post[$key] = trim(filter_var($field, FILTER_SANITIZE_EMAIL));
        	elseif(stripos("username", $field) !== FALSE AND stripos("password", $field)){}
        	else
	        	$post[$key] = trim(strip_tags($field));
        }
        
        foreach ($post as $key=>$field)
        {
        	if($key === "email")
        	{
        		$status = filter_var($field, FILTER_VALIDATE_EMAIL);
        		if($status === FALSE)
        			$this->produceError($key);
        		
        		$counselor_details["email_address"] = $field;
        	}
        	elseif($key === "username")
        	{
        		if($field !== "")
        		{
					$restful_curl =	new	restfulCurl();
					$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/check-username';
					
        			if($is_edit === FALSE)
				        $restful_curl->postData(array("username"=>$field));
        			else
				        $restful_curl->postData(array("username"=>$field, "user_id"=>$post["user_id"]));
        			
					$status	= $restful_curl->response_code;
					$result	= $restful_curl->result;
					
					if(!($status == "200" AND $result == "0"))
        				$this->produceError($key);
        		}
        		else
        			$this->produceError($key);
        		
        		$counselor_details["username"] = $field;
        	}
        	elseif($key === "repassword")
        	{
        		if($is_edit === FALSE)
        		{
	        		if($field !== $post["password"])
	        			$this->produceError($key);
	        		
	        		$counselor_details["password"] = $field;
        		}
        		elseif ($is_edit !== FALSE AND ($post["password"] !== "" OR $post["repassword"] !== ""))
        		{
	        		if($field !== $post["password"])
	        			$this->produceError($key);
	        		
	        		$counselor_details["password"] = $field;
        		}
        	}
        	else 
        	{
        		if($key === "password")
        		{
	        		if ($is_edit !== FALSE AND ($post["password"] !== "" OR $post["repassword"] !== ""))
	        		{
		        		if($field === "")
		        			$this->produceError($key);
	        		}
        		}
        		else
        		{
        			if($field === "")
        				$this->produceError($key);
        			
        			if($key == "first_name")
        				$counselor_details["firstname"] = $field;
        			elseif($key == "last_name")
        				$counselor_details["lastname"] = $field;
        			elseif($key == "user_id")
        				$counselor_details["user_id"] = $field;
        			else 
        				$to_encode[$key] = $field;
        		}
        	}
        }
        
        $counselor_details["contact_details"] = json_encode($to_encode);
        return $counselor_details;
	}
	
// -------------------------------------------------------------------------------------------------------------
	
	private function produceError($key)
	{
		jQuery("span[data-id=invalid_".$key."]")->show();
		$this->checker++;      
	}
}