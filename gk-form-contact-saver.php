<?php
/*
  Plugin Name: GK Form Contact Saver
  Plugin URI: http://www.greateck.com/
  Description: Create contacts from front end form screen data via AJAX
  Version: 0.1.0
  Author: mcfarhat
  Author URI: http://www.greateck.com
  License: GPLv2
 */

if ( ! function_exists('gk_create_contacts_type') ) {

// Register Contact Custom Post Type
function gk_create_contacts_type() {

	$labels = array(
		'name'                  => _x( 'Contacts', 'Post Type General Name', 'gk_unload_contacts' ),
		'singular_name'         => _x( 'Contact', 'Post Type Singular Name', 'gk_unload_contacts' ),
		'menu_name'             => __( 'Contacts', 'gk_unload_contacts' ),
		'name_admin_bar'        => __( 'Contact', 'gk_unload_contacts' ),
		'archives'              => __( 'Contact Archives', 'gk_unload_contacts' ),
		'parent_item_colon'     => __( 'Parent Contact:', 'gk_unload_contacts' ),
		'all_items'             => __( 'All Contacts', 'gk_unload_contacts' ),
		'add_new_item'          => __( 'Add New Contact', 'gk_unload_contacts' ),
		'add_new'               => __( 'Add Contact', 'gk_unload_contacts' ),
		'new_item'              => __( 'New Contact', 'gk_unload_contacts' ),
		'edit_item'             => __( 'Edit Contact', 'gk_unload_contacts' ),
		'update_item'           => __( 'Update Contact', 'gk_unload_contacts' ),
		'view_item'             => __( 'View Contact', 'gk_unload_contacts' ),
		'search_items'          => __( 'Search Contact', 'gk_unload_contacts' ),
		'not_found'             => __( 'Not found', 'gk_unload_contacts' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'gk_unload_contacts' ),
		'featured_image'        => __( 'Featured Image', 'gk_unload_contacts' ),
		'set_featured_image'    => __( 'Set featured image', 'gk_unload_contacts' ),
		'remove_featured_image' => __( 'Remove featured image', 'gk_unload_contacts' ),
		'use_featured_image'    => __( 'Use as featured image', 'gk_unload_contacts' ),
		'insert_into_item'      => __( 'Insert into item', 'gk_unload_contacts' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'gk_unload_contacts' ),
		'items_list'            => __( 'Contacts list', 'gk_unload_contacts' ),
		'items_list_navigation' => __( 'Contacts list navigation', 'gk_unload_contacts' ),
		'filter_items_list'     => __( 'Filter Contacts list', 'gk_unload_contacts' ),
	);
	$args = array(
		'label'                 => __( 'Contact', 'gk_unload_contacts' ),
		'description'           => __( 'Contact Type', 'gk_unload_contacts' ),
		'labels'                => $labels,
		'supports'              => array( ),//'thumbnail', 'custom-fields',//'title', 
		'taxonomies'            => array( ),//'category', 'post_tag'
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 65,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'capabilities' => array(
			'create_posts' => 'do_not_allow', //to prevent from manually adding this custom post type false < WP 4.5, credit @Ewout
		),
		'map_meta_cap' => true, // Set to `false`, if users are not allowed to edit/delete existing posts		
	);
	register_post_type( 'gk_unload_contacts', $args );

}
add_action( 'init', 'gk_create_contacts_type', 0 );

}

// Change the columns for the edit CPT screen
function gk_change_columns( $cols ) {
  $cols = array(
    'cb'       => '<input type="checkbox" />',
	'contact_date'      => __( 'Contact Date',      'gk_unload_contacts' ),
    'fname'      		=> __( 'First Name',      'gk_unload_contacts' ),
	'mname'      		=> __( 'Middle Initial',      'gk_unload_contacts' ),
	'lname'      		=> __( 'Last Name',      'gk_unload_contacts' ),
	'email'     		=> __( 'Email',      'gk_unload_contacts' ),
	'pphone'     		=> __( 'Primary Phone',      'gk_unload_contacts' ),
	'mphone'     		=> __( 'Mobile Phone',      'gk_unload_contacts' ),
	'stadd'				=> __( 'Street Address',      'gk_unload_contacts' ),
	'city'				=> __( 'City',      'gk_unload_contacts' ),
	'state'				=> __( 'State',      'gk_unload_contacts' ),
	'zip'				=> __( 'Zip',      'gk_unload_contacts' ),
	'dob'				=> __( 'DOB',      'gk_unload_contacts' ),
	'lang'				=> __( 'Language',      'gk_unload_contacts' ),
	'refer'				=> __( 'Referrer', 		'gk_unload_contacts' ),
  );
  return $cols;
}
add_filter( "manage_gk_unload_contacts_posts_columns", "gk_change_columns" );

function gk_custom_columns( $column, $post_id ) {
	echo get_post_meta( $post_id, $column, true);
}
add_action( "manage_gk_unload_contacts_posts_custom_column", "gk_custom_columns", 10, 2 );

// Make these columns sortable
function gk_sortable_columns() {
  return array(
    'fname'	  		 	=> 'fname',
	'mname'	   			=> 'mname',
	'lname'	   			=> 'lname',
	'email'			    => 'email',
	'city'      		=> 'city',
	'state'      		=> 'state',
	'zip'      			=> 'zip',
	'refer'				=> 'refer',
  );
}
add_filter( "manage_edit-gk_unload_contacts_sortable_columns", "gk_sortable_columns" );

// making this available for Ajax requests
add_action( 'wp_ajax_nopriv_store_contact_form_unload', 'gk_store_contact' );
add_action( 'wp_ajax_store_contact_form_unload', 'gk_store_contact' );

function gk_store_contact() {
	$post_id = wp_insert_post(array (
	   'post_type' => 'gk_unload_contacts',
	   'post_title' => '',
	   'post_content' => '',
	   'post_status' => 'publish',
	   'comment_status' => 'closed',
	   'ping_status' => 'closed',
	));
	if ($post_id) {
		foreach ($_POST as $pkey => $pval){
			//skip saving the action
			if ($pkey == 'action')
				continue;
			add_post_meta($post_id, $pkey, $pval);
		}
		//also saving the current date
		add_post_meta($post_id, 'contact_date', date("m/d/Y"));
	}
}

//Allow calls to be made from site outside domain to this site to perform AJAX. This might be necessary if form is on a different servers, but could pause a security risk.
/*
add_filter('allowed_http_origins', 'gk_add_allowed_origins');

function gk_add_allowed_origins($origins) {
    $origins[] = '*';
    return $origins;
}
*/

?>