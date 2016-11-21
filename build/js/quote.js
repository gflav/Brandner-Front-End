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