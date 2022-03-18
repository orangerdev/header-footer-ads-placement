<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ridwan-arifandi.com
 * @since             1.0.0
 * @package           Hfads
 *
 * @wordpress-plugin
 * Plugin Name:       HF Ads Placement
 * Plugin URI:        https://ridwan-arifandi.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ridwan Arifandi
 * Author URI:        https://ridwan-arifandi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hfads
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HFADS_VERSION', 	'1.0.0' );
define( 'HFADS_MODE',	  	'production'); // remember to switch back to 'production' when you push this file
define( 'HFADS_DIR', 		plugin_dir_path(__FILE__) );
define( 'HFADS_URL', 		plugin_dir_url( __FILE__ ) );

if( file_exists( HFADS_DIR . 'vendor/autoload.php' ) ) {
	require_once( HFADS_DIR . 'vendor/autoload.php' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hfads-activator.php
 */
function activate_hfads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hfads-activator.php';
	Hfads_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hfads-deactivator.php
 */
function deactivate_hfads() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hfads-deactivator.php';
	Hfads_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hfads' );
register_deactivation_hook( __FILE__, 'deactivate_hfads' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hfads.php';

if(!function_exists('__debug')) :
function __debug()
{
	$bt     = debug_backtrace();
	$caller = array_shift($bt);
	$args   = [
		"file"  => $caller["file"],
		"line"  => $caller["line"],
		"args"  => func_get_args()
	];

	if ( class_exists( 'WP_CLI' ) ) :
		?><pre><?php print_r($args); ?></pre><?php
	else :
		do_action('qm/info', $args);
	endif;
}
endif;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hfads() {

	$plugin = new Hfads();
	$plugin->run();

}
run_hfads();
