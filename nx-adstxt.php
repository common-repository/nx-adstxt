<?php

/**
 * @author    MAIRDUMONT NETLETIX <info@mairdumont-netletix.com>
 * @link      https://www.mairdumont-netletix.com
 * @copyright 2018 MAIRDUMONT NETLETIX
 * @since     1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       MAIRDUMONT NETLETIX ads.txt Agent
 * Plugin URI:        https://www.mairdumont-netletix.com/
 * Description:       With the ads.txt Agent of MAIRDUMONT NETLETIX not only local ads.txt data in the WordPress can be comfortably created and managed, but also remote ads.txt sources can be integrated via URL.
 * Version:           1.0.1
 * Author:            MAIRDUMONT NETLETIX
 * Author URI:        https://www.mairdumont-netletix.com/
 * Text Domain:       nx-adstxt
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Names */
define( 'NX_ADSTXT_BRAND', 'MD-NX' );
define( 'NX_ADSTXT_FULLBRAND', 'MAIRDUMONT NETLETIX' );
define( 'NX_ADSTXT_FULLTITLE', sprintf(__( '%s ads.txt Agent', 'nx-adstxt'), NX_ADSTXT_FULLBRAND) );
define( 'NX_ADSTXT_TITLE', sprintf(__( '%s ads.txt Agent', 'nx-adstxt'), NX_ADSTXT_BRAND) );

/* Version & Prefixes */
define( 'NX_ADSTXT_VERSION', '1.0.0' );
define( 'NX_ADSTXT_DOMAIN', 'nx-adstxt' );
define( 'NX_ADSTXT_VAR', str_replace('-', '_', NX_ADSTXT_DOMAIN) );

/* Capabilities */
define( 'NX_ADSTXT_CAP_CONFIG', NX_ADSTXT_VAR . "_config" );

/* CRON */
define( 'NX_ADSTXT_CRON', NX_ADSTXT_VAR . "_cron" );

/* Transient Activated */
define( 'NX_ADSTXT_ACTIVATED', NX_ADSTXT_VAR . "_activated" );

function activate_nx_adstxt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nx-adstxt-activator.php';
	NX_Adstxt_Activator::activate();
}

function deactivate_nx_adstxt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nx-adstxt-deactivator.php';
	NX_Adstxt_Deactivator::deactivate();
}

function uninstall_nx_adstxt() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nx-adstxt-uninstall.php';
	NX_Adstxt_Uninstaller::uninstall_hook();
}

require plugin_dir_path( __FILE__ ) . 'includes/class-nx-adstxt.php';

register_activation_hook( __FILE__,   'activate_nx_adstxt');
register_deactivation_hook( __FILE__, 'deactivate_nx_adstxt');
register_uninstall_hook( __FILE__,    'uninstall_nx_adstxt');

function run_nx_adstxt() {
	$plugin = new NX_Adstxt();
	$plugin->run();
}

run_nx_adstxt();