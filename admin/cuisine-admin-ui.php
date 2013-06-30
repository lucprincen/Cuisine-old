<?php
/**
 * Cuisine Admin
 * 
 * Handles the simple view UI
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */

	/**
	* Get the simple view UI:
	*
 	* @access public
	* @return html
	*/
	function cuisine_simple_ui_init(){
		
		// first get the cuisine settings:
		$settings = get_cuisine_setting( 'simple_view' );

		do_action( 'cuisine_before_simple_view' );
		?>

		<div class="cuisine-simple-view-wrapper">

<?php
		// if cuisine is setup to show the pages first:
		if( $settings['show_pages_front'] == true ){
			
			do_action( 'cuisine_simple_view_before_collections' );

			//get the pages from the database:
			$pages = get_pages(array('sort_order' => 'ASC','sort_column' => 'menu_order'));

			//add the posibility for plugins to add pages:
			$pages = apply_filters( 'register_cuisine_simple_view_page', $pages );


				//check if $pages isn't empty:
				if( !empty( $pages ) ){ ?>

					<div class="cuisine-collection">
						
						<h2 class="cuisine-collection-title"><?php echo __('Which page would you like to edit?', 'cuisine');?></h2>

						<?php foreach( $pages as $page ):

								if( isset( $page->ID ) ){

									$link = get_admin_url().'post.php?post='. $page->ID .'&action=edit&post_type=page';
									$title = get_the_title( $page->ID );

								}else{

									$link = $page['link'];
									$title = $page['title'];

								}?> 
				
							<a class="cuisine-item" href="<?php echo $link;?>" style="width:<?php echo $settings['icon_size']?>px !important">
								<div class="page-icon">
									<img src="<?php cuisine_simple_view_icon( $page );?>"/>
								</div>
								<strong><?php echo $title;?></strong>
							</a>

						<?php endforeach;?>
		
					</div>
<?php			

				} // pages empty check
		}// pages on front check



		//then add the plugins:
		global $cuisine;
		$plugins = array();

		if($settings['show_pages_front'] == false){
			$plugins[] = array(
				'Title' => __('Pages', 'cuisine'),
				'post_type' => 'page',
				'icon'	=> $cuisine->asset_url.'/images/pages-icon.png'
			);			
		}	

		$plugins = apply_filters( 'register_cuisine_simple_view_plugin', $plugins );

			//check if there are any plugins:
			if( !empty( $plugins ) ){ ?>

				<div class="cuisine-collection cuisine-plugins">

					<h2 class="cuisine-collection-title"><?php echo __('What would you like to edit?', 'cuisine');?></h2>

					<?php foreach( $plugins as $plugin ):?> 
							
						<a class="cuisine-item" href="<?php cuisine_get_icon_link( $plugin );?>"  style="width:<?php echo $settings['icon_size']?>px !important">
							<div class="page-icon">
								<img src="<?php cuisine_simple_view_icon( $plugin );?>"/>
							</div>
							<strong><?php echo $plugin['Title']?></strong>
						</a>

					<?php endforeach;?>

				</div>
				<div class="clearfix"></div>
<?php 		} //plugins empty check
			
			// allow plugins to create there own collection of icons:
			$collections = apply_filters( 'cuisine_simple_view_collections', array() );

			if( !empty( $collections ) ):
			  foreach( $collections as $collection ):?>
			  <div class="cuisine-collection">
			  	<h2 class="cuisine-collection-title"><?php echo $collection['title'];?></h2>

			  	<?php foreach( $collection['items'] as $item ):?>
			  		<a class="cuisine-item" href="<?php cuisine_get_icon_link( $item );?>"  style="width:<?php echo $settings['icon_size']?>px !important">
						<div class="page-icon">
							<img src="<?php cuisine_simple_view_icon( $item );?>"/>
						</div>
						<strong><?php echo $item['Title']?></strong>
					</a>
			  	<?php endforeach;?>
			  </div>
		<?php endforeach; endif; ?>
			
		<?php do_action( 'cuisine_simple_view_after_collections' );?>

		</div><!-- simple-view-wrapper -->
		<?php do_action( 'cuisine_after_simple_view' );?>
<?php

	}


	/**
	*	Get Icon link:
	*
 	* @access public
	* @return url
	*/
	
	function cuisine_get_icon_link( $plugin ){

		if( isset( $plugin['post_type'] ) ){
			echo get_admin_url().'edit.php?post_type='.$plugin['post_type'];

		}else if( isset( $plugin['link'] ) ){
			echo get_admin_url().$plugin['link'];

		}
	}


	/**
	* 	Get the simple view Button:
	*
 	* @access public
	* @return html
	*/

	function cuisine_simple_view_button(){

		//if simple view is active:
		if( cuisine_simple_view_is_active() ){

			echo '<a class="cuisine_toggle_button" href="'.admin_url().'?toggle-simple-view=true">'.__('Advanced view', 'cuisine').'</a>';

		}
			
	}


	/**
	*	Add the Simple view back button:
	*
 	* @access public
	* @return html
	*/

	function cuisine_simple_view_back_button(){

		global $pagenow,$cuisine;
		if( $pagenow != 'index.php' ){

			echo '<a class="cuisine_back_button" href="'.cuisine_simple_view_back_url().'"><img src="'.$cuisine->asset_url.'/images/arrow-back.png"/><span>'.__('Back', 'cuisine').'</span></a>';

		}
	}


	/**
	*	Generate the back url:
	*
 	* @access public
	* @return url
	*/

	function cuisine_simple_view_back_url(){

		global $pagenow, $cuisine;


		if( $pagenow == 'post-new.php'){

			if( isset( $_GET['post_type'] ) )
				return admin_url().'/edit.php?post_type='.$_GET['post_type'];

			$url = admin_url().'/edit.php?post_type=post';

		}else if( $pagenow == 'post.php'){

			$pid = cuisine_get_post_id();
			$pt = get_post_type( $pid );
			
			$url = admin_url().'/edit.php?post_type='.$pt;

		}else{

			$url = admin_url();

		}

		return apply_filters( 'cuisine_simple_view_back_url', $url );

	}


	/**
	*	Get icons for specific blocks:
	*
 	* @access public
	* @return url
	*/

	function cuisine_simple_view_icon( $obj ){

		$done_by_filter = false; 

		//First, check if there's a plugin wantin' a piece of this action:
		if( has_filter( 'cuisine_simple_view_icon' ) ){

			$icons = apply_filters( 'cuisine_simple_view_icon', $obj );

			//check if there's an icon for the current object:
			if( isset( $icons[ $obj->post_name] ) ){
				
				//yes it is, don't look any further
				$done_by_filter = true;

				//and echo the damn thing:
				echo $icons[ $obj->post_name ];

			}
		}


		//else, we'll get the icon via cuisine or a cuisine registered plugin:
		if( !$done_by_filter ){

			//check if it's a page:
			if( isset( $obj->post_type ) && $obj->post_type == 'page' ){
	
				global $cuisine;
				//now we have to delve deeper:
				if( $obj->post_name == 'home' ){
					 echo $cuisine->asset_url.'/images/home-icon.png';
				
				}else if( $obj->post_name == 'contact' ){
					echo $cuisine->asset_url.'/images/contact-icon.png';
	
				}else{
					echo $cuisine->asset_url.'/images/page-icon.png';
	
				}
	
			//it's from a plugin
			}else{
	
				//check if there's an icon set:
				if( isset($obj['icon'] ) ){

					echo $obj['icon'];
				
				}else{
					//else, use the default:
					echo $cuisine->asset_url.'/images/page-icon.png';

				}
			}
		}
	}



	/**
	*	Output the UI for the overview page:
	*
 	* @access public
	* @return css
	*/
	function cuisine_simple_ui_overview(){
		
		$settings = get_cuisine_setting( 'simple_view' );
		
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'page' ){
			echo '<style>';

				if($settings['add_pages']){
					echo '.add-new-h2{display:inline !important;}';
				}else{
					echo '.add-new-h2{display:none !important;}';
				}
	
				if($settings['delete_pages']){
					echo '.trash{display:inline !important;}';
				}else{
					echo '.trash{display:none !important;}';
					echo '.tablenav.top{display:none !important;}';
					echo 'input[type="checkbox"]{display:none}';
				}

				do_action( 'cuisine_simple_view_style' );
	
			echo '</style>';
		}
	}


?>
