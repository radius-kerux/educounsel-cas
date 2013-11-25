<?php

/**
 * 
 * Folder specific main class controller can be set up this way :
 * The name of this main controller should be foldername_mainController
 * Before any requested controller within a folder is called, SPINE will call first the folder specific main controller if it exists
 * @author mon
 *
 */
class home_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$params = array(
							'description'	=>	'Spine Website'
		);
		
		$this->displayPhtml('main_content', 'home/main', $params);
	}
	
	public function end()
	{
	}
	
}