<?php
/**
 * Cuisine Theme handles the admin initiation and the compatibilities of a Chef du Web theme
 *
 * @class 		Cuisine_Theme
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Theme {

	var $customize_options;

	var $theme_customization_sections;
	var $theme_customization_controls;
	var $customizer_json;
	var $js_customizers;

	var $theme_scripts_to_query;
	var $theme_vars_to_query;

	var $theme_setting_priority = 0;


	function __construct() {
	
		$this->customize_options = array();

		$this->theme_custimization_sections = array();
		$this->theme_custimization_controls = array();

		$this->customizer_json = ''; 
		$this->js_customizers = array();

		$this->theme_scripts_to_query = array();
		$this->theme_vars_to_query = array();
	}



	function init(){ 

		if( is_admin() ) $this->init_theme_admin();
		if( !is_admin() ) $this->init_theme_frontend();

		//setup the theme editor:
		$this->setup_theme_editor();


		return false;

	}


	/*************************************************************************/
	/** ADMIN functions ******************************************************/
	/*************************************************************************/


	/**
	*	Init all the admin functions:
	*/
	function init_theme_admin(){

		if( $this->is_cuisine_enabled_theme() ){

			//setup links to the customizer:
			$this->setup_customizer_links();
		}
	}


	/** ######################################################################/
	/** THEME CUSTOMIZER MENU LINK ==========================================*/


	/**
	*	Register the customizer links:
	*/
	function setup_customizer_links(){
		//Add the redirect for the menu item (this is a little Hack, since WordPress isn't giving us the option of adding a real link):
		add_action( 'admin_init', array( &$this, 'redirect_from_admin_menu' ) );

		//Add the menu item:
		add_action( 'admin_menu', array( &$this, 'theme_menu' ) );

	}


	/**
	*	Add the customizer links:
	*/
	function theme_menu(){
		add_theme_page('Theme Options', __('Theme Options', 'cuisine'), 'edit_cuisine_template', 'cuisine_theme_options', array(&$this, 'theme_options_page') );

	}


	/**
	*	The theme options page (this is empty)
	*/
	function theme_options_page(){ }


	/**
	*	Add the customizer redirect:
	*/
	function redirect_from_admin_menu(){
		global $pagenow;
		//check for the proper $_GET value:
		if( $pagenow == 'themes.php' && isset( $_GET['page'] ) && $_GET['page'] == 'cuisine_theme_options' ){
			
			//redirect to the propper page:
			wp_redirect( admin_url().'customize.php' );
		}
	}



	/** ######################################################################/
	/** THEME CUSTOMIZER ====================================================*/


    /**
	 * Set the theme editor:
	 */
    function setup_theme_editor(){
		
	 	//first check the permissions:
    	if(! current_user_can('edit_cuisine_template') )
    		return false;


    	//get the default theme options:

  		$this->customize_options = $this->get_theme_defaults();
		

		add_action( 'customize_register', array( &$this, 'cuisine_setup_theme_customizer' ) );
   
    }




    /**
    *	Add the basic sections and controls for Cuisine enabled themes 
    *	After that, add the ones generated from the theme itself.
    */
	
	function cuisine_setup_theme_customizer( $wp_customize ) {

		/* ##########################################################*/
		/* Sections =================================================*/

			//General
			$this->add_theme_section(
				__('General', 'cuisine'),									//label
				'cuisine_general_section',									//id
				39															//priority (default: 45)
			);

			//Header
			$this->add_theme_section( __('Header', 'cuisine'), 'cuisine_header_section', 40 );

			//Fonts
			$this->add_theme_section( __('Fonts', 'cuisine'), 'cuisine_font_section' );

			//Links
			$this->add_theme_section( __('Links', 'cuisine'), 'cuisine_link_section', 50 );

			//Buttons
			$this->add_theme_section( __('Buttons', 'cuisine'), 'cuisine_button_section', 51 );

			//Logo
			$this->add_theme_section( __('Logo', 'cuisine'), 'cuisine_logo_section', 30 );

			//Header menu's
			$this->add_theme_section( __('Menu\'s', 'cuisine'), 'cuisine_header_menu_section', 52 );

			//Sidebar 
			$this->add_theme_section( __('Sidebar', 'cuisine'), 'cuisine_sidebar_section' );

			//Footer:
			$this->add_theme_section( __('Footer', 'cuisine'), 'cuisine_footer_section', 57 );


			


			/* ########################################################*/
			/* Algemeen ===============================================*/

				//body
				$this->add_theme_setting(
					__('Background image'),							//label
					'image', 												//type
					'cuisine_general_section', 								//section											
					'body-background-image',								//id
					$this->customize_options['body-background-image'],		//default
					array()													//choices
				);

				

				$this->add_theme_setting( __('Background repeat', 'cuisine'), 'select', 'cuisine_general_section', 'body-background-repeat', $this->customize_options['body-background-repeat'], $this->customize_options['body-background-repeat-choices'] );


				//body
				$this->add_theme_setting( __('Background color', 'cuisine'), 'color', 'cuisine_general_section', 'body-background-color', $this->customize_options['body-background-color'] );

				//container
				$this->add_theme_setting( __('Container background', 'cuisine'), 'color', 'cuisine_general_section', 'container-background-color', $this->customize_options['container-background-color'] );





			/* ########################################################*/
			/* Header =================================================*/

				//header
				$this->add_theme_setting( __('Header image', 'cuisine'), 'image', 'cuisine_header_section', 'header-image', $this->customize_options['header-image'] );

				//header
				$this->add_theme_setting( __('Header background', 'cuisine'), 'color', 'cuisine_header_section', 'header-background-color', $this->customize_options['header-background-color'] );


			/* #######################################################*/
			/* Links =================================================*/

				//link
				$this->add_theme_setting( __('Link color', 'cuisine'), 'color', 'cuisine_link_section', 'a-color', $this->customize_options['a-color'] );

				//hover
				$this->add_theme_setting( __('Link Hover', 'cuisine'), 'color', 'cuisine_link_section', 'a-hover-color', $this->customize_options['a-hover-color'] );

				//visited
				$this->add_theme_setting( __('Link Visited', 'cuisine'), 'color', 'cuisine_link_section', 'a-visited-color', $this->customize_options['a-visited-color'] );


			/* #####################################################*/
			/* Buttons =============================================*/

				//button color
				$this->add_theme_setting( __('Text color', 'cuisine'), 'color', 'cuisine_button_section', 'button-color', $this->customize_options['button-color'] );

				//button background:
				$this->add_theme_setting( __('Backgroundcolor', 'cuisine'), 'color', 'cuisine_button_section', 'button-background-color', $this->customize_options['button-background-color'] );

				//button hover color:
				$this->add_theme_setting( __('Text color hover', 'cuisine'), 'color', 'cuisine_button_section', 'button-hover-color', $this->customize_options['button-hover-color'] );

				//button hover background:
				$this->add_theme_setting( __('Background color hover', 'cuisine'), 'color', 'cuisine_button_section', 'button-hover-background-color', $this->customize_options['button-hover-background-color'] );

				//button icon color:
				$this->add_theme_setting( __('Icon color', 'cuisine'), 'select', 'cuisine_button_section', 'button-icon', $this->customize_options['button-icon'], $this->customize_options['button-icon-choices'] );



			/* #######################################################*/
			/* Fonts =================================================*/


				//H1 color + size:
				$this->add_theme_setting( __('H1 font', 'cuisine'), 'select', 'cuisine_font_section', 'h1-font-family', $this->customize_options['h1-font-family'], $this->customize_options['h1-font-family-choices'] );

				$this->add_theme_setting( __('H1 font size', 'cuisine'), 'text', 'cuisine_font_section', 'h1-font-size', $this->customize_options['h1-font-size'] );

				$this->add_theme_setting( __('H1 color', 'cuisine'), 'color', 'cuisine_font_section', 'h1-font-color', $this->customize_options['h1-font-color'] );


				//H2 color + size:
				$this->add_theme_setting( __('H2 font', 'cuisine'), 'select', 'cuisine_font_section', 'h2-font-family', $this->customize_options['h2-font-family'], $this->customize_options['h2-font-family-choices'] );
				
				$this->add_theme_setting( __('H2 font size', 'cuisine'), 'text', 'cuisine_font_section', 'h2-font-size', $this->customize_options['h2-font-size'] );

				$this->add_theme_setting( __('H2 color', 'cuisine'), 'color', 'cuisine_font_section', 'h2-font-color', $this->customize_options['h2-font-color'] );


				//H3 color + size:
				$this->add_theme_setting( __('H3 font', 'cuisine'),  'select', 'cuisine_font_section', 'h3-font-family', $this->customize_options['h3-font-family'], $this->customize_options['h3-font-family-choices'] );
				
				$this->add_theme_setting( __('H3 font size', 'cuisine'), 'text', 'cuisine_font_section', 'h3-font-size', $this->customize_options['h3-font-size'] );

				$this->add_theme_setting( __('H3 color', 'cuisine'), 'color', 'cuisine_font_section', 'h3-font-color', $this->customize_options['h3-font-color'] );


				//P Font size:
				$this->add_theme_setting( __('P font', 'cuisine'), 'select', 'cuisine_font_section', 'p-font-family', $this->customize_options['p-font-family'], $this->customize_options['p-font-family-choices'] );
				
				$this->add_theme_setting( __('P font size', 'cuisine'), 'text', 'cuisine_font_section', 'p-font-size', $this->customize_options['p-font-size'] );

				$this->add_theme_setting( __('P color', 'cuisine'), 'color', 'cuisine_font_section', 'p-font-color', $this->customize_options['p-font-color'] );


			/* ########################################################*/
			/* Logo =================================================*/
	
				//Website title
				$this->add_theme_setting( __('Show website title', 'cuisine'), 'checkbox', 'cuisine_logo_section', 'logo-show-text', $this->customize_options['logo-show-text'] );

				//Logo H1 color
				$this->add_theme_setting( __('Logo text color', 'cuisine'), 'color', 'cuisine_logo_section', 'logo-h1-color', $this->customize_options['logo-h1-color'] );
	
				//Logo H1 background-color
				$this->add_theme_setting( __('Logo label background color', 'cuisine'), 'color', 'cuisine_logo_section', 'logo-h1-background-color', $this->customize_options['logo-h1-background-color'] );
	
				//Logo image:
				$this->add_theme_setting( __('Logo image', 'cuisine'), 'image', 'cuisine_logo_section', 'logo-image', $this->customize_options['logo-image'] );


			/* #############################################################*/
			/* Header Menu =================================================*/

				//Top menu background color
				$this->add_theme_setting( __('Top menu background color', 'cuisine'), 'color', 'cuisine_header_menu_section', 'topmenu-background-color', $this->customize_options['topmenu-background-color'] );
	
				//Top menu font size
				$this->add_theme_setting( __('Top menu font size', 'cuisine'), 'text', 'cuisine_header_menu_section', 'topmenu-font-size', $this->customize_options['topmenu-font-size'] );

				//Main menu background color
				$this->add_theme_setting( __('Main menu background color', 'cuisine'), 'color', 'cuisine_header_menu_section', 'mainmenu-background-color', $this->customize_options['mainmenu-background-color'] );

				//Main menu hover background color
				$this->add_theme_setting( __('Main menu hover background', 'cuisine'), 'color', 'cuisine_header_menu_section', 'mainmenu-background-hover-color', $this->customize_options['mainmenu-background-hover-color'] );
	
				//Main menu font
				$this->add_theme_setting( __('Main menu font', 'cuisine'), 'select', 'cuisine_header_menu_section', 'mainmenu-font-family', $this->customize_options['mainmenu-font-family'], $this->customize_options['mainmenu-font-family-choices'] );

				//Main menu font size
				$this->add_theme_setting( __('Main menu font size', 'cuisine'), 'text', 'cuisine_header_menu_section', 'mainmenu-font-size', $this->customize_options['mainmenu-font-size'] );

				//Main menu font color
				$this->add_theme_setting( __('Main menu font color', 'cuisine'), 'color', 'cuisine_header_menu_section', 'mainmenu-font-color', $this->customize_options['mainmenu-font-color'] );


			/* #############################################################*/
			/* Sidebar =====================================================*/

				//Sidebar background:
				$this->add_theme_setting( __('Sidebar background color', 'cuisine'), 'color', 'cuisine_sidebar_section', 'sidebar-background-color', $this->customize_options['sidebar-background-color'] );

				//Sidebar text:
				$this->add_theme_setting( __('Sidebar font color', 'cuisine'), 'color', 'cuisine_sidebar_section', 'sidebar-color', $this->customize_options['sidebar-color'] );


			/* #############################################################*/
			/* Footer =====================================================*/

				//Footer background:
				$this->add_theme_setting( __('Footer background color', 'cuisine'), 'color', 'cuisine_footer_section', 'footer-background-color', $this->customize_options['footer-background-color'] );

				//Footer text:
				$this->add_theme_setting( __('Footer font color', 'cuisine'), 'color', 'cuisine_footer_section', 'footer-color', $this->customize_options['footer-color']);


		/* #######################################################################*/
		/* Theme specific panels =================================================*/

		if( !empty( $this->theme_customization_sections ) ){

			foreach( $this->theme_customization_sections as $section ){
				$this->add_theme_section($section['label'], $section['id'], $section['priority']);
			
			}
		}	


		$this->theme_customization_sections = array();


		/* #######################################################################*/
		/* Theme specific controls ===============================================*/

		if( !empty( $this->theme_customization_controls ) ){
			foreach( $this->theme_customization_controls as $control){
				$this->add_theme_setting( $control['label'], $control['type'], $control['section'], $control['id'], $control['default'], $control['choices']);

			}
		}

		$this->theme_customization_controls = array();


		/* #######################################################################*/
		/* Add the Script on the bottom of customize for live updates ============*/

		global $wp_customize;

		if( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_footer', array( &$this, 'theme_customize_init') );


	}

	function theme_customize_init(){

		global $cuisine;

		
			if( !$this->customizer_json )
				$this->customizer_json = $this->generate_customizer_json( );

		

		wp_enqueue_script( 'cuisine_customizer', $cuisine->asset_url.'/js/customize.js', false, false, true);
		wp_localize_script( 'cuisine_customizer', 'controls_object', $this->customizer_json );

	}


	/**
	*	Theme setting en control toevoegen aan de customizer:
	*/
	function add_theme_setting($label, $type, $section, $id, $default = '', $choices = array()){
		global $wp_customize;

		//add the setting:
		$wp_customize->add_setting(
			'cuisine_theme_options['.$id.']', array(
					'default'        => $default,
					'type'           => 'option',
					'capability'     => 'edit_cuisine_template',
					'transport'      => 'postMessage'
		) );


		//add a control for the setting:
		switch($type){

			case 'color':
				$wp_customize->add_control(
					new WP_Customize_Color_Control(
						$wp_customize,
							$id , array(
								'label'   		=> $label,
								'section' 		=> $section,
								'settings'  	=> 'cuisine_theme_options['.$id.']',
								'priority' 		=> $this->theme_setting_priority++,
							)
					 )
				);
			break;
			case 'select':
			    $wp_customize->add_control(
			    	$id, array(
        				'label'      => $label,
        				'section'    => $section,
        				'settings'   => 'cuisine_theme_options['.$id.']',
        				'type'		 => 'select',
        				'choices'	 => $choices,
  						'priority' 	 => $this->theme_setting_priority++,
    				)
			    );
			break;
			case 'checkbox':
				$wp_customize->add_control(
					$id, array(
						'settings' => 'cuisine_theme_options['.$id.']',
						'label'    => $label,
						'section'  => $section,
						'type'     => 'checkbox',
						'priority' => $this->theme_setting_priority++,

					)
				);
			break;
			case 'radio':
				$wp_customize->add_control(
					'themename_color_scheme', array(
						'label'      => $label,
						'section'  	 => $section,
						'settings'   => 'cuisine_theme_options['.$id.']',
						'type'       => 'radio',
						'choices'    => $choices,
						'priority' 	 => $this->theme_setting_priority++,

					)
				);
			break;
			case 'image':
			   	$wp_customize->add_control(
			   		new WP_Customize_Image_Control(
			   			$wp_customize,
			   				$id, array(
       							'label'    			=> $label,
        						'section'  			=> $section,
								'settings'   		=> 'cuisine_theme_options['.$id.']',
								'priority' 			=> $this->theme_setting_priority++,

    						)
    				)
    			);
			break;
			case 'text':
			   	$wp_customize->add_control(
			   		$id, array(
       					'label'    		=> $label,
        				'section'  		=> $section,
						'settings'   	=> 'cuisine_theme_options['.$id.']',
						'priority' 		=> $this->theme_setting_priority++,

    				)
    			);
			break;
		}


	}


	/**
	*	Add theme customization section:
	*/

	function add_theme_section($label, $id, $priority = 45){
		global $wp_customize;
		
		$wp_customize->add_section(
			$id, array(
				'title'          => $label,
				'priority'       => $priority,
			)
		);
	}


	/*****************************************************************************/
	/*  Theme Customisation ******************************************************/
	/*****************************************************************************/


	function register_theme_sections($sections){

		foreach($sections as $section){

			if( empty( $section['label'] ) || empty( $section['id'] ) )
				throw new Exception("A section needs a label and ID");

			if( empty( $section['priority'] ) )
				$section['priority'] = 55;

				$this->theme_customization_sections[] = $section;
		}
	}


	function register_theme_controls($controls){
		//$label, $wp_customize, $type, $section, $id, $default = '', $choices = array()
		foreach($controls as $control){

			if( empty( $control['label'] ) || empty( $control['id'] ) || empty($control['section'] ) )
				throw new Exception("A control needs a label, section and ID");

			if( empty( $control['type'] ) )
				$control['type'] = 'text';

			if( empty( $control['default'] ) )
				$control['default'] = '';

			if( empty( $control['choices'] ) )
				$control['choices'] = array();

			$this->theme_customization_controls[] = $control;
		}
	}


	function generate_customizer_json(){

		if( !$this->js_customizers )
			$this->js_customizers = $this->get_theme_customizers();


		if( empty( $this->js_customizers ) ) return '';


		$json = array();

		foreach( $this->js_customizers as $key => $cu ){
			$arr = array();

			$arr['control'] = array( 'id' => $key, 'element' => $cu['object'], 'property' => $cu['property'] );
			$json[] = $arr;
		}

		//turn into a json object:
		return json_encode( $json );
	}



	/*****************************************************************************/
	/** Menu + Widget functions **************************************************/
	/*****************************************************************************/


    /**
	 * Register nav_menus for a theme:
	 */
	function register_menus($menus){
		$array = array();
		foreach($menus as $m){
			$array[str_replace(' ', '-', strtolower($m))] = $m;
		}

		register_nav_menus($array);
	}



    /**
	 * Register a single nav_menu for a theme:
	 */
	function add_menu($menu){
		$this->register_menus($menu);
	}



    /**
	 * Register the widget areas for a theme:
	 */
	function register_widgetareas($areas){
		foreach($areas as $area){
			if(empty($area['class'])){
				$area['class'] = 'widget %2$s';
			}
			if(empty($area['title-class'])){
				$area['title-class'] = 'widgettitle';
			}

			$args = array(
				'name'          => $area['title'],
				'id'            => $area['id'],
				'description'   => '',
				'before_widget' => '<li id="%1$s" class="'.$area['class'].'">',
				'after_widget'  => '</li>',
				'before_title'  => '<h2 class="'.$area['title-class'].'">',
				'after_title'   => '</h2>' );
				
			register_sidebar($args);
		}
	}

    /**
	 * Register a single widget area:
	 */
	function add_widgetarea($area){
		$this->register_widgetares($area);
	}



	/*******************************************************************************/
	/** CONDITIONAL functions ******************************************************/
	/*******************************************************************************/


	function is_cuisine_enabled_theme(){

		$theme = wp_get_theme();
		$tags = $theme->{'Tags'};

		if( strtolower($theme->Author) == 'Chef du Web')
			return true;

		if( in_array( 'chef du web', $tags) || in_array( 'Chef du Web', $tags) || in_array( 'Chef du web', $tags ) )
			return true;

		if( in_array( 'cuisine enabled', $tags ) || in_array( 'Cuisine Enabled', $tags ) || in_array( 'Cuisine enabled', $tags ) )
			return true;

		return false;
	}


//

	/*****************************************************************************/
	/** Theme Frontend functions *************************************************/
	/*****************************************************************************/


	/**
	*	Init all the Theme frontend functions:
	*/
	function init_theme_frontend(){

		//add the registered scripts to the footer:
		add_action( 'wp_footer', array( &$this, 'enqueue_registered_scripts' ), 0  );


	}

	/**
	*	Register the scripts that need to be added in the footer:
	*/
	function register_scripts($script){

		//add dependencies and variables if not set:
		if( empty( $script['deps'] ) )
			$script['deps'] = false;

		if( empty( $script['vars'] ) )
			$script['vars'] = false;

		//default the page on which to load this script to 'all'
		if( empty( $script['on_page'] ) )
			$script['on_page'] = 'all';

		//add the type of page on which to load the script ( single, archive, post_type_archive )
		if( empty( $script['page_type'] ) || $script['page_type'] == '' ){

			global $cuisine;

			//default to the post_type if it's actually a posttype:
			if($cuisine->posttypes->is_public_posttype( $script['on_page'] ) ){
				$script['page_type'] = 'post_type';
			}else{
				//else default to a page slug:
				$script['page_type'] = 'page';
			}

		}

		//add the scripts that need to be added to the array:
		$this->theme_scripts_to_query[ $script['id'] ] = $script;
	}


	/**
	*	Register localization / variables for javascript:
	*/

	function register_js_vars( $var ){

		if( !isset( $var['on_page'] ) )
			$var['on_page'] = 'all';

		if( !isset( $var['page_type'] ) )
			$var['page_type'] = 'page';

		$this->theme_vars_to_query[] = $var;

	}




	/**
	*	Enqueue the scripts in theme_scripts_to_query if this is the right page:
	*/
	function enqueue_registered_scripts(){

		global $cuisine;


		//check if the array isn't empty:
		if( !empty( $this->theme_scripts_to_query ) ){


			//if Cuisine's in production mode, first include the minfied master:
			if( $cuisine->production_mode ){
	
				//first, queue the script and all its dependencies:
				$relativepath = $this->get_minified_js_path();
	
				$minified = $this->get_minified_js_scripts();
	
				$dv = $this->get_minified_js_deps( $minified );
				$deps = ( empty( $dv['deps'] ) ) ? array() : $dv['deps'] ;
				$vars = ( empty( $dv['vars'] ) ) ? array() : $dv['vars'] ;
					
				wp_enqueue_script( 'cuisine_script', $relativepath, $deps, $vars, true );
	
				foreach( $this->theme_scripts_to_query as $key => $script ){
	
					//remove these scripts from the array, they don't need to be loaded it again.
					if( $script['on_page'] == 'all' && isset( $script['root_url'] ) ){
	
						unset( $this->theme_scripts_to_query[ $key ] );
						
					}
				}
			}


			foreach( $this->theme_scripts_to_query as $script ){
				//check if on this page / single post or archive on which to load:
				if( $script['on_page'] == 'all' || $this->is_correct_enqueue_page( $script['on_page'], $script['page_type'] ) ){
				
					if( isset( $script['url'] ) ){
						//enqueue the script:
		 				wp_enqueue_script( $script['id'], $script['url'], $script['deps'], $script['vars'], true );

					}else{
						echo 'SCRIPT URL NOT FOUND:'. $script['id'];
					}

	 			}
			}
		}


		/**
		*	Add the variables, if they are set:
		*/

		if( !empty( $this->theme_vars_to_query ) ){

			foreach( $this->theme_vars_to_query as $var ){

				if( $var['on_page'] == 'all' || $this->is_correct_enqueue_page( $var['on_page'], $var['page_type'] ) ){ 
					
					//setup the variables:
					wp_localize_script( $var['id'], $var['var'], $var['value'] );

				}
			}

		}

	}


	/**
	*	Figure out if this is the correct page to enqueue a script:	
	*/
	function is_correct_enqueue_page( $page, $type ){

		global $wp_query, $post;
		$q = $wp_query->query_vars;

		if( $type == 'page' && is_page( $page ) )
			return true;

		if( $type == 'post' && is_single( $page ) )
			return true;

		if( $type == 'post_type' && $q['post_type'] == $page )
			return true;
		
		if( isset( $post ) && $post->ID == $page )
			return true;


		return false;

	}


	/**
	*	Get the JS to minify:
	*/

	function generateMinifiedJS(){
		
		global $cuisine;
	
		//check if the array isn't empty:
		if( !empty( $this->theme_scripts_to_query ) ){

			$to_minify = $this->get_minified_js_scripts();

			//Check if there's anything to minify, if so... do it:
			if( !empty( $to_minify ) ){

				$name = $this->get_minified_js_filename();

				//generate the root and url paths:
				$filepath = $this->root_url( 'scripts', true ).$name;
				$relativepath = $this->get_minified_js_path();

				include('includes/JSMin.php');
						
				$js = '';
				foreach( $to_minify as $script ){
					//add the contents of each js file to the js variable:
					$js .= file_get_contents( $script['root_url'] );
				
				}
						
				//minify the js variable:
				$js = JSMin::minify( $js );

				//write the contents to a new file:
				if( file_put_contents( $filepath, $js ) ){
					return 'success-minify';
				
				}else{
					return 'fail-minify';

				}
				
			
			}else{

				return 'zero-minify';
			}

		}

		return 'zero-minify';
	}


	/**
	*	Minify JS:
	*/
	function minify_js( $scripts ){



		
	//	$depsvars = $this->get_minified_js_deps( $scripts );
	//	$deps = $depsvars['deps'];
	//	$vars = $depsvars['vars'];

		
		//include the minifier script:
	

	}


	function get_minified_js_scripts(){
		//first, check if there are scripts we need to minify ( on_page 'all' scripts ):
		$minified = array();

		foreach( $this->theme_scripts_to_query as $script ){

			if( $script['on_page'] == 'all' && isset( $script['root_url'] ) ){

				//add it to the minify array:
				$minified[] = $script;

			}

		}

		return $minified;
	}


	/**
	*	Get the minified js path:
	*/
	function get_minified_js_path(){

		return $this->url( 'scripts', true ).$this->get_minified_js_filename();
	
	}

	/**
	*	Get the minified js filename:
	*/
	function get_minified_js_filename(){
		//get the theme object:
		$themeobj = wp_get_theme();

		//create the name:
		$name = 'script_'.str_replace( ' ', '_', $themeobj->stylesheet ).'.min.js';

		return $name;
	}


	/**
	*	Get the minified js deps and vars:
	*/
	function get_minified_js_deps( $scripts ){

		//add the deps + vars and create the script id-string:
		$array = array( 'deps', 'vars' );
		foreach( $scripts as $script ){

			if( !empty( $script['deps'] ) )
				$array['deps'] = array_merge( $array['deps'], $script['deps'] );
			
			if( !empty( $script['vars'] ) )
				$array['vars'] = array_merge( $array['vars'], $script['vars'] );
			
		}

		return $array;

	}


	/**
	*	A little function to reset the minified file.
	*/
	function clear_minified_stack(){

		update_option( 'cuisine_minified_js', '' );

	}




	/*****************************************************************************/
	/** Get Theme Style functions ************************************************/
	/*****************************************************************************/


	    /**
		 * check to see if there is a themestyle set:
		 */
	    function has_theme_style(){
	    	$style = get_option('cuisine_theme_options', true);
	    	if(empty($style))
	    		return false;
	
	    	return true;
	    }
	
	
	    /**
		 * Returns all the custom styling of this theme:
		 */
		function get_theme_style( $sanitize = false ){
	

			//Add default theme options first:
	
			//first the custom work:
			$options = apply_filters( 'cuisine_get_theme_options', array() );
	
			//then the defaults:
			$options = array_merge($options, $this->get_theme_defaults() );
	
			//then the options from the database:
			$options = array_merge( $options, get_option( 'cuisine_theme_options', true ) );
				
			//(we've overwritten some styles by now by order of importance; defaults from plugins, defaults from theme and info from the database.)
	
			if($sanitize)
				$options = $this->sanitize_theme_style( $options );

		
			$GLOBALS['style'] = $options;

			return $options;
		
		}


		/*
		*	Sanitizes the theme style for the css file:
		*/
	
		function sanitize_theme_style( $options ){
				
			// Sanitize fonts:
			$options = $this->sanitize_fonts( $options );

			return $options;
		}
	
	
		/**
		*	Santitize fonts (Google fonts need a '+' in the url but a space in the css):
		*/
	
		function sanitize_fonts( $options ){
	
			foreach( $options as $key => $option){
	
				//the array key ends with 'font-family':
				if( substr( $key, -11 ) == 'font-family' ){

					$value = str_replace( '+', ' ', $option );
					$val = explode( ':', $value );

					$options[ $key ] = "'".$val[0]."'";
				
				}
			}
	
			return $options;
	
		}


	/*****************************************************************************/
	/** Font functions ***********************************************************/
	/*****************************************************************************/


	/**
	*	Gets all google fonts that are being used:
	*/

	function get_google_fonts(){

		//get the fonts set:
		$fonts = $this->get_set_fonts();

		//get all allowed google fonts :: we need the keys to check the real values
		$gfonts = array_keys( cuisine_get_google_fonts() );


		$in_html = array();

		$html = '<link href="http://fonts.googleapis.com/css?family=';

		//now loop through the fonts:
		foreach($fonts as $font){
			//if this is a google font:
			if( in_array( $font, $gfonts) && !in_array( $font, $in_html ) ){
				
				//add it to the html:
				$html .= $font.'|';

				//add it to an array to prevent doubles:
				$in_html[] = $font;
			}
		}

		//check if there are any fonts added to the html:
		if($html > '<link href="http://fonts.googleapis.com/css?family='){
			
			//echo the html: 
			echo substr( $html, 0, -1).'" rel="stylesheet" type="text/css">'; 
		}
	}


	/**
	*	This function retrieves all fonts currently being used by this theme:
	*/

	function get_set_fonts(){

		//get the options:
		$options = $this->get_theme_style();

		//set the empty variables:
		$fonts = array();

		//and the keys of the options to determine if this is a font:
		$keys = array_keys( $options );

		$i = 0;
		foreach( $options as $option){

			//the array key ends with 'font-family':
			if( substr( $keys[$i], -11 ) == 'font-family' )
				$fonts[] = $option;

			$i++;
		}
		//return the fonts:
		return $fonts;
	}




	/*****************************************************************************/
	/** THEME DEFAULTS ***********************************************************/
	/*****************************************************************************/

	/**
	*	Basic things you can set up in a theme:
	*/
	function get_theme_defaults(){

		$fonts = cuisine_get_all_fonts();
		$background_repeat = array('no-repeat' => 'Niet herhalen', 'repeat' => 'Herhalen', 'repeat-x' => 'Herhalen (horizontaal)', 'repeat-y' => 'Herhalen (verticaal)');
	
		$array = array(
	
			'body-background-image'				=> 'none',
			'body-background-repeat' 			=> 'repeat',
			'body-background-repeat-choices'	=> $background_repeat,
			'header-image'				 		=> 'none',
	
			'logo-show-text'					=> '1',
			
			'body-background-color'				=> '#D7D3C6',
			'container-background-color' 		=> '#F8F8F8',
			'header-background-color'			=> '#DFDFDF',
			'footer-background-color'			=> '#DFDFDF',
		
			'a-color'							=> '#3399FF',
			'a-hover-color'						=> '#FF6633',
			'a-visited-color'					=> '#3399FF',
			'h1-font-family'					=> 'helvetica',
			'h1-font-size'						=> '32px',
			'h1-font-color'						=> '#333333',
			'h2-font-family'					=> 'helvetica',
			'h2-font-size'						=> '24px',
			'h2-font-color'						=> '#333333',
			'h3-font-family'					=> 'helvetica',
			'h3-font-size'						=> '18px',
			'h3-font-color'						=> '#333333',
			'p-font-family'						=> 'helvetica',
			'p-font-size'						=> '14px',
			'p-font-color'						=> '#333333',
			'h1-font-family-choices'			=> $fonts,
			'h2-font-family-choices'			=> $fonts,
			'h3-font-family-choices'			=> $fonts,
			'p-font-family-choices'				=> $fonts,
		
			'logo-h1-color'						=> '#F8F8F8',
			'logo-h1-background-color'			=> '#333333',
			'logo-image'						=> 'none',
		
			'topmenu-background-color'			=> '#333333',
			'topmenu-font-size'					=> '12px',
		
			'mainmenu-background-color'			=> '#333333',
			'mainmenu-background-hover-color'	=> '#888888',
			'mainmenu-font-size'				=> '16px',
			'mainmenu-font-color'				=> '#F8F8F8',
			'mainmenu-font-family'				=> 'helvetica',
			'mainmenu-font-family-choices'		=> $fonts,
			'footer-background-color'			=> '#F8F8F8',
			'footer-color'						=> '#333333',
			'sidebar-background-color'			=> 'transparent',
			'sidebar-color'						=> '#333333',
	
			'button-color'						=> '#F8F8F8',
			'button-hover-color'				=> '#333333',
			'button-background-color'			=> '#333333',
			'button-hover-background-color'		=> '#F8F8F8',
			'button-icon'						=> 'white',
			'button-icon-choices'				=> array( 'white' => 'Wit', 'black' => 'Zwart' )
	
		);

		//enable themes to customly override it::
		$array = apply_filters( 'cuisine_default_theme_options', $array );

		return $array;

	}


	/**
	*	Theme default JavaScript properties (for auto refresh )
	*/

	function get_theme_customizers(){

		$array = array(
	
			'body-background-image'				=> array( 'object' => 'body', 'property' => 'background-image' ),
			'body-background-repeat' 			=> array( 'object' => 'body', 'property' => 'background-repeat' ),
			'header-image'				 		=> array( 'object' => '#header', 'property' => 'background-image' ),
			'body-background-color'				=> array( 'object' => 'body', 'property' => 'background-color' ),
			'container-background-color' 		=> array( 'object' => '#container', 'property' => 'background-color' ),
			'header-background-color'			=> array( 'object' => '#header', 'property' => 'background-color' ),
			'footer-background-color'			=> array( 'object' => '#footer', 'property' => 'background-color' ),
		
			'a-color'							=> array( 'object' => 'a', 'property' => 'color' ),
			'h1-font-family'					=> array( 'object' => 'h1', 'property' => 'font-family' ),
			'h1-font-size'						=> array( 'object' => 'h1', 'property' => 'font-size' ),
			'h1-font-color'						=> array( 'object' => 'h1', 'property' => 'color' ),
			'h2-font-family'					=> array( 'object' => 'h2', 'property' => 'font-family' ),
			'h2-font-size'						=> array( 'object' => 'h2', 'property' => 'font-size' ),
			'h2-font-color'						=> array( 'object' => 'h2', 'property' => 'color' ),
			'h3-font-family'					=> array( 'object' => 'h3', 'property' => 'font-family' ),
			'h3-font-size'						=> array( 'object' => 'h3', 'property' => 'font-size' ),
			'h3-font-color'						=> array( 'object' => 'h3', 'property' => 'color' ),
			'p-font-family'						=> array( 'object' => 'p', 'property' => 'font-family' ),
			'p-font-size'						=> array( 'object' => 'p', 'property' => 'font-size' ),
			'p-font-color'						=> array( 'object' => 'p', 'property' => 'color' ),
		
			'logo-h1-color'						=> array( 'object' => '#logo h1', 'property' => 'color' ),
			'logo-h1-background-color'			=> array( 'object' => '#logo h1', 'property' => 'background-color' ),
		
			'topmenu-background-color'			=> array( 'object' => '#topmenu', 'property' => 'background-color' ),
			'topmenu-font-size'					=> array( 'object' => '#topmenu a', 'property' => 'font-size' ),
		
			'mainmenu-background-color'			=> array( 'object' => '#mainmenu', 'property' => 'background-color' ),
			'mainmenu-background-hover-color'	=> array( 'object' => '#mainmenu .current_page_item a', 'property' => 'background-color' ),
			'mainmenu-font-size'				=> array( 'object' => '#mainmenu a', 'property' => 'font-size' ),
			'mainmenu-font-color'				=> array( 'object' => '#mainmenu a', 'property' => 'color' ),
			'mainmenu-font-family'				=> array( 'object' => '#mainmenu a', 'property' => 'font-family' ),
			'footer-background-color'			=> array( 'object' => '#footer', 'property' => 'background-color' ),
			'footer-color'						=> array( 'object' => '#footer', 'property' => 'color' ),
			'sidebar-background-color'			=> array( 'object' => '#sidebar', 'property' => 'background-color' ),
			'sidebar-color'						=> array( 'object' => '#sidebar', 'property' => 'color' ),
	
			'button-color'						=> array( 'object' => '.button', 'property' => 'color' ),
			'button-background-color'			=> array( 'object' => '.button', 'property' => 'background-color' )	
		);

		//enable themes to customly override it::
		$array = apply_filters( 'cuisine_theme_customizers', $array );

		return $array;
	}

	/*****************************************************************************/
	/** THEME VARIABLE RETURNERS *************************************************/
	/*****************************************************************************/


	/**
	*	Return the current theme url:
	*/
	function url( $type = 'theme', $trail = false ){

		$url = '';

		if( $type == 'theme' )
			$url = get_bloginfo( 'template_url' );

		if( $type == 'stylesheet' )
			$url = get_bloginfo( 'stylesheet_directory' );

		if( $type == 'scripts' )
			$url = get_bloginfo( 'template_url' ) .'/js';

		if( $type == 'images' )
			$url = get_bloginfo( 'template_url' ) .'/images';

		if( $trail ) $url .= '/';


		return $url;

	}


	/**
	*	Return the current root url:
	*/ 

	function root_url( $type = 'root', $trail = false ){

		$root = ABSPATH;
		$themeobj = wp_get_theme();

		if( $type == 'root' )
			$url = $root;

		if( $type == 'theme' )
			$url = $themeobj->theme_root.'/'.$themeobj->stylesheet;

		if( $type == 'scripts' )
			$url = $themeobj->theme_root.'/'.$themeobj->stylesheet.'/js';


		if( $trail )
			return $url.'/';

		return $url;

	}


}
	