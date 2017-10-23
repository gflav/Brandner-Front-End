jQuery(document).ready(function(){
	jQuery(document).bind('gform_confirmation_loaded', function(event, formId){
	    if(formId == 3) {
	        jQuery('.hidden.quote-form-hidden .col-sm-12.col-lg-5').detach();
	        jQuery('.hidden.quote-form-hidden .col-sm-12.col-lg-7').removeClass('col-lg-7').addClass('col-lg-12');
	    }
	});
	jQuery('body').on('click', '.hidden.quote-form-hidden #gform_confirmation_wrapper_3 .close-window', function(){
		jQuery('.hidden.quote-form-hidden').fadeOut();
		jQuery('.quote-form-hidden').removeClass('open');
	});
})