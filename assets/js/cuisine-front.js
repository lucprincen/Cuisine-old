jQuery(document).ready(function($){

	if ( $('.cuisine-step').length > 0 ){

		var amount = $('.cuisine-step').length;

		$('.content').append('<div class="step-nav"></div>');


		for( var i = 1; i <= amount; i++ ){

			$('.step-nav').append('<a href="#" class="step-page" id="show-step-'+i+'">Stap '+i+'</a>');
			
			$('#show-step-'+i).bind('click', function(){

				var id = $(this).attr('id').substring(10);
				$('.cuisine-step').hide();
				$('#step-'+id).fadeIn('fast');

			});

		}
		
		$('#step-1').fadeIn('fast');
	}

	if( jQuery('.post-social-counter').length > 0 ){
		
		//count a tweet or a fb share:
		jQuery('.post-social-counter').click(function( e ){
	
			e.preventDefault;
	
			if( ! jQuery(this).hasClass('post-comments') ){
	
				
				var type = 'twitter';
				var count = parseInt( jQuery(this).data('count') );
				var pid = jQuery(this).data('postid');
	
				if( jQuery(this).hasClass('post-fb') ){
					type = 'facebook';
				}
	
	
				var obj = jQuery(this);
				window.open( obj.data('href'), '_blank', 'width=626,height=300' );
	
				var data = {
					action: 'social_count',
					postid: pid,
					type: type,
				};
					
				//post with ajax:
				jQuery.post( ajaxurl, data, function(response) {
	
					if(response != 0 && response != ''){
	
						obj.find('p').html( count + 1 );
					}
	
				});
	
			}
		});
	}

	CuisineImages.loadImages();
	
});


function doSmartEmpty(id, string){
	if($(id).val() == string){
		$(id).val('');
		$(id).focus();
		$(id).bind('blur', function(){
			if($(id).val() == ''){
				$(id).val(string);
			}
		})
	}
}


var body = document.body, timer;
window.addEventListener('scroll', function() {
	
	clearTimeout(timer);
	if(!body.classList.contains('disable-hover')) {
		body.classList.add('disable-hover')
	}
  
	timer = setTimeout(function(){
		body.classList.remove('disable-hover')
	}, 500);

}, false);




// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());