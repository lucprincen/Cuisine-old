<?php

/**
 * Cuisine Simple Search
 * 
 * Adds a simple searchbox to a widgetarea. No button, no description and no title.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_simple_search extends WP_Widget { 
	function cuisine_widget_simple_search() {
		/* Widget settings. */
		$widget_ops = array( 'description' => __('Eenvoudige zoekbox (geen knop)') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 100, 'id_base' => 'cuisine_widget_simple_search' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_simple_search', __('Simple Search'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		echo $before_widget;
		echo '<form role="search" method="get" id="searchform" action="'.cuisine_site_url().'">';
		echo '<input type="text" value="'.__('Typ en druk op enter').'" name="s" id="s-'.$this->id.'" onclick="doSmartEmpty(\'#s-'.$this->id.'\', \''.__('Typ en druk op enter').'\')"/>';
		echo '</form>';
		echo $after_widget;
	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		return $instance;
	}
	
	function form($instance) {
		
	 ?>
		<p><?php __('Er zijn geen instellingen voor deze widget')?></p>
    <?php
	}
}
function cuisine_widget_simple_search_init()
{
	register_widget('cuisine_widget_simple_search');
}
add_action('widgets_init', 'cuisine_widget_simple_search_init');



?>