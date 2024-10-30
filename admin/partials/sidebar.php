<?php

defined( 'ABSPATH' ) or exit;

/**
 * @ignore
 */
function _crm_pro_admin_sidebar_pro_plugin() {
	echo '<div class="crm-pro-box crm-pro-margin-m">';
	echo '<h4 class="crm-pro-title">', esc_html__( 'CRM Pro Upgrade Features', 'crm-pro' ), '</h4>';
	echo '<ul style="margin-bottom: 0;">';
	
	echo '<li style="margin: 12px 0;">';
	echo sprintf( '<strong>White Label Plugin</strong></br>' );
	echo esc_html__( 'Convert CRM Pro to the look and feel of your white label CRM plugin.', 'crm-pro' );
	echo '</li>';

	echo '<li style="margin: 12px 0;">';
	echo sprintf( '<strong>Upgraded Chat Settings</strong></br>' );
	echo esc_html__( 'Manage your chat settings from the plugin panel.', 'crm-pro' );
	echo '</li>';

	echo '<li style="margin: 12px 0;">';
	echo sprintf( '<strong>Funnels</strong></br>' );
	echo esc_html__( 'Import all your funnel pages into your WordPress website.', 'crm-pro' );
	echo '</li>';

	echo '</ul>';
	echo '</div>';
}

add_action( 'crm_pro_admin_sidebar', '_crm_pro_admin_sidebar_pro_plugin', 40 );

/**
 * Runs when the sidebar is outputted on Mailchimp for WordPress settings pages.
 *
 * Please note that not all pages have a sidebar.
 *
 * @since 1.0.0
 */
do_action( 'crm_pro_admin_sidebar' );