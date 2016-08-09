<?php

if( isset( $_GET['action'] ) AND $_GET['action'] == 'delete' ) :
	include( 'administration_delete.php' );
elseif( isset( $_GET['action'] ) AND $_GET['action'] == 'edit' ) :
	include( 'administration_edit.php' );
elseif( isset( $_GET['action'] ) AND $_GET['action'] == 'restore' ) :
	include( 'administration_restore.php' );
elseif( isset( $_GET['action'] ) AND $_GET['action'] == 'delete_data' ) :
	include( 'administration_delete_data.php' );
elseif( isset( $_GET['action'] ) AND $_GET['action'] == 'view' ) :
	include( 'administration_view.php' );
else :


if( $_POST ) {
	call_user_func( 'tqmcf_user_field_' . $_POST['action'] );
}

$tqmcf = get_tqmcf();

?>

<div class="wrap">  
   
    <h2><?php echo TQ_PLUGIN_LONGNAME ?> Administration</h2>  
    
    <?php if( isset( $_GET['action'] ) AND $_GET['message'] ) : ?>
		<div class="updated below-h2" id="message">
			<p><?php echo urldecode( $_GET['message'] ) ?></p>
		</div>
	<?php endif ?>

    <h3><?php _e( 'Add a Custom Field', TQ_PLUGIN_TEXTDOMAIN ) ?></h3>
	
	<form method="post" action="?page=<?php echo $_GET['page'] ?>&message=<?php echo urlencode( 'Custom field created' ) ?>">
	
		<?php wp_nonce_field('tqmcf-add_field'); ?>
	
		<table class="form-table describe">
			
			<tr valign="top">
				<th scope="row"><?php _e( 'Field Name', TQ_PLUGIN_TEXTDOMAIN ) ?></th>
				<td>
					<input type="text" title="<?php _e( 'Add a short descriptive name for your field name', TQ_PLUGIN_TEXTDOMAIN ) ?>" name="custom_field_name" />
				</td>
			</tr>
		
			<tr valign="top">
				<th scope="row">Field Description</th>
				<td>
					<textarea title="<?php _e( 'An optional short description of the field', TQ_PLUGIN_TEXTDOMAIN ) ?>" name="custom_field_description"></textarea>
				</td>
			</tr>
			
		</table>
		
		<p class="submit">
			<input type="hidden" name="action" value="add" />
			<input type="submit" class="button-primary" value="<?php _e( 'Add custom field', TQ_PLUGIN_TEXTDOMAIN ) ?>" />
		</p>
	
	</form>
	
	
	<h3><?php _e( 'Existing Custom Fields', TQ_PLUGIN_TEXTDOMAIN ) ?></h3>	
	
	<div id="tqmcf-list">
		<?php 
			if ( $tqmcf ) : foreach ( $tqmcf as $field ) :
			$item_count = $wpdb->get_var("SELECT COUNT(post_id) FROM $wpdb->postmeta WHERE meta_key = '$field[slug]' AND meta_value != '' ");
		?>
			<div class="item">
		    	<h4><?php echo $field['name'] ?> <small>(<?php echo $field["slug"] ?>)</small></h4>
		    	<p class="desc"><?php echo $field['description'] ?></p>
		    	
		   
				<a href="?page=<?php echo $_GET['page'] ?>&action=view&item_id=<?php echo( $field['ID'] ) ?>">
					view items (<?php echo $item_count ?>)
				</a>
		    	<a href="?page=<?php echo $_GET['page'] ?>&action=delete&item_id=<?php echo $field['ID'] ?>">delete</a>
		    	<a href="?page=<?php echo $_GET['page'] ?>&action=edit&item_id=<?php echo $field['ID'] ?>">modify</a>	   
		    </div>
	    <?php 
	    	endforeach; 
	    	else :
	    		echo '<p>' . __( 'You have not created any custom fields yet', TQ_PLUGIN_TEXTDOMAIN ) . '</p>';
	    	endif;
	    ?>
	</div>
	
	
	
	
		
	<div id="tqmcf-list-old">
	<h3><?php _e( 'Deleted Custom Fields', TQ_PLUGIN_TEXTDOMAIN ) ?></h3>	


		<?php 
			$fields = array();
			foreach($tqmcf as $field) {
				$fields[] = $field["slug"];
			}
			$fields = "'".implode("', '", $fields)."'";
			$items = $wpdb->get_col("SELECT DISTINCT(meta_key) FROM $wpdb->postmeta WHERE meta_key LIKE '%tqmcf_%' AND
				meta_key NOT IN ($fields)	
			");
		?>
			<?php if($items) : ?>
			<p> The following fields have been deleted, but their data was not removed from Wordpress. </p>

			<?php foreach($items as $item) : ?>
			<div class="item">
		    	<h5><?php echo $item ?></h5>
		    	<a href="?page=<?php echo $_GET['page'] ?>&action=restore&item_slug=<?php echo $item ?>">restore</a>
		    	<a href="?page=<?php echo $_GET['page'] ?>&action=delete_data&item_slug=<?php echo $item ?>">delete data</a>	   
		    </div>
	    <?php 
	    	endforeach; 
	    	else :
	    		echo '<p>' . __( "You don't have any deleted custom fields", TQ_PLUGIN_TEXTDOMAIN ) . '</p>';
	    	endif;
	    ?>
	</div>
	
	
	
	
</div> 

<?php endif ?>
