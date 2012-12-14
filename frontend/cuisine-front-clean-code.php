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
	


?>