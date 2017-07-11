<?php
// Custom Post Type(s) added by the a-staff plugin



// Register Custom Post Type: Staff Member (a-staff)
add_action( 'init', 'a_staff_cpt', 0 );

function a_staff_cpt() {

	$labels = array(
		'name'                  => _x( 'Staff Members', 'Post Type General Name', 'a-staff' ),
		'singular_name'         => _x( 'Staff Member', 'Post Type Singular Name', 'a-staff' ),
		'menu_name'             => __( 'Staff Members', 'a-staff' ),
		'name_admin_bar'        => __( 'Staff Member', 'a-staff' ),
		'archives'              => __( 'Member Archives', 'a-staff' ),
		'parent_item_colon'     => __( 'Parent Member:', 'a-staff' ),
		'all_items'             => __( 'All Members', 'a-staff' ),
		'add_new_item'          => __( 'Add New Member', 'a-staff' ),
		'add_new'               => __( 'Add New', 'a-staff' ),
		'new_item'              => __( 'New Member', 'a-staff' ),
		'edit_item'             => __( 'Edit Member', 'a-staff' ),
		'update_item'           => __( 'Update Member', 'a-staff' ),
		'view_item'             => __( 'View Member', 'a-staff' ),
		'search_items'          => __( 'Search Member', 'a-staff' ),
		'not_found'             => __( 'Not found', 'a-staff' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'a-staff' ),
		'featured_image'        => __( 'Photo of the Member', 'a-staff' ),
		'set_featured_image'    => __( 'Set Member\'s photo', 'a-staff' ),
		'remove_featured_image' => __( 'Remove Member\'s photo', 'a-staff' ),
		'use_featured_image'    => __( 'Use as Member\'s photo', 'a-staff' ),
		'insert_into_item'      => __( 'Insert into Member\'s page', 'a-staff' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Member\'s page', 'a-staff' ),
		'items_list'            => __( 'Members list', 'a-staff' ),
		'items_list_navigation' => __( 'Members list navigation', 'a-staff' ),
		'filter_items_list'     => __( 'Filter Members list', 'a-staff' ),
	);
	$args = array(
		'label'                 => __( 'Staff Member', 'a-staff' ),
		'description'           => __( 'Staff members of your team', 'a-staff' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 25,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'				=> array( 'slug' => 'team' ),
	);
	register_post_type( 'a-staff', $args );

}



// Register Custom Taxonomy: Staff Member Titles
function a_staff_member_titles() {

	$labels = array(
		'name'                       => _x( 'Member Titles', 'Taxonomy General Name', 'a-staff' ),
		'singular_name'              => _x( 'Member Title', 'Taxonomy Singular Name', 'a-staff' ),
		'menu_name'                  => __( 'Member Title', 'a-staff' ),
		'all_items'                  => __( 'All Titles', 'a-staff' ),
		'parent_item'                => __( 'Parent Title', 'a-staff' ),
		'parent_item_colon'          => __( 'Parent Title:', 'a-staff' ),
		'new_item_name'              => __( 'New Member Title', 'a-staff' ),
		'add_new_item'               => __( 'Add New Member Title', 'a-staff' ),
		'edit_item'                  => __( 'Edit Title', 'a-staff' ),
		'update_item'                => __( 'Update Title', 'a-staff' ),
		'view_item'                  => __( 'View Title', 'a-staff' ),
		'separate_items_with_commas' => __( 'You can add titles like "CEO", "Assistant", "Senior Developer", etc. here. Separate titles with commas', 'a-staff' ),
		'add_or_remove_items'        => __( 'Add or remove Member Titles', 'a-staff' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'a-staff' ),
		'popular_items'              => __( 'Popular Titles', 'a-staff' ),
		'search_items'               => __( 'Search Member Titles', 'a-staff' ),
		'not_found'                  => __( 'Not Found', 'a-staff' ),
		'no_terms'                   => __( 'No items', 'a-staff' ),
		'items_list'                 => __( 'Titles list', 'a-staff' ),
		'items_list_navigation'      => __( 'Titles list navigation', 'a-staff' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'a-staff-member-titles', array( 'a-staff' ), $args );

}
add_action( 'init', 'a_staff_member_titles', 0 );
