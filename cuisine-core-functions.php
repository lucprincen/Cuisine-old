<?php

	/**
	*	Generate a code:
	*	
	*	@access public
	*	@param  Int string length
	*	@return  String with a unique code
	*/
	function cuisine_create_code( $len = 10 ){
		
		$pass = '';
		$lchar = 0;
		$char = 0;
		
		for( $i = 0; $i < $len; $i++ ) {
			while( $char == $lchar ) {
				$char = rand( 48, 109 );
				if( $char > 57 ) $char += 7;
				if( $char > 90 ) $char += 6;
			}

			$pass .= chr( $char );
			$lchar = $char;
		}

		return $pass;
	}




	/**
	 * Sort an Array by a field
	 * 
	 * @access public
	 * @param  Array $data  Input array
	 * @param  String $field Field to sort on
	 * @param  String $order ASC/DESC
	 * @return Array (sorted)
	 */
	function cuisine_sort_array_by( $data, $field, $order = null ){
	
		if( $order == null || $order == 'ASC' ){
	  		$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	  	}else if( $order == 'DESC' ){
	  		$code = "return strnatcmp(\$b['$field'], \$a['$field']);";
		}
	
		uasort( $data, create_function( '$a,$b', $code ) );
		return $data;
	}
	


	/**
	*	Function to make parsing out data a bit more pleasant:
	* @access public
	* @param Object (whatever)
	* @return  a nicely formatted Object.
	*/
	function cuisine_dump( $obj ){
		echo '<pre>';
		print_r( $obj );
		echo '</pre>';
	}



	/**
	*	Make links in strings clickable:
	* @access public
	* @param  String text to filter
	* @return  String (html) with clickable links
	*/
	function cuisine_make_links_clickable( $text ){
    	return preg_replace('!(((f|ht)tp://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
	}



	/**
	*	Check if something is plural:
	*	@access public
	*	@param  Int number
	*	@return  bool
	*/
	function cuisine_is_plural( $num ) {
		if ( $num != 1 ){
			return true;
		}else{
			return false;
		}
	}



	/**
	 * Remove whitespace between html tags
	 * @param  String (html)
	 * @return String (html) - without whitespace
	 */
	function cuisine_remove_whitespace( $string ){
		return preg_replace('~>\s+<~', '><', $string );
	}



	/**
	*	Create a relative time:
	* @access public
	* @param  String time / data
	* @return  String relative time
	*/
	function cuisine_relative_time($date) {

		$diff = time() - strtotime($date);
			if ( $diff<60){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('seconds ago', 'cuisine');
				}else{
					return $diff .' '. __('second ago', 'cuisine');
				}
			}
		
		$diff = round($diff/60);
		
			if ( $diff<60 ){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('minutes ago', 'cuisine');
				}else{
					return $diff .' '. __('minute ago', 'cuisine');
				}
			}
		
		$diff = round($diff/60);

			if ( $diff<24 ){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('hours ago', 'cuisine');
				}else{
					return $diff .' '. __('hour ago', 'cuisine');
				}
			}
	
		$diff = round($diff/24);

			if ( $diff<7 ){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('days ago', 'cuisine');
				}else{
					return $diff .' '. __('day ago', 'cuisine');
				}
			}
		
		$diff = round($diff/7);

			if ( $diff<4 ){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('weeks ago', 'cuisine');
				}else{
					return $diff .' '. __('week ago', 'cuisine');
				}
			}

		$diff = round($diff/30);

			if ( $diff<4 ){
				if( cuisine_is_plural( $diff ) ){
					return $diff .' '. __('months ago', 'cuisine');
				}else{
					return $diff .' '. __('month ago', 'cuisine');
				}
			}

	}

	/**
	 * Translate dutch months
	 *
	 * @access public
	 * @param String $val  monthname or dayname
	 * @return String (translated)
	 */
	function cuisine_dutch_date( $val ){

		$val = strtolower( $val );

			switch( $val ){

				case 'monday':
					return 'maandag';
					break;

				case 'tuesday':
					return 'dinsdag';
					break;

				case 'wednesday':
					return 'woensdag';
					break;

				case 'thursday':
					return 'donderdag';
					break;

				case 'friday':
					return 'vrijdag';
					break;

				case 'saturday':
					return 'zaterdag';
					break;

				case 'sunday':
					return 'zondag';
					break;

				case 'january':
					return 'januari';
					break;

				case 'february':
					return 'februari';
					break;

				case 'march':
					return 'maart';
					break;

				case 'may':
					return 'mei';
					break;

				case 'june':
					return 'juni';
					break;

				case 'july':
					return 'juli';
					break;

				case 'august':
					return 'augustus';
					break;

				case 'october':
					return 'oktober';
					break;

				case 'oct':
					return 'okt';
					break;

				default:

					return $val;
					break;
			}
	}


	/**
	 * Echoes any date in WordPress formats (so also translated)
	 *
	 * @access public
	 * @param  string $format The date format
	 * @param  int $date   unix timestamp
	 * @return void
	 */
	function cuisine_date( $format = 'j F Y', $date = null ){

		global $post;

		if( $date == null )
			$date = strtotime( $post->post_date );

		echo cuisine_get_date( $format, $date );

	}


	/**
	 * Returns any date in WordPress formats (so also translated)
	 *
	 * @access public
	 * @param  string $format The date format
	 * @param  int $date   unix timestamp
	 * @return String (date)
	 */
	function cuisine_get_date( $format, $date = null ){

		if( $date == null ){
			$date_string = $post->post_date;

		}else{
			$date_string = date( 'Y-m-d H:i:s', $date );

		}

		$d = mysql2date( 'j F Y', $date_string );
		$date = apply_filters( 'get_the_date', $d, 'j F Y' );
		return $date;
	}




?>