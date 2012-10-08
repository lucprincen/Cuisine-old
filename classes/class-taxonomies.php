<?php
/**
 * Cuisine taxonomies handles the all the settings 
 *
 * @class 		Cuisine_Taxonomies
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Taxonomies {
	

	function register( $slug, $rewrite, $post_type, $singular, $plural, $language_domain = 'cuisine'){
		
		register_taxonomy($slug, $post_type, array(
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'rewrite'			=> array('slug' => $rewrite),
			'labels'			=> array(
					'name' 							=> __($plural, $language_domain),
					'singular_name'					=> __($singular, $language_domain),
					'search_items' 					=> __('Search '.$plural, $language_domain),
					'popular_items'					=> __('Popular '.$plural, $language_domain),
					'all_items'						=> __('All '.$plural, $language_domain ),
					'parent_item'					=> __('Parent '.$singular, $language_domain),
					'parent_item_colon'				=> __('Parent '.$singular, $language_domain),
					'edit_item'						=> __('Edit '.$singular, $language_domain), 
					'update_item'					=> __('Update '.$singular, $language_domain),
					'add_new_item'					=> __('Add New '.$singular, $language_domain),
					'new_item_name'					=> __('New '.$singular, $language_domain),
					'separate_items_with_commas'	=> __('Separate  .$pluralwith commas', $language_domain),
					'add_or_remove_items' 			=> __('Add or remove '.$plural, $language_domain),
					'choose_from_most_used' 		=> __('Choose from the most used '.$plural, $language_domain)
			)
		));


	}

}
	