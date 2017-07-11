<?php
// Setting up the options for the a-staff plugin



// Adding the Settings > a-staff Settings page to WP-Admin
add_filter( 'tpl_settings_pages', 'a_staff_settings_page', 10, 1 );

function a_staff_settings_page( $pages ) {

	$pages["a_staff_settings"] = array(
		"page_title"	=> __( 'a-staff Settings', 'a-staff' ),
		"menu_title"	=> __( 'a-staff Settings', 'a-staff' ),
		"capability"	=> 'edit_theme_options',
		"menu_slug"		=> 'a_staff_settings',
		"function"		=> 'a_staff_settings',
		"post_type"		=> 'a_staff_settings',
		"menu_func"		=> 'add_options_page'
	);
	return $pages;

}



// Add plugin-specific sections and options
add_action( 'init', 'a_staff_setup', 30 );

function a_staff_setup() {

	global $tpl_load_version;

	// Dont'd do anything if our version of Themple Framework is lower than the required
	if ( version_compare( $tpl_load_version["version"], A_STAFF_REQ_TPL_VERSION ) < 0 ) {
		return;
	}



	// First set up the required sections

	// Section for the a-staff Social settings
	$section = array (
		"name"			=> 'a_staff_social',
		"tab"			=> __( 'Social', 'a-staff' ),
		"title"			=> __( 'Social Networks setup', 'a-staff' ),
		"description"	=> __( 'Set up the available social networks here for the a-staff plugin', 'a-staff' ),
		"post_type"		=> 'a_staff_settings',
	);
	tpl_register_section ( $section );



	// Section for the a-staff Layout settings
	$section = array (
		"name"			=> 'a_staff_layout',
		"tab"			=> __( 'Layout', 'a-staff' ),
		"title"			=> __( 'Layout settings', 'a-staff' ),
		"description"	=> __( 'Settings for the front end layout of this plugin', 'a-staff' ),
		"post_type"		=> 'a_staff_settings',
	);
	tpl_register_section ( $section );



	// Section for the a-staff System settings
	$section = array (
		"name"			=> 'a_staff_system',
		"tab"			=> __( 'System', 'a-staff' ),
		"title"			=> __( 'System settings', 'a-staff' ),
		"description"	=> __( 'Settings about how the plugin should work under the hood', 'a-staff' ),
		"post_type"		=> 'a_staff_settings',
	);
	tpl_register_section ( $section );



	// And now the options inside the sections

	// Combined field: Social Networks
	$tpl_option_array = array (
		"name"			=> 'a_staff_social_networks',
		"title"			=> __( 'Social Network manager', 'a-staff' ),
		"description"	=> __( 'You can add/remove social networks here that will be available on Staff Member pages', 'a-staff' ),
		"section"		=> 'a_staff_social',
		"type"			=> 'combined',
		"repeat"		=> true,
		"repeat_button_title"	=> __( 'Add Social Network', 'a-staff' ),
		"parts"		=> array(
			array(
				"name"			=> 'network_name',
				"title"			=> __( 'Name of the Social Network', 'a-staff' ),
				"description"	=> __( 'This is the public name of the social network, it should be something like "Facebook", "Twitter", or similar.', 'a-staff' ),
				"type"			=> 'text',
				"placeholder"	=> __( 'Enter the name of the network here, e.g. "Facebook"', 'a-staff' ),
			),
			array(
				"name"			=> 'network_icon',
				"title"			=> __( 'Icon for the Social Network', 'a-staff' ),
				"description"	=> __( 'Choose an icon for the Network', 'a-staff' ),
				"type"			=> 'font_awesome',
			),
			array(
				"name"			=> 'icon_color',
				"title"			=> __( 'Icon color', 'a-staff' ),
				"description"	=> __( 'Set the color of this icon here.', 'a-staff' ),
				"type"			=> 'color',
				"default"		=> '#666666',
				"condition"		=> array(
					array(
						"type"		=> 'option',
						"name"		=> 'a_staff_icon_colors',
						"relation"	=> '=',
						"value"		=> 'yes',
					)
				),
			),
		),
		"less"			=> false,
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: where to open the social links
	$tpl_option_array = array (
		"name"			=> 'a_staff_social_target',
		"title"			=> __( 'Social link target', 'a-staff' ),
		"description"	=> __( 'Should it open in a new browser tab or in the same tab?', 'a-staff' ),
		"section"		=> 'a_staff_social',
		"type"			=> 'select',
		"values"		=> array(
			"same"			=> __( 'Same tab', 'a-staff' ),
			"new"			=> __( 'New tab', 'a-staff' ),
		),
		"default"		=> 'new',
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: default size of the social icons
	$tpl_option_array = array (
		"name"			=> 'a_staff_social_icon_size',
		"title"			=> __( 'Social icon size', 'a-staff' ),
		"description"	=> sprintf( __( 'Size of the icon in %1$sFont Awesome sizes%2$s', 'a-staff' ), '<a href="http://fontawesome.io/examples/#larger" target="_blank">', '</a>' ),
		"section"		=> 'a_staff_social',
		"type"			=> 'select',
		"default"		=> '2x',
		"values"		=> array(
			""				=> __( 'Normal', 'a-staff' ),
			"lg"			=> __( 'Larger', 'a-staff' ),
			"2x"			=> __( 'Double', 'a-staff' ),
			"3x"			=> __( 'Triple', 'a-staff' ),
			"4x"			=> __( '4x', 'a-staff' ),
			"5x"			=> __( '5x', 'a-staff' ),
		),
		"key"			=> true,
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: whether to enable custom colors for icons or not
	$tpl_option_array = array (
		"name"			=> 'a_staff_icon_colors',
		"title"			=> __( 'Enable icon colors?', 'a-staff' ),
		"description"	=> __( 'If enabled, you can specify the color of each social icon separately. Otherwise they will use the default theme colors.', 'a-staff' ),
		"section"		=> 'a_staff_social',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: whether to enable phone numbers for the staff members
	$tpl_option_array = array (
		"name"			=> 'a_staff_enable_phone_numbers',
		"title"			=> __( 'Enable phone numbers?', 'a-staff' ),
		"description"	=> __( 'If enabled, you can add phone numbers to the staff member boxes.', 'a-staff' ),
		"section"		=> 'a_staff_social',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );



	// Number field: number of columns in the shortcode's output
	$tpl_option_array = array (
		"name"			=> 'a_staff_columns',
		"title"			=> __( 'Number of columns', 'a-staff' ),
		"description"	=> __( 'The default number of columns to be used when displaying staff members in the front end. You can override this setting with the shortcode\'s <strong>columns</strong> attribute.<br>
		In the current version 1 to 6 columns are supported.', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'number',
		"min"			=> 1,
		"max"			=> 6,
		"step"			=> 1,
		"default"		=> 3,
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: Should the member bio be formatted with wpautop()?
	$tpl_option_array = array (
		"name"			=> 'a_staff_format_bio',
		"title"			=> __( 'Add some formatting to the member bio?', 'a-staff' ),
		"description"	=> __( 'If eanbled, the new lines will be displayed in the member descriptions.', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'no',
	);
	tpl_register_option ( $tpl_option_array );



	// Number field: number of words to be used when bio is formatted
	$tpl_option_array = array (
		"name"			=> 'a_staff_bio_length',
		"title"			=> __( 'Length of member bio', 'a-staff' ),
		"description"	=> __( 'Maximum length of member bio in words. Currently only available if the bio is formatted.', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'number',
		"min"			=> 1,
		"step"			=> 1,
		"default"		=> 55,
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_staff_format_bio',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: the image size displayed in the member boxes
	$image_sizes = array();

	foreach ( a_staff_get_image_sizes() as $key => $size ) {

		$image_sizes[$key] = $key . ' (' . $size["width"] . 'x' . $size["height"];
		if ( $size["crop"] ) {
			$image_sizes[$key] .= 'c';
		}
		$image_sizes[$key] .= ')';

	}

	$tpl_option_array = array (
		"name"			=> 'a_staff_image_size',
		"title"			=> __( 'Image sizes in member boxes', 'a-staff' ),
		"description"	=> __( 'Which image size should we use in the staff member boxes when it\'s displayed in the front end? (\'c\' at the end means that the image is cropped)', 'a-staff' ) . '<br><a href="https://developer.wordpress.org/reference/functions/add_image_size/" target="_blank">' . __( 'You can read more about WordPress image sizes here.', 'a-staff' ) . '</a>',
		"section"		=> 'a_staff_layout',
		"type"			=> 'select',
		"values"		=> $image_sizes,
		"default"		=> 'a-staff-default',
		"key"			=> true,
	);
	tpl_register_option ( $tpl_option_array );



	// Textarea field: Member box template
	$tpl_option_array = array (
		"name"			=> 'a_staff_box_template',
		"title"			=> __( 'Box template', 'a-staff' ),
		"description"	=> __( 'Template editor for the member boxes. You can use the following shorttags in it:<br>
			<b>{MEMBER_NAME}</b> - Name of the Staff Member (the title of the article)<br>
			<b>{MEMBER_EXCERPT}</b> - Short bio of Staff Member (the excerpt of the article)<br>
			<b>{MEMBER_TITLE}</b> - Title of the Staff Member (Member Title taxonomy), will be separated by commas if there is more<br>
			<b>{MEMBER_SOCICONS}</b> - A list of the Staff Member\'s social links<br>
			<b>{MEMBER_URL}</b> - URL to the Staff Member\'s single page<br>
			<b>{MEMBER_IMAGE}</b> - URL to the Staff Member\'s featured image<br>
			<b>{MEMBER_PHONE}</b> - The Staff Member\'s phone number (if you enabled it on the Social tab)
			', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'textarea',
		"size"			=> 10,
		"default"		=> '<div class="a-staff-member-box-wrapper">
  <div class="a-staff-member-box">
    <a href="{MEMBER_URL}"><img class="a-staff-member-image" src="{MEMBER_IMAGE}" alt="Picture of {MEMBER_NAME}"></a>
    <h3 class="a-staff-member-name"><a href="{MEMBER_URL}">{MEMBER_NAME}</a></h3>
    <p class="a-staff-member-title">{MEMBER_TITLE}</p>
    <p class="a-staff-member-bio">{MEMBER_EXCERPT}</p>
    <ul class="a-staff-member-social">{MEMBER_SOCICONS}</ul>
  </div>
</div>',
		"default_wrapper" => array(
			"before"		=> '<pre>',
			"after"			=> '</pre>',
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: load the front end CSS or not
	$tpl_option_array = array (
		"name"			=> 'a_staff_load_css',
		"title"			=> __( 'Load basic front end CSS?', 'a-staff' ),
		"description"	=> __( 'If Yes is selected, a small CSS file will be loaded in the front end in order to give some shape for the Staff Member boxes.<br>
		Select No if you have your own style rules for a-staff boxes in your theme\'s stylesheets (recommended only for experts).', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'yes',
	);
	tpl_register_option ( $tpl_option_array );



	// Select field: enable responsiveness?
	$tpl_option_array = array (
		"name"			=> 'a_staff_responsive',
		"title"			=> __( 'Responsive behavior', 'a-staff' ),
		"description"	=> __( 'Should the built-in styles support responsive behavior? "No" is only recommended for non-responsive themes.', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'yes',
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_staff_load_css',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Combined field: Responsive breakpoints
	$tpl_option_array = array (
		"name"			=> 'a_staff_responsive_breakpoints',
		"title"			=> __( 'Responsive breakpoints', 'a-staff' ),
		"description"	=> __( 'You can fine-tune here the behavior of the team member columns for smaller screens.', 'a-staff' ),
		"section"		=> 'a_staff_layout',
		"type"			=> 'combined',
		"parts"		=> array(
			array(
				"name"			=> 'breakpoint_1',
				"title"			=> __( 'Breakpoint #1 (5-6 → 3)', 'a-staff' ),
				"description"	=> __( 'Under this window width the 5 and 6 column layouts change to 3-column layout', 'a-staff' ),
				"type"			=> 'number',
				"default"		=> 1200,
				"suffix"		=> 'px',
			),
			array(
				"name"			=> 'breakpoint_2',
				"title"			=> __( 'Breakpoint #2 (3-6 → 2)', 'a-staff' ),
				"description"	=> __( 'Under this window width the 3, 4, 5 and 6 column layouts change to 2-column layout', 'a-staff' ),
				"type"			=> 'number',
				"default"		=> 960,
				"suffix"		=> 'px',
			),
			array(
				"name"			=> 'breakpoint_3',
				"title"			=> __( 'Breakpoint #3 (2-6 → 1)', 'a-staff' ),
				"description"	=> __( 'Under this window width all the multi-column layouts change to 1-column layout', 'a-staff' ),
				"type"			=> 'number',
				"default"		=> 480,
				"suffix"		=> 'px',
			),
		),
		"less"			=> false,
		"condition"		=> array(
			array(
				"type"		=> 'option',
				"name"		=> 'a_staff_load_css',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
			array(
				"type"		=> 'option',
				"name"		=> 'a_staff_responsive',
				"relation"	=> '=',
				"value"		=> 'yes',
			),
		),
	);
	tpl_register_option ( $tpl_option_array );



	// Show the selected social settings on the Member edit page
	$social_network_blocks = tpl_get_option( 'a_staff_social_networks' );

	if ( !empty( $social_network_blocks[0] ) || tpl_get_option( 'a_staff_enable_phone_numbers' ) == 'yes' ) {

		// Section for the Staff Member social networks
		$section = array (
			"name"			=> 'a_staff_member_social',
			"title"			=> __( 'Staff Member\'s social profiles', 'a-staff' ),
			"description"	=> __( 'Enter the URLs for the social links here. If left empty, it won\'t appear in the front end.', 'a-staff' ),
			"post_type"		=> 'a-staff',
		);
		tpl_register_section ( $section );

		// Add the social network options on the single member pages
		if ( !empty( $social_network_blocks[0] ) ) {

			foreach ( $social_network_blocks as $key => $network ) {

				$tpl_option_array = array (
					"name"			=> 'a_staff_member_social_' . sanitize_title( $network["network_name"] ),
					"title"			=> tpl_get_value( array( "name" => 'a_staff_social_networks/' . $key . '/network_icon', "size" => '3x', "class" => 'fa-fw' ) ) . ' ' . $network["network_name"],
					"section"		=> 'a_staff_member_social',
					"type"			=> 'text',
					"placeholder"	=> __( 'URL starting with http://, https://, mailto:, etc.', 'a-staff' ),
				);
				tpl_register_option ( $tpl_option_array );

			}

			// If it's not the theme version of Themple, enqueue the Font Awesome library
			if ( !defined( 'THEMPLE_THEME' ) ) {
				add_action( 'wp_enqueue_scripts', 'a_staff_fa_css' );
			}

		}

		// Add the phone number field on the single member pages
		if ( tpl_get_option( 'a_staff_enable_phone_numbers' ) == 'yes' ) {

			$tpl_option_array = array (
				"name"			=> 'a_staff_member_phone',
				"title"			=> __( 'Member\'s phone number', 'a-staff' ),
				"section"		=> 'a_staff_member_social',
				"type"			=> 'text',
			);
			tpl_register_option ( $tpl_option_array );

		}

	}



	// Select field: should we delete all data upon plugin uninstall
	$tpl_option_array = array (
		"name"			=> 'a_staff_delete_data',
		"title"			=> __( 'Delete data on plugin uninstall?', 'a-staff' ),
		"description"	=> __( 'If yes, all data will be removed when you delete the a-staff plugin. Otherwise the data added by the plugin will be kept in the database.', 'a-staff' ),
		"section"		=> 'a_staff_system',
		"type"			=> 'select',
		"values"		=> array(
			"yes"			=> __( 'Yes', 'a-staff' ),
			"no"			=> __( 'No', 'a-staff' ),
		),
		"default"		=> 'yes',
	);
	tpl_register_option ( $tpl_option_array );



}
