<?php

/*
Themple Framework main file
For more information and documentation, visit [http://a-idea.studio/themple]
*/



// Version number of the framework
define( 'THEMPLE_VERSION', '1.2b' );

// Loading basic configuration for this installation
require_once "themple-config.php";

// Loading the Themple Framework CORE file
require_once "tpl-inc/themple-core.php";

// If available, load the theme specific functions for the framework
if ( file_exists( tpl_base_dir() . "/framework/tpl-inc/themple-theme.php" ) ) {
	require_once "tpl-inc/themple-theme.php";
}
