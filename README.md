=== Plugin Name ===

Plugin Name: GK Form Contact Saver
Contributors: mcfarhat
Donate link:
Tags: wordpress, contacts, form, ajax
Requires at least: 4.3
Tested up to: 4.9
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

=== Short Summary ===

The Gk Form Contact Saver plugin provides the capability, through a custom post type called contacts, of saving front end form submissions via AJAX.

== Description ==

The plugin gives your wordpress install the capability to store front end form data that you either created manually, or via any plugin, via AJAX methodology instead of using POST or GET params, into a custom post type called "contacts", specifically stored under wordpress as the post type "gk_unload_contacts".

The front end form needs to adhere to the standard data available for use via the plugin. The data includes following fields: First Name, Middle Initial, Last Name, Email, Primary Phone, Mobile Phone, Street Address, City, State, Zip, DOB, Language, and Referrer.
You do not to make use of all fields, only a subset of such fields. 

Hooking your form into the plugin can be easily done. You would need to add in to you form's code a JS function that you would hook to a new submit button. Alternatively, you can also hook the function upon the page unload functionality, so that any user who visited your page and filled some of the data, will still end up submitting this information to you.

A sample hooking JS function would look as follows (this is hooking into the unload event of the page):
jQuery( window ).on('beforeunload',function($) {
	jQuery.ajax({
		crossOrigin: true,
		url: ajax_url,//needs to be intialized first
		type: "POST",
		async: false,
		data: {
			action : 'store_contact_form_unload',
			fname : jQuery('#GK_CUST_FIRSTNAME').val(),
			mname : jQuery('#GK_CUST_MI').val(),
			lname : jQuery('#GK_CUST_LASTNAME').val(),
			dob	  : jQuery('#GK_CUST_DOB').val(),
			stadd : jQuery('#GK_CM_ADDRESS2').val(),
			city  : jQuery('#GK_CM_CITY').val(),
			state : jQuery('#GK_CM_STATE').val(),
			zip   : jQuery('#GK_CM_ZIP').val(),
			lang  : jQuery('#GK_CUST_BILL_TYPE').val(),
			pphone: jQuery('#GK_PHONE1').val(),
			mphone: jQuery('#GK_PHONE2').val(),
			email : jQuery('#GK_EMAIL_ADDRESS').val(),
			refer : jQuery('#GK_REFERRED_BY').val(),
		},
		/*success : function( response ) {
			console.log( "success response:"+response );
		},*/
	});
	//bring up confirmation to ensure we have enough time to send AJAX
	return;
});			

Once submissions are being saved using the plugin, they will actually show in the backend interface under the relevant "contacts" tab. For screenshots refer to the screenshots section below.

If you require a different set of fields to be supported, need custom work done, or have an idea for a plugin you're ready to fund, check our site at www.greateck.com or contact us at info@greateck.com

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/gk-form-contact-saver/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to a page or create a new page
4. Insert the plugin code into the text editor [collatz_simulator] and save/publish
5. That's it! Now you can visit that page from front end, and play around with the simulator

== Screenshots ==
1. Screenshot of the left tab menu where contacts are accessible https://www.dropbox.com/s/ruzr7ojm555ph4v/contacts_left_menu.png?dl=0
2. Screenshot of the headers/data within the contacts screen https://www.dropbox.com/s/1z4y5401apngidp/header_screen_contacts_submission.png?dl=0

= 0.1.0 =
* Initial Version *
