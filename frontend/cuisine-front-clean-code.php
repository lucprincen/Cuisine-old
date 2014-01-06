<?php

	/**
	*	Annoying little hack to maintain clean code when we use WordPress' Recent comments widget,
	*	The css is in the Chef du Web theme's reset file
	*
 	* @access public
	* @return void
	*/

	add_action( 'widgets_init', 'chef_remove_recent_comments_style' );
	
	function chef_remove_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
	}
	

	/**
	*	Generate a fluid bootstrap row based on the current loop:
	*/
	function cuisine_row( $class = '' ){

		global $current_row_item, $current_total_item;

		if( !isset( $current_row_item ) && $current_total_item ){
			$GLOBALS['current_row_item'] = 0;
			$GLOBALS['current_total_item'] = 0;
			global $current_row_item, $current_total_item;
		}

		if( $current_row_item == 0 )
			echo '<div class="row-fluid '.$class.'">';

	}


	/**
	*	Generate a fluid bootstrap row based on the current loop:
	*/
	function cuisine_row_close( $items, $a_query = null ){
		
		$GLOBALS['current_row_item']++;
		$GLOBALS['current_total_item']++;
		global $current_row_item, $current_total_item, $wp_query;
		if( $a_query == null ) $a_query = $wp_query;

		if( $current_row_item == $items || $current_total_item == $a_query->found_posts ){
			$GLOBALS['current_row_item'] = 0;
			echo '</div>';
		}

	}


?>