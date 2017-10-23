<?php
  
  $title = get_the_title($post->ID);
  $gallery = get_field('post_media', $post->ID);
  
  $slides = [];
  $thumbs = [];
  
  if(!empty($gallery)):
    echo '<div class="product-gallery-container product-gallery">';
    foreach($gallery as $gallery):
      if(isset($gallery['bd_img_gal']) && count($gallery['bd_img_gal']) > 0):
        // image
        foreach($gallery['bd_img_gal'] as $item):
          if($image = wp_get_attachment_image( $item['ID'], 'viewer-large')) {
            list($url, $width, $height, $is_intermediate) = wp_get_attachment_image_src($item['ID'], 'viewer-large');
            $thumbs[] = [
              'url' => $url,
              'width' => $width,
              'height' => $height
            ];
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
            
            $thumbs[] = [
              'url' =>  $url,
              'width' => NULL,
              'height' => NULL,
            ];
            
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
      list($url, $width, $height, $is_intermediate) = wp_get_attachment_image_src($item['ID'], 'viewer-large');
      $thumbs[] = [
        'url' => $url,
        'width' => $width,
        'height' => $height
      ];
      echo '<div class="product-image">';
      echo $image;
      echo '</div>';
    }
  endif; 
  
?>

  <div class="product-gallery-thumbs-container">
    <div class="row">
      <div class="col-sm-12">
        <?php if(!empty($thumbs)): ?>
        <ul class="product-thumbs">
        <?php foreach($thumbs as $thumb): ?>
          <li><a href="#"><img src="<?php echo $thumb['url']; ?>" alt=""></a></li>
        <?php endforeach; ?>
        </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>