<?php
/**
 * Cuisine Caches all frontend views.
 * Code derived from Batcache by Andy Skelton
 *
 * @class 		Cuisine_Cache
 * @package		Cuisine
 * @category	Class
 * @author		Chef du Web
 */
class Cuisine_Cache {
	
	// This is the base configuration. You can edit these variables or move them into your wp-config.php file.
	var $max_age =  300; // Expire CuisineCache items aged this many seconds (zero to disable CuisineCache)
	
	var $remote  =    0; // Zero disables sending buffers to remote datacenters (req/sec is never sent)
	
	var $times   =    2; // Only CuisineCache a page after it is accessed this many times... (two or more)
	var $seconds =  120; // ...in this many seconds (zero to ignore this and use CuisineCache immediately)
	
	var $group   = 'CuisineCache'; // Name of memcached group. You can simulate a cache flush by changing this.
	
	var $unique  = array(); // If you conditionally serve different content, put the variable values here.
	
	var $headers = array(); // Add headers here. These will be sent with every response from the cache.

	var $cache_redirects = false; // Set true to enable redirect caching.
	var $redirect_status = false; // This is set to the response code during a redirect.
	var $redirect_location = false; // This is set to the redirect location.

	var $uncached_headers = array('transfer-encoding'); // These headers will never be cached. Apply strtolower.

	var $debug   = true; // Set false to hide the CuisineCache info <!-- comment -->

	var $cache_control = true; // Set false to disable Last-Modified and Cache-Control headers

	var $cancel = false; // Change this to cancel the output buffer. Use CuisineCache_cancel();

	var $genlock; // Used internally
	var $do; // Used internally

	function CuisineCache( $settings ) {
		if ( is_array( $settings ) ) foreach ( $settings as $k => $v )
			$this->$k = $v;
	}

	function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) )
				return true;
			if ( '1' == $_SERVER['HTTPS'] )
				return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}

	function status_header( $status_header ) {
		$this->status_header = $status_header;

		return $status_header;
	}

	function redirect_status( $status, $location ) {
		if ( $this->cache_redirects ) {
			$this->redirect_status = $status;
			$this->redirect_location = $location;
		}

		return $status;
	}

	function configure_groups() {
		// Configure the memcached client
		if ( ! $this->remote )
			if ( function_exists('wp_cache_add_no_remote_groups') )
				wp_cache_add_no_remote_groups(array($this->group));
		if ( function_exists('wp_cache_add_global_groups') )
			wp_cache_add_global_groups(array($this->group));
	}

	// Defined here because timer_stop() calls number_format_i18n()
	function timer_stop($display = 0, $precision = 3) {
		global $timestart, $timeend;
		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$timeend = $mtime;
		$timetotal = $timeend-$timestart;
		$r = number_format($timetotal, $precision);
		if ( $display )
			echo $r;
		return $r;
	}

	function ob($output) {
		if ( $this->cancel !== false )
			return $output;

		// PHP5 and objects disappearing before output buffers?
		wp_cache_init();

		// Remember, $wp_object_cache was clobbered in wp-settings.php so we have to repeat this.
		$this->configure_groups();

		// Do not CuisineCache blank pages unless they are HTTP redirects
		$output = trim($output);
		if ( $output === '' && (!$this->redirect_status || !$this->redirect_location) )
			return;

		// Construct and save the CuisineCache
		$cache = array(
			'output' => $output,
			'time' => time(),
			'timer' => $this->timer_stop(false, 3),
			'status_header' => $this->status_header,
			'redirect_status' => $this->redirect_status,
			'redirect_location' => $this->redirect_location,
			'version' => $this->url_version
		);

		if ( function_exists( 'headers_list' ) ) {
			foreach ( headers_list() as $header ) {
				list($k, $v) = array_map('trim', explode(':', $header, 2));
				$cache['headers'][$k] = $v;
			}
		} elseif ( function_exists( 'apache_response_headers' ) ) {
			$cache['headers'] = apache_response_headers();
		}

		if ( $cache['headers'] && !empty( $this->uncached_headers ) ) {
			foreach ( $cache['headers'] as $header => $value ) {
				if ( in_array( strtolower( $header ), $this->uncached_headers ) )
					unset( $cache['headers'][$header] );
			}
		}

		wp_cache_set($this->key, $cache, $this->group, $this->max_age + $this->seconds + 30);

		// Unlock regeneration
		wp_cache_delete("{$this->url_key}_genlock", $this->group);

		if ( $this->cache_control ) {
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $cache['time'] ) . ' GMT', true );
			header("Cache-Control: max-age=$this->max_age, must-revalidate", false);
		}

		if ( !empty($this->headers) ) foreach ( $this->headers as $k => $v ) {
			if ( is_array( $v ) )
				header("{$v[0]}: {$v[1]}", false);
			else
				header("$k: $v", true);
		}

		// Add some debug info just before </head>
		if ( $this->debug ) {
			$tag = "<!--\n\tgenerated in " . $cache['timer'] . " seconds\n\t" . strlen(serialize($cache)) . " bytes CuisineCached for " . $this->max_age . " seconds\n-->\n";
			if ( false !== $tag_position = strpos($output, '</head>') ) {
				$tag = "<!--\n\tgenerated in " . $cache['timer'] . " seconds\n\t" . strlen(serialize($cache)) . " bytes CuisineCached for " . $this->max_age . " seconds\n-->\n";
				$output = substr($output, 0, $tag_position) . $tag . substr($output, $tag_position);
			}
		}

		// Pass output to next ob handler
		return $output;
	}

}
?>