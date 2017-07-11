<?php
// Additional functions used by the a-staff plugin



// Gets the available default and user-defined image sizes. Used by the image size selector dropdown on the settings page
function a_staff_get_image_sizes () {

	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {

		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );

		}

		elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {

			$sizes[$_size] = array(
				'width'  => $_wp_additional_image_sizes[$_size]['width'],
				'height' => $_wp_additional_image_sizes[$_size]['height'],
				'crop'   => $_wp_additional_image_sizes[$_size]['crop'],
			);

		}

	}

	// Sort them by item width
	uasort( $sizes, function( $a, $b ) {
		if ( $a["width"] == $b["width"] ) {
	        return 0;
	    }
	    return ( $a["width"] < $b["width"] ) ? -1 : 1;
	} );

	return $sizes;

}



// Creates an array of the $id staff member's social icons
function a_staff_get_members_socicons () {

	$social_network_blocks = tpl_get_option( 'a_staff_social_networks' );
	$output = array();

	// Do it only if any social network is found
	if ( !empty( $social_network_blocks[0] ) ) {

		foreach ( $social_network_blocks as $key => $network ) {

			$sname = sanitize_title( $network["network_name"] );
			$field_name = 'a_staff_member_social_' . $sname;
			$network_url = tpl_get_option( $field_name );

			if ( $network_url != '' ) {

				if ( tpl_get_option( 'a_staff_social_target' ) == 'new' ) {
					$newtab = 'yes';
				}
				else {
					$newtab = 'no';
				}

				$icon_args = array(
					"name"		=> 'a_staff_social_networks/' . $key . '/network_icon',
					"size"		=> esc_attr( tpl_get_option( 'a_staff_social_icon_size' ) ),
					"url"		=> esc_url( $network_url ),
					"newtab"	=> $newtab,
					"title"		=> esc_attr( $network["network_name"] ),
					"class"		=> 'fa-fw',
				);

				if ( tpl_get_option( 'a_staff_icon_colors' ) == 'yes' ) {
					$icon_args["color"] = tpl_get_option( 'a_staff_social_networks/' . $key . '/icon_color' );
				}

				// We use Themple Framework's inner tool to format the icon with the $args above
				$output[$sname] = tpl_get_value( $icon_args );

			}

		}

	}

	return $output;

}



// Creates a formatted excerpt of the $text (html text) variable. $excerpt_length is in words
function a_staff_trim_excerpt( $text, $excerpt_length = 55 ) {

	// Other than $allowed_tags will be removed from the $text
	$allowed_tags = array( 'b' ,'strong', 'i', 'em', 'br', 'span', 'a' );
    $text = apply_filters( 'the_content', $text );
    $text = str_replace( '\]\]\>', ']]&gt;', $text );

	$allowed_tags_text = '';
	foreach ( $allowed_tags as $tag ) {
		$allowed_tags_text .= '<' . $tag . '>,';
	}
	$allowed_tags_text = rtrim( $allowed_tags_text, ',' );

    $text = strip_tags( $text, $allowed_tags_text );
    $words = explode( ' ', $text, $excerpt_length + 1 );

    if ( count( $words ) > $excerpt_length ) {
        array_pop( $words );
		$open_tags = array();

		foreach ( $words as $i => $word ) {
			$word = $word . ' ';
			foreach ( $allowed_tags as $tag ) {
				if ( strpos( $word, '<' . $tag . ' ' ) !== false || strpos( $word, '<' . $tag . '>' ) ) {
					if ( $tag != 'br' ) {
						$open_tags[] = $tag;
					}
				}
				if ( strpos( $word, '</' . $tag . ' ' ) !== false || strpos( $word, '</' . $tag . '>' ) ) {
					for ( $j = count( $open_tags ) - 1; $j >= 0; $j-- ) {
					    if ( $open_tags[$j] == $tag ) {
							array_splice( $open_tags, $j, 1 );
						}
					}
				}
			}
		}

		$words[] = '...';
		if ( !empty( $open_tags ) ) {
			foreach ( array_reverse( $open_tags ) as $tag ) {
				$words[] = '</' . $tag . '>';
			}
		}
		$text = implode( ' ', $words );
    }

	$text = wpautop( $text );
	$text = str_replace( array( '<p>', '</p>' ), '', $text );

    return $text;

}
