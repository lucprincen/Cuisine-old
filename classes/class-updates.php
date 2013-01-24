<?php
/**
 * Cuisine Updates handles all updates for Chef du Web plugins.
 *
 * @class 		Cuisine_Updates
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Updates {


	var $update_url = 'http://www.chefduweb.nl/updates';



	function __construct(){

		global $cuisine;

		// check_updates_for is an array which holds all 
		// plugin-hashes to check.
		$this->check_updates_for = array();

		// Add cuisine to the update array:
		$cuisine_args = array(
		               		'slug'		=> 'cuisine',
		                    'version'	=> $cuisine->version
		                );

		$this->check_updates_for['cuisine'] = $cuisine_args;

		$this->check_updates_for = apply_filters( 'cuisine_check_update', $this->check_updates_for );


		/** DEVELOPMENT :: */
		/*		
		set_site_transient('update_plugins', null);

		// TEMP: Show which variables are being requested when query plugin API
		add_filter('plugins_api_result', array( &$this, 'result_info' ), 100, 3);
		*/		
		
		add_action( 'admin_init', array( &$this, 'run_check_plugins'), 0 );

	}


	function result_info( $res, $action, $args ) {
		cuisine_dump( $res );
		return $res;
	}


	/**
	*	On Admin init, check for updates.
	*/
	function run_check_plugins(){

		//Add the filter for plugin-update checks --this only runs when the transient isn't set--:
		add_filter('pre_set_site_transient_update_plugins', array( &$this, 'check_for_updates' ), 100, 1 );

		// Take over the Plugin info screen
		add_filter('plugins_api', array( &$this, 'plugin_api_call' ), 10, 3);
	}


	/**
	*	Check if there are updates available
	*/
	function check_for_updates( $checked_data ){
		global $wp_version;


		if ( !isset( $checked_data ) || empty( $checked_data->checked ) )
			return $checked_data;


		if( !empty( $this->check_updates_for ) ){


			foreach( $this->check_updates_for as $pl ){

				$args = array(
					'slug' => $pl['slug'],
					'version' => $pl['version'],
				);
				
				$request_string = array(
						'body' => array(
							'action' => 'basic_check', 
							'request' => serialize($args),
							'api-key' => md5(get_bloginfo('url'))
						),
						'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
					);
			


				// Start checking for an update
				$raw_response = wp_remote_post($this->update_url, $request_string);
			

				if (!is_wp_error($raw_response) && ($raw_response['response']['code'] == 200))
					$response = unserialize($raw_response['body']);
					
				if (is_object($response) && !empty($response)) // Feed the update data into WP updater
					$checked_data->response[$pl['slug'] .'/'. $pl['slug'] .'.php'] = $response;

			}

			return $checked_data;

		}
	}


	/**
	*	Do a plugin-api call...
	*/
	function plugin_api_call($def, $action, $args) {
		global $wp_version;
		
		if( !isset( $args->slug ) )
			return false;

		if (isset($args->slug) && isset( $this->check_updates_for[ $args->slug ] ) == false )
			return false;
		
		// Get the current version
		$plugin_info = get_site_transient('update_plugins');
		$plugin_slug = $args->slug;
		$current_version = $this->check_updates_for[ $args->slug ];
		
		$request_string = array(
				'body' => array(
					'action' => $action, 
					'request' => serialize($args),
					'api-key' => md5(get_bloginfo('url'))
				),
				'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
			);
		
		$request = wp_remote_post( $this->update_url , $request_string );
		
		if (is_wp_error($request)) {
			$res = new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the 	API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">	Try again</a>'), $request->get_error_message());
		} else {
			$res = unserialize($request['body']);
			
			if ($res === false)
				$res = new WP_Error('plugins_api_failed', __('An unknown error occurred'), $request['	body']);
		}
		
		return $res;

	}


	/**
	*	Add a plugin to the 'update-check' list.
	*/
	function add_to_update_list( $args ){

		if( !isset( $this->check_updates_for[ $args['slug'] ] ) ){
			$this->check_updates_for[ $args['slug'] ] = $args;
		}

	}


}?>