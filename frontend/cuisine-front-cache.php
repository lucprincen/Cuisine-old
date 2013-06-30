<?php

global $cuisinecache;
// Pass in the global variable which may be an array of settings to override defaults.
$cuisinecache = new Cuisine_Cache($cuisinecache);

if ( ! defined( 'WP_CONTENT_DIR' ) )
	return;

// Never cuisinecache interactive scripts or API endpoints.
if ( in_array(
		basename( $_SERVER['SCRIPT_FILENAME'] ),
		array(
			'wp-app.php',
			'xmlrpc.php',
			'ms-files.php',
		) ) )
	return;

// Never cuisinecache WP javascript generators
if ( strstr( $_SERVER['SCRIPT_FILENAME'], 'wp-includes/js' ) )
	return;

// Never cuisinecache when POST data is present.
if ( ! empty( $GLOBALS['HTTP_RAW_POST_DATA'] ) || ! empty( $_POST ) )
	return;

// Never cuisinecache when cookies indicate a cache-exempt visitor.
if ( is_array( $_COOKIE) && ! empty( $_COOKIE ) )
	foreach ( array_keys( $_COOKIE ) as $cuisinecache->cookie )
		if ( $cuisinecache->cookie != 'wordpress_test_cookie' && ( substr( $cuisinecache->cookie, 0, 2 ) == 'wp' || substr( $cuisinecache->cookie, 0, 9 ) == 'wordpress' || substr( $cuisinecache->cookie, 0, 14 ) == 'comment_author' ) )
			return;

if ( ! include_once( WP_CONTENT_DIR . '/object-cache.php' ) )
	return;

wp_cache_init(); // Note: wp-settings.php calls wp_cache_init() which clobbers the object made here.

if ( ! is_object( $wp_object_cache ) )
	return;

// Now that the defaults are set, you might want to use different settings under certain conditions.

/* Example: if your documents have a mobile variant (a different document served by the same URL) you must tell cuisinecache about the variance. Otherwise you might accidentally cache the mobile version and serve it to desktop users, or vice versa.
$cuisinecache->unique['mobile'] = is_mobile_user_agent();
*/

/* Example: never cuisinecache for this host
if ( $_SERVER['HTTP_HOST'] == 'do-not-cuisinecache-me.com' )
	return;
*/

/* Example: cuisinecache everything on this host regardless of traffic level
if ( $_SERVER['HTTP_HOST'] == 'always-cuisinecache-me.com' ) {
	$cuisinecache->max_age = 600; // Cache for 10 minutes
	$cuisinecache->seconds = $cuisinecache->times = 0; // No need to wait till n number of people have accessed the page, cache instantly
}
*/

/* Example: If you sometimes serve variants dynamically (e.g. referrer search term highlighting) you probably don't want to cuisinecache those variants. Remember this code is run very early in wp-settings.php so plugins are not yet loaded. You will get a fatal error if you try to call an undefined function. Either include your plugin now or define a test function in this file.
if ( include_once( 'plugins/searchterm-highlighter.php') && referrer_has_search_terms() )
	return;
*/

// Disabled
if ( $cuisinecache->max_age < 1 )
	return;

// Make sure we can increment. If not, turn off the traffic sensor.
if ( ! method_exists( $GLOBALS['wp_object_cache'], 'incr' ) )
	$cuisinecache->times = 0;

// Necessary to prevent clients using cached version after login cookies set. If this is a problem, comment it out and remove all Last-Modified headers.
header('Vary: Cookie', false);

// Things that define a unique page.
if ( isset( $_SERVER['QUERY_STRING'] ) )
	parse_str($_SERVER['QUERY_STRING'], $cuisinecache->query);
$cuisinecache->keys = array(
	'host' => $_SERVER['HTTP_HOST'],
	'method' => $_SERVER['REQUEST_METHOD'],
	'path' => ( $cuisinecache->pos = strpos($_SERVER['REQUEST_URI'], '?') ) ? substr($_SERVER['REQUEST_URI'], 0, $cuisinecache->pos) : $_SERVER['REQUEST_URI'],
	'query' => $cuisinecache->query,
	'extra' => $cuisinecache->unique
);

if ( $cuisinecache->is_ssl() )
	$cuisinecache->keys['ssl'] = true;

$cuisinecache->configure_groups();

// Generate the cuisinecache key
$cuisinecache->key = md5(serialize($cuisinecache->keys));

// Generate the traffic threshold measurement key
$cuisinecache->req_key = $cuisinecache->key . '_req';

// Get the cuisinecache
$cuisinecache->cache = wp_cache_get($cuisinecache->key, $cuisinecache->group);

// Are we only caching frequently-requested pages?
if ( $cuisinecache->seconds < 1 || $cuisinecache->times < 2 ) {
	$cuisinecache->do = true;
} else {
	// No cuisinecache item found, or ready to sample traffic again at the end of the cuisinecache life?
	if ( !is_array($cuisinecache->cache) || time() >= $cuisinecache->cache['time'] + $cuisinecache->max_age - $cuisinecache->seconds ) {
		wp_cache_add($cuisinecache->req_key, 0, $cuisinecache->group);
		$cuisinecache->requests = wp_cache_incr($cuisinecache->req_key, 1, $cuisinecache->group);

		if ( $cuisinecache->requests >= $cuisinecache->times )
			$cuisinecache->do = true;
		else
			$cuisinecache->do = false;
	}
}

// Recreate the permalink from the URL
$cuisinecache->permalink = 'http://' . $cuisinecache->keys['host'] . $cuisinecache->keys['path'] . ( isset($cuisinecache->keys['query']['p']) ? "?p=" . $cuisinecache->keys['query']['p'] : '' );
$cuisinecache->url_key = md5($cuisinecache->permalink);
$cuisinecache->url_version = (int) wp_cache_get("{$cuisinecache->url_key}_version", $cuisinecache->group);

// If the document has been updated and we are the first to notice, regenerate it.
if ( $cuisinecache->do !== false && isset($cuisinecache->cache['version']) && $cuisinecache->cache['version'] < $cuisinecache->url_version )
	$cuisinecache->genlock = wp_cache_add("{$cuisinecache->url_key}_genlock", 1, $cuisinecache->group);

// Did we find a cuisinecached page that hasn't expired?
if ( isset($cuisinecache->cache['time']) && ! $cuisinecache->genlock && time() < $cuisinecache->cache['time'] + $cuisinecache->max_age ) {
	// Issue redirect if cached and enabled
	if ( $cuisinecache->cache['redirect_status'] && $cuisinecache->cache['redirect_location'] && $cuisinecache->cache_redirects ) {
		$status = $cuisinecache->cache['redirect_status'];
		$location = $cuisinecache->cache['redirect_location'];
		// From vars.php
		$is_IIS = (strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== false || strpos($_SERVER['SERVER_SOFTWARE'], 'ExpressionDevServer') !== false);
		if ( $is_IIS ) {
			header("Refresh: 0;url=$location");
		} else {
			if ( php_sapi_name() != 'cgi-fcgi' ) {
				$texts = array(
					300 => 'Multiple Choices',
					301 => 'Moved Permanently',
					302 => 'Found',
					303 => 'See Other',
					304 => 'Not Modified',
					305 => 'Use Proxy',
					306 => 'Reserved',
					307 => 'Temporary Redirect',
				);
				$protocol = $_SERVER["SERVER_PROTOCOL"];
				if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol )
					$protocol = 'HTTP/1.0';
				if ( isset($texts[$status]) )
					header("$protocol $status " . $texts[$status]);
				else
					header("$protocol 302 Found");
			}
			header("Location: $location");
		}
		exit;
	}

	// Issue "304 Not Modified" only if the dates match exactly.
	if ( $cuisinecache->cache_control && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) {
		$since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if ( isset($cuisinecache->cache['headers']['Last-Modified']) )
			$cuisinecache->cache['time'] = strtotime( $cuisinecache->cache['headers']['Last-Modified'] );
		if ( $cuisinecache->cache['time'] == $since ) {
			header('Last-Modified: ' . $_SERVER['HTTP_IF_MODIFIED_SINCE'], true, 304);
			exit;
		}
	}

	// Use the cuisinecache save time for Last-Modified so we can issue "304 Not Modified"
	if ( $cuisinecache->cache_control ) {
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $cuisinecache->cache['time'] ) . ' GMT', true );
		header('Cache-Control: max-age=' . ($cuisinecache->max_age - time() + $cuisinecache->cache['time']) . ', must-revalidate', true);
	}

	// Add some debug info just before </head>
	if ( $cuisinecache->debug ) {
		if ( false !== $tag_position = strpos($cuisinecache->cache['output'], '</head>') ) {
			$tag = "<!--\n\tgenerated " . (time() - $cuisinecache->cache['time']) . " seconds ago\n\tgenerated in " . $cuisinecache->cache['timer'] . " seconds\n\tserved from cuisinecache in " . $cuisinecache->timer_stop(false, 3) . " seconds\n\texpires in " . ($cuisinecache->max_age - time() + $cuisinecache->cache['time']) . " seconds\n-->\n";
			$cuisinecache->cache['output'] = substr($cuisinecache->cache['output'], 0, $tag_position) . $tag . substr($cuisinecache->cache['output'], $tag_position);
		}
	}

	if ( !empty($cuisinecache->cache['headers']) ) foreach ( $cuisinecache->cache['headers'] as $k => $v )
		header("$k: $v", true);

	if ( !empty($cuisinecache->headers) ) foreach ( $cuisinecache->headers as $k => $v ) {
		if ( is_array( $v ) )
			header("{$v[0]}: {$v[1]}", false);
		else
			header("$k: $v", true);
	}

	if ( !empty($cuisinecache->cache['status_header']) )
		header($cuisinecache->cache['status_header'], true);

	// Have you ever heard a death rattle before?
	die($cuisinecache->cache['output']);
}

// Didn't meet the minimum condition?
if ( !$cuisinecache->do && !$cuisinecache->genlock )
	return;

$wp_filter['status_header'][10]['cuisinecache'] = array( 'function' => array(&$cuisinecache, 'status_header'), 'accepted_args' => 1 );
$wp_filter['wp_redirect_status'][10]['cuisinecache'] = array( 'function' => array(&$cuisinecache, 'redirect_status'), 'accepted_args' => 2 );

ob_start(array(&$cuisinecache, 'ob'));