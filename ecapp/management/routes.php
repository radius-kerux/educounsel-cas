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
	'home'	=>	array( // home is the url for the folder specified by _name index
			'_name'	=> 'home', //indexes that start with underscore are keywords
					'learn-more'	=>	array(	//from here on the indexes will be treated as folder name
						'_name'	=> 'learn'
			)
		),
	'about-us'	=>	array(
			'_name'	=>	'about'
		),
	'contact-us'	=>	array(
			'_name'	=>	'contact'
		),
		
	'_default'	=>	'home' //so home is the dafault
	);

//------------------------------------------------------------------------------------
	
	public function getRoutes()
	{
		//uncomment this if you do not want to use this routing
		//return	=	FALSE;

		return $this->routes;
	}
}
