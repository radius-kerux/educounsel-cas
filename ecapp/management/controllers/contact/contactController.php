<?php
class contactController extends Spine_SuperController
{
	public function main()
	{
		$this->displayPhtml('inner_most', 'home/set');
	}
	
	public function indexAction()
	{
	}
}