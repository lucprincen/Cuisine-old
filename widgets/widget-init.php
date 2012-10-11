<?php

/**
 * Cuisine Widgets
 * 
 * Includes all the widget files
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */


	function cuisine_widgets_include(){

		// Home extra text
		include( 'widget-home-text.php' );

		// Home image
		include( 'widget-home-image.php' );

		//image widget:
		include( 'widget-image.php' );

		// video widget:
		include( 'widget-video.php' );

		// Contact info
		include( 'widget-contact-info.php' );

		//simple search
		include('widget-simple-search.php');

		//twitter
		include('widget-twitter.php');

	}


	cuisine_widgets_include();
?>