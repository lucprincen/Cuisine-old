/**
 * Cuisine's frontend Responsive class
 *
 *
 * @since Cuisine 1.2
 */

    var CuisineResponsive = new function(){

        var element;
        var responsiveNav;
        var location;


        this.init = function( e, l ){

            if( l === undefined ) l = 'body';

            element = jQuery( e );
            location = jQuery( l );

            responsiveNav = element.clone();

            location.prepend( responsiveNav );

            responsiveNav.wrap('<div class="responsive-nav"></div>');

            responsiveNav.prepend( '<h2 class="menu-title">MENU</h2>' );

        }


       this.toggleMenu = function(){

            if( jQuery( '.responsive-nav' ).hasClass('fold-out') ){

                jQuery('.responsive-nav').removeClass('fold-out');


            }else{

                jQuery('.responsive-nav').addClass('fold-out');

            }

       }


    }
