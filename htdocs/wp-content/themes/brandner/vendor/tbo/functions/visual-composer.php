<?php

// a simple promo image with caption & link
vc_map( array(
   "name" => __("Promo Image"),
   "base" => "promo_image",
   "category" => __('TBO'),
   'icon' => 'icon-wpb-single-image',
   "params" => array(
      array(
         "type" => "dropdown",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Type"),
         "param_name" => "type",
         "description" => __("Promo type."),
         'value' => ['default', 'project', 'column-2-right', 'column-2-left'] // column-2-right -> text on right, column-2-left -> text on left
      ),
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Caption"),
         "param_name" => "caption",
         "description" => __("The caption.")
      ),
      array(
         "type" => "dropdown",
         "class" => "",
         "heading" => __("Caption Position"),
         "param_name" => "caption_position",
         "description" => __("The caption position."),
         'value' => ['center', 'left', 'right'],
      ),
      array(
         "type" => "attach_image",
         "holder" => 'img',
         "class" => "",
         "heading" => __("Image"),
         "param_name" => "image",
         "description" => __("The image that will be displayed.")
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("URL or Slug"),
         "param_name" => "url",
         "description" => __("The url or slug (when project).")
      ),
      array(
        'type' => 'textarea_html',
        'class' => '',
        'heading' => __('Text'),
        'param_name' => 'content',
        'description' => __('The promo text.'),
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("CSS"),
         "param_name" => "css_class",
         "description" => __("Any additional CSS classes.")
      ),
   )
) );

vc_map( array(
   "name" => __("Promo Video"),
   "base" => "promo_video",
   "category" => __('TBO'),
   'icon' => 'icon-wpb-single-video',
   "params" => array(
      array(
         "type" => "dropdown",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Type"),
         "param_name" => "type",
         "description" => __("Promo type."),
         'value' => ['default', 'project', 'column-2-right', 'column-2-left'] // column-2-right -> text on right, column-2-left -> text on left
      ),
      /*
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Caption"),
         "param_name" => "caption",
         "description" => __("The caption.")
      ),
      */
      array(
         "type" => "attach_image",
         "holder" => 'img',
         "class" => "",
         "heading" => __("Image"),
         "param_name" => "image",
         "description" => __("The image that will be displayed.")
      ),
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Video"),
         "param_name" => "video",
         "description" => __("The URL of the video.")
      ),
      array(
        'type' => 'textarea_html',
        'class' => '',
        'heading' => __('Text'),
        'param_name' => 'content',
        'description' => __('The promo text.'),
      ),
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("CSS"),
         "param_name" => "css_class",
         "description" => __("Any additional CSS classes.")
      ),
   )
) );