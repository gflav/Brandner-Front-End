jQuery(document).ready(function(){
 	var slides = jQuery('#brandnerdesign-home-slideshow-pager .slick-dots li').length;
    var i = 0;
    setInterval(function(){
        if(i <= slides){
            if(!jQuery('#mobile-menu-button-container').hasClass('active'))
                jQuery('#brandnerdesign-home-slideshow-pager .slick-dots li:eq('+i+')').click();
        }else{
            i = 0;
            if(!jQuery('#mobile-menu-button-container').hasClass('active'))
                jQuery('#brandnerdesign-home-slideshow-pager .slick-dots li:eq('+i+')').click();
        }
        i++;
    },5000);	
});