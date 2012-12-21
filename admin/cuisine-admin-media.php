<?php


/**
 * Cuisine Admin Media
 * 
 * Handles media metaboxes where you can add images, videos and texts.
 *
 * @author 		Chef du Web
 * @category 	Admin
 * @package 	Cuisine
 */

	/**
	*	SETUP A META BOX FOR MEDIA
	*
 	* @access public
	* @return void
	*/

	function cuisine_media_init( $post_type, $include = array( 'image', 'video', 'text' ), $label = '' ){

		global $cuisine;

		if( $label == '' )
			$label = ucwords( $post_type .' media' );

		$GLOBALS['cuisine_media_includes'][$post_type] = $include; 

			$meta = array(
				'id'			=> 'chef_'.$post_type.'_media',
				'title'			=> $label,
				'post_type'		=> $post_type,
				'function'		=> 'cuisine_media_html',
				'data'			=> array(
					'key'		=>	$post_type.'_media',
					'value'		=> 	$post_type.'_media',
					'orderby'	=> 	'position'
				)				
			);

			$cuisine->plugins->add_plugin_meta( $meta );
	}

	/**
	*	RETURN THE HTML FOR THE METABOX
	*
 	* @access public
	* @return void
	*/

	function cuisine_media_html(){


		$pid = cuisine_get_post_id();
		$posttype = get_post_type( $pid );

		$media = cuisine_get_media( $pid, $posttype );

		global $cuisine, $cuisine_media_includes;
		$cuisine->plugins->get_plugin_nonce();

		$includes = $cuisine_media_includes[$posttype];
		$metaname = $posttype.'_media';

		$i = 0;
		?>
		<div id="cuisine-media-add">
			<?php if( in_array( 'image', $includes ) ):?>
				<input type="button" value="Afbeeldingen toevoegen" class="button tagadd cuisine-add-media" data-media-type="image" data-multiple="true"/>
			<?php endif;?>

			<?php if( in_array( 'video', $includes ) ):?>
			<input type="button" value="Videos toevoegen" class="button tagadd" id="addvideos"/>
			<?php endif;?>

			<?php if( in_array( 'text', $includes ) ):?>
			<input type="button" value="Tekst toevoegen" class="button tagadd" id="addtext"/>
			<?php endif;?>

			<?php if( in_array( 'video', $includes ) ):?>
			<div id="cuisine-media-video-add">
				
				<label class="cuisine_label label_left">
					<p>Video URL:</p>
					<input type="text" class="cuisine_input input_left video_input" id="vidurl" value="Plak hier het webadres van de video" onClick="doSmartEmpty('#vidurl', 'Plak hier het webadres van de video');"/>
					<input type="button" value="Voeg toe" class="button tagadd" id="addvid"/>
				</label>
				<div class="clearfix"></div>
			</div>
			<?php endif;?>
		</div>
		
		<div id="cuisine-media-items">
		
			<ul id="sortable">
		
				<?php if( $media != null ):
						
						if (is_array($media) && count($media) > 0):

							foreach ( $media as $item):  

								if(!isset($item['position'])) $item['position'] = $i;
								if(!isset($item['type'])) $item['type'] = 'image';
								if(!isset($item['link'])) $item['link'] = '';
				?>	

				<li class="mitem" id="mitem-<?php echo $item['id']?>">
		
				<div class="mitem-container">
					
					<?php if($item['type'] == 'image'):?>
						<img src="<?php echo $item['url']?>" class="mitem-img"/>
					<?php elseif($item['type'] == 'video'):?>
						<img src="<?php echo $item['vidthumb']?>" class="mitem-img"/>
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][vidthumb]" value="<?php echo $item['vidthumb'];?>"/>
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][vidtype]" value="<?php echo $item['vidtype'];?>"/>
					<?php endif;?>
					<div class="mitem-body">
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][id]" value="<?php echo $item['id'];?>"/>
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][url]" value="<?php echo $item['url'];?>"/>
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][position]" value="<?php echo $item['position'];?>" class="mitem-position"/>
						<input type="hidden" name="<?php echo $metaname;?>[<?php echo $i;?>][type]" value="<?php echo $item['type'];?>"/>

						<label>Titel:</label><input type="text" name="<?php echo $metaname;?>[<?php echo $i;?>][title]" value="<?php echo $item['title'];?>" class="mitem-title"/>
						<label>Beschrijving:</label><textarea name="<?php echo $metaname;?>[<?php echo $i;?>][description]" class="mitem-description"><?php echo $item['description'];?></textarea>
					<?php if( $item['type'] != 'video' ):?>
						<label>Link:</label><input type="text" name="<?php echo $metaname;?>[<?php echo $i;?>][link]" value="<?php echo $item['link']?>" class="mitem-link"/>
					<?php endif;?>
					</div>
		
					<div class="mitem-controls">
						<div class="mitem-control pin" id="pin-<?php echo $item['id']?>"></div>
						<div class="mitem-control trash" id="trash-<?php echo $item['id']?>"></div>
					</div>
		
				<div class="clearfix"></div></div></li>	
					
				<?php $i++; endforeach; endif; endif; ?>
			</ul>
	</div>

		<?php

	}

	/**
	*	GET THE MEDIA POST META OBJECT:
	*
 	* @access public
	* @return void
	*/

	function cuisine_get_media( $pid = null, $posttype = null ){

		if( $pid == null )
			$pid = cuisine_get_post_id();

		if( $posttype == null )
			$posttype = get_post_type( $pid );


		return get_post_meta( $pid, $posttype.'_media', true );


	}


?>