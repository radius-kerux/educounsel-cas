<?php
/**
 * 
 * Routing configuration file utilizing routes class
 * $routes property is use to set the routing configuration
 * If the user wants to utilize the normal routing i.e. by traversing through the folders in controllers
 * set the return value of getRoutes method to FALSE
 */

class routes extends Spine_RouteAbstract
{
//------------------------------------------------------------------------------------
	private $routes = array (
		'dashboard'			=>	array(
				'_name'		=> 'dashboard' 
			),
		'user'				=>	array(
				'_name'		=>	'user'
			),
		'activities'		=>	array(
				'_name'		=>	'activities'
			),
		'applications'		=>	array(
				'_name'		=>	'applications'
			),
		'colleges'			=>	array(
				'_name'		=>	'colleges'
			),
		'counselors'		=>	array(
				'_name'		=>	'counselors'
			),
		'payment'			=>	array(
				'_name'		=>	'payment'
			),
		'_default'			=>	'dashboard' 
	);

//------------------------------------------------------------------------------------
	
	public function getRoutes()
	{
		//uncomment this if you do not want to use this routing
		//return	=	FALSE;
		
		return $this->routes;
	}
}
