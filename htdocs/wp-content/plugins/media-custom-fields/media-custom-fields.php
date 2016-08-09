<?php
/*
Plugin Name: Media Custom Fields
Plugin URI: http://webtastique.net/media-custom-fields/
Author: Daniel Pataki
Author URI: http://danielpataki.com/
Description: Enables the addition of custom fields to attachments in Wordpress
Version: 1.0
License: GPL3


==============================================================================
Licence
==============================================================================

Copyright 2010 Webtastique  (email : daniel@webtastique.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


==============================================================================
Donations
==============================================================================

If you like this plugin, and would like to say thank you, please consider 
donating. I would love a Bentley Continental GT, which costs $200,000.
I only need about $199,500 more, and your donation could go a long way!
If you don't like giving money, I am willing to accept the Bentley itself 
directly (I am flexible). 

Additionally, if you think the plugin is awesome, and/or it helps you, 
I would appreciate a link to it's homepage at 
http://webtastique.net/plugins/media-custom-fields/

==============================================================================
Helping out with development, and studying plugins
==============================================================================

I have done my best to create documentation for those who would like to learn
about how plugins work in general. If you have any questions, or want to 
add to the documentation please let me know, just go to 
http://webtastique.net/plugins/media-custom-fields/

I have also gone to some lengths to ensure that the plugin complies with all 
the Wordpress coding standards in place. One such document can be found here:
http://codex.wordpress.org/WordPress_Coding_Standards
I would appreciate it if you could report even non-important problems, like a 
space left out or something similar. Again, go to 
http://webtastique.net/plugins/media-custom-fields/

==============================================================================
Naming conventions, tq and tqmcf
==============================================================================

tq is the abbreviation for Tastique, my company. All functions which are 
generally a part of all our functions will begin with tq_.

tqmcf is the abbreviation of Tastique Media Custom Fields. Wordpress suggests
using unique and descriptive names, so all my functions would have been named
tastique_media_custom_fields_edit(), and so on. I found this a bit unweildy,
so function names now start with tqmcf usually. 

To ease plugin development, this string "tqmcf" is stored in a constant. 
However in some cases the constant is used, while in other, "tqmcf" is hard 
coded. The reason for this is compatability and error prevention.

If the name of the plugin changes, "tqmcf" might change, and the custom 
fields from the database would not be retireved. Therefore every time 
database interaction is involved, tqmcf is hard coded.

==============================================================================

*/



/*
The following lines ensure that noone naughty is accessing the plugin. 
If someone is trying to access this file independantly, Wordpress functions
should not be loaded. Hence, we look if a simple functions (any function) exists
and if it doesn't, we terminate the script.
*/

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	
	$title   = 'Accessing this file outside of Wordpress is not allowed for security reasons';
	$message = 'You must use this plugin from the Wordpress backend.';
	
	exit( tq_plugin_error( $title, $message ) );
}

/*
For the plugin to work, we need the user to have PHP 5. For people who don't, we 
need to exit the running of the plugin, and generate an error message. 
*/ 

elseif ( version_compare(phpversion(), '5.0.0', '<') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	
	$title   = 'Sorry, but this plugin requires PHP version 5.0.0 or higher';
	$message = 'Your host might allow you to switch to PHP 5, check your cPanel, or contact your hosting company.';
	
	exit( tq_plugin_error( $title, $message ) );
}

/*
The plugin also ensures usage of Wordpress 3.0 or higher. The plugin could be made to work with lower versions,
but backwards compatablity is not a great thing here. The goal is to get people to use the latest version of 
Wordpress for safety reasons. 
*/ 

elseif ( version_compare( get_bloginfo( 'version' ) , '3.0' , '<' ) ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	
	$title   = 'Sorry, but this plugin will requires Wordpress version 3.0 or higher';
	$message = 'To tell you the truth, the plugin would work with lower versions, but you really 
		should update your blog, it is extremely prone to attacks without updates. You can use the automatic update option, or, you can 
		visit <a href="http://wordpress.org">Wordpress.org</a> to download the newest version.';
	
	exit( tq_plugin_error( $title, $message ) );
}

/* Definitions that we will be using throughout the files */

define( 'TQ_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'TQ_PLUGIN_BASEDIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'TQ_PLUGIN_URL' , plugins_url() . '/' . TQ_PLUGIN_BASEDIR );
define( 'TQ_PLUGIN_TEXTDOMAIN', 'tastique-media-custom-fields' );
define( 'TQ_PLUGIN_SHORTNAME', 'tqmcf' );
define( 'TQ_PLUGIN_LONGNAME', 'Media Custom Fields' );


/* Activation Hook */

register_activation_hook( __FILE__, 'tqmcf_activation' );

function tqmcf_activation() {

	$tqmcf = get_tqmcf();
	if(!empty($tqmcf)) {
		$i = 0;
		foreach($tqmcf as $key => $field) {
			if(!isset($field["ID"])) {
				$new_tqmcf[$i]["ID"] = $i;
				$new_tqmcf[$i]["name"] = $field["name"];
				$new_tqmcf[$i]["slug"] = "tqmcf_".sanitize_title_with_dashes($field["name"]);
				$new_tqmcf[$i]["description"] = $field["description"];
			}
			else {
				$new_tqmcf[$i]["ID"] = $i;
				$new_tqmcf[$i]["name"] = $field["name"];
				$new_tqmcf[$i]["slug"] = $field["slug"];
				$new_tqmcf[$i]["description"] = $field["description"];			
			}
			$i++;
		}
		
		
		
		update_option( 'tastique_media_custom_fields', $new_tqmcf );

	}

}


class tastique_media_custom_fields {

	/**
	 * Class constructor
	 * 
	 * Sets thngs up when the class is instantiated, initiates the retrieval 
	 * of the textdomain, and prepares the execution of the edit and save methods
	 *
	 * @package Media Custom Fields
	 * @since 1.0
	 */
	 
	function __construct() {
		add_action( 'init', array( &$this, TQ_PLUGIN_SHORTNAME . '_text_domain' ) );
		add_filter( 'attachment_fields_to_edit', array( &$this, TQ_PLUGIN_SHORTNAME . '_edit'), 10, 2 );
		add_filter( 'attachment_fields_to_save', array( &$this, TQ_PLUGIN_SHORTNAME . '_save'), 10, 2 );
	}
	
	/**
	 * Localization setup
	 * 
	 * Loads the plugin textdomain so that we can internationalize the plugin. See the
	 * following links for more information
	 * http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
     * http://codex.wordpress.org/Translating_WordPress 
	 *
	 * @package Media Custom Fields
	 * @since 1.0
	 */
	
	function tqmcf_text_domain() {
		load_plugin_textdomain( TQ_PLUGIN_TEXTDOMAIN, false, TQ_PLUGIN_BASEDIR . '/languages' );
	}

	/**
	 * Gets the field for the media edit page
	 * 
	 * The function receives the usual Wordpress form fields as a parameter, 
	 * and them we add our own fields to this array, and return it. 
	 *
	 * @package Media Custom Fields
	 * @since 1.0
	 *
	 * @param	array	$form_fields	The default Wordpress form fields
	 * @param	object	$post			An object containing the post (media) details
	 * @return	array					An array of bult in, and user defined media custom fields
	 */
	 
	function tqmcf_edit( $form_fields, $post ) {
		$tqmcf = get_tqmcf();
		
		foreach ( $tqmcf as $field ) {
			$form_fields[$field["slug"]]['label'] = $field['name'];
			$form_fields[$field["slug"]]['value'] = get_post_meta( $post->ID, $field["slug"], true );
			$form_fields[$field["slug"]]['helps'] = $field['description'];
		}
		
		return $form_fields;
	}

	/**
	 * Saves the media's entered post data to the database
	 * 
	 * This function inserts the custom field data that the users enters
	 * into the database. It goes through all the fields the user has created
	 * And updates the postmeta to the appropriate value
	 *
	 * @package Media Custom Fields
	 * @since 1.0
	 *
	 * @param	array	$post		The data regarding the post (media) in question
	 * @param	array	$attachment	The data regarding additional data for the media
	 * @return	array				An array of the details of the post in question
	 */
	 
	function tqmcf_save( $post, $attachment ) {
		$tqmcf = get_tqmcf();
		
		foreach ( $tqmcf as $field ) {
			if(trim($attachment[$field["slug"]]) != "") {
				update_post_meta( $post['ID'], $field["slug"], $attachment[$field["slug"]] );
			}
		}
		
		return $post;
	}
}


/**
 * Creating error messages
 * 
 * This function creates error messages and adds some formatting
 * to them, to make them more user friendly. 
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @param	string	$title		Short description of the error
 * @param	string	$message	Long description of the error, and/or direction to solve it
 */

function tq_plugin_error( $title, $description ) {
	$message  = '<div class="wrap">';
	$message .= '<h2>' . TQ_PLUGIN_LONGNAME . '</h2>';  
	$message .= '<h3>' . $title . '</h3>';
	$message .= '<p>' . $description . '</p>';
	$message .= '<a href="' . $_SERVER['HTTP_REFERER'] . '">go back</a>';
	$message .= '</div>';
	
	echo $message;
}

/**
 * Get all user defined custom fields
 * 
 * The function retrieves an option from the database which contains all the 
 * suer defined fields. This is stored as a serialized multi-dimensional 
 * array in the database, so we need to unserialize it before returning it
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @return	array	an array of all user defined custom fields
 */

function get_tqmcf() {
	$tqmcf = get_option( 'tastique_media_custom_fields' );
	if (is_string($tqmcf))
		$tqmcf = unserialize($tqmcf);
	return $tqmcf;
}

/**
 * Main class loader
 * 
 * The function instantiates our main class
 *
 * @package Media Custom Fields
 * @since 1.0
 */
function load_tqmcf() {
	new tastique_media_custom_fields();
}

/*
Hook the function that instantiates our class to Wordpress, at the point when the plugin is loaded.
This means that the given function will be run when the Wordpress function "plugin_loaded" has been run.
*/
add_action( 'plugins_loaded', 'load_tqmcf' );


/**
 * Add a user defined custom field
 * 
 * The function adds a custom field by creating an array containing the data for the custom field. 
 * It them appends it to the list of existing fields, by serializing it and saving it to the 
 * database.
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @global	object	$wpdb	The database interaction object 
 */
function tqmcf_user_field_add() {
	global $wpdb;
	$tqmcf = get_tqmcf();
	
	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'tqmcf-add_field' ) ) {
		$title   = 'Sorry, your nonce did not verify';
		$message = 'It seems that you are either logged out, or trying to access this page in an unusual way. Log back into your website and try again.';
	   	   
	    exit( tq_plugin_error( $title, $message ) );
	}
	else {
	
		$fields_count = ( $tqmcf ) ? count( $tqmcf ) : 0;
		
		$new_field = array (
			$fields_count => array (
				'ID'		  => $fields_count,
				'slug'		  => "tqmcf_".sanitize_title_with_dashes($_POST['custom_field_name']),
				'name'        => stripslashes($_POST['custom_field_name']),
				'description' => stripslashes($_POST['custom_field_description'])
			)
		);
		
		if( $tqmcf ) {
			$new_fields = array_merge( $tqmcf, $new_field );
		}
		else {
			$new_fields = $new_field;
		}
					
		update_option( 'tastique_media_custom_fields', $new_fields );
	}
}


/**
 * Edits a user defined custom field
 * 
 * The function edits fields by modifying its details in the options table. 
 * Meta Keys for the wp_postmeta table are generated from the name of the 
 * custom field, so these must all be renamed. 
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @global	object	$wpdb	The database interaction object 
 */
function tqmcf_user_field_edit() {
	global $wpdb;
	$tqmcf = get_tqmcf();

	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'tqmcf-edit_field' ) ) {
		$title   = 'Sorry, your nonce did not verify';
		$message = 'It seems that you are either logged out, or trying to access this page in an unusual way. Log back into your website and try again.';
	 
	    exit( tq_plugin_error( $title, $message ) );
	}
	else {
	
		$tqmcf[$_POST["id"]] = array(
			"ID" => $_POST["id"],
			"name" => stripslashes($_POST["custom_field_name"]),
			"slug" => $tqmcf[$_POST["id"]]["slug"],
			"description" => stripslashes($_POST["custom_field_description"])
		);
		
			
		update_option( 'tastique_media_custom_fields', $tqmcf );
	}
}




/**
 * Restores a user defined custom field
 * 
 * The function restores fields by modifying the tastique_media_custom_fields
 * option in the database. It adds a new custom field based on the slug of an 
 * old deleted one.
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @global	object	$wpdb	The database interaction object 
 */
function tqmcf_user_field_restore() {
	global $wpdb;
	$tqmcf = get_tqmcf();

	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'tqmcf-restore_field' ) ) {
		$title   = 'Sorry, your nonce did not verify';
		$message = 'It seems that you are either logged out, or trying to access this page in an unusual way. Log back into your website and try again.';
	 
	    exit( tq_plugin_error( $title, $message ) );
	}
	else {
	
		$new_value = count($tqmcf);
	
		$tqmcf[$new_value] = array(
			"ID" => $new_value,
			"name" => str_replace("-", " ", $_POST["slug"]),
			"slug" => $_POST["slug"],
			"description" => ""
		);
		
			
		update_option( 'tastique_media_custom_fields', $tqmcf );
	}
}



/**
 * Delete the data of a deleted field
 * 
 * The function deletes the data of a deleted custom field by removing all the custom postmeta 
 * from the wordpress postmeta table
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @global	object	$wpdb	The database interaction object 
 */
function tqmcf_user_field_delete_data() {
	global $wpdb;

	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'tqmcf-delete_field_data' ) ) {
		$title   = 'Sorry, your nonce did not verify';
		$message = 'It seems that you are either logged out, or trying to access this page in an unusual way. Log back into your website and try again.';
	 
	    exit( tq_plugin_error( $title, $message ) );
	}
	else {
	
		$wpdb->query("DELETE FROM wp_postmeta WHERE meta_key = '$_POST[slug]' ");	
			
	}
}


/**
 * Deletes a user defined custom field
 * 
 * The function deletes fields by removing them from the options in the database.
 * Optionally, if the user wants it, metadata entered for media items into these
 * fields can all be deleted.
 *
 * @package Media Custom Fields
 * @since 1.0
 *
 * @global	object	$wpdb	The database interaction object 
 */
function tqmcf_user_field_delete() {
	global $wpdb;
	$tqmcf = get_tqmcf();

	if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'tqmcf-delete_field' ) ) {
		$title   = 'Sorry, your nonce did not verify';
		$message = 'It seems that you are either logged out, or trying to access this page in an unusual way. Log back into your website and try again.';
	   
	   exit( tq_plugin_error( $title, $message ) );
	}
	else {
		
		if( $_POST['custom_field_data_delete'] ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->postmeta WHERE meta_key = %s", $tqmcf[$_POST["id"]]["slug"] ) );
		}


		unset( $tqmcf[$_POST['id']] );
			
		$fields = serialize( $tqmcf );
		
		update_option( 'tastique_media_custom_fields', $fields );
		

	}
}

/** 
 * This file handles everything needed to create the administration pages
 */
include("administration.php");
?>
