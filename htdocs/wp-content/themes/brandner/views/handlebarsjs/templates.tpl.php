
<script id="template-modal" type="text/x-handlebars-template">
  <div class="modal modal-instance-{{name}} {{css_class}}" data-instance="{{name}}" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="container">
            <div class="row" id="default-modal-close">
              <div class="col-sm-12"><a class="trigger-modal-close btn-close" href="#"><span class="close-label">Close</span><span class="close-x">X</span></a></div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="modal-inner"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>

<script id="template-share-modal" type="text/x-handlebars-template">
  <div class="share-icons-container">
    <h1>Share</h1>
    <div class="share-icons"></div>
  </div>
</script>

<script id="template-print-iframe" type="text/x-handlebars-template">
  <iframe id="{{id}}" style="display:none;"></iframe>
</script>

<script id="template-like-button" type="text/x-handlebars-template">
  <div class="fb-like-container">
    <div class="fb-like" 
      data-href="{{url}}" 
      data-layout="standard"
      data-width="200"
      data-action="like"
      data-show-faces="true">
    </div>
  </div>
</script>

<script id="template-cart-items" type="text/x-handlebars-template">
  <span class="cart-items">{{count}}</span>
</script>

<script id="template-quote-msg-default" type="text/x-handlebars-template">
  Hi I'd like a quote on {{{product}}}
</script>

<script id="template-get-quote-form" type="text/x-handlebars-template">
  <div class="row">

    <div class="col-sm-12 col-lg-7">
      <h1>Get A Quote</h1>
      <form id="form-quote" method="post" action="">
      <textarea name="message" class="hidden"></textarea>
  
        <div class="input-wrapper">
          <label>YOUR NAME</label>
          <input name="name" type="text" placeholder="YOUR NAME">
        </div>
  
        <div class="input-wrapper">
          <label>YOUR EMAIL</label>
          <input name="email" type="text" placeholder="YOUR EMAIL">
        </div>
  
        <div class="input-wrapper">
          <label>YOUR NUMBER(OPTIONAL)</label>
          <input name="phone" type="text" placeholder="YOUR NUMBER(OPTIONAL)">
        </div>
  
        <label>MESSAGE</label>
        <div class="textarea" data-name="message"></div>
        
        <div class="btn-group">
          <a class="btn btn-submit">Send Qoute Request</a>
          <a class="btn btn-save-continue">Save & continue</a>
        </div>
        
      <form>
    </div>
  
    <div class="col-sm-12 col-lg-5">
       <div class="image">
      {{{featured_image}}}
      </div>
  
      <h4>{{title}}</h4>
      {{{body}}}
    </div>
  
  </div>
</script>

<script id="template-modal-gallery" type="text/x-handlebars-template">

  <div class="row modal-gallery-header">
    <div class="col-sm-9">
      <h1>{{title}}</h1>
    </div>
    <div class="col-sm-3">
        <a class="trigger-modal-close" href="#"><span class="close-x">X</span></a>
    </div>
   </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="modal-gallery">
        {{#each items}}
          <div class="modal-gallery-item">
            {{#this}}
              {{#if_eq type 'image'}}
              <img src="{{url}}">
              {{else}}
              <div class="video-container">{{{embed}}}</div>
              {{/if_eq}}
            {{/this}}
          </div>
        {{/each}}
      </div>
    </div>
  </div>
  {{#if_gt count 1}}
  <div class="row modal-gallery-nav">
    <div class="col-sm-9">
      <a href="#" class="btn-prev"><span class="icon-triangle-up"></span>Previous</a> /
      <a href="#" class="btn-next">Next<span class="icon-triangle-up"></span></a>
    </div>
    <div class="col-sm-3 counter" >
      <span class="current-item">1</span> / {{count}}
    </div>
  </div>
  {{/if_gt}}
</script>