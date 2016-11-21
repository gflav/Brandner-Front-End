(function($){
  
  /**
   * Modal Gallery
   * Modal Video
   **/
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    source: null,
    template: null,
    
    init: function() {
      
      this.setup();
      this.listen();
      
    },
    
    setup: function() {
      
      this.templates();
      
    },
    
    templates: function() {
      this.source = $('#template-modal-gallery').html();
      this.template = Handlebars.compile(this.source);
    },
    
    listen: function() {
      
      $(document).on('modal.close', function($evt, $name) {
        if($name == 'gallery') {
          $modal.remove('gallery');
        }
      });
      
      $('.trigger-modal-video').click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        if($this.data('loading')) {
          return;
        }
        $this.data('loading', 1);
        var $items = [{
          type: 'video',
          embed: $tbo.convertMedia($this.attr('data-video'), {autoplay: 1})
        }];
        $modal.open('gallery', {html: $controller.template({items: $items, count: $items.length})});
        $this.data('loading', 0);
      });
      
      $('.btn-gallery,.trigger-gallery').click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        if($this.data('loading')) {
          return;
        }
        $this.data('loading', 1);
        var $title = $this.attr('title');
        $.ajax({
          url: $this.attr('data-route')
        }).done(function($response) {
          
          $this.data('loading', 0);
          
          var $modal_name = 'gallery';
          var $items = $controller.parseGalleryItems($response);
          
          $modal.open($modal_name, {html: $controller.template({title: $title, items: $items, count: $items.length})});
          
          var $gallery = $('.modal-gallery', $modal.getInstance($modal_name).instance);
          
          var $gallery_items = $('.modal-gallery-item', $gallery);
          $gallery_items.css('visibility', 'hidden');
          
          // loader
          $gallery.css('height', '120px');
          $gallery.prepend('<div class="brandner-loader"></div>');
          
          $gallery.imagesLoaded().always(function() {

            // remove loader
            $gallery.css('height', 'auto');
            $('.brandner-loader').remove();
            
            $gallery.slick({
              infinite: true,
              autoplay:false,
              autoplaySpeed: brandnerdesign.setting.sliderSpeed,
              dots: false,
              slidesToShow: 1,
              adaptiveHeight: true,
              centerMode: true,
              centerPadding: "0px",
              cssEase: brandnerdesign.setting.cssEase,
              nextArrow: '.btn-next',
              prevArrow: '.btn-prev',
              lazyLoad: 'ondemand',
            });
            
            $gallery_items.css('visibility', 'visible');

            // update index number
            $gallery.on('afterChange', function($slick, $currentSlide) {
              var $idx = $currentSlide.currentSlide + 1;
              $('.modal-instance-gallery .current-item').text($idx);
            });
            
            // resize width
            /*
            $gallery.on('afterChange', function() {
              var $image = $('.modal-instance-gallery .slick-active img');
              $('.modal-instance-gallery .modal-gallery').css('width', $image.width()+'px');
              var $outerWidth = $image.width()+60;
              $('.modal-instance-gallery .modal-content').css('width', $outerWidth+'px');
            });
            */
            
          });

        });
      });
      
    },
    
    // helper functions
    
    parseGalleryItems: function($post) {
      
      var $items = [];
      
      if($post) {
        
        if($post.acf && $post.acf.post_media && $post.acf.post_media.length > 0) {
          
          var $media = $post.acf.post_media;
          $.each($media, function($idx, $item) {
            
            if($item.bd_img_gal && $item.bd_img_gal.length > 0) {
              
              $.each($item.bd_img_gal, function($i, $v) {
                $items.push({
                  type: 'image',
                  url: $v.sizes['viewer-large']
                });
              });
              
            }
            
            if($item.vimeo_vids && $item.vimeo_vids != '0' && $item.vimeo_vids.length > 0) {
              
              $.each($item.vimeo_vids, function($i, $v) {
                $items.push({
                  type: 'video',
                  embed: $tbo.convertMedia($v.vimeo_url)
                });
              });
              
            }
            
          });
          
        }
        
        // if gallery empty, use featured image
        if($items.length === 0 && $post.better_featured_image && $post.better_featured_image.media_details) {
          
          if($post.better_featured_image.media_details.sizes && $post.better_featured_image.media_details.sizes['viewer-large']) {
            $items.push({
              type: 'image',
              url: $post.better_featured_image.media_details.sizes['viewer-large'].source_url
            });
            // TODO: find out why 1 image doesn't work
            $items.push({
              type: 'image',
              url: $post.better_featured_image.media_details.sizes['viewer-large'].source_url
            });
          } else {
            // use original
            $items.push({
              type: 'image',
              url: $post.better_featured_image.source_url
            });
            // TODO:
            $items.push({
              type: 'image',
              url: $post.better_featured_image.source_url
            });
          }
          
        }
        
      }
      
      return $items;
      
    }
    
  };
  
})(jQuery);