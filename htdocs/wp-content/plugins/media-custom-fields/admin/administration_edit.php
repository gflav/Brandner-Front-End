<?php 
	$tqmcf = get_tqmcf();
	$field = $tqmcf[$_GET["item_id"]];
?>
<div class="wrap">  
   
    <h2><?php _e( 'Edit Custom Field', TQ_PLUGIN_TEXTDOMAIN ) ?></h2>   
  
    
	<form method="post" action="?page=<?php echo $_GET["page"] ?>&message=<?php echo urlencode("Custom field details updated") ?>">
	
		<?php wp_nonce_field( 'tqmcf-edit_field' ) ?>
		
		<table class="form-table describe">

			<tr valign="top">
				<th scope="row"><?php _e( 'Field Name', TQ_PLUGIN_TEXTDOMAIN ) ?></th>
				<td>
					<input type="text" title="<?php _e( 'Add a short descriptive name for your field name', TQ_PLUGIN_TEXTDOMAIN ) ?>" 
						name="custom_field_name" value="<?php echo $field['name'] ?>" />
				</td>
			</tr>
		
			<tr valign="top">
				<th scope="row"><?php _e( 'Field Description', TQ_PLUGIN_TEXTDOMAIN ) ?></th>
				<td>
					<textarea title="<?php _e( 'An optional short description of the field', TQ_PLUGIN_TEXTDOMAIN ) ?>" 
						name="custom_field_description"><?php echo $field['description'] ?></textarea>
				</td>
			</tr>
		
		</table>
		
		<p class="submit">
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="id" value="<?php echo $field['ID'] ?>" />
			<input type="submit" class="button-primary" value="<?php _e( 'Edit custom field' ) ?>" />
		</p>
		
	</form>

	<a href="?page=<?php echo $_GET['page'] ?>">back</a>

</div>
