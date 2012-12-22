<?php
/**
 * Cuisine Admin
 * 
 * Handles all the functionality surrounding Cuisine's simple admin view
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */

	/**
	* Get the simple view status:
	*
 	* @access public
	* @return string
	*/

	function cuisine_get_simple_view(){
		return get_option( 'cuisine_simple_view' );
	}

	/**
	* Check if the simple view is active (users who can't toggle_admin_mode, will always get a simple view):
	*
 	* @access public
	* @return boolean
	*/

	function cuisine_simple_view_is_active(){

		if( !current_user_can( 'toggle_admin_mode' ) )
			return true;

		if( cuisine_get_simple_view() == 'true' )
			return true;

		return false;
	}



	/**
	* Check if the simpel view is toggled:
	*
 	* @access public
	* @return void
	*/

	function cuisine_check_toggle_simple_view(){
		
		//check if the current user can toggle:
		if(! current_user_can( 'toggle_admin_mode' ) )
			return false;

		if( isset($_GET['toggle-simple-view'] ) ){
			
			//get the view option:
			$view = cuisine_get_simple_view();

			// update the simple view option:
			if( $view == 'false' ){
				cuisine_update_simple_view( 'true' );

			}else{
				cuisine_update_simple_view( 'false' );

			}

			//redirect the user to the dashboard:
			wp_redirect( admin_url() );
			exit();
		}
	}




	/**
	* Update the simpleview option:
	*
 	* @access public
	* @return boolean
	*/

	function cuisine_update_simple_view( $value ){
		update_option( 'cuisine_simple_view', $value );
	}


	/**
	* Check to see if the simpleview exists:
	*
 	* @access public
	* @return boolean
	*/

	function cuisine_simple_view_exists(){
		$view = get_option('cuisine_simple_view');
		if( !empty( $view ) ){
			return true;
		}

		return false;
	}


?>
