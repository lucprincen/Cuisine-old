<?php
/**
 * Cuisine Integrations handles the connections between themes and plugins.
 * Also gives warnings if a theme and plugin aren't connected
 *
 * @class 		Cuisine_Integrations
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Integrations {
	

	var $plugin_list = array();
	var $plugin_simple_view_list = array();

	var $theme_capabilities;
	var $theme_compatibilities;


	function __construct(){
		// the plugin_list contains all Cuisine enabled plugins:
		$this->plugin_list = array();


		$this->theme_capabilities = array();
		$this->theme_compatibilities = array();

	}


	function init(){
	
	}




	/*************************************************************************/
	/** PLUGIN functions *****************************************************/
	/*************************************************************************/



    /**
	 * Register a plugin:
	 */
    function plugin_register( $plugin_name, $version = null ){

    	$this->plugin_list[] = $plugin_name;
    
    	if( ( is_admin() && $version != null ) &&  !defined('DOING_AJAX') ){
    		global $cuisine;
			    		
    		$args = array( 'slug' => $plugin_name, 'version' => $version );
    		$cuisine->updates->add_to_update_list( $args );

    	}
    }




	/**
	* Check if a plugin is registered:	
	*/
	function is_plugin_registered($plugin_slug){		
		if( in_array( $plugin_slug, $this->plugin_list ) )
			return true;
		
		return false;
	}

	/**
	*
	*/


	/*************************************************************************/
	/** Theme functions ******************************************************/
	/*************************************************************************/



	/**
	*	Check if a theme is compatible:
	*/



	/**
	*	Check if a theme is capable:
	*/

	function is_theme_capable( $slug ){
		if( in_array( $slug, $this->theme_capabilities ) )
			return true;

		return false;
	}


}