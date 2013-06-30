<?php


	function cuisine_get_all_fonts(){
		$fonts = array_merge( cuisine_get_google_fonts(), cuisine_get_basic_fonts() );
		asort( $fonts );
		return $fonts;
	}

	function cuisine_get_google_fonts(){
		$fonts = array('Alice' => 'Alice', 'Antic' => 'Antic', 'Ruluko' => 'Rukolo', 'Marko+One' => 'Marko One', 'Voltaire' => 'Voltaire', 'Capriola' => 'Capriola', 'Advent+Pro' => 'Advent Pro', 'Ropa+Sans' => 'Ropa Sans', 'Droid+Sans' => 'Droid Sans', 'Lobster' => 'Lobster', 'Open+Sans:400,600,700,300' => 'Open Sans', 'Stoke:300,400' => 'Stoke', 'Montserrat:400,700' => 'Montserrat', 'Gabriela' => 'Gabriela', 'Lato:300,400,700,900' => 'Lato', 'Crete+Round' => 'Crete Round', 'PT+Sans:400,700' => 'PT Sans');
		asort( $fonts );
		return $fonts;
	}

	function cuisine_get_basic_fonts(){
		$fonts = array('arial' => 'Arial', 'helvetica' => 'Helvetica', 'georgia' => 'Georgia', 'times' => 'Times', 'Trebuchet MS' => 'Trebuchet MS', 'calibri' => 'Calibri');
		asort( $fonts );
		return $fonts;
	}

	/**
	*	Get's the propper Google font name
	*/
	function cuisine_santize_font_name($name){
		$name = str_replace( '+', ' ', $name );
		$val = explode( ':', $name );

		return "'".$val[0]."'";

	}


?>