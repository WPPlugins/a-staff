<?php
/*
Plugin Name: a-staff
Plugin URI:  http://a-idea.studio/a-staff/
Description: Add a team member section easily to your website with this plugin
Version:     1.1
Author:      a-idea studio
Author URI:  http://a-idea.studio/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: a-staff
Domain Path: /languages
*/


// Defining the current plugin version
define( 'A_STAFF_VERSION', '1.1' );


// Including the plugin files
require_once "inc/a-staff-cpt.php";
require_once "inc/a-staff-init.php";
require_once "inc/a-staff-functions.php";
require_once "inc/a-staff-options.php";
require_once "inc/a-staff-shortcode.php";


// Rewrite rules for the CPT added by this plugin
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'a_staff_flush_rewrites' );


// Load the textdomain for i18n
add_action( 'plugins_loaded', function() {
	if ( is_admin() ) {
		load_plugin_textdomain( 'a-staff', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
} );
