<div class="wrap">  
   
    <h2><?php _e( 'Delete Custom Field', TQ_PLUGIN_TEXTDOMAIN ) ?></h2>   
    
    <h3>Are you sure you would like to restore the field: <span class="highlight"><?php echo $_GET["item_slug"] ?></span>?</h3>
    <p>
    	Since only the slug of the delete field is saved we can only approximate what the name of the field used to be. If 
    	the name doesn't seem right, simply modify the field to change it. 
    <p>
    
	<form method="post" action="?page=<?php echo $_GET['page'] ?>&message=<?php echo urlencode( 'Custom field restored' ) ?>">
		<?php wp_nonce_field( 'tqmcf-restore_field' ) ?>
		
		
		<p class="submit">
			<input type="hidden" name="action" value="restore">
			<input type="hidden" name="slug" value="<?php echo $_GET["item_slug"] ?>">
			<input type="submit" class="button-primary" value="<?php _e( 'Restore this custom field' ) ?>" />
		</p>
		
	</form>
	
	<a href="?page=<?php echo $_GET['page'] ?>">back</a>

</div> 
