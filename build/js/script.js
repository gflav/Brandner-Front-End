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
    
    $('.btn-disabled').click(function($evt) {
      $evt.preventDefault();
    });
    
    // NOTE: needs CORS to work if not same domain
    $('.btn-print').click(function($evt) {
      $evt.preventDefault();
      var $this = $(this);
      var $url = $this.attr('href') || $this.attr('data-print');
      if ($url) {
        // print url
        var $iframe_id = 'print-iframe';
        var $iframe = $($templates.print.template({id: $iframe_id}));
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
        $modal.open($modal_name, {url: $href, selector: '#site-content > div', css_class: 'modal-gray modal-capability'});
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

})(jQuery);