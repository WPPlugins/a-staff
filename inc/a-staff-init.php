<?php
// Initial settings for the a-staff plugin



// The minimum required Themple Framework version to use with this plugin. It's needed to ensure the plugin is compatible with the theme
define( 'A_STAFF_REQ_TPL_VERSION', '1.2b' );



// Themple Lite Purgatory
global $tpl_load_version;

// Defining the Themple version included in this plugin. Change the version line if you updated the framework.
$a_staff_tpl_version = array(
	"type"		=> 'plugin',
	"name"		=> 'a-staff',
	"version"	=> '1.2b',
);
if ( !is_array( $tpl_load_version ) ) {
	$tpl_load_version = $a_staff_tpl_version;
}
else {
	if ( version_compare( $tpl_load_version["version"], $a_staff_tpl_version["version"] ) < 0 ) {
		$tpl_load_version = $a_staff_tpl_version;
	}
}



// Detect if there is a built-in Themple version in the theme. If yes, we'll use that version. If no, we'll use the plugin's own version. In the different cases it needs to be loaded with different hooks.
if ( get_option( 'tpl_version' ) !== false || defined( 'THEMPLE_THEME' ) ) {
	add_action( 'init', 'a_staff_init', 11 );
}
else {
	add_action( 'after_setup_theme', 'a_staff_init' );
}

// Loads the correct version of the framework
function a_staff_init() {

	global $tpl_load_version, $a_staff_tpl_version;

	// Check if the theme contains a version of Themple Framework and connect to it if the version numbers are OK
	if ( get_option( 'tpl_version' ) !== false || defined( 'THEMPLE_THEME' ) ) {

		if ( defined( 'THEMPLE_VERSION' ) ) {
			$tpl_version = THEMPLE_VERSION;
		}
		else {
			$tpl_version = get_option( 'tpl_version' );
		}

		$tpl_load_version = array(
			"type"		=> 'theme',
			"name"		=> get_stylesheet(),
			"version"	=> $tpl_version,
		);

		// Show an error message if the theme's Themple version is too old
		if ( version_compare( $tpl_version, A_STAFF_REQ_TPL_VERSION ) < 0 ) {
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-error"><p><b>a-staff:</b> ' . sprintf( __( 'It looks like the version of Themple Framework (%1$s) you are using in your current theme is older than the framework version required by the a-staff plugin (%2$s). Please update your theme to the latest version or contact your web developer.', 'a-staff' ), $tpl_version, A_STAFF_REQ_TPL_VERSION ) . '</p></div>';
			} );
		}

		if ( get_option( 'tpl_version' ) ) {
			require_once get_template_directory() . "/framework/themple.php";
		}

	}

	// If we use a non-Themple-based theme, go with the plugin's built-in Themple Lite version
	else if ( $tpl_load_version["type"] == 'plugin' && $tpl_load_version["name"] == $a_staff_tpl_version["name"] ) {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . "framework/themple.php";

		// Load the framework's l10n files in this case
		$mo_filename = plugin_dir_path( dirname( __FILE__ ) ) . 'framework/languages/' . get_locale() . '.mo';
		if ( is_admin() && file_exists( $mo_filename ) ) {
			load_textdomain( 'themple', $mo_filename );
		}

	}

}



// This function is needed for interpreting the Settings page settings.
function a_staff_settings () {

	tpl_settings_page( 'a_staff_settings');

}



// Font Awesome CSS loader function. Loads the FA CSS file if it's not yet available in the front end
function a_staff_fa_css() {

	wp_enqueue_style( 'font-awesome', plugins_url( 'assets/font-awesome.min.css', dirname( __FILE__ ) ) );

}



// Adding some extra settings just to make sure JS works fine
add_filter( 'tpl_admin_js_strings', 'a_staff_admin_js_values', 10, 1 );

function a_staff_admin_js_values( $values ) {

	$values["remover_confirm"] = 'yes';
	$values["pb_fewer_confirm"] = 'yes';
	$values["pb_fewer_instances"] = '';

	return $values;
}



// Add the default image size for the plugin
add_filter( 'tpl_image_sizes', 'a_staff_image_sizes', 10, 1 );

function a_staff_image_sizes( $image_sizes = array() ) {

	// The post thumbnail in sidebar view
	$image_sizes["a-staff-default"] = array(
		'title'		=> __( 'a-staff Default', 'a-staff' ),
		'width'		=> 480,
		'height'	=> 480,
		'crop'		=> array( 'center', 'top' ),
		'select'	=> true,
	);

	return $image_sizes;

}



// Load the plugin's front end CSS if it's enabled in admin
add_action( 'wp_enqueue_scripts', function() {

	if ( tpl_get_option( 'a_staff_load_css' ) == 'yes' ) {

		wp_register_style( 'a-staff-style', plugins_url( 'assets/a-staff.css', dirname( __FILE__ ) ), array(), A_STAFF_VERSION );

		// Add some responsive code if it was enabled in plugin settings
		if ( tpl_get_option( 'a_staff_responsive' ) == 'yes' ) {

			$custom_css = '@media (max-width: ' . tpl_get_value( 'a_staff_responsive_breakpoints/0/breakpoint_1' ) . ') {
				.a-staff-cols-5 .a-staff-member-box-wrapper, .a-staff-cols-6 .a-staff-member-box-wrapper { width: 33.3333%; }
			}
			@media (max-width: ' . tpl_get_value( 'a_staff_responsive_breakpoints/0/breakpoint_2' ) . ') {
				.a-staff-cols-3 .a-staff-member-box-wrapper, .a-staff-cols-4 .a-staff-member-box-wrapper, .a-staff-cols-5 .a-staff-member-box-wrapper, .a-staff-cols-6 .a-staff-member-box-wrapper { width: 50%; }
			}
			@media (max-width: ' . tpl_get_value( 'a_staff_responsive_breakpoints/0/breakpoint_3' ) . ') {
				.a-staff-cols-2 .a-staff-member-box-wrapper, .a-staff-cols-3 .a-staff-member-box-wrapper, .a-staff-cols-4 .a-staff-member-box-wrapper, .a-staff-cols-5 .a-staff-member-box-wrapper, .a-staff-cols-6 .a-staff-member-box-wrapper { width: 100%; }
			}';
			wp_add_inline_style( 'a-staff-style', esc_html( $custom_css ) );

		}

	}

} );



// Rewrite rules update to avoid 404 errors
function a_staff_flush_rewrites() {

	a_staff_cpt();
	flush_rewrite_rules();

}
