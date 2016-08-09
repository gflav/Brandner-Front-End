<?php

vc_map( array(
    "name" => __("Content Slider", "tbo"),
    "base" => "content_slider",
    "category" => __('TBO'),
    "as_parent" => array('only' => 'slide_image,slide_video'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "params" => array(
        array(
          "type" => "textfield",
          "heading" => __("Heading"),
          "param_name" => "heading",
        ),
        array(
          "type" => "textfield",
          "heading" => __("ID"),
          "param_name" => "el_id",
         ),
        array(
          "type" => "textfield",
          "heading" => __("UL ID"),
          "param_name" => "el_ul_id",
         ),
    ),
    "js_view" => 'VcColumnView'
) );

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_Content_Slider extends WPBakeryShortCodesContainer {
      
      protected function content( $atts, $content = null ) {
        $atts['slides'] = do_shortcode($content);
        return tbo()->view->load('content-slider', $atts);
      }
      
    }
}

// a simple image with title & link
vc_map( array(
   "name" => __("Slide Image"),
   "base" => "slide_image",
   "category" => __('TBO'),
   "as_child" => array('only' => 'content_slider'),
   "params" => array(
      array(
         "type" => "dropdown",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Content Alignment"),
         "param_name" => "content_align",
         "description" => __("Which side does the content display on?"),
         "value" => ['left', 'center', 'right']
      ),
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Heading"),
         "param_name" => "heading",
         "description" => __("The text below the image.")
      ),
      array(
         "type" => "attach_image",
         "holder" => 'img',
         "class" => "",
         "heading" => __("Image"),
         "param_name" => "image",
         "description" => __("The image.")
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("Link"),
         "param_name" => "link",
         "description" => __("The URL the link goes to.")
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("CSS"),
         "param_name" => "el_class",
         "description" => __("The CSS class.")
      ),
   )
) );

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Slide_Image extends WPBakeryShortCode {
      
      protected function content( $atts, $content = null ) {

        extract(shortcode_atts(array(
            'content_align' => 'left',
            'heading' => '',
            'image' => '',
            'link' => '',
            'el_class' => '',
          ), $atts
        ));
        
        $params = array(
          'item' => (object)array(
            'content_align' => $content_align,
            'image' => !empty($image) ? wp_get_attachment_image($image, $size='full') : '',
            'heading' => $heading,
            'link' => $link
          ),
        );
        
        return tbo()->view->load('content-slider--slide-image', $params);
        
      }
      
    }
}

// a simple image with title & link
vc_map( array(
   "name" => __("Slide Video"),
   "base" => "slide_video",
   "category" => __('TBO'),
   "as_child" => array('only' => 'content_slider'),
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => 'div',
         "class" => "",
         "heading" => __("Heading"),
         "param_name" => "heading",
         "description" => __("The text below the image.")
      ),
      array(
         "type" => "attach_image",
         "holder" => 'img',
         "class" => "",
         "heading" => __("Image"),
         "param_name" => "image",
         "description" => __("The image.")
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("Video URL"),
         "param_name" => "video",
         "description" => __("The video URL goes to.")
      ),
      array(
         "type" => "textfield",
         "class" => "",
         "heading" => __("CSS"),
         "param_name" => "el_class",
         "description" => __("The CSS class.")
      ),
   )
) );

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_Slide_Video extends WPBakeryShortCode {
      
      protected function content( $atts, $content = null ) {
        
        extract(shortcode_atts(array(
            'heading' => '',
            'image' => '',
            'video' => '',
            'el_class' => '',
            'content_align' => 'right'
          ), $atts
        ));
        
        $params = array(
          'item' => (object)array(
            'heading' => $heading,
            'image' => !empty($image) ? wp_get_attachment_image($image, $size='full') : '',
            'video' => $video,
            'el_class' => $el_class,
          ),
        );
        
        return tbo()->view->load('content-slider--slide-video', $params);
        
      }
      
    }
}