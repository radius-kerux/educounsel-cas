<?php

/**
 * 
 * Folder specific main class controller can be set up this way :
 * The name of this main controller should be foldername_mainController
 * Before any requested controller within a folder is called, SPINE will call first the folder specific main controller if it exists
 * @author mon
 *
 */
class visa_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$this->displayPhtml( 'main_content', 'visa/visa_main' );
        $this->displayPhtml('main_content', 'visa/visa_main');
        $this->renderLocalScript('local_bottom_script', 'visa/js/local_bottom_script.js');

	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
	}
	
}