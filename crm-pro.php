<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://crmproplugin.com
 * @since             1.0.0
 * @package           Crm_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       CRM Pro
 * Plugin URI:        https://crmproplugin.com
 * Description:       Connect Your CRM. Automate on WordPress. Grow Your Leads.
 * Version:           1.0.2
 * Author:            CRM Pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crm-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-crm-pro-activator.php
 */
function activate_crm_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-crm-pro-activator.php';
	Crm_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-crm-pro-deactivator.php
 */
function deactivate_crm_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-crm-pro-deactivator.php';
	Crm_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_crm_pro' );
register_deactivation_hook( __FILE__, 'deactivate_crm_pro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-crm-pro.php';

/**
 * Define all plugin used constant if not already defined.
 *
 * @since 1.0.0
 */
function crm_pro_safe_define() {
	crm_pro_define( 'CRM_PRO_ABSPATH', dirname( __FILE__ ) . '/' );
	crm_pro_define( 'CRM_PRO_URL', plugin_dir_url( __FILE__ ) );
	crm_pro_define( 'CRM_PRO_VERSION', '1.0.2' );
	crm_pro_define( 'CRM_PRO_PLUGINS_PATH', plugin_dir_path( __DIR__ ) );
	crm_pro_define( 'CRM_PRO_BASEURL', 'https://rest.gohighlevel.com/' ); // base url of crm api.
	crm_pro_define( 'CRM_PRO_FORM_BASEURL', 'https://link.gohighlevel.com/' ); // base url for form scripts.
	crm_pro_define( 'CRM_PRO_CDN_BASE_URL', 'https://widgets.leadconnectorhq.com/' ); // base url for chatbot scripts.
	crm_pro_define( 'CRM_PRO_OPTION_NAME', 'crm_pro' );
}

/**
 * Define constant if not already defined.
 *
 * @param string $name name for the constant.
 * @param string $value value for the constant.
 * @since 1.0.0
 */
function crm_pro_define( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_crm_pro() {
	// define contants if not defined..
	crm_pro_safe_define();

	$plugin = new Crm_Pro();
	$plugin->run();

}
run_crm_pro();