<?php
/**
 * Cuisine Font functions
 * 
 * Handles Google + regular fonts in the pipeline
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 * @since 		0.9
 */


	/**
	 * Get available fonts
	 * @access public
	 * @return Array all fonts available in Cuisine.
	 */
	function cuisine_get_all_fonts(){
		$fonts = array_merge( cuisine_get_google_fonts(), cuisine_get_basic_fonts() );
		asort( $fonts );
		return $fonts;
	}

	/**
	 * Generate a clean font name for CSS
	 * @access public
	 * @param  String $name -> the font name.
	 * @return String -> the sanatized font name.
	 */
	function cuisine_santize_font_name($name){
		$name = str_replace( '+', ' ', $name );
		$val = explode( ':', $name );

		return "'".$val[0]."'";

	}


	/**
	 * Generate the correct import url for google fonts
	 * @access public
	 * @return String if found, else false 
	 */
	function cuisine_get_google_font_url(){
		global $cuisine;
		
		$responds = $cuisine->theme->get_google_font_url();

		if( $responds != '' )
			return $responds;

		return false;
	}


	/**
	 * All Google Fonts Cuisine deems nice enough.
	 * @access public
	 * @return Array key -> value pairs of all fonts.
	 */
	function cuisine_get_google_fonts(){
		$fonts = array(
			'Alice' => 'Alice',
			'Antic' => 'Antic',
			'Ruluko' => 'Rukolo',
			'Marko+One' => 'Marko One',
			'Voltaire' => 'Voltaire',
			'Capriola' => 'Capriola',
			'Advent+Pro' => 'Advent Pro',
			'Ropa+Sans' => 'Ropa Sans',
			'Droid+Sans' => 'Droid Sans',
			'Lobster' => 'Lobster',
			'Open+Sans:400,600,700,300' => 'Open Sans',
			'Stoke:300,400' => 'Stoke',
			'Montserrat:400,700' => 'Montserrat',
			'Gabriela' => 'Gabriela',
			'Lato:300,400,700,900' => 'Lato',
			'Crete+Round' => 'Crete Round',
			'PT+Sans:400,700' => 'PT Sans',
			'Yanone+Kaffeesatz:400,300,700,200' => 'Yanone Kaffeesatz',
			'Dosis:300,400,600,700' => 'Dosis',
			'Cabin:400,500,600,700' => 'Cabin',
			'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
			'Arimo:400,700' => 'Arimo',
			'Josefin+Sans:400,600,700' => 'Josefin Sans',
			'Anton' => 'Anton',
			'Rokkitt:400,700' => 'Rokkitt',
			'Ubuntu+Condensed' => 'Ubuntu Condensed',
			'Droid+Serif:400,700' => 'Droid Serif',
			'Oswald:400,700' => 'Oswald',
			'Raleway:400,600,700' => 'Raleway',
			'Nunito:400,700' => 'Nunito',
			'Merriweather:400,700,900' => 'Merriweather',
			'Pacifico' => 'Pacifico',
			'Bitter:400,700,400italic' => 'Bitter'
		);
		asort( $fonts );
		return $fonts;
	}

	/**
	 * System font returner
	 * @access public
	 * @return Array key -> value pairs of systemfonts:
	 */
	function cuisine_get_basic_fonts(){
		$fonts = array('arial' => 'Arial', 'helvetica' => 'Helvetica', 'georgia' => 'Georgia', 'times' => 'Times', 'Trebuchet MS' => 'Trebuchet MS', 'calibri' => 'Calibri');
		asort( $fonts );
		return $fonts;
	}


	/**
	 * An array of possible fontawesome icons:
	 * @access public
	 * @return Array fontawesome class names.
	 */
	function cuisine_get_fontawesome_icons(){
		$icons = array('glass','music','search','envelope-alt','heart','star','star-empty','user','film','th-large','th','th-list','ok','remove','zoom-in','zoom-out','power-of','signal','gea','trash','home','file-alt','time','road','download-alt','download','upload','inbox','play-circle','rotate-righ','refresh','list-alt','lock','flag','headphones','volume-off','volume-down','volume-up','qrcode','barcode','tag','tags','book','bookmark','print','camera','font','bold','italic','text-height','text-width','align-left','align-center','align-right','align-justify','list','indent-left','indent-right','facetime-video','picture','pencil','map-marker','adjust','tint','edit','share','check','move','step-backward','fast-backward','backward','play','pause','stop','forward','fast-forward','step-forward','eject','chevron-left','chevron-right','plus-sign','minus-sign','remove-sign','ok-sign','question-sign','info-sign','screenshot','remove-circle','ok-circle','ban-circle','arrow-left','arrow-right','arrow-up','arrow-down','mail-forwar','resize-full','resize-small','plus','minus','asterisk','exclamation-sign','gift','leaf','fire','eye-open','eye-close','warning-sign','plane','calendar','random','comment','magnet','chevron-up','chevron-down','retweet','shopping-cart','folder-close','folder-open','resize-vertical','resize-horizontal','bar-chart','twitter-sign','facebook-sign','camera-retro','key','gear','comments','thumbs-up-alt','thumbs-down-alt','star-half','heart-empty','signout','linkedin-sign','pushpin','external-link','signin','trophy','github-sign','upload-alt','lemon','phone','unchecke','bookmark-empty','phone-sign','twitter','facebook','github','unlock','credit-card','rss','hdd','bullhorn','bell','certificate','hand-right','hand-left','hand-up','hand-down','circle-arrow-left','circle-arrow-right','circle-arrow-up','circle-arrow-down','globe','wrench','tasks','filter','briefcase','fullscreen','group','link','cloud','beaker','cut','copy','papercli','save','sign-blank','reorder','list-ul','list-ol','strikethrough','underline','table','magic','truck','pinterest','pinterest-sign','google-plus-sign','google-plus','money','caret-down','caret-up','caret-left','caret-right','columns','sort','sort-down','sort-up','envelope','linkedin','rotate-lef','legal','dashboard','comment-alt','comments-alt','bolt','sitemap','umbrella','paste','lightbulb','exchange','cloud-download','cloud-upload','user-md','stethoscope','suitcase','bell-alt','coffee','food','file-text-alt','building','hospital','ambulance','medkit','fighter-jet','beer','h-sign','plus-sign-alt','double-angle-left','double-angle-right','double-angle-up','double-angle-down','angle-left','angle-right','angle-up','angle-down','desktop','laptop','tablet','mobile-phone','circle-blank','quote-left','quote-right','spinner','circle','mail-repl','github-alt','folder-close-alt','folder-open-alt','expand-alt','collapse-alt','smile','frown','meh','gamepad','keyboard','flag-alt','flag-checkered','terminal','code','reply-all','mail-reply-all','star-half-ful','location-arrow','crop','code-fork','unlink','question','info','exclamation','superscript','subscript','eraser','puzzle-piece','microphone','microphone-off','shield','calendar-empty','fire-extinguisher','rocket','maxcdn','chevron-sign-left','chevron-sign-right','chevron-sign-up','chevron-sign-down','html5','css3','anchor','unlock-alt','bullseye','ellipsis-horizontal','ellipsis-vertical','rss-sign','play-sign','ticket','minus-sign-alt','check-minus','level-up','level-down','check-sign','edit-sign','external-link-sign','share-sign','compass','collapse','collapse-top','expand','eur','gbp','dolla','rupe','ye','renminb','wo','bitcoi','file','file-text','sort-by-alphabet','sort-by-alphabet-alt','sort-by-attributes','sort-by-attributes-alt','sort-by-order','sort-by-order-alt','thumbs-up','thumbs-down','youtube-sign','youtube','xing','xing-sign','youtube-play','dropbox','stackexchange','instagram','flickr','adn','bitbucket','bitbucket-sign','tumblr','tumblr-sign','long-arrow-down','long-arrow-up','long-arrow-left','long-arrow-right','apple','windows','android','linux','dribbble','skype','foursquare','trello','female','male','gittip','sun','moon','archive','bug','vk','weibo','renren');
		asort( $icons );
		return $icons;
	}

?>