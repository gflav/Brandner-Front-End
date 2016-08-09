<div class="wrap">  
   
    <h2><?php _e( 'Delete Old Custom Field Data', TQ_PLUGIN_TEXTDOMAIN ) ?></h2>   
    
    <h3>Are you sure you would like to delete the data of the field: <span class="highlight"><?php echo $_GET["item_slug"] ?></span>?</h3>
    <p>
    	This is a field you have previously delete, but you did not delete the data associated to your media files. 
    	If you would like to do so now, click the button below, which will delete this field both from the "Delete Custom Fields" list, 
    	and it will delete all its data associated to your media items as well. 
    <p>
    
	<form method="post" action="?page=<?php echo $_GET['page'] ?>&message=<?php echo urlencode( 'Custom field deleted' ) ?>">
		<?php wp_nonce_field( 'tqmcf-delete_field_data' ) ?>
		
		<p class="submit">
			<input type="hidden" name="action" value="delete_data">
			<input type="hidden" name="slug" value="<?php echo $_GET["item_slug"] ?>">
			<input type="submit" class="button-primary" value="<?php _e( 'Delete this custom field\'s data' ) ?>" />
		</p>
		
	</form>
	
	<a href="?page=<?php echo $_GET['page'] ?>">back</a>

</div> 
