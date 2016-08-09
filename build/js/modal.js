(function($){
  
  $(document).ready(function() {
    $modal.init();
  });
  
  var $modal = {
    
    source: null,
    template: null,
    
    instance: null, // current instance
    instances: {},
    queue: [],
    
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
          if($modal.queue.length > 0) {
            var $instance = $modal.queue.pop();
            $instance.modal('hide').trigger('modal.close');
            $modal.queue.push($instance); // TODO:
            // TODO: make sure bs.close is triggered
          }
        }
      });
      
      // outside of modal click to close
      // TODO: 
      //$(document).on('click', '.modal-dialog', function($evt) {
        //$evt.preventDefault();
        //$modal.close();
      //});
      
      // generic way to trigger a modal
      $(document).on('click', 'li.trigger-modal a,a.trigger-modal', function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        var $modal_name = $.md5($this.attr('href'));
        $modal.open($modal_name, {url: $this.attr('href'), selector: '#site-content', css_class: 'modal-instance-simple', footer: '<a href="#" class="trigger-top">Back to top</a>'});
      });
      
      $(document).on('click', '.trigger-modal-close', function($evt) {
        $evt.preventDefault();
        var $this = $(this);
        var $modal_name = $this.closest('.modal').attr('data-instance');
        $modal.close($modal_name);
      });
      
      $(document).on('show.bs.modal', '.modal', function() {
        var $this = $(this);
        setTimeout(function() {
          $this.addClass('animate');
        }, 100);
      });

      $(document).on('hide.bs.modal', '.modal', function() {
        var $this = $(this);
        $this.removeClass('animate');
      });
      
    },

    addInstance: function($name, $instance) {
      this.instances[$name] = $instance; // TODO: use queue
      this.queue.push($instance);
    },
    
    getInstance: function($name) {
      if ($name) {
        return this.instances[$name];
      }
      return this.instance;
    },


    exists: function($name) {
      return this.instances && this.instances[$name];
    },
    
    open: function($name, $o) {

      var $deferred = $.Deferred();
      
      if (!this.exists($name)) {
        
        // defaults
        
        var $defaults = {
          url: null,
          html: null,
          selector: null,
          css_class: '',
          footer: ''
        };
        
        var $options = $.extend({}, $defaults, $o);
        
        // create

        $('body').append(this.template({name: $name, css_class: $options.css_class}));
        
        this.instance = $('.modal[data-instance="'+$name+'"]');
        
        if ($options.url) {
          var $url = $options.url;
          if ($options.selector) {
            $url += ' ' + $options.selector;
          }
          $('.modal-inner', this.instance).load($url, function($response) {
            var $html = $.parseHTML($response);
            var $content = $('#site-content', $html);
            if($content.length > 0) {
              // remove #site-content
              $('.modal-inner', this.instance).html($content.html());
            }
            if($options.footer) {
              $('.modal-inner', this.instance).append($options.footer);
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
        
        this.getInstance($name).modal('show');
        $deferred.resolve();
        
      }

      return $deferred.promise();
      
    },
    
    close: function($name) {
      if ($name && this.exists($name)) {
        this.getInstance($name).modal('hide').trigger('modal.close');
      } else {
        // close last instance
        this.getInstance().modal('hide').trigger('modal.close');
      }
    },
    
    // remove from $modal instance list, and dom
    remove: function($name) {
      if(this.exists($name)) {
        this.getInstance($name).remove();
        delete this.instances[$name];
      }
      return this;
    }
    
  };
  
  window.$modal = $modal;
  
})(jQuery);