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
});