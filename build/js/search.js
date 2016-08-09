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