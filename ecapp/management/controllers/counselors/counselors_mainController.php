<?php
class counselors_mainController extends Spine_SuperController
{
	public function main()
	{

		$this->displayPhtml('main_content', 'counselors/main');
		$this->includeInPageScript( 'local_bottom_script', 'counselors/js/local_bottom_script.js' );
	}

	public function indexAction()
	{
	}
}