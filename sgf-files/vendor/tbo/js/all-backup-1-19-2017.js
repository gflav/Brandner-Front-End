(function($){

	var brandnerdesign = {
		
		// global value
		
		KEYCODE_ESC: 27,
		
		KEYWORD_MIN_LENGTH: 3,
    
    setting: {
      sliderSpeed: 5000,
      cssEase: "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
      jsEasing: 'easeInOutCubic'
    },
		
		// init

		init : function() {

			var width = window.innerWidth || document.documentElement.clientWidth;
			brandnerdesign.setMode(width);

			brandnerdesign.UI = {
				window					: $(window),
				html					: $("html"),
				body					: $("body"),
				header					: $("header"),
				homeSlideShow			: $("#brandnerdesign-home-slideshow"),
				homeSlideShowUL			: $("#brandnerdesign-home-slideshow-ul"),
				homeSlideShowPager		: $("#brandnerdesign-home-slideshow-pager"),
				mobileNavBtn 			: $("#mobile-menu-button-container"),
				headerLinks 			: $("#brandnerdesign-header-links")
			};

			brandnerdesign.UI.window.resize(function() {
				brandnerdesign.resizeHandler();
			});

			if(brandnerdesign.UI.homeSlideShow.length){
				brandnerdesign.initHomeSlideShow();
			}

			brandnerdesign.initMobileNav();
			brandnerdesign.initForms();
		},


		initMobileNav : function() {
      
      var $mobile = $('#dl-menu');
      $mobile.dlmenu();

		},

		initForms : function(){

			$(document).on('focus', '.modal-instance-quote input', function($evt) {
				$(this).parent().addClass("focus");
			});

			$(document).on('blur', '.modal-instance-quote input', function($evt) {
				$(this).parent().removeClass("focus");
			});

		},


		initHomeSlideShow : function(){
			brandnerdesign.UI.homeSlideShowUL.slick({
				infinite: true,
				autoplay:false,
				autoplaySpeed: brandnerdesign.setting.sliderSpeed,
				arrows: false,
				dots: true,
				adaptiveHeight: true,
				cssEase: brandnerdesign.setting.cssEase,
				appendDots : brandnerdesign.UI.homeSlideShowPager,
				customPaging: function(slider, i) {
					return $('<span></span>');
				},

			});

		},


		resizeHandler : function() {
			width = window.innerWidth || document.documentElement.clientWidth;
			brandnerdesign.setMode(width);
      if($tbo.getMode() == 'desktop') {
        brandnerdesign.UI.mobileNavBtn.removeClass('open');
        brandnerdesign.UI.body.removeClass("mobile-menu-open");
      }
		},

		setMode : function(w) {
			if(w >= 768){
				brandnerdesign.mode = "desktop";
			}else{
				brandnerdesign.mode = "mobile";
			}
		}
};

$(document).ready(function () {
	brandnerdesign.init();
});

window.brandnerdesign = brandnerdesign;

})(jQuery);

(function($){
  
  // handlebar templates
  var $templates = {
    share: {
      source: null,
      template: null
    },
    print: {
      source: null,
      template: null
    }
  };
  
  window.$tbo = {
    
    onceTimeout: {},
    
    getMode: function() {
      var $mode = 'mobile';
      var $width = $(window).width();
      if($width >= 1225) {
        $mode = 'desktop';
      } else if($width >= 768) {
        $mode = 'tablet';
      } else {
        $mode = 'mobile';
      }
      return $mode;
    },
    
    api: function($options) {
      
      return $.ajax({
        url: $options.url,
        data: $options.data
      });
    
    },
    
    convertMedia: function(html, $o){
      
      var $options = $.extend({}, {
        autoplay: 0
      }, $o);
      
        var pattern1 = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
        var pattern2 = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
        var pattern3 = /([-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?(?:jpg|jpeg|gif|png))/gi;
        if(pattern1.test(html)){
           var replacement = '<iframe width="420" height="345" src="//player.vimeo.com/video/$1?autoplay='+$options.autoplay+'&badge=0&byline=0&portait=0&title=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
           var html = html.replace(pattern1, replacement);
        }
        if(pattern2.test(html)){
              var replacement = '<iframe width="420" height="345" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>';
              var html = html.replace(pattern2, replacement);
        } 
        if(pattern3.test(html)){
            var replacement = '<a href="$1" target="_blank"><img class="sml" src="$1" /></a><br />';
            var html = html.replace(pattern3, replacement);
        }          
        return html;
    },
    
    once: function($func, $realm, $delay) {
      
      if (!$realm) {
        $realm = 'tbo';
      }
      
      if (!$delay) {
        $delay = 250;
      }
      
      var $timeout = this.onceTimeout[$realm];
      
      if ($timeout) {
        clearTimeout($timeout);
      }
      
      this.onceTimeout[$realm] = setTimeout($.proxy($func, this), $delay);
      
    }
    
  };
  
  $(document).ready(function() {
    init();
  });
  
  function init() {
    setup();
    listen();
  }
  
  function setup() {
    templates();
    helpers();
  }
  
  function templates() {
    // share template
    $templates.share.source = $('#template-share-modal').html();
    $templates.share.template = Handlebars.compile($templates.share.source);
    // print template
    $templates.print.source = $('#template-print-iframe').html();
    $templates.print.template = Handlebars.compile($templates.print.source);
  }
  
  function helpers() {
    Handlebars.registerHelper('if_eq', function(a, b, opts) {
      if (a === b) {
        return opts.fn(this);
      }
      return opts.inverse(this);
    });
    Handlebars.registerHelper('if_gt', function(a, b, opts) {
      if (a > b) {
        return opts.fn(this);
      }
      return opts.inverse(this);
    });
  }
  
  function listen() {
    
    $(document).on('click', '#wp-admin-bar-tbo-regenerate-thumbs a', function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $data = {
        action: 'regenerate',
      };
      $.post($this.attr('href'), $data).done(function($response) {
        console.log('Regeneration Completed, Doctor.', $response);
      });
    });
    
    $('.btn-disabled').click(function($evt) {
      $evt.preventDefault();
    });
    
    // NOTE: needs CORS to work if not same domain
    $('.btn-print').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $url = $this.attr('href') || $this.attr('data-print');
      if ($url) {
        // add print variable
        if($url.indexOf('?') === -1) {
          $url += '?';
        } else {
          $url += '&';
        }
        $url += 'print=true';
        $url += '&cid='+Math.round(new Date().getTime() / 1000);
        // print url
        var $iframe_id = 'print-iframe';
        var $iframe = $('#'+$iframe_id);
        if($iframe.length > 0) {
          $iframe.remove();
        }
        $iframe = $($templates.print.template({id: $iframe_id}));
        $('body').append($iframe);
        $iframe.attr('src', $url);
        $iframe.load(function() {
          document.getElementById($iframe_id).contentWindow.print();
        });
      } else {
        // print page
        window.print();
      }
    });
    
    /*
     *old way using modal
    $('.btn-share').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $url = $this.attr('href');
      var $modal_name = 'share-'+btoa($url);
      if ($this.data('initialized')) {
        $modal.open($modal_name);
      } else {
        $this.data('initialized', 1);
        $modal.open($modal_name, {html: $templates.share.template()}).done(function() {
          $(".share-icons", $modal.getInstance('share')).jsSocials({
            url: $url,
            showLabel: false,
            showCount: false,
            shares: ["email", "twitter", "facebook", "pinterest"]
          });
        });
      }
    });*/
    
    /*
    $('.btn-share').hover(
      function() {
        var $this = $(this);
        var $url = $this.attr('href');
        $this.tooltip({
          placement: 'bottom',
          trigger: 'manual',
          html: true,
          title: '<div class="share-icons"></div>'
        }).tooltip('show');
        $(".share-icons").jsSocials({
          url: $url,
          showLabel: false,
          showCount: false,
          shares: ["email", "twitter", "facebook", "pinterest"]
        });
        setTimeout(function() {
          $this.tooltip('hide');
        }, 3000);
      },
      function() {
        
      }
    );
    */
    
    $('.btn-share').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $url = $this.attr('href');
      $this.tooltip({
        placement: 'bottom',
        trigger: 'manual',
        html: true,
        title: '<div class="share-icons"></div>'
      }).tooltip('show');
      $(".share-icons").jsSocials({
        url: $url,
        showLabel: false,
        showCount: false,
        shares: ["email", "twitter", "facebook", "pinterest"]
      });
      setTimeout(function() {
        $this.tooltip('hide');
      }, 5000);
    });
    
    // capability pillar
    $('.brandnerdesign-capabilities-pillar').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $post_id = $this.attr('data-post-id');
      var $href = $this.attr('data-href');
      if ($href) {
        var $modal_name = 'pillar-' + $post_id;
        $modal.open($modal_name, {ajax: true, url: '/wp-json/wp/v2/capability/'+$post_id, data: {id: $post_id}, selector: '#site-content > div', css_class: 'modal-gray modal-capability'});
      }
    });
    
    // anchors to slide down
    $('a[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        var $top = target.offset().top - ($('#wpadminbar').outerHeight(true) + $('#brandnerdesign-header-content').outerHeight(true));
        if (target.length) {
          $('html,body').animate({
            scrollTop: $top
          },
          {
            duration: 1000,
            easing: brandnerdesign.setting.jsEasing
          });
          return false;
        }
      }
    });
    
    // scroll to top / .trigger-top
    $(document).on('click', '.trigger-top', function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $m = $this.closest('.modal');
      if($m.length > 0) {
        // modal
        $m.animate({
          scrollTop: 0
        },
        {
          duration: 500,
          easing: brandnerdesign.setting.jsEasing
        });
      } else {
        // normal page
        $('html,body').animate({
          scrollTop: 0
        },
        {
          duration: 1000,
          easing: brandnerdesign.setting.jsEasing
        }); 
      }
    });
    
  }
  
  
  $('.ginput_container input, .ginput_container textarea').focusin(function($evt) {
    $evt.preventDefault();
    var $this = $(this);
    var $parent = $this.closest('li');
	$parent.addClass('active');
  });
  $('.ginput_container input, .ginput_container textarea').focusout(function($evt) {
    $evt.preventDefault();
    var $this = $(this);
    var $parent = $this.closest('li');
	var $value = $this.val();
	if($value === '') {
		$parent.removeClass('active');
	}
  });
  

})(jQuery);
(function($){
  
  var $selector = '.product-gallery';
  var $container = null;
  
  $(document).ready(function() {
    init();
  });
  
  function init() {
    $container = $($selector);
    if ($container.length > 0) {
      setup();
      listen();
    }
  }
  
  function setup() {
    
    $container.slick({
      infinite: true,
      autoplay:true,
      autoplaySpeed: brandnerdesign.setting.sliderSpeed,
      arrows: false,
      dots: false,
      cssEase: brandnerdesign.setting.cssEase,
      adaptiveHeight: true,
    });
    
    // set first active
    $('.product-thumbs a').first().toggleClass('active');
    
  }
  
  function listen() {
    
    // events
    
    // set thumbs active
    $container.on('afterChange', function($slick, $currentSlide) {
      var $idx = $currentSlide.currentSlide;
      $('.product-thumbs a').removeClass('active');
      $('.product-thumbs li:eq('+$idx+') a').addClass('active');
    });
    
    // custom pager clicks
    
    $('.product-thumbs a').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      $container.slick('slickGoTo', $this.parent().index());
    });
    
  }

})(jQuery);
(function($){
  
  $(document).ready(function() {
    $modal.init();
  });
  
  var $modal = {
    
    source: null,
    template: null,
    
    // TODO: phase out instance because array stores the object
    instance: null, // current instance
    instances: [],
    
    init: function() {
      this.setup();
      this.listen();
    },
    
    setup: function() {
      this.templates();
    },
    
    templates: function() {
      this.source = $('#template-modal').html();
      this.template = Handlebars.compile(this.source);
    },
    
    listen: function() {
      
      // close most recent modal
      $(document).keyup(function($evt) {
        if($evt.keyCode == brandnerdesign.KEYCODE_ESC) {
          $modal.close();
        }
      });
      
      // NOTE: need to use default click outside to close
      //       putting your own doesn't work
      
      // generic way to trigger a modal
      $(document).on('click', 'li.trigger-modal a,a.trigger-modal,.terms_chkbox', function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        var $modal_name = $.md5($this.attr('href'));
        $modal.open($modal_name, {url: $this.attr('href'), selector: '#site-content', css_class: 'modal-instance-simple', footer: '<a href="#" class="trigger-top">Back to top</a>'});
      });
      
      $(document).on('click', '.trigger-modal-close', function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        $this.closest('.modal').modal('hide');
      });
      
      $(document).on('show.bs.modal', '.modal', function() {
        var $this = $(this);
        setTimeout(function() {
          $this.addClass('animate');
        }, 100);
      });

      $(document).on('hide.bs.modal', '.modal', function() {
        var $this = $(this);
        $modal.close($this);
      });
      
    },

    addInstance: function($name, $instance) {
      this.instances.push({
        name: $name,
        instance: $instance
      });
      return this;
    },
    
    exists: function($name) {
      return (this.getInstance($name) !== false);
    },
    
    getInstance: function($name) {
      if ($name) {
        var $instances = $.grep(this.instances, function($inst) {
          return $inst.name === $name;
        });
        return $instances.length > 0 ? $instances.pop() : false;
      }
      return this.instance;
    },
    
    popInstance: function($name) {
      var $instance = null;
      this.instances = $.grep(this.instances, function($inst) {
        if($name === $inst.name) {
          $instance = $inst.instance;
          return false;
        } else {
          return true;
        }
      });
      return $instance;
    },

    open: function($name, $o) {

      var $deferred = $.Deferred();
      
      if (!this.exists($name)) {
        
        // defaults
        
        var $defaults = {
          ajax: false,
          data: null,
          url: null,
          html: null,
          selector: null,
          css_class: '',
          footer: '',
          cache: true
        };
        
        var $options = $.extend({}, $defaults, $o);
        
        // create

        $('body').append(this.template({name: $name, css_class: $options.css_class}));
        
        this.instance = $('.modal[data-instance="'+$name+'"]');
        
        if($options.ajax) {
          
          $.get($options.url, $options.data).done(function($response) {
            if($response.content.rendered) {
              $('.modal-inner', $modal.instance).html($response.content.rendered);
              $modal.instance.modal('show');
              $deferred.resolve();
            }
          });
          
        }
        
        if ($options.url && !$options.ajax) {
          var $url = $options.url;
          if(!$options.cache) {
            if($url.indexOf('?') !== -1) {
              $url += '&cid=' + (new Date().getTime());
            }
          }
          if ($options.selector) {
            $url += ' ' + $options.selector;
          }
          $('.modal-inner', this.instance).load($url, function($response) {
            var $html = $.parseHTML($response);
            var $content = $('#site-content', $html);
            if($content.length > 0) {
              // remove #site-content
              $('.modal-inner', $modal.instance).html($content.html());
            }
            if($options.footer) {
              $('.modal-inner', $modal.instance).append($options.footer);
            }
            $modal.instance.modal('show');
            $deferred.resolve();
          });
        }
        
        if ($options.html) {
          $('.modal-inner', this.instance).html($options.html);
          this.instance.modal('show');
          $deferred.resolve();
        }
        
        this.addInstance($name, this.instance);
        
      } else {
        
        // reopen
        
        this.instance = this.getInstance($name).instance;
        this.instance.modal('show');
        $deferred.resolve();
        
      }

      return $deferred.promise();
      
    },
    
    close: function($name) {
      var $instance = null;
      if($.type($name) == 'object') {
        $instance = {
          name: $name.attr('data-instance'),
          instance: $name,
          closed: true
        };
      } else if ($name && this.exists($name)) {
        $instance = this.getInstance($name);
      } else {
        // close last instance
        $instance = this.getInstance();
      }
      if($instance) {
        if(!$instance.closed) {
          $instance.instance.modal('hide');
        }
        $instance.instance.removeClass('animate').trigger('modal.close', [$instance.name, $instance.instance]);
      }
      return this;
    },
    
    // remove from $modal instance list, and dom
    remove: function($name) {
      if(this.exists($name)) {
        var $instance = this.popInstance($name);
        if($instance) {
          $instance.remove();  
        }
      }
      return this;
    }
    
  };
  
  window.$modal = $modal;
  
})(jQuery);
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
(function($){
  
  var $trigger_selector = '.trigger-modal-search a';
  var $trigger = null;
  
  var $search = {
    
    instance:null,
    
    execute: function($options) {
      
      var $deferred = $.Deferred();
      
      var $keyword = $options.keyword;
      var $data = {s: $keyword, format: 'json'};
      var $url = $options.url ? $options.url : '/';
      
      if($options.data) {
        $data = $options.data;
      }
      
      this.abort();
      this.instance = $.ajax({
        url: $url,
        data: $data
      }).done(function($response) {
        var $html = $.parseHTML($response, null);
        var $dom = $($html);
        if ($dom && $dom.length > 0) {
          var $results = $dom.filter('.search-results-container');
          if ($results && $results.length > 0) {
            $('.search-results-container').html($results.html()).removeClass('loading').show(); 
          }
        }
      });
      
      return this;
    
    },
    
    reset: function() {
      $('search-form [type="search"]').val('');
      $('.search-results-container').html('');
    },
    
    abort: function() {
      if (this.instance) {
        this.instance.abort();
        this.instance = null;
      }
      return this;
    }
    
  };
  
  $(document).ready(function() {
    init();
  });
  
  function init() {
    listen();
  }
  
  function listen() {
    
    $($trigger_selector).click(function($evt) {
      $evt.preventDefault();
      $modal.open('search', {url: $(this).attr('href'), css_class: 'modal-type-search'});
    });
    
    $(document).on('submit', '.search-form', function($evt) {
      $evt.preventDefault();
      var $keyword = $('[type="search"]', $(this));
      var $value = $keyword.val();
      $search.execute({keyword: $value});
    });
    
    $(document).on('keyup', '.search-form [type="search"]', function($evt) {
      // escape to close modal
      if ($evt.keyCode == brandnerdesign.KEYCODE_ESC) {
        $modal.close('search');
      }
      var $this = $(this);
      var $value = $this.val();
      if ($value.length >= brandnerdesign.KEYWORD_MIN_LENGTH) {
        $search.execute({keyword: $value});
      } else if ($value === "") {
        // reset
        $search.reset();
      }
    });
    
    $(document).on('click', '.nav-links a', function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $parts = $this.attr('href').split('?');
      $('search-results-container').addClass('loading').fadeOut('slow'); // TODO: css animation
      $search.execute({url: $parts[0], data: $parts[1]});
    });
    
  }
  
})(jQuery);
(function($){
  
  $(document).ready(function() {
    $cart.init();
  });
  
  var $cart = {
    
    items: 0,
    count: 0,
    
    init: function() {
      this.setup();
      this.listen();
      this.render();
    },
    
    setup: function() {
      
      if($cart_data) {
        this.count = $cart_data.count;
      }
      
    },
    
    listen: function() {
      
      // buy now
      $('.btn-buy-now').click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        var $pid = $this.attr('data-product-id');
        var $checkout_url = $this.attr('data-checkout-url');
        // add to cart
        $.post('/cart/?add-to-cart='+$pid).done(function() {
          // go to checkout
          window.location.href = $checkout_url;
        });
      });
      
      // add to cart
      $('.btn-cart-add').click(function($evt) {
        $evt.preventDefault();
        var $pid = $(this).attr('data-product-id');
        $modal.open('cart', {url: '/cart/?add-to-cart='+$pid, selector: '#site-content .woocommerce', cache:false});
      });
      
      // view cart
      $('a.icon-cart,li.icon-cart a').click(function($evt) {
        $evt.preventDefault();
        $modal.open('cart', {url: '/cart/', selector: '#site-content .woocommerce'});
      });
      
      $(document).on('click', '.btn-continue-shopping', function($evt) {
        if($(this).closest('.modal').length > 0) {
          $evt.preventDefault();
          $modal.close('cart').remove('cart');
        }
      });
      
      $(document).on('modal.close', function($evt, $name) {
        if($name == 'cart') {
          $modal.remove('cart');
        }
      });
      
    },
    
    render: function() {
      
      if (this.count && this.count > 0) {
        
        var $source = $('#template-cart-items').html();
        var $template = Handlebars.compile($source);
        $('.icon-cart').parent().prepend($template({count: this.count}));
        
      }
      
    }
    
  };
  
  window.$cart = $cart;
  
})(jQuery);
(function($){
  
  var $url = null;
  var $container = null;
  
  // handlebar templates
  var $templates = {
    like: {
      source: null,
      template: null
    }
  };
  
  $(document).ready(function() {
    init();
  });
  
  function init() {
    
    $container = $('.icon-like');
    if ($container.length > 0) {
      setup();
      listen();
    }
    
  }
  
  function setup() {
    
    templates();
    
    // TODO:
    //$url = $container.attr('href') || window.location.href;
    $url = '//brandnerdesign.com';
    
    $.getScript('//connect.facebook.net/en_US/sdk.js', function() {
      FB.init({
        appId: '1095794757125904',
        version: 'v2.6',
        xfbml: 1,
        status: true,
        cookie: true,
      });
      FB.getLoginStatus(function() {
        listen_facebook();
      });
    });
    
  }
  
  function templates() {
    $templates.like.source = $('#template-like-button').html();
    $templates.like.template = Handlebars.compile($templates.like.source);
  }
  
  function listen() {
    
    // NOTE: FB will not be defined at this point
    
    $container.on('show.bs.tooltip', function() {
      // close when body clicked
      $(document).on('click.tooltip-fb', 'body', function() {
        $container.tooltip('hide');
        $(document).off('click.tooltip-fb', 'body');
      });
      // close on timeout
      setTimeout(function() {
        $container.tooltip('hide');
      }, 5000);
    });
    
    $container.on('hidden.bs.tooltip', function() {
      
    });
    
    $container.click(function($evt) {
      $evt.preventDefault();
      $evt.stopPropagation();
      var $this = $(this);
      if(!$this.data('parsed')) {
        $this.tooltip({
          placement: 'bottom',
          trigger: 'manual',
          html: true,
          title: $templates.like.template({url: $url})
        }).tooltip('show');
        FB.XFBML.parse();
        $this.data('parsed');
      } else {
        $this.tooltip('show');  
      }      
    });
  }
  
  function listen_facebook() {
    
    // liked
    FB.Event.subscribe('edge.create', function() {
      $container.addClass('active');
      $container.tooltip('hide');
    });
    
    // unliked
    FB.Event.subscribe('edge.remove', function() {
      $container.removeClass('active');
      $container.tooltip('hide');
    });
    
  }
  
})(jQuery);
(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    selector: '#form-quote',
    el: null,
    
    trigger_selector: '.btn-quote',
    trigger: null,
    
    source: null,
    template: null,
    
    init: function() {
      
      this.trigger = $(this.trigger_selector);
      if(this.trigger.length > 0) {
      
        this.setup();
        this.listen();
        
      }
      
    },
    
    setup: function() {
      this.normalize();
      this.templates();
    },
    
    normalize: function() {
      
      // for older devices
      if (typeof String.prototype.endsWith !== 'function') {
        String.prototype.endsWith = function(suffix) {
          return this.indexOf(suffix, this.length - suffix.length) !== -1;
        };
      }
      
    },
    
    templates: function() {
      // form
      this.source = $('#template-get-quote-form').html();
      this.template = Handlebars.compile(this.source);
      // msg
      this.msg = Handlebars.compile($('#template-quote-msg-default').html());
    },
    
    listen: function() {
      
      // trigger the quote form
      this.trigger.click(function($evt) {
        
        $evt.preventDefault();
        
        if($modal.exists('quote')) {
          $modal.open('quote');
        } else {
          // create form
          var $title = $('h1').text();
          var $body = $('.post-content').html();
          var $featured_image = $('.product-image:first-child').html();
          $modal.open('quote', {html: $controller.template({title: $title, body: $body, featured_image: $featured_image})}).done(function() {
            $controller.unpackForm();
            var $item = '<strong>' + $title + '</strong>';
            var $message = $($controller.wrapSelector('.textarea'));
            if($controller.get('message').val() === "") {
              // first timer
              $message.html($controller.msg({product: $item}));
            } else if($controller.get('message').val().indexOf($title) === -1) {
              // insert & update
              $message.html($controller.parseMsg($controller.get('message').val().trim(), $item));
            } else {
              // existing
              $message.html($controller.get('message').val()); 
            }
            $message.attr('contenteditable', true);
          });
        }
        
      });
      
      // sync textarea
      // NOTE: do when form saves, or closes
      
      // save button
      $(document).on('click', this.wrapSelector('.btn-submit'), function($evt) {
        $evt.preventDefault();
        $($controller.selector).submit();
      });
      
      // save & continue
      $(document).on('click', this.wrapSelector('.btn-save-continue'), function($evt) {
        $evt.preventDefault();
        $controller.syncEditable().storeForm();
        $modal.close('quote');
      });

      // form submit to database
      $(document).on('submit', this.selector, function($evt) {
        $evt.preventDefault();
        // click & in case enter was hit
        $controller.syncEditable();
        // simple validate
        if($controller.get('name').val() === "" || $controller.get('email').val() === "") {
          return;
        }
        // ajax post
        $controller.submit();
        // clear the storage
        $controller.clearForm();
        // close modal
        $modal.close('quote').remove('quote');
      });
      
    },
    
    submit: function() {
      
      var $gravity = $('.quote-form-hidden form');
      
      // map fields
      $('[name="input_1"]', $gravity).val($controller.get('name').val());
      $('[name="input_2"]', $gravity).val($controller.get('email').val());
      $('[name="input_3"]', $gravity).val($controller.get('phone').val());
      $('[name="input_4"]', $gravity).val($controller.get('message').val()); // TODO: strip html
      
      // submit
      $gravity.submit();
      
    },
    
    // helper functions
    
    parseMsg: function($msg, $item) {
      // check for period within tags
      if($msg.indexOf('.</strong>') !== -1) {
        $msg =$msg.replace('.</strong>', '</strong>.');
      }
      if($msg.endsWith('.')) {
        return $msg + " I'd also like a quote on " + $item + '.';
      } else {
        return $msg + ', ' + $item;
      }
    },
    
    syncEditable: function() {
      $(this.wrapSelector('.textarea')).each(function() {
        var $this = $(this);
        $controller.get($this.attr('data-name')).val($this.html());
      });
      return this;
    },
    
    unpackForm: function() {
      var $data = $.localStorage.get(this.selector);
      if($data && $.type($data) == 'array' && $data.length > 0) {
        $.each($data, function($idx, $field) {
          $controller.get($field.name).val($field.value);
        });
      }
    },
    
    storeForm: function() {
      var $data = $(this.selector).serializeArray();
      $.localStorage.set(this.selector, $data);
      return this;
    },
    
    clearForm: function() {
      $.localStorage.remove(this.selector);
      return this;
    },
    
    // get the html field
    get: function($name) {
      return $(this.wrapSelector('[name="'+$name+'"]'));
    },
    
    // prefixes the parent selector
    wrapSelector: function($selector) {
      if($selector) {
        return this.selector + ' ' + $selector;  
      }
      return this.selector;
    }
    
  };
  
})(jQuery);
(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    el: null,
    image: null,
    
    init: function() {
      this.el = $('.project-intro-info');
      this.image = $('.project-intro-graphic img');
      if(this.el.length > 0 && this.image.length > 0) {
        this.listen();
        $tbo.once(function() {
          $controller.render();
        }, 'layout', 400);
      }
    },
    
    listen: function() {
      
      $(window).resize(function() {
        $tbo.once(function() {
          $controller.render();
        }, 'layout');
      });
      
    },
    
    render: function() {
      
      if($tbo.getMode() == 'desktop') {
        var $margin_top = Math.abs(this.image.css('margin-top').replace('px', '')) * 2;
        var $height = this.image.height() - $margin_top;
        // check again
        if($height === 0) {
          $tbo.once(function() {
            $controller.render();
          }, 'layout');
        }
        this.el.css('height', $height+'px');
      } else {
        this.el.css('height', 'auto');
      }
      
    }
    
  };
  
})(jQuery);
(function($){
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    el: null,
    
    source: null,
    template: null,
    
    init: function() {
      
      this.el = $('.tile-grid');
      if(this.el.length > 0) {
        this.setup();
        this.listen();
      }

    },
    
    setup: function() {
      
      var $width = $(window).width();
      if($width > 768) {
        $width = '580';
      }
      
      $('.product-finish', this.el).each(function() {
        var $this = $(this);
        var $html = $('.product-finish-tooltip', $this).html();
        $this.tooltip({
          placement: 'auto',
          html: true,
          title: $html,
          trigger: 'manual',
          template: '<div class="tooltip tooltip-tile" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="width:'+$width+'px;"></div></div>'
        });
      });
      
    },
    
    templates: function() {
      
    },
    
    listen: function() {
      
      $('.product-finish', this.el).click(function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        $this.tooltip('show');
      });
      
      $('.product-finish', this.el).hover(
        function hoverOver() {
          var $this = $(this);
          $('.product-finish').not($this).tooltip('hide');
          $this.tooltip('show');
        },
        function hoverOut() {
          var $this = $(this);
          $tbo.once(function() {
            if(!$('.product-finish:hover').length) {
              $this.tooltip('hide');  
            }
          }, 'product-finish-tooltip', 1000);
        }
      );
      
    }
    
  };
  
})(jQuery);
/**
 * jquery.dlmenu.js v1.0.1
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( $, window, undefined ) {

	'use strict';

	// global
	var Modernizr = window.Modernizr, $body = $( 'body' );

	$.DLMenu = function( options, element ) {
		this.$el = $( element );
		this._init( options );
	};

	// the options
	$.DLMenu.defaults = {
		// classes for the animation effects
		animationClasses : { classin : 'dl-animate-in-1', classout : 'dl-animate-out-1' },
		// callback: click a link that has a sub menu
		// el is the link element (li); name is the level name
		onLevelClick : function( el, name ) { return false; },
		// callback: click a link that does not have a sub menu
		// el is the link element (li); ev is the event obj
		onLinkClick : function( el, ev ) { return false; }
	};

	$.DLMenu.prototype = {
		_init : function( options ) {

			// options
			this.options = $.extend( true, {}, $.DLMenu.defaults, options );
			// cache some elements and initialize some variables
			this._config();
			
			var animEndEventNames = {
					'WebkitAnimation' : 'webkitAnimationEnd',
					'OAnimation' : 'oAnimationEnd',
					'msAnimation' : 'MSAnimationEnd',
					'animation' : 'animationend'
				},
				transEndEventNames = {
					'WebkitTransition' : 'webkitTransitionEnd',
					'MozTransition' : 'transitionend',
					'OTransition' : 'oTransitionEnd',
					'msTransition' : 'MSTransitionEnd',
					'transition' : 'transitionend'
				};
			// animation end event name
			this.animEndEventName = animEndEventNames[ Modernizr.prefixed( 'animation' ) ] + '.dlmenu';
			// transition end event name
			this.transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ] + '.dlmenu',
			// support for css animations and css transitions
			this.supportAnimations = Modernizr.cssanimations,
			this.supportTransitions = Modernizr.csstransitions;

			this._initEvents();

		},
		_config : function() {
			this.open = false;
			this.$trigger = this.$el.children( '.dl-trigger' );
			this.$menu = this.$el.children( 'ul.dl-menu' );
			this.$menuitems = this.$menu.find( 'li:not(.dl-back)' );
			this.$el.find( 'ul.sub-menu' ).prepend( '<li class="dl-back"><a href="#">back</a></li>' );
			this.$back = this.$menu.find( 'li.dl-back' );
		},
		_initEvents : function() {

			var self = this;

			this.$trigger.on( 'click.dlmenu', function() {
        
				if( self.open ) {
					self._closeMenu();
				} 
				else {
					self._openMenu();
				}
				return false;

			} );

			this.$menuitems.on( 'click.dlmenu', function( event ) {
        
				event.stopPropagation();

				var $item = $(this),
					$submenu = $item.children( 'ul.sub-menu' );

				if( $submenu.length > 0 ) {

					var $flyin = $submenu.clone().insertAfter( self.$menu ),
						onAnimationEndFn = function() {
							self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classout ).addClass( 'dl-subview' );
							$item.addClass( 'dl-subviewopen' ).parents( '.dl-subviewopen:first' ).removeClass( 'dl-subviewopen' ).addClass( 'dl-subview' );
							$flyin.remove();
						};

					setTimeout( function() {

						$flyin.addClass( self.options.animationClasses.classin );
						self.$menu.addClass( self.options.animationClasses.classout );
						if( self.supportAnimations ) {
							self.$menu.on( self.animEndEventName, onAnimationEndFn );
						}
						else {
							onAnimationEndFn.call();
						}

						self.options.onLevelClick( $item, $item.children( 'a:first' ).text() );
					} );

					return false;

				}
				else {
					self.options.onLinkClick( $item, event );
				}

			} );

			this.$back.on( 'click.dlmenu', function( event ) {
        
				var $this = $( this ),
					$submenu = $this.parents( 'ul.sub-menu:first' ),
					$item = $submenu.parent(),

					$flyin = $submenu.clone().insertAfter( self.$menu );

				var onAnimationEndFn = function() {
					self.$menu.off( self.animEndEventName ).removeClass( self.options.animationClasses.classin );
					$flyin.remove();
				};

				setTimeout( function() {
          
					$flyin.addClass( self.options.animationClasses.classout );
					self.$menu.addClass( self.options.animationClasses.classin );
					if( self.supportAnimations ) {
						self.$menu.on( self.animEndEventName, onAnimationEndFn );
					}
					else {
						onAnimationEndFn.call();
					}

					$item.removeClass( 'dl-subviewopen' );
					
					var $subview = $this.parents( '.dl-subview:first' );
					if( $subview.is( 'li' ) ) {
						$subview.addClass( 'dl-subviewopen' );
					}
					$subview.removeClass( 'dl-subview' );
          
				} );

				return false;

			} );
			
		},
		closeMenu : function() {
			if( this.open ) {
				this._closeMenu();
			}
		},
		_closeMenu : function() {
			var self = this,
				onTransitionEndFn = function() {
					self.$menu.off( self.transEndEventName );
					self._resetMenu();
				};
			
      $('body').removeClass('mobile-menu-open');
			this.$menu.removeClass( 'dl-menuopen' );
			this.$menu.addClass( 'dl-menu-toggle' );
			this.$trigger.removeClass( 'active' );
			
			if( this.supportTransitions ) {
				this.$menu.on( this.transEndEventName, onTransitionEndFn );
			}
			else {
				onTransitionEndFn.call();
			}

			this.open = false;
		},
		openMenu : function() {
			if( !this.open ) {
				this._openMenu();
			}
		},
		_openMenu : function() {
			var self = this;
      $('body').addClass('mobile-menu-open');
			// clicking somewhere else makes the menu close
			$body.off( 'click' ).on( 'click.dlmenu', function() {
				self._closeMenu() ;
			} );
			this.$menu.addClass( 'dl-menuopen dl-menu-toggle' ).on( this.transEndEventName, function() {
				$( this ).removeClass( 'dl-menu-toggle' );
			} );
			this.$trigger.addClass( 'active' );
			this.open = true;
		},
		// resets the menu to its original state (first level of options)
		_resetMenu : function() {
			this.$menu.removeClass( 'dl-subview' );
			this.$menuitems.removeClass( 'dl-subview dl-subviewopen' );
		}
	};

	var logError = function( message ) {
		if ( window.console ) {
			window.console.error( message );
		}
	};

	$.fn.dlmenu = function( options ) {
		if ( typeof options === 'string' ) {
			var args = Array.prototype.slice.call( arguments, 1 );
			this.each(function() {
				var instance = $.data( this, 'dlmenu' );
				if ( !instance ) {
					logError( "cannot call methods on dlmenu prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for dlmenu instance" );
					return;
				}
				instance[ options ].apply( instance, args );
			});
		} 
		else {
			this.each(function() {	
				var instance = $.data( this, 'dlmenu' );
				if ( instance ) {
					instance._init();
				}
				else {
					instance = $.data( this, 'dlmenu', new $.DLMenu( options, this ) );
				}
			});
		}
		return this;
	};

} )( jQuery, window );

(function($) {
  
  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    selector: '.form-payment',
    el: null,
    
    init: function() {
      
      this.el = $(this.selector);
      if(this.el.length > 0) {
        
        this.listen();
        
      }
      
    },
    
    listen: function() {
      
      $(this.el).submit(function($evt) {
        
        var $account_number = $('[name="account_number"]', this.el);
        var $amount = $('[name="amount"]', this.el);
        
        // simple validation
        if(!$account_number.val() || $amount.val() <= 0) {
          $evt.preventDefault();
        } else {
          // valid
          
          var $item_name = $('[name="item_name"]', this.el);
          $item_name.val($item_name.val().replace('[account_number]', $account_number.val()));
          
        }
        
      });
      
    }
    
  };
  
  window.$payment = $controller; // NOTE: just in case it's needed outside of scope
  
})(jQuery);
(function($) {

  $(document).ready(function() {
    $controller.init();
  });
  
  var $controller = {
    
    init: function() {
      setTimeout(function() {
        $controller.listen();  
      });
    },
    
    listen: function() {
      
      $(document).on('click', '.btn', function() {
        var $this = $(this);
        var $label = $controller.findLabel($this);
        $controller.track({eventAction: $this.text(), eventLabel: $label});
      });
      
      $(document).on('click', 'a[href^="tel:"]', function() {
         var $this = $(this);
         $controller.track({eventAction: 'Phone Call', eventLabel: $this.text()});
      });

      $(document).on('click', 'a[href^="mailto:"]', function() {
         var $this = $(this);
         $controller.track({eventAction: 'Mail', eventLabel: $this.text()});
      });
      
    },
    
    // helper functions
    
    track: function($o) {
      try {
        var $options = $.extend({}, {
            hitType: 'event',
            eventCategory: 'Click',
            eventAction: '',
            eventLabel: ''
        }, $o);
        if($.type(window.ga) == 'function') {
          ga('send', $options);
        }
        this.log($options);
      } catch($e) {
        this.log($e);
      }
    },
    
    log: function($msg) {
      if(console) {
        console.log($msg);
      }
    },
    
    findLabel: function($this) {
      
      var $test = $this.closest('.project-featured');
      
      // project
      if($test.length > 0) {
        return $test.find('h2').text();
      }
      
      // product
      $test = $this.closest('.product-detail-container');
      if($test.length > 0) {
        return $('h1').text();
      }
      
    }
    
  };
  
})(jQuery);