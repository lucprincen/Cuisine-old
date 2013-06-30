<?php
/**
 * Cuisine posttypes handles the creation and management of a posttype
 *
 * @class 		Cuisine_Posttypes
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Posttypes {


	/**
	* Register a post type
	*/
	function register( $type, $singular, $plural, $slug = null, $supports = array( 'title', 'editor', 'thumbnail', 'revisions' ), $taxonomies = array(), $vars = array() ){

		if( $slug == null )
			$slug = strtolower( $plural );

		$vars = $this->set_defaults( $vars );

		$args = array(
			'labels'			=> array(
				'name'					=> __($plural),
				'singular_name' 		=> __($singular),
				'add_new'				=> __($singular.' toevoegen'),
				'add_new_item'			=> __($singular.' toevoegen'),
				'new_item'				=> __($singular.' toevoegen'),
				'view_item'				=> __($singular.' bekijken'),
				'search_items' 			=> __($plural.' doorzoeken'), 
				'edit_item' 			=> __($singular.' bewerken'),
				'all_items'				=> __('Alle '.strtolower($plural)),
				'not_found'				=> __('Geen '.strtolower($plural).' gevonden'),
				'not_found_in_trash'	=> __('Geen '.strtolower($plural).' gevonden in de prullenbak')
			),
			'public'			=> $vars['public'],
			'show_ui'			=> $vars['show_ui'],
			'_builtin'			=> $vars['_builtin'],
			'_edit_link'		=> $vars['_edit_link'],
			'capability_type'	=> $vars['capability_type'],
			'rewrite'			=> array( 'slug' => $slug ),
			'hierarchical'		=> $vars['hierarchical'],
			'menu_position'		=> $vars['menu_position'],
			'taxonomies'		=> $taxonomies,
			'supports'			=> $supports
		);
	
	
		/** create the custom post type */
		register_post_type( $type, $args );

	}


	function set_defaults( $vars ){

		if( !isset( $vars['public'] ) ) $vars['public'] = true;

		if( !isset( $vars['show_ui'] ) ) $vars['show_ui'] = true;

		if( !isset( $vars['_builtin'] ) ) $vars['_builtin'] = false;

		if( !isset( $vars['_edit_link'] ) ) $vars['_edit_link'] = 'post.php?post=%d';

		if( !isset( $vars['capability_type'] ) ) $vars['capability_type'] = 'post';

		if( !isset( $vars['hierarchical'] ) ) $vars['hierarchical'] = true;

		if( !isset( $vars['menu_position'] ) ) $vars['menu_position'] = 20;

		return $vars;
	}

	/**
	*	Return the current post type:
	*/

	function get( ){
		global $post;

		if( is_admin() ){

			if( isset( $_GET['post_type' ] ) )
				return $_GET['post_type'];

			if( isset( $post ) )
				return get_post_type( $post->ID );

			if( isset( $_GET['post' ] ) )
				return get_post_type( $_GET['post'] );

		}else{
			
			if( isset( $post ) )
				return $post->post_type;
		}


		return false;

	}


	/**
	* Get the current post type:
	*/
	function current( $type ){
		global $post;
		if( isset( $post ) && $post->post_type == $type )
			return true;
		
		return false;
	}

	/**
	* Get the current post type in the admin:
	*/
	function admin_current( $type ){
		if( isset( $_GET['post_type'] ) && $_GET['post_type'] == $type )
			return true;
		
		global $post;
		if( isset( $post ) && get_post_type( $post->ID ) == $type )
			return true;

		if( isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == $type )
			return true;

		return false;
	}

	/**
	* Find all public post types:
	*/
	function find(){
		$post_types = get_post_types();
		$return = array();
		
		foreach( $post_types as $post_type ){
			if( $this->filter_public( $post_type ))
				$return[] = $post_type;
		}

		return $return;
	}

	/**
	* return if a post type is public or not:
	*/
	function filter_public($pt){
		if( $pt == 'revision' || $pt == 'nav_menu_item' )
			return false;

		return true;
	}

	/**
	*	Check if a slug is a post type
	*/
	function is_public_posttype( $slug ){

		$pt = $this->find();
		foreach( $pt as $p ){
			if($p == $slug){
				return true;
			}
		}

		return false;

	}

}
	