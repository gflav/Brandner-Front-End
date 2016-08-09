<?php 
	$tqmcf = get_tqmcf();
	$field = $tqmcf[$_GET["item_id"]];
?>
<div class="wrap">  
   
    <h2><?php _e( 'Delete Custom Field', TQ_PLUGIN_TEXTDOMAIN ) ?></h2>   
    
    <h3>Are you sure you would like to delete the field: <span class="highlight"><?php echo $field["name"] ?></span>?</h3>
    <p>
    	By default the data entered into the custom fields will not be deleted. This means that if you later want to restore this 
    	custom field you can do so, but it clutters your database. If you are certain you don't need the data from these custom fields, 
    	feel free to delete the data as well 
    <p>
    
	<form method="post" action="?page=<?php echo $_GET['page'] ?>&message=<?php echo urlencode( 'Custom field deleted' ) ?>">
		<?php wp_nonce_field( 'tqmcf-delete_field' ) ?>
		
		<table class="form-table describe">
			<tr valign="top">
				<th scope="row"><?php _e( 'Delete stored data?', TQ_PLUGIN_TEXTDOMAIN ) ?></th>
				<td>
					<input type="checkbox" name="custom_field_data_delete" value="1" />
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="hidden" name="action" value="delete">
			<input type="hidden" name="id" value="<?php echo $field["ID"] ?>">
			<input type="submit" class="button-primary" value="<?php _e( 'Delete this custom field' ) ?>" />
		</p>
		
	</form>
	
	<a href="?page=<?php echo $_GET['page'] ?>">back</a>

</div> 
