<?php

add_action( 'wp_ajax_regenerate', 'tbo_ajax_regenerate');
function tbo_ajax_regenerate() {
  
  $post_id = $_GET['post_id'];
  $response = [];
  $count = 0;
  
  $post = TBO_Wrapper::findById($post_id);
  if($post) {
    
    // find all images from slider
    $gallery = get_field('post_media', $post->getId());
    if(!empty($gallery)) {
      foreach($gallery as $gallery) {
        if(isset($gallery['bd_img_gal']) && count($gallery['bd_img_gal']) > 0) {
          foreach($gallery['bd_img_gal'] as $item) {
            $fullsizepath = get_attached_file($item['ID']);
            if ( false === $fullsizepath || ! file_exists( $fullsizepath ) ) {
              continue;
            }
            @set_time_limit( 900 ); // 5 minutes per image should be PLENTY
            // generate sizes
            $metadata = wp_generate_attachment_metadata( $item['ID'], $fullsizepath );
            if ( is_wp_error( $metadata ) || empty($metadata)) {
              continue;
            }
            // update
            wp_update_attachment_metadata( $item['ID'], $metadata );
            // count
            $count++;
          }
        }
      }
    }
    
  }
  
  $response['processed_images'] = $count;
  
  wp_send_json($response);
  
  wp_die();
  
}