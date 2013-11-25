<?php
class activitiesController extends Spine_SuperController
{
	public function main()
	{
		$params['events']	=	NULL;
		$this->displayPhtml( 'calendar', 'main/calendar', $params );
	}
	
	//------------------------------------------------------------------------------------
	
	public function indexAction()
	{
		echo	'<h1>Activities</h1>';
	}
	
	//------------------------------------------------------------------------------------
}