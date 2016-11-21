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