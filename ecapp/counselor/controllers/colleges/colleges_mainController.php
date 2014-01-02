<?php

/**
 * 
 * Folder specific main class controller can be set up this way :
 * The name of this main controller should be foldername_mainController
 * Before any requested controller within a folder is called, SPINE will call first the folder specific main controller if it exists
 * @author mon
 *
 */
class colleges_mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	public function main()
	{
		$this->includeInPageScript('local_top_script', '/colleges/js/inpage_colleges_script.js');
		$this->includeInPageScript('local_top_script', '/colleges/js/lavenshtein_distance.js');
	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
	}
	
}