<?php
// Shortcodes added by the a-staff plugin



// Add Shortcode: [a-staff]
function a_staff_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'class'		=> '',
			'ids'		=> '',
			'exclude'	=> '',
			'columns'	=> 0,
		),
		$atts
	);

	// Load the template
	$template = tpl_kses( tpl_get_option( 'a_staff_box_template' ) );

	// Register the shorttags in the template
	$shorttags = array(
		0	=> '{MEMBER_NAME}',
		1	=> '{MEMBER_EXCERPT}',
		2	=> '{MEMBER_TITLE}',
		3	=> '{MEMBER_SOCICONS}',
		4	=> '{MEMBER_URL}',
		5	=> '{MEMBER_IMAGE}',
		6	=> '{MEMBER_PHONE}',
	);

	// Add extra classes to the container if needed
	if ( $atts["class"] != '' ) {
		$extra_class = ' ' . $atts["class"];
	}
	else {
		$extra_class = '';
	}

	// Setting up the number of columns
	$extra_class .= ' a-staff-cols-';
	if ( $atts["columns"] != 0 ) {
		$extra_class .= $atts["columns"];
	}
	else if ( tpl_get_option( 'a_staff_columns' ) != '' ) {
		$extra_class .= tpl_get_option( 'a_staff_columns' );
	}
	else {
		$extra_class .= '3';
	}

	// If no social icons are added in settings, add a special extra class
	$social_network_blocks = tpl_get_option( 'a_staff_social_networks' );
	if ( empty( $social_network_blocks[0] ) ) {
		$extra_class .= ' a-staff-noicons';
	}

	if ( tpl_get_option( 'a_staff_enable_phone_numbers' ) != 'yes' ) {
		$extra_class .= ' a-staff-nophone';
	}

	// Initialize the output
	$output = '<div class="a-staff-members' . esc_attr( $extra_class ) . '">';

	// Now run a loop with the system's Staff Members
	$args = array (
		'post_type'              => array( 'a-staff' ),
		'nopaging'               => true,
		'posts_per_page'         => '-1',
		'ignore_sticky_posts'    => true,
		'order'                  => 'ASC',
		'orderby'                => 'menu_order',
	);

	// Display only the posts with specific IDs if the ids="" parameter is not empty
	if ( $atts["ids"] != '' ) {
		$args["post__in"] = explode( ',', $atts["ids"] );
	}

	// Or exclude some posts in the other case
	if ( $atts["exclude"] != '' ) {
		$args["post__not_in"] = explode( ',', $atts["exclude"] );
	}

	// Do the query
	$query = new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			// Find the member's picture URL
			$thumb_id = get_post_thumbnail_id();
			if ( $thumb_id != '' ) {
				$thumb_url_array = wp_get_attachment_image_src( $thumb_id, tpl_get_option( 'a_staff_image_size' ), true );
				$thumb_url = $thumb_url_array[0];
			}
			else {
				$thumb_url = plugins_url( 'assets/avatar.png', dirname( __FILE__ ) );
			}

			// Set up the list of the member's titles
			$titles = wp_get_post_terms( get_the_ID(), 'a-staff-member-titles' );
			$titles_text = '';
			$i = 0;
			foreach ( $titles as $title ) {
				if ( $i > 0 ) {
					$titles_text .=', ';
				}
				$titles_text .= $title->name;
				$i++;
			}

			// Set up the member bio text
			if ( tpl_get_option( 'a_staff_format_bio' ) == 'yes' ) {
				if ( has_excerpt() ) {
					$excerpt = nl2br( get_the_excerpt() );
				}
				else {
					$excerpt = get_the_content();
				}
				$excerpt = a_staff_trim_excerpt( $excerpt, tpl_get_option( 'a_staff_bio_length' ) );
			}
			else {
				$excerpt = get_the_excerpt();
			}

			// Set up the social icons
			$socicons = a_staff_get_members_socicons();
			$social_text = '';

			foreach ( $socicons as $icon ) {
				$social_text .= '<li>' . $icon . '</li>';
			}

			// The shorttags will be changed to these values
			$values = array(
				0	=> esc_html( get_the_title() ),
				1	=> tpl_kses( $excerpt ),
				2	=> esc_html( $titles_text ),
				3	=> $social_text,
				4	=> esc_url( get_the_permalink() ),
				5	=> esc_url( $thumb_url ),
				6	=> esc_html( tpl_get_value( 'a_staff_member_phone' ) ),
			);

			// Here we do the string replacements
			$output .= str_replace( $shorttags, $values, $template );

		}
	} else {
		// no posts found
	}

	$output .= '</div>';

	// Restore original Post Data
	wp_reset_postdata();

	if ( tpl_get_option( 'a_staff_load_css' ) == 'yes' ) {
		wp_enqueue_style( 'a-staff-style' );
	}

	// And return the output
	return $output;

}

add_shortcode( 'a-staff', 'a_staff_shortcode' );
