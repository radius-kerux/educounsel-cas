<?php

/**
 * 
 * Folder specific main class controller can be set up this way :
 * The name of this main controller should be foldername_mainController
 * Before any requested controller within a folder is called, SPINE will call first the folder specific main controller if it exists
 * @author mon
 *
 */
class dashboard_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$this->displayPhtml( 'content', 'dashboard/dashboard_main' );
		//$this->includeInPageScript( 'local_bottom_script', 'dashboard/js/dashboard_calendar_bottom_script.js' );
	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
	}
	
}