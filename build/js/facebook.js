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