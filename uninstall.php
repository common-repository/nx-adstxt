<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'nx-adstxt.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-nx-adstxt-uninstall.php';
NX_Adstxt_Uninstaller::uninstall();