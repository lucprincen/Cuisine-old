<?php

/**
 * Cuisine Home Text widget
 * 
 * displays a text message, adds editors on the homepage edit page.
 *
 * @author 		Chef du Web
 * @category 	Widgets
 * @package 	Cuisine
 */

class cuisine_widget_home_text extends WP_Widget { 
	
	var $instance;

	function cuisine_widget_home_text() {

	// abort silently for the second instance of the widget
	if ( ! $this->instance ) {
		$this->instance = true;
    } else {
    	return;
    }


		/* Widget settings. */
		$widget_ops = array( 'description' => __('Text of HTML (ook bewerkbaar vanaf de "home" editpagina') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cuisine_widget_home_text' );
	
		/* Create the widget. */
		$this->WP_Widget( 'cuisine_widget_home_text', __('Homepage Text'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		// Output
		extract($args);

		$home = get_page_by_path( 'home' );
		$home_text = get_post_meta( $home->ID, 'home_text_widget', true );
		if( empty( $home_text ) ){
			$home_text['home_text_title'] = __('Titel');
			$home_text['home_text_body'] = '';
		}

		echo $before_widget;
		echo '<h2 class="widgettitle">'.$home_text['home_text_title'].'</h2>';
		echo '<div class="textwidget">'.$home_text['home_text_body'].'</div>';
		echo $after_widget;
	}	
	
	
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 

		$home = get_page_by_path( 'home' );
		update_post_meta( $home->ID, 'home_text_widget', array( 'home_text_title' => $_POST['home_text_title'], 'home_text_body' => $_POST['home_text_body'] ) );

		return $instance;
	}
	
	function form($instance) {

		$home = get_page_by_path( 'home' );
		$home_text = get_post_meta( $home->ID, 'home_text_widget', true );
		if( empty( $home_text ) ){
			$home_text['home_text_title'] = __('Titel');
			$home_text['home_text_body'] = '';
		}

	 ?>
	 <p>
	 	<label for="home_text_title">Titel</label>
	 	<input id="home_text_title" name="home_text_title" value="<?php echo $home_text['home_text_title']; ?>" style="width:100%;" />
	 </p>
	 <p>
	 	<label for="home_text_body">Body</label>
	 	<textarea id="home_text_body" name="home_text_body" style="width:100%;height:80px;"><?php echo $home_text['home_text_body'];?></textarea>
	 </p>
    <?php
	}
}

/**
*	Register the widget:
*/
function cuisine_widget_home_text_init(){

	register_widget('cuisine_widget_home_text');
}

add_action( 'widgets_init', 'cuisine_widget_home_text_init' );


/**
*	Add the metabox on the 'home' editscreen:
*/

function cuisine_widget_home_text_meta(){

	if( is_active_widget( false, false, 'cuisine_widget_home_text' ) ){

		$home = get_page_by_path( 'home' );
		$home_text = get_post_meta( $home->ID, 'home_text_widget', true );
		if( empty( $home_text ) ){
			$home_text['home_text_title'] = __('Titel');
			$home_text['home_text_body'] = '';
		}

		global $cuisine;

		$meta = array(
			'id'			=> 'cuisine_widget_home_text_meta',
			'title'			=> '"'.$home_text['home_text_title'].'" text',
			'post'			=> 'home',
			'context'		=> 'side',
			'data'			=> array(
				'key'		=>	'home_text_widget',
				'value'		=> 	array( 'home_text_title', 'home_text_body' )
			)				
		);

		$cuisine->plugins->add_plugin_meta( $meta );


	}
}

/**
*	Add the html for the metabox:
*/

function cuisine_widget_home_text_meta_html(){

	$pid = cuisine_get_post_id();
	$home_text = get_post_meta( $pid, 'home_text_widget', true );

	//add the nonce:
	cuisine_get_nonce();

?>
	<label class="cuisine_label clearfix">Titel: <input type="text" value="<?php echo $home_text['home_text_title'];?>" name="home_text_title" class="cuisine_input" /></label>
	<label class="cuisine_label clearfix">Body:</label>
 	<textarea id="home_text_body" name="home_text_body" style="width:100%;height:150px;"><?php echo $home_text['home_text_body'];?></textarea>

<?php

}
add_action( 'admin_init', 'cuisine_widget_home_text_meta' );
?>