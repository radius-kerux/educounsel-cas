<?php

/**
 * mainController is called before any controller as requested by clients is called
 * Rendering of global elements can be done here so the developer doesn't have to repeat the process
 */
	
class mainController extends Spine_SuperController implements Spine_MainInterface
{
	
	/**
	*
	* Before any action methods is fired in the the requested controller SPINE calls for main() method first
	* After the action method is invoked SPINE will then call end()
	* So for every invoked controller whether it is a main controller or a normal controller SPINE will call main() at the beginning and end() at the end
	*
	*/
	
	public function main()
	{
		$this->loadClasses();
		doAuth(); //a function inside plugins/global_functions/auth_functions.php

		if (Spine_SessionRegistry::getSession('auth', 'role_id') < 2)
		{
			header('location: user/logout/');
			exit();
		}
		
		$params = array(
							'title'=>'Receptionist\'s Module',
		);
		
		$this->displayPhtml('main_phtml', 'main/main', $params); //Rendering of main template
		$this->displayPhtml('header', 'main/header');
		$this->getGlobalScripts();
		$this->getLocalScripts();
		$this->getProjectStylesheets();
		$this->loadSiteWideModules();
		//Spine_GlobalRegistry::viewRegistryContent();
	}
	
	//------------------------------------------------------------------------------------
	
	public function end()
	{
		$this->displayPhtml('footer', 'main/footer');
		
		//End usually is the best place to put response headers
		//setHeaders is useful in sending response headers to SPINE
		//SPINE will send the header before the final output is sent
		
		$this->setHeaders("Cache-Control: public");
		$this->setHeaders("Expires: Sat, 26 Jul 2016 05:00:00 GMT");
	}
	
	//------------------------------------------------------------------------------------
	
	private function getGlobalScripts()
	{
		//includeExternalScript gets the specified scripts so SPINE can compile them as an external script
		//The first parameter is the section in which this scripts will be included
		
		$this->includeExternalScript('global_script', 'plugins/jquery/jquery-1.9.1.min.js');
		$this->includeExternalScript('global_script', 'plugins/php.jquery/javascript/jquery.php.js');
		$this->includeExternalScript('global_script', 'plugins/bootstrap/js/bootstrap.min.js');
	}
	
	//------------------------------------------------------------------------------------
	
	private function getProjectStylesheets()
	{
		//includeStyleSheet gets the specified style sheets so SPINE can compile them as an external stylesheet
		//The first parameter is the section in which this stylesheet will be included
		
		$this->includeStyleSheet('global_stylesheet', 'plugins/bootstrap/css/bootstrap.min.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/header.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/footer.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/main.css');
	}
	
	//------------------------------------------------------------------------------------
	
	private function getLocalScripts()
	{
		//includeInPageScript gets the specified local script so SPINE can put them in place of the specified section as an inpage script
		$this->includeInPageScript('local_top_script','main/js/local_top_script.js');
		$this->includeInPageScript('local_bottom_script','main/js/local_bottom_script.js');
	}
	
	//------------------------------------------------------------------------------------
	
	private function loadClasses ()
	{
		$classes_array	=	array(
			'pdo_helper/Db.class',
			'auth/Authentication'
		);
		
		$this->loadSpineClasses($classes_array);
	}
	
	//------------------------------------------------------------------------------------
	
	private function loadSiteWideModules()
	{
		$this->loadModule('restfulCurl');
	}
}