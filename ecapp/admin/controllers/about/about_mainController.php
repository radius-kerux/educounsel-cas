<?php

/**
 * 
 *
 */
class about_mainController extends Spine_SuperController implements Spine_MainInterface
{
	public function main()
	{
		$params = array(
							'description'	=>	'Spine Website'
		);
		
		$this->displayPhtml('main_content', 'about/main', $params);
	}
	
	public function end()
	{
	}
}