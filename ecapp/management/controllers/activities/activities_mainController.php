<?php

/**
 *
 *
 */
class activities_mainController extends Spine_SuperController implements Spine_MainInterface
{
	public function main()
	{
		$this->displayPhtml( 'content', 'activities/activities_main' );
	}

	public function end()
	{
	}
}