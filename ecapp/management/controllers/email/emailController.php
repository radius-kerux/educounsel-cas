<?php
class emailController extends Spine_SuperController
{
	public function main()
	{
		$this->displayPhtml('main_content', 'email/main');
	}

	public function indexAction()
	{
	}
}