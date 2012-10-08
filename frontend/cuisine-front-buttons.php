<?php

/**
 * Cuisine Front buttons
 * 
 * Handles the button css
 *
 * @author 		Chef du Web
 * @category 	Front
 * @package 	Cuisine
 */


	function cuisine_get_button_icons( $type ){

		if($type == 'white'){

			echo cuisine_get_button_icons_white();

		}else{

			echo cuisine_get_button_icons_black();

		}

	}
	
	function cuisine_get_button_icons_black(){
?>
a.button span.icon.book { background-position: 0 0 }
a.button:hover span.icon.book { background-position: 0 -15px }
a.button span.icon.calendar { background-position: 0 -30px }
a.button:hover span.icon.calendar { background-position: 0 -45px }
a.button span.icon.chat { background-position: 0 -60px }
a.button:hover span.icon.chat { background-position: 0 -75px }
a.button span.icon.check { background-position: 0 -90px }
a.button:hover span.icon.check { background-position: 0 -103px }
a.button span.icon.clock { background-position: 0 -116px }
a.button:hover span.icon.clock { background-position: 0 -131px }
a.button span.icon.cog { background-position: 0 -146px }
a.button:hover span.icon.cog { background-position: 0 -161px }
a.button span.icon.comment { background-position: 0 -176px }
a.button:hover span.icon.comment { background-position: 0 -190px }
a.button span.icon.cross { background-position: 0 -204px }
a.button:hover span.icon.cross { background-position: 0 -219px }
a.button span.icon.downarrow { background-position: 0 -234px }
a.button:hover span.icon.downarrow { background-position: 0 -249px }
a.button span.icon.fork { background-position: 0 -264px }
a.button:hover span.icon.fork { background-position: 0 -279px }
a.button span.icon.heart { background-position: 0 -294px }
a.button:hover span.icon.heart { background-position: 0 -308px }
a.button span.icon.home { background-position: 0 -322px }
a.button:hover span.icon.home { background-position: 0 -337px }
a.button span.icon.key { background-position: 0 -352px }
a.button:hover span.icon.key { background-position: 0 -367px }
a.button span.icon.leftarrow { background-position: 0 -382px }
a.button:hover span.icon.leftarrow { background-position: 0 -397px }
a.button span.icon.lock { background-position: 0 -412px }
a.button:hover span.icon.lock { background-position: 0 -427px }
a.button span.icon.loop { background-position: 0 -442px }
a.button:hover span.icon.loop { background-position: 0 -457px }
a.button span.icon.magnifier { background-position: 0 -472px }
a.button:hover span.icon.magnifier { background-position: 0 -487px }
a.button span.icon.mail { background-position: 0 -502px }
a.button:hover span.icon.mail { background-position: 0 -514px }
a.button span.icon.move { background-position: 0 -526px }
a.button:hover span.icon.move { background-position: 0 -541px }
a.button span.icon.pen { background-position: 0 -556px }
a.button:hover span.icon.pen { background-position: 0 -571px }
a.button span.icon.pinpoint { background-position: 0 -586px }
a.button:hover span.icon.pinpoint { background-position: 0 -601px }
a.button span.icon.plus { background-position: 0 -616px }
a.button:hover span.icon.plus { background-position: 0 -631px }
a.button span.icon.reload { background-position: 0 -646px }
a.button:hover span.icon.reload { background-position: 0 -660px }
a.button span.icon.rightarrow { background-position: 0 -674px }
a.button:hover span.icon.rightarrow { background-position: 0 -689px }
a.button span.icon.rss { background-position: 0 -704px }
a.button:hover span.icon.rss { background-position: 0 -719px }
a.button span.icon.tag { background-position: 0 -734px }
a.button:hover span.icon.tag { background-position: 0 -749px }
a.button span.icon.trash { background-position: 0 -764px }
a.button:hover span.icon.trash { background-position: 0 -779px }
a.button span.icon.unlock { background-position: 0 -794px }
a.button:hover span.icon.unlock { background-position: 0 -809px }
a.button span.icon.uparrow { background-position: 0 -824px }
a.button:hover span.icon.uparrow { background-position: 0 -839px }
a.button span.icon.user { background-position: 0 -854px }
a.button:hover span.icon.user { background-position: 0 -869px }

<?php
	}


	function cuisine_get_button_icons_white(){
?>
a.button:hover span.icon.book { background-position: 0 0 }
a.button span.icon.book { background-position: 0 -15px }
a.button:hover span.icon.calendar { background-position: 0 -30px }
a.button span.icon.calendar { background-position: 0 -45px }
a.button:hover span.icon.chat { background-position: 0 -60px }
a.button span.icon.chat { background-position: 0 -75px }
a.button:hover span.icon.check { background-position: 0 -90px }
a.button span.icon.check { background-position: 0 -103px }
a.button:hover span.icon.clock { background-position: 0 -116px }
a.button span.icon.clock { background-position: 0 -131px }
a.button:hover span.icon.cog { background-position: 0 -146px }
a.button span.icon.cog { background-position: 0 -161px }
a.button:hover span.icon.comment { background-position: 0 -176px }
a.button span.icon.comment { background-position: 0 -190px }
a.button:hover span.icon.cross { background-position: 0 -204px }
a.button span.icon.cross { background-position: 0 -219px }
a.button:hover span.icon.downarrow { background-position: 0 -234px }
a.button span.icon.downarrow { background-position: 0 -249px }
a.button:hover span.icon.fork { background-position: 0 -264px }
a.button span.icon.fork { background-position: 0 -279px }
a.button:hover span.icon.heart { background-position: 0 -294px }
a.button span.icon.heart { background-position: 0 -308px }
a.button:hover span.icon.home { background-position: 0 -322px }
a.button span.icon.home { background-position: 0 -337px }
a.button:hover span.icon.key { background-position: 0 -352px }
a.button span.icon.key { background-position: 0 -367px }
a.button:hover span.icon.leftarrow { background-position: 0 -382px }
a.button span.icon.leftarrow { background-position: 0 -397px }
a.button:hover span.icon.lock { background-position: 0 -412px }
a.button span.icon.lock { background-position: 0 -427px }
a.button:hover span.icon.loop { background-position: 0 -442px }
a.button span.icon.loop { background-position: 0 -457px }
a.button:hover span.icon.magnifier { background-position: 0 -472px }
a.button span.icon.magnifier { background-position: 0 -487px }
a.button:hover span.icon.mail { background-position: 0 -502px }
a.button span.icon.mail { background-position: 0 -514px }
a.button:hover span.icon.move { background-position: 0 -526px }
a.button span.icon.move { background-position: 0 -541px }
a.button:hover span.icon.pen { background-position: 0 -556px }
a.button span.icon.pen { background-position: 0 -571px }
a.button:hover span.icon.pinpoint { background-position: 0 -586px }
a.button span.icon.pinpoint { background-position: 0 -601px }
a.button:hover span.icon.plus { background-position: 0 -616px }
a.button span.icon.plus { background-position: 0 -631px }
a.button:hover span.icon.reload { background-position: 0 -646px }
a.button span.icon.reload { background-position: 0 -660px }
a.button:hover span.icon.rightarrow { background-position: 0 -674px }
a.button span.icon.rightarrow { background-position: 0 -689px }
a.button:hover span.icon.rss { background-position: 0 -704px }
a.button span.icon.rss { background-position: 0 -719px }
a.button:hover span.icon.tag { background-position: 0 -734px }
a.button span.icon.tag { background-position: 0 -749px }
a.button:hover span.icon.trash { background-position: 0 -764px }
a.button span.icon.trash { background-position: 0 -779px }
a.button:hover span.icon.unlock { background-position: 0 -794px }
a.button span.icon.unlock { background-position: 0 -809px }
a.button:hover span.icon.uparrow { background-position: 0 -824px }
a.button span.icon.uparrow { background-position: 0 -839px }
a.button:hover span.icon.user { background-position: 0 -854px }
a.button span.icon.user { background-position: 0 -869px }

<?php 
	}


?>