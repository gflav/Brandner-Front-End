<?php 
	$tqmcf = get_tqmcf();
	$field = $tqmcf[$_GET["item_id"]];
	$current = ($_GET["num"]) ? $_GET["num"] : 1;
	

      
 ?>
<div class="wrap">  
   
    <h2><?php _e( 'Related Media', TQ_PLUGIN_TEXTDOMAIN ) ?> <small>(<?php echo $field["name"] ?>)</small></h2>   
	<a href="?page=<?php echo $_GET['page'] ?>">back</a>



<?php

$args = array(
	"post_type" => "attachment",
	"post_status" => "inherit",
	"paged" => $current,
	'meta_query' => array(
		array(
			'key' => $field["slug"],
		)
	)
	
);

$mcf_query = new WP_Query( $args );

$pagination = array(
    'base'         => @add_query_arg('num','%#%'),
    'format'       => '?num=%#%',
    'total'        => $mcf_query->max_num_pages,
    'current'      => $current,
    'show_all'     => False,
    'end_size'     => 1,
    'mid_size'     => 2,
    'prev_next'    => True,
    'prev_text'    => __('&laquo; Previous'),
    'next_text'    => __('Next &raquo;'),
    'type'         => 'plain',
    'add_args'     => False,
    'add_fragment' =>  ""
); 
echo "<div class='mcf_pagination'>";
print paginate_links($pagination);
echo "</div>";


// The Loop
echo "<table id='mcf_imagelist'>";
global $post;
while ( $mcf_query->have_posts() ) : $mcf_query->the_post(); $imagemeta = wp_get_attachment_metadata( $post->ID );
	echo '<tr>';
	echo "<pre>";
	echo "</pre>";
	echo '<td>'.wp_get_attachment_image( $post->ID, array(120,120) ).'</td>';
	echo '<td>';
	echo "<h2>".the_title("", "", false)."</h2>";
	edit_post_link( "edit details" );
	echo "<br>";	
	echo "<strong>".$field["name"].":</strong> ".get_post_meta($post->ID, $field["slug"], true)."<br>";
	echo "<strong>dimensions:</strong> ".$imagemeta["width"]."x".$imagemeta["height"]."<br>";
	echo "<strong>location:</strong> ".$imagemeta["file"]."<br>";	
		echo '</td>';
	echo "</tr>";
endwhile;
echo '</table>';

wp_reset_postdata();

?>


<?php 
echo "<div class='mcf_pagination'>";
print paginate_links($pagination);
echo "</div>";
?>


</div>
