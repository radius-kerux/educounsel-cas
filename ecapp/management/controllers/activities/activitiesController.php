<?php
class activitiesController extends Spine_SuperController
{
	public function main()
	{
		$this->displayPhtml('calendar', 'main/calendar');
		$this->includeInPageScript('local_bottom_script', 'activities/js/activities_calendar_bottom_script.js');
	}
	
//------------------------------------------------------------------------------------

	public function indexAction()
	{
	}
	
//------------------------------------------------------------------------------------

	public static function getSchedule()
	{
       	$status = verifyCurlToken();
       	
       	if($status !== '401')
       	{
	       	$session_details = Spine_SessionRegistry::getSession("auth");
	       	$credentials = json_decode($session_details["credentials"]);
	       	
	       	$data["user_id"] = $credentials->user_id;
	       	
			$restful_curl =	new	restfulCurl();
			$restful_curl->application_url	=	DATA_RESOURCE_URL.'users/get-schedule';
            $restful_curl->postData($data);
			$status	= $restful_curl->response_code;
			$results = (array)json_decode($restful_curl->result);
			
			$schedule = array();
			foreach ($results as $result)
			{
				$schedule[] = array(
									"id"	=> $result->schedule_id,
									"title"	=> $result->title,
									"start"	=> $result->start,
									"end"	=> $result->end,
									"allDay"=> false
				);
			}
			
			return json_encode($schedule);
       	}
	}
}