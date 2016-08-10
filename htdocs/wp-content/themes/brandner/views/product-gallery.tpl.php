<?php
  
  $gallery = get_field('post_media', $post->ID);
  $thumbs = [];
  
  if(!empty($gallery)):
    echo '<div class="product-gallery-container product-gallery">';
    foreach($gallery as $gallery):
      if(isset($gallery['bd_img_gal']) && count($gallery['bd_img_gal']) > 0):
        // image
        $gallery['bd_img_gal'] = array_slice($gallery['bd_img_gal'], 0, 6);
        foreach($gallery['bd_img_gal'] as $item):
          if($image = wp_get_attachment_image( $item['ID'], 'viewer-large')) {
            $thumbs[] = '<li><a href="#">' . wp_get_attachment_image($item['ID'], 'viewer-thumb') . '</a></li>';
            echo '<div class="product-image">';
            echo $image;
            echo '</div>';
          }
        endforeach;
       elseif(isset($gallery['vimeo_vids']) && !empty($gallery['vimeo_vids']) && count($gallery['vimeo_vids']) > 0):
        // vimeo
        foreach($gallery['vimeo_vids'] as $item):
          if (preg_match("/(https?:\/\/)?(www.)?(player.)?vimeo.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $item['vimeo_url'], $matches)):

            $vimeo_id = $matches[5];
            
            $data = json_decode(file_get_contents("http://vimeo.com/api/v2/video/$vimeo_id.json"));
            $url = $data[0]->thumbnail_medium;
            
            $thumbs[] = '<li><a href="#"><img src="' .$url. '" alt=""></a></li>';
            
            echo '<div class="product-video product-image">';
            echo tbo()->shortcode('[vimeo]'.$item['vimeo_url'].'[/vimeo]');
            echo '</div>';
            
          endif;
        endforeach;
      endif;
    endforeach;
    echo '</div>';
  else:
    // featured image
    if($image = get_the_post_thumbnail($post->ID, 'viewer-large')) {
      $thumbs[] = '<li><a href="#">' . wp_get_attachment_image($post->ID, 'viewer-thumb') . '</a></li>';
      echo '<div class="product-image">';
      echo $image;
      echo '</div>';
    }
  endif; 
  
?>

  <div class="product-gallery-thumbs-container">
    <div class="row">
      <div class="tbo-col-sm-6 tbo-col-md-3">
        <div class="product-gallery-thumbs-container-headline">
            <h1><?php echo $post->post_title; ?></h1>
         </div>
      </div>
      <div class="tbo-col-sm-6 tbo-col-md-9">
        <?php if(!empty($thumbs)): ?>
        <ul class="product-thumbs">
        <?php echo join('', $thumbs); ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>