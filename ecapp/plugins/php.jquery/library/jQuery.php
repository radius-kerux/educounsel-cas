<?php

//The data comes back in the $_POST

/**
 * jQuery
 *
 * @author dark jquery.hohli.com/
 * @date 27.12.2007
 */
require_once 'jQuery/Action.php';
require_once 'jQuery/Element.php';
require_once 'JQuery_SuperClass.php';

class jQuery extends JQuery_SuperClass 
{
    /**
     * static var for realize singlton
     * @var jQuery
     */
    public static $jQuery;
    
    /**
     * response stack
     * @var array
     */
    public $response = array(
                              // actions (addMessage, addError, eval etc.)
                              'a' => array(),
                              // jqueries
                              'q' => array()
                            );
    /**
     * __construct
     *
     * @access  public
     */
    function __construct() 
    {
    	
    }
    
    /**
     * init
     * init singleton if needed
     *
     * @return void
     */
    private static function init()
    {
        if (empty(jQuery::$jQuery)) {
            jQuery::$jQuery = new jQuery();
        }
        return true;
    }
    
    /**
     * addMessage
     * 
     * @param string $msg
     * @param string $callBack
     * @param array  $params
     * @return jQuery
     */
    public static function addMessage ($msg, $callBack = null, $params = null)
    {
        jQuery::init();
        
        $jQuery_Action = new jQuery_Action();        
        $jQuery_Action ->add("msg", $msg);
        
        
        // add call back func into response JSON obj
        if ($callBack) {
            $jQuery_Action ->add("callback", $callBack);
        }
        
        if ($params) {
            $jQuery_Action ->add("params",  $params);
        }
        
        jQuery::addAction(__FUNCTION__, $jQuery_Action);
        
        return jQuery::$jQuery;
    }
    
    /**
     * addError
     * 
     * @param string $msg
     * @param string $callBack
     * @param array  $params
     * @return jQuery
     */
    public static function addError ($msg, $callBack = null, $params = null)
    {
        jQuery::init();
        
        $jQuery_Action = new jQuery_Action();        
        $jQuery_Action ->add("msg", $msg);

        // add call back func into response JSON obj
        if ($callBack) {
            $jQuery_Action ->add("callback", $callBack);
        }
        
        if ($params) {
            $jQuery_Action ->add("params",  $params);
        }
        
        jQuery::addAction(__FUNCTION__, $jQuery_Action);
        
        return jQuery::$jQuery;
    }
    /**
     * evalScript
     *      
     * @param  string $foo
     * @return jQuery
     */
    public static function evalScript ($foo)
    {
        jQuery::init();
        
        $jQuery_Action = new jQuery_Action();        
        $jQuery_Action ->add("foo", $foo);

        jQuery::addAction(__FUNCTION__, $jQuery_Action);
        
        return jQuery::$jQuery;
    }
    
    /**
     * response
     * init singleton if needed
     *
     * @return string JSON
     */
    
 	public static function getResponseForFileUploads()
    {
    	//use this when you are are uploading with an ajax form
    	//reason: http://malsup.com/jquery/form/#code-samples
        jQuery::init();
        //THIS CODE WORKS YOU DONT NEED TO DO ANYTHING HERE,
       //Just call it if you are uploading something via ajax and the upload isnt empty
       //it works along side php.success
       ob_clean();
        echo '<textarea>'.json_encode(jQuery::$jQuery->response).'</textarea>';
        exit ();
    }
    
    
	public static function getResponse()
    {
        jQuery::init();
        
        echo json_encode(jQuery::$jQuery->response);
        exit ();
    }
    
    /**
     * addQuery
     * add query to stack
     *
     * @return jQuery_Element
     */
    public static function addQuery($selector)
    {
        jQuery::init();
        
        return new jQuery_Element($selector);
    }
    
    /**
     * addQuery
     * add query to stack
     * 
     * @param  jQuery_Element $jQuery_Element
     * @return void
     */
    public static function addElement(jQuery_Element &$jQuery_Element)
    {
        jQuery::init();
        
        array_push(jQuery::$jQuery->response['q'], $jQuery_Element);
    }
    
        
    /**
     * addAction
     * add query to stack
     * 
     * @param  string $name
     * @param  jQuery_Action $jQuery_Action
     * @return void
     */
    public static function addAction($name, jQuery_Action &$jQuery_Action)
    {
        jQuery::init();
        
        jQuery::$jQuery->response['a'][$name][] = $jQuery_Action;
    }
}

/**
 * jQuery
 *
 * alias for jQuery::jQuery
 *
 * @access  public
 * @param   string   $selector
 * @return  jQuery_Element
 */
function jQuery($selector) 
{
    return jQuery::addQuery($selector);
}