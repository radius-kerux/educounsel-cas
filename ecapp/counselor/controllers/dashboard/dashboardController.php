<?php
class dashboardController extends Spine_SuperController
{
	public function main()
	{
		$params['events']	=	NULL;
		$this->displayPhtml( 'calendar', 'main/calendar', $params );
	}
	
//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
	}
	
//------------------------------------------------------------------------------------
	
	public function testAction()
	{
		$this->displayPhtml('calendar', 'test/test');
	}
	
//------------------------------------------------------------------------------------

	public function sampleTestAction()
	{
		$content	=	file_get_contents($_FILES['file']['tmp_name']);
		$content	=	base64_encode($content);
		dumpData($content);
	}
}