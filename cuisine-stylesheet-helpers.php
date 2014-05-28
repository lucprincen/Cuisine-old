<?php 
/**
 * Cuisine Stylesheet helper functions
 * 
 * Handles functions for the stylesheet (all overwritable).
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 * @since 		1.3
 */



/**************************************************/
/** Stylesheet Helpers ****************************/
/**************************************************/


if( !function_exists( '_s' ) ){
	/**
	*	Simple function to make style-echoing a little easier
	*	@access public
	*	@return String style element
	*/
	function _s( $type ){

        global $style;
        echo $style[$type].';';

    }

}


if( !function_exists( 'borderbox' ) ){
	/**
	*	Function for quick box-sizing settings:
	*	@access public
	*	@return String box-sizing css
	*/
    function borderbox(){
        echo '-moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;';
    }

}



if( !function_exists( 'boxshadow' ) ){
	/**
	*	Generate a neat box-shadow
	*	@access public
	*	@return String box-shadow css
	*/
    function boxshadow($string, $trailinglinebreak = false){
        $css =  '-webkit-box-shadow:'.$string.';';
        $css .= '-moz-box-shadow:'.$string.';';
        $css .= 'box-shadow:'.$string.';';
        if($trailinglinebreak)
            $css .= "\n";
        echo $css;
    }

}



if( !function_exists( 'transition' ) ){
	/**
	*	Set a css transition:
	*	@access public
	*	@return String transition css
	*/
    function transition( $seconds = '.3s' ){
        echo '-webkit-transition: all '.$seconds.' ease; -moz-transition: all '.$seconds.' ease; -ms-transition: all '.$seconds.' ease; -o-transition: all '.$seconds.' ease; transition: all '.$seconds.' ease;';
    }

}



if( !function_exists( 'transitionnone' ) ){
	/**
	*	Unset a transition:
	*	@access public
	*	@return String no transition css
	*/
    function transitionnone(){
        echo '-webkit-transition:none; -moz-transition:none; -ms-transition:none; -o-transition:none; transition:none;';
    }

}



if( !function_exists( 'set_font_size' ) ){
	/**
	*	Calculate a new font-size:
	*	@access public
	*	@return String font-size css
	*/
    function set_font_size( $size, $minmax = 0 ){

        $t = '+';

        $size = str_replace( 'px', '', $size );

        if($minmax != 0){
            $t = substr($minmax, 0, 1);
            $minmax = str_replace('+', '', str_replace('-', '', str_replace('*', '', str_replace('%', '', $minmax))));
        }


        if($t == '+'){
            $num = $size + $minmax;
        }else if($t == '-'){
            $num = $size - $minmax;
        }else if($t == '*'){
            $num = $size * $minmax; 
        }else if($t == '%'){
            $num = $size / $minmax;
        }else{
            $num = $size + $minmax;
        }       

        echo $num.'px';
    }

}



if( !function_exists( 'trim_css' ) ){

	/**
	*	Simple minify-css function:
	* 	@access public
	*	@return String minified css
	*/
    function trim_css( $css ){
        return str_replace('; ',';',str_replace(' }','}',str_replace('{ ','{',str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),"",preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','',$css)))));

    }

}


/**************************************************/
/** Stylesheet Color Helpers **********************/
/**************************************************/



if ( ! function_exists( 'rgb_from_hex' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @return string
	 */
	function rgb_from_hex( $color ) {
		$color = str_replace( '#', '', $color );
		// Convert shorthand colors to full format, e.g. "FFF" -> "FFFFFF"
		$color = preg_replace( '~^(.)(.)(.)$~', '$1$1$2$2$3$3', $color );

		$rgb['R'] = hexdec( $color{0}.$color{1} );
		$rgb['G'] = hexdec( $color{2}.$color{3} );
		$rgb['B'] = hexdec( $color{4}.$color{5} );
		return $rgb;
	}
}

if ( ! function_exists( 'hex_darker' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @param int $factor (default: 30)
	 * @return string
	 */
	function hex_darker( $color, $factor = 30 ) {
		$base = rgb_from_hex( $color );
		$color = '#';

		foreach ($base as $k => $v) :
	        $amount = $v / 100;
	        $amount = round($amount * $factor);
	        $new_decimal = $v - $amount;

	        $new_hex_component = dechex($new_decimal);
	        if(strlen($new_hex_component) < 2) :
	        	$new_hex_component = "0".$new_hex_component;
	        endif;
	        $color .= $new_hex_component;
		endforeach;

		return $color;
	}
}

if ( ! function_exists( 'hex_lighter' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @param int $factor (default: 30)
	 * @return string
	 */
	function hex_lighter( $color, $factor = 30 ) {
		$base = rgb_from_hex( $color );
		$color = '#';

	    foreach ($base as $k => $v) :
	        $amount = 255 - $v;
	        $amount = $amount / 100;
	        $amount = round($amount * $factor);
	        $new_decimal = $v + $amount;

	        $new_hex_component = dechex($new_decimal);
	        if(strlen($new_hex_component) < 2) :
	        	$new_hex_component = "0".$new_hex_component;
	        endif;
	        $color .= $new_hex_component;
	   	endforeach;

	   	return $color;
	}
}

if ( ! function_exists( 'light_or_dark' ) ) {

	/**
	 * Detect if we should use a light or dark colour on a background colour
	 *
	 * @access public
	 * @param mixed $color
	 * @param string $dark (default: '#000000')
	 * @param string $light (default: '#FFFFFF')
	 * @return string
	 */
	function light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {
	    //return ( hexdec( $color ) > 0xffffff / 2 ) ? $dark : $light;
	    $hex = str_replace( '#', '', $color );

		$c_r = hexdec( substr( $hex, 0, 2 ) );
		$c_g = hexdec( substr( $hex, 2, 2 ) );
		$c_b = hexdec( substr( $hex, 4, 2 ) );
		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

		return $brightness > 155 ? $dark : $light;
	}
}

if ( ! function_exists( 'format_hex' ) ) {

	/**
	 * Format string as hex
	 *
	 * @access public
	 * @param string $hex
	 * @return string
	 */
	function format_hex( $hex ) {

	    $hex = trim( str_replace( '#', '', $hex ) );

	    if ( strlen( $hex ) == 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	    }

	    if ( $hex ) return '#' . $hex;
	}
}

?>