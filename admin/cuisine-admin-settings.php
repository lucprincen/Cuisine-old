<?php
/**
 * Cuisine Admin
 * 
 * Handles the setting and getting of variables
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */



	/*************************************************************************/
	/** GET & SET functions **************************************************/
	/*************************************************************************/


	/**
	* Get a cuisine setting:
	*
 	* @access public
	* @return array
	*/
	function get_cuisine_setting( $type ){

		// first check if this setting excists:
		$setting = get_option( 'cuisine-setting-'.$type );

		if( empty( $setting ) ){
			$setting = get_cuisine_defaults( $type );
		} 

		return $setting;
	}


	/**
	*	Update a setting:
	*
 	* @access public
	* @return void
	*/

	function update_cuisine_setting( $type, $value ){

		update_option( 'cuisine-setting-'.$type, $value );

	}


	/**
	*	Get defaults to a cuisine setting:
	*
 	* @access public
	* @return Array
	*/

	function get_cuisine_defaults ( $type ){

		switch( $type ){

			case 'simple_view':

				$settings = array(

					'simple_view'		=> true,
					'show_pages_front'	=> true,
					'icon_size'			=> '120',

					'back_button'		=> true,
					'add_pages'			=> false,
					'delete_pages'		=> false,
					'edit_menus'		=> false
				);

				return $settings;

			break;
			default:

				return array();

			break;

		}

		return array();
	}


	/*************************************************************************/
	/** OPTIONS pages ********************************************************/
	/*************************************************************************/



	/**
	*	Register the options page:
	*
 	* @access public
	* @return void
	*/
	cuisine_register_option_page();

	function cuisine_register_option_page(){

		global $cuisine;

		$cuisine->plugins->add_plugin_page(

			'main',
			array(

				'title'			=> 	'cuisine_options',
				'label'			=>	'Cuisine',
				'capability'	=>	'manage_cuisine',
				'id'			=>	'cuisine_options',
				'position'		=>	101,
				'func'			=>	'cuisine_option_page'
			)
		);
	}


	/**
	*	Show the options page:
	*
 	* @access public
	* @return void
	*/
	function cuisine_option_page(){

		global $cuisine;


		//include the options page:
		require_once('cuisine-admin-options.php');


		//handle the saving of the options:
		if( isset( $_POST[$cuisine->plugins->get_nonce_name()] ) ){

			$values = cuisine_sanitize_options();

			update_cuisine_setting( 'simple_view', $values );

			cuisine_options_saved();

		}

		cuisine_show_options_page();

	}

	/**
	*	RETURN A DEFAULTED ARRAY OF CUISINES OPTIONS
	*
 	* @access public
	* @return void
	*/

	function cuisine_sanitize_options(){
		$values = array();

		if( !isset( $_POST['cuisine_simple_view' ] ) ){
			$values['cuisine_simple_view'] = false;
		}else{
			$values['cuisine_simple_view'] = true;
		}

		if( !isset($_POST['cuisine_show_pages'] ) ){
			$values['show_pages_front'] = false;

		}else{
			$values['show_pages_front'] = true;
		}

		if( !isset($_POST['cuisine_edit_menus'] ) ){
			$values['edit_menus'] = false;

		}else{
			$values['edit_menus'] = true;
		}

		if( !isset($_POST['cuisine_add_pages'] ) ){
			$values['add_pages'] = false;

		}else{
			$values['add_pages'] = true;
		}

		if( !isset($_POST['cuisine_delete_pages'] ) ){
			$values['delete_pages'] = false;

		}else{
			$values['delete_pages'] = true;
		}

		if( !isset($_POST['cuisine_back_button'] ) ){
			$values['back_button'] = false;

		}else{
			$values['back_button'] = true;
		}

		$values['icon_size'] = $_POST['cuisine_icon_size'];

		return $values;
	}




?>
