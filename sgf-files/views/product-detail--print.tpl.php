<div class="container">
	<div id="site-content">
<?php

  $gallery = get_field('post_media', $post->ID);
  $thumbs = [];
  
  // display title
  the_title('<h1>', '</h1>');
  
  if(!empty($gallery)):
    foreach($gallery as $gallery):
      if(isset($gallery['bd_img_gal']) && count($gallery['bd_img_gal']) > 0):
        // image
        $gallery['bd_img_gal'] = array_slice($gallery['bd_img_gal'], 0, 6);
        foreach($gallery['bd_img_gal'] as $item):
          if($image = wp_get_attachment_image( $item['ID'], 'viewer-large')) {
            echo '<div class="product-image">';
            echo $image;
            echo '</div>';
          }
          break;
        endforeach;
        break;
       elseif(isset($gallery['vimeo_vids']) && !empty($gallery['vimeo_vids']) && count($gallery['vimeo_vids']) > 0):
        // vimeo
        foreach($gallery['vimeo_vids'] as $item):
          if (preg_match("/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $item['vimeo_url'], $matches)):

            $vimeo_id = $matches[5];
            
            $data = json_decode(file_get_contents("http://vimeo.com/api/v2/video/$vimeo_id.json"));
            $url = $data[0]->thumbnail_medium;
            
            //echo '<div class="product-video product-image">';
           // echo tbo()->shortcode('[vimeo]'.$item['vimeo_url'].'[/vimeo]');
            //echo '</div>';
            
          endif;
        endforeach;
      endif;
    endforeach;
  else:
    // featured image
    if($image = get_the_post_thumbnail($post->ID, 'viewer-large')) {
      echo '<div class="product-image">';
      echo $image;
      echo '</div>';
    }
  endif;
  
  // content
  
?>
  <?php the_content(); ?>
  </div>
</div>