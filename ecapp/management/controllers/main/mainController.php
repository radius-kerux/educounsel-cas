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
		//displayPhtml gets the contents of the specified Phtml file				
		//Then SPINE will put it in the place of specified section
		//The first parameter is the section in the template where the contents of phtml file will be placed
		//Sections are markings inside the Phtml files that are notated in this way: <spine::header/> where header is the name of the section
		//The second parameter for displayPhtml is the path to Phtml files
		//By convention the files should always have .template appended to its name before the phtml file extension
		//So if you want your Phtml file named hello_world your actual file should be named hello_world.template.phtml
		//In passing the path to the Phtml file, the whole path should not be included, only its path from the views folder
		//Do not include the .template.phtml suffix when you pass the phtml path parameter
		//So if your file is located at website/views/sample/hello_world.template.phtml your path-to-phtml parameter is 'sample/hello_world'
		//The third parameter is the $params - this actually are the data that you want to pass to your templates
		//$params should be an associative array, you can access this data by using their array keys as variable name
		//For example if you have array('title'=>'Hello World') you can utilize the data by accessing $title in the templates
		
		$params = array(
							'title'=>'Manager\'s Module',
		);
		
		$this->displayPhtml('main_phtml', 'main/main', $params); //Rendering of main template
		$this->displayPhtml('header', 'main/header');
		$this->getGlobalScripts();
		$this->getLocalScripts();
		$this->getProjectStylesheets();
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
	
	public function getGlobalScripts()
	{
		//includeExternalScript gets the specified scripts so SPINE can compile them as an external script
		//The first parameter is the section in which this scripts will be included
		
		$this->includeExternalScript('global_script', 'plugins/jquery/jquery-1.9.1.min.js');
		$this->includeExternalScript('global_script', 'plugins/php.jquery/javascript/jquery.php.js');
		$this->includeExternalScript('global_script', 'plugins/bootstrap/js/bootstrap.min.js');
	}
	
	//------------------------------------------------------------------------------------
	
	public function getProjectStylesheets()
	{
		//includeStyleSheet gets the specified style sheets so SPINE can compile them as an external stylesheet
		//The first parameter is the section in which this stylesheet will be included
		
		$this->includeStyleSheet('global_stylesheet', 'plugins/bootstrap/css/bootstrap.min.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/header.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/footer.css');
		$this->includeStyleSheet('global_stylesheet', SITE.'/views/main/css/main.css');
	}
	
	//------------------------------------------------------------------------------------
	
	public function getLocalScripts()
	{
		//includeInPageScript gets the specified local script so SPINE can put them in place of the specified section as an inpage script
		$this->includeInPageScript('local_top_script','main/js/local_top_script.js');
		$this->includeInPageScript('local_bottom_script','main/js/local_bottom_script.js');
	}
}