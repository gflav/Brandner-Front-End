=== Media Custom Fields ===
Contributors: danielpataki
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SSFM9F5Q4DBMU
Tags: media, custom fields
Requires at least: 3.0
Tested up to: 3.1.2
Stable tag: 1.0

Allows users to add and manage custom fields for the media post type

== Description ==

Media custom fields is a plugin which enables you to add custom data to your media, much like you can with custom fields in regular posts. 
You can now add data like "Photographer", "Date Taken", "Photographer Website" very easily, and you can manage your custom fields using
a simple, but efficient interface. 

== Installation ==

I highly recommend logging in to your blog, going into the add plugins section and searching for "Media Custom Fields". You will be able to use install button to get the plugin up and running with just one click. 

If you would like to install manually, you can do the following. 

1. Download the plugin and unpack the files. 
2. Upload the folder "media-custom-fields" to the "/wp-content/plugins/" directory
3. Activate the plugin through the "Plugins"" menu in WordPress
4. You can now go to the "Media Custom Fields" page under "Media" in your WordPress admin to create custom fields

== Frequently Asked Questions ==

= Does this plugin integrate well into Wordpress? =

Yes! in addition to giving you the option to add custom fields, your custom fields will show up in all appropriate places. If you go to the media section to manage your media like you usually do, they will show up on the edit media form. The custom fields also show up when you are uploading an image to a post, and in all other expected locations. 

= How does this plugin handle my data? =

Media items are stored in WordPress just like regular posts. This enables us to use native WordPress functions to store and retrieve your custom fields. Due to this, your data is stored in a very future-proof way, in the postmeta table of the WordPress database. 

= Does all my added data disappear when I uninstall the plugin? =

No, your data is not deleted when you uninstall the plugin. Data added to media items are considered to be linked to that media item, and not to the plugin, therefore there is no reason to remove it upon deactivation or uninstallation. 

= Is my data delete when a custom field is deleted using the plugin? =

The plugin does two things. It enables you to create custom fields, and once created, enables you to add data to media items using those custom field. You can delete a custom field at any time, and choose weather you want to delete all the data you have entered using that custom field. 

if you have created a custom field named "Photographer Name" and have added photographers to numerous photos, then subsequently delete the "Photographer Name" custom field, by default, your data will not be deleted with it. Each photo will still have the correct photographer assigned to it, however, you will not be able to modify this information until you recreate the "Photographer Name" custom field.

When deleting a custom field you can choose to delete all the data associated with that field. In this case, each photographer you added to an image will be removed and will no longer be accessible in any way, even if you recreate the same field. 

== Screenshots ==

1. This page enables you to add your custom fields
2. This screenshot shows the usual Wordpress edit media screen with the custom fields enabled
3. You can view images linked to each custom field


== Changelog ==

= 0.5 =
* The first version of the plugin

= 1.0 =
* Fixed a bug where quotes couldn't be handled properly due to poor developer performance (sorry). This also caused problems in deleting some items which should now all be fixed
* Added a view page where you can view all the media items related to a specific field
* A counter is shown on the main page, showing the number of items which have this field filled out
* Custom fields are now stored based on an ID, making them easier to handle on the code end 

== Upgrade Notice ==

I would strongly recommend the 1.0 update to everyone, as it fixes a stupid error on my part (sorry), and also adds a new page where you can view all the media items which have a certain custom field

== Planned Features ==

* Ability to add field data to attachment pages automatically
* Much better UI
* Easier access to media items for editing
