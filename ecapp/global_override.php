<?php

/**
*
*
*
*/

class globalOverride extends Spine_OverrideAbstract
{
	protected $overrides_array	=	array(
		'_info'			=>	array(
			'call_back'	=>	'siteInfo',
			'parameters'=>	array(
				'name'		=>	'Your Name',
				'message'	=>	'Your message'
			),
			'exit'		=>	TRUE	
		),
		'admin'			=>	array(
			'call_back'	=>	'setSite',
			'parameters'=>	array(
				'site'	=>	'admin'
			)
		),
		'mngmt'			=>	array(
			'call_back'	=>	'setSite',
			'parameters'=>	array(
				'site'	=>	'mngmt'
			)
		)
	);

// -------------------------------------------------------------------------------------------------------------------	
	
	public function siteInfo()
	{
		echo 'Access Forbidden';
	}
	
// -------------------------------------------------------------------------------------------------------------------

	public function setSite($parameters)
	{
		if (isset($parameters['site']))
		{
			if ($parameters['site'] == 'admin')
				define('SITE', 'admin');
			elseif ($parameters['site'] == 'mngmt')
				define('SITE', 'management');
			else
				define('SITE', 'counselor');
		}
			
	}
}