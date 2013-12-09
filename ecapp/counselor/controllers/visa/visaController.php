<?php
class visaController extends Spine_SuperController
{
	public function main()
    {
    }

    //------------------------------------------------------------------------------------

    public function indexAction()
    {
        //Check credential validation
        $status	=	verifyCurlToken();

        $_POST["id"] = "1";
        //if credential is valid
        if ($status !== '401')
        {
            //Fetch data via curl
            $restful_curl	=	new	restfulCurl();
            $restful_curl->application_url	=	DATA_RESOURCE_URL.'users/get-student-details';
            $restful_curl->postData($_POST);
            $status	=	$restful_curl->response_code;
            $result	=	$restful_curl->result;

            //If process is OK
            if ($status == 202)
                $this->displayPhtml('email_template', 'visa/visa_email', array('user_data' => json_decode($result)));
        }
    }

    //------------------------------------------------------------------------------------

    public function changeUserAction()
    {
        //Check credential validation
        $status	=	verifyCurlToken();

        //if credential is valid
        if ($status !== '401')
        {
            //Fetch data via curl
            $restful_curl	=	new	restfulCurl();
            $restful_curl->application_url	=	DATA_RESOURCE_URL.'users/get-other-student-details';
            $restful_curl->postData($_POST);
            $status	=	$restful_curl->response_code;
            $result	=	$restful_curl->result;

            //If process is OK
            if ($status == 202)
            {
                $student_details = json_decode($result);
                $content = "First Name: ".$student_details->firstname."\n";
                $content .= "Last Name: ".$student_details->lastname."\n";
                $content .= "Project: ".$student_details->project;
                jQuery("textarea[name=student_info]")->text($content);
                jQuery::getResponse();
            }
        }
    }

    //------------------------------------------------------------------------------------

    public function emailAgencyAction()
    {
        //kuya mon ganito muna
        $headers = "MIME-Version: 1.0\r\nContent-type: text/html\r\nFrom: kevin.baisas@yahoo.com";

        mail($_POST["student"], "Dummy email", $_POST["student_info"], $headers);
    }

    //------------------------------------------------------------------------------------

    public function end()
    {
    }

}