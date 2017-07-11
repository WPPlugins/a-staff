<?php

// if uninstall.php is not called by WordPress, die
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}


// Do the delete process only if we chose it in a-staff Settings
$a_staff_settings = get_option( 'a_staff_settings' );


if ( $a_staff_settings["a_staff_delete_data"][0] == 'yes' ) {


	// Delete the posts and the metadata connected to the posts
	$args = array (
		'post_type'             => array( 'a-staff' ),
		'posts_per_page'        => '-1',
		'post_status'			=> 'any',
	);

	$a_staff_posts = new WP_Query( $args );


	// Run a loop through all posts added by the plugin and do the hard delete command
	$relationships_posts = array();

	if ( $a_staff_posts->have_posts() ) {
		while ( $a_staff_posts->have_posts() ) {
			$a_staff_posts->the_post();

			$relationships_posts[] = get_the_ID();
			wp_delete_post( get_the_ID(), true );
		}
	}


	// Delete the taxonomy terms associated with the Member Titles taxonomy
	global $wpdb;

	$terms = $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s') ORDER BY t.name ASC", 'a-staff-member-titles' ) );

	// Delete Terms
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$wpdb->delete( $wpdb->term_taxonomy, array( 'term_taxonomy_id' => $term->term_taxonomy_id ) );
			$wpdb->delete( $wpdb->terms, array( 'term_id' => $term->term_id ) );
		}
	}

	if ( $relationships_posts ) {
		foreach ( $relationships_posts as $r_post ) {
			$wpdb->delete( $wpdb->term_relationships, array( 'object_id' => $r_post ) );
		}
	}

	// Delete Taxonomy
	$wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => 'a-staff-member-titles' ), array( '%s' ) );


	// Delete the central plugin settings
	delete_option( 'a_staff_settings' );
	delete_option( 'a-staff-member-titles_children' );


}
