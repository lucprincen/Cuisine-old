<?php
/**
 * Plugin Name: Cuisine
 * Plugin URI: http://www.chefduweb.nl/cuisine/
 * Description: Cuisine contains the basic framework for Chef du Web. Comunication between plugins and themes and loads of admin & frontend helpers
 * Version: 1.2
 * Author: Chef du Web
 * Author URI: http://www.chefduweb.nl
 * Requires at least: 3.5
 * Tested up to: 3.5.1
 * 
 * Text Domain: cuisine
 * Domain Path: /languages/
 * 
 * @package Cuisine
 * @category Core
 * @author Chef du Web
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Cuisine' ) ) {


/**
 * Main Cuisine Class
 *
 * Contains the main functions for Cuisine, stores variables, and handles error messages
 * And starts up the whole shebang.
 *
 * @since Cuisine 1.0
 */
class Cuisine {

	/** Version ***************************************************************/
	
	var $version = '1.2';
	
	/** URLS ******************************************************************/
	
	var $plugin_url;
	var $asset_url;
	var $plugin_path;
	var $template_url;
	var $site_url;

	/** SAFETY ****************************************************************/

	var $session_name;

	/** Errors / Messages *****************************************************/
	
	var $errors = array(); // Stores errors
	var $messages = array(); // Stores messages
	
	/** Class Instances *******************************************************/
	
	var $integrations;
	var $theme;
	var $plugins;

	var $posttypes;
	var $taxonomies;

	/** Production / Development **********************************************/
	var $production_mode = false;




	/**************************************************************************/
	/** CORE functions ********************************************************/
	/**************************************************************************/


	/**
	 * Cuisine Constructor
	 *
	 * Gets things started
	 */
	function __construct() {

		// Start a PHP session - uses a unqiue session name for this install
		
		if ( ! session_id() ) {
			
			$this->session_name = 'PHPSESSID_' . substr( md5( get_bloginfo('name') ), 0, 6 );

			session_name( $this->session_name );
			session_start();
		}



		// Include required files
		$this->includes();
		
		// Installation
		if ( is_admin() && ! defined('DOING_AJAX') ) $this->install();
		
		// Actions
		add_action( 'init', array( &$this, 'init' ), 0 );

		
		// Loaded action
		do_action( 'cuisine_loaded' );
	}



	/**
	 * Include required core files
	 **/
	function includes() {

		//Specifics:
		if ( is_admin() ) $this->admin_includes();
		if ( ! is_admin() || defined('DOING_AJAX') ) $this->frontend_includes();


		//Initiate the basic:		
		include( 'cuisine-core-functions.php' );				// Contains core functions for the front/back end
		include( 'cuisine-font-functions.php' );				// Contains all the font-functions
		include( 'cuisine-core-helpers.php');					// Contains all the helpers to make life a little easier.

		//Init widgets:
		include( 'widgets/widget-init.php' );					// Widget classes

		//Init shortcodes:
		include( 'shortcodes/shortcode-init.php' );				// Shortcode classes


		//Classes:
		include( 'classes/class-integrations.php');				// Intergrations classes
		include( 'classes/class-plugins.php');					// Plugins class
		include( 'classes/class-theme.php');					// Theme class

		include( 'classes/class-posttypes.php');				// Handle posttypes easier
		include( 'classes/class-taxonomies.php');				// Handle taxonomies easier

	}


	/**
	 * Include required admin files
	 **/
	function admin_includes() {
		include( 'admin/cuisine-admin-init.php' );				// Takes care of the 'open' admin functions
		include( 'classes/class-updates.php');					// Update functions


	}

	/**
	 * Include required frontend files
	 **/
	function frontend_includes() {
		include( 'frontend/cuisine-front-init.php' );			// All Frontend functions

		
		
	}
	


	/**
	 * Install upon activation
	 **/
	function install() {
		if ( get_option('cuisine_db_version') != $this->version ) 
			add_action( 'init', 'install_cuisine', 1 );
	}



	/**
	 * Init Cuisine when WordPress Initialises
	 **/
	function init() {

		// Set up localisation
		$this->load_plugin_textdomain();

		// Variables
		$this->plugin_url			= $this->get_plugin_url();
		$this->asset_url			= $this->get_plugin_url().'/assets';
		$this->plugin_path 			= $this->get_plugin_path();
		$this->template_url			= get_bloginfo('template_url','raw');
		$this->site_url				= get_bloginfo('url', 'raw');

		// Load class instances
		$this->integrations			= new Cuisine_Integrations();		// Integrations class
		$this->theme 	 			= new Cuisine_Theme();				// Theme class
		$this->plugins 				= new Cuisine_Plugins();			// Plugins class

		$this->posttypes 			= new Cuisine_Posttypes();			// Posttypes class
		$this->taxonomies 			= new Cuisine_Taxonomies();			// Taxonomies class

		// Init integrations, theme and plugins
		$this->integrations->init();
		$this->theme->init();
		$this->plugins->init();

		// Load messages
		$this->load_messages();


		if( is_admin() ){

			$this->updates 				= new Cuisine_Updates();

		}

		// Init user roles
		$this->init_user_roles();
				
		// Init action
		do_action( 'cuisine_init' );


		//Get the production/development variable:
		$this->production_mode = get_option( 'cuisine_production_mode', false );

		$this->register_scripts();
	}


	/**
	 * Localisation
	 **/
	function load_plugin_textdomain() {
		// Note: the first-loaded translation file overrides any following ones if the same translation is present
		$locale = apply_filters( 'plugin_locale', get_locale(), 'cuisine' );
		load_plugin_textdomain( 'cuisine', false, dirname( plugin_basename( __FILE__ ) ).'/languages' );
	}



	/**
	 * Init Cuisine user roles
	 **/
	function init_user_roles() {
		global $wp_roles;
	
		if ( class_exists('WP_Roles') ) if ( ! isset( $wp_roles ) ) $wp_roles = new WP_Roles();	
		
		if ( is_object($wp_roles) ) {
			
			// Main capabilities for admin
			
			$wp_roles->add_cap( 'administrator', 'toggle_admin_mode' );
			$wp_roles->add_cap( 'administrator', 'toggle_production_mode' );

			$wp_roles->add_cap( 'administrator', 'manage_cuisine' );
			$wp_roles->add_cap( 'administrator', 'edit_cuisine_template' );

			$wp_roles->add_cap( 'administrator', 'edit_cuisine_content');
			$wp_roles->add_cap( 'editor', 'edit_cuisine_content');

		}
	}

	/**************************************************************************/
	/** Register front-end scripts ********************************************/
	/**************************************************************************/

	function register_scripts(){

		//first, add jQuery:
		if ( ! is_admin() ){

			wp_deregister_script( 'jquery' );
   			wp_register_script( 'jquery', "http" . ( $_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js", false, null, true );
   			wp_enqueue_script( 'jquery' );

   		}

		// Register Cuisine frontend javascripts
		$args = array(
			'id'			=>	'cuisine_script',
			'url'			=> 	$this->asset_url.'/js/cuisine-front.js',
			'root_url'		=>	$this->plugins->root_url('cuisine', true ).'assets/js/cuisine-front.js',
			'on_page'		=>	'all'
		);

		$this->theme->register_scripts( $args );

		$args = array(
			'id'			=>	'cuisine_responsive',
			'url'			=> 	$this->asset_url.'/js/cuisine-responsive.js',
			'root_url'		=>	$this->plugins->root_url('cuisine', true ).'assets/js/cuisine-responsive.js',
			'on_page'		=>	'all'
		);

		$this->theme->register_scripts( $args );

		$args = array(
		      'id'			=> 'cuisine_images',
		      'url'			=> 	$this->asset_url.'/js/cuisine-images.js',
		      'root_url'	=> $this->plugins->root_url('cuisine', true).'assets/js/cuisine-images.js',
		      'on_page'		=> 'all'
		);

		$this->theme->register_scripts( $args );

		$args = array(
		      'id'			=> 'cuisine_validate',
		      'url'			=> 	$this->asset_url.'/js/cuisine-validate.js',
		      'root_url'	=> $this->plugins->root_url('cuisine', true).'assets/js/cuisine-validate.js',
		      'on_page'		=> 'all'
		);

		$this->theme->register_scripts( $args );


		if( isset( $post ) )
			wp_localize_script( 'jquery', 'post', array( 'ID' => $post->ID, 'post_title' => $post->post_title, 'slug' => $post->post_name, 'post_parent' => $post->post_parent, 'guid' => $post->guid ) );

		wp_localize_script( 'jquery', 'ajaxurl', admin_url("admin-ajax.php"));
	}



	/**************************************************************************/
	/** Helper functions ******************************************************/
	/**************************************************************************/

	
	/**
	 * Get the plugin url
	 */
	function get_plugin_url() { 
		if ( $this->plugin_url ) return $this->plugin_url;
		return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}
	
	/**
	 * Get the plugin path
	 */
	function get_plugin_path() { 	
		if ( $this->plugin_path ) return $this->plugin_path;
		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	 
	/**
	 * Ajax URL
	 */ 
	function ajax_url() { 
		return str_replace( array('https:', 'http:'), '', admin_url( 'admin-ajax.php' ) );
	} 



	/**************************************************************************/
	/** Messages functions ****************************************************/
	/**************************************************************************/
    
    /**
	 * Load Messages
	 */
	function load_messages() { 


		if ( isset( $_SESSION['errors'] ) ) $this->errors = $_SESSION['errors'];
		if ( isset( $_SESSION['messages'] ) ) $this->messages = $_SESSION['messages'];


		unset( $_SESSION['messages'] );
		unset( $_SESSION['errors'] );
			
			
		// Load errors from querystring
		if ( isset( $_GET['cuisine_error'] ) ) {
			$this->add_error( esc_attr( $_GET['cuisine_error'] ) );
		}
	}

	/**
	 * Add an error
	 */
	function add_error( $error ) { $this->errors[] = apply_filters( 'cuisine_add_error', $error ); $this->set_messages(); }
	
	/**
	 * Add a message
	 */
	function add_message( $message ) { $this->messages[] = apply_filters( 'cuisine_add_message', $message ); $this->set_messages(); }
	
	/** Clear messages and errors from the session data */
	function clear_messages() {
		$this->errors = $this->messages = array();
		unset( $_SESSION['messages'], $_SESSION['errors'] );
	}
	
	/**
	 * Get error count
	 */
	function error_count() { return sizeof($this->errors); }
	
	/**
	 * Get message count
	 */
	function message_count() { return sizeof($this->messages); }
	
	/**
	 * Get errors
	 */
	function get_errors() { return (array) $this->errors; }
	
	/**
	 * Clear errors
	 */
	function clear_errors() { $this->errors = array(); unset( $_SESSION['errors'] ); }

	/**
	 * Get messages
	 */
	function get_messages() { return (array) $this->messages; }
	
	
	/**
	 * Set session data for messages
	 */
	function set_messages() {
		$_SESSION['errors'] = $this->errors;
		$_SESSION['messages'] = $this->messages;
	}

}


/**
 * Init cuisine class
 */
$GLOBALS['cuisine'] = new Cuisine();

}	
?>