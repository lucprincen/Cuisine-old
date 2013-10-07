<?php

	/**
	 * Install cuisine
	 *
	 * @access public
	 * @return void
	 */
	function do_install_cuisine() {
	
	
	}
	
	function cuisine_toggle_production_mode(){
	
		if( isset( $_GET['page'] ) && $_GET['page'] == 'cuisine_options' && isset( $_GET[ 'toggle_production_mode' ] ) && $_GET['toggle_production_mode'] == 'true' ){
	
			//check if the current user can make this switch:
			if( !current_user_can( 'toggle_production_mode' ) )
				return false;
	
	
			global $cuisine;
		
			if( $cuisine->production_mode ){
		
				//Get everything back to development:
				update_option( 'cuisine_production_mode', false );
				$cuisine->production_mode = false;
		
				do_action( 'cuisine_in_development_mode' );
	
			}else{
		
				//Get everything to production:
				update_option( 'cuisine_production_mode', true );
				$cuisine->production_mode = true;
	
				do_action( 'cuisine_in_production_mode' );
			
			}
	
			do_action( 'cuisine_production_mode_toggled', $cuisine->production_mode );
	
			wp_redirect( admin_url().'admin.php?page=cuisine_options' );
			exit();
	
		}
	}
	
	
	/**
	*	ADD TOGGLE ACTIONS:
	*/
	function cuisine_production_mode_hooks(){
	
		global $cuisine;
	
		add_action( 'cuisine_in_production_mode', 'cuisine_minify_js' );
		add_action( 'cuisine_in_production_mode', 'cuisine_generate_css' );
	
		if( $cuisine->production_mode ){
	
			add_action( 'customize_save_after', 'cuisine_generate_css' );

		}
	
	}
	
	cuisine_production_mode_hooks();


	
	


	function cuisine_minify_js(){

		global $cuisine;

		//Minify the JS files:
		//Scripts need to be initted for this. 
		$responds = $cuisine->theme->generateMinifiedJS();

		if( $responds == 'fail-minify' ){

			$cuisine->add_error( 'Javascript verkleinen mislukt; de scripts-map is niet schrijfbaar.' );

		}
	
	}


	function cuisine_generate_css(){

		global $cuisine;

		$responds = $cuisine->theme->generate_stylesheet();

		if( $responds == 'fail-minify' ){
			$cuisine->add_error( 'Stylesheet maken misklukt; de theme-map is niet schrijfbaar' );
		}

	}



?>