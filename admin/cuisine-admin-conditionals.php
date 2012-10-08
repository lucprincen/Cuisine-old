<?php
/**
 * Cuisine Admin
 * 
 * Simple Cuisine Admin conditionals
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */


	/**
	*	Check the Cuisine session:
	*/

	function cuisine_is_valid_session(){
		global $cuisine;

		if( session_name() == $cuisine->session_name )
			return true;

		return false;
	}


 ?>