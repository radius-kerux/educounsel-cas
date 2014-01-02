<?php
/**
 * 
 * Folder specific main class controller can be set up this way :
 * The name of this main controller should be foldername_mainController
 * Before any requested controller within a folder is called, SPINE will call first the folder specific main controller if it exists
 * @author mon
 *
 */
class applications_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$this->displayPhtml( 'main_content', 'applications/applications_main' );
		$this->displayPhtml( 'side_navigation', 'applications/side_navigation');
		
		$this->includeInPageScript( 'local_bottom_script', 'applications/js/forms.js' );
	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
	}
	
	//------------------------------------------------------------------------------------
	
}