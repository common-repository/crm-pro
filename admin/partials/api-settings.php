<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ghldevs.com/team/rakhi-sharma
 * @since      1.0.0
 *
 * @package    Crm_Pro
 * @subpackage Crm_Pro/admin/partials
 */

defined( 'ABSPATH' ) or exit;
?>

<div id="crm-pro-admin" class="wrap crm-pro-settings">
    <div class="crm-pro-row">

        <!-- Main Content -->
        <div class="main-content crm-pro-col crm-pro-col-4">

            <h1 class="crm-pro-page-title">
                CRM PRO | <?php echo esc_html__( 'API Settings', 'crm-pro' ); ?>
            </h1>

            <?php
            //add_settings_error('crm_pro_settings', 'crm_code', 'test message here', 'alert');
            settings_errors();
            ?>

            <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
                <?php settings_fields( 'crm_pro_settings' ); ?>

                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">
                            <?php echo esc_html__( 'Status', 'crm-pro' ); ?>
                        </th>
                        <td>
                            <?php
                            if ( $connected ) {
                                ?>
                            <span
                                class="crm-pro-status positive"><?php echo esc_html__( 'CONNECTED', 'crm-pro' ); ?></span>
                            <?php
                            } else {
                                ?>
                            <span
                                class="crm-pro-status neutral"><?php echo esc_html__( 'NOT CONNECTED', 'crm-pro' ); ?></span>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label
                                for="crm_pro_api_key"><?php echo esc_html__( 'API Key', 'crm-pro' ); ?></label></th>
                        <td>
                            <input type="text" class="widefat"
                                placeholder="<?php echo esc_html__( 'Your API key', 'crm-pro' ); ?>"
                                id="crm_pro_api_key" name="crm_pro[api_key]" value="<?php echo esc_attr( $api_key ); ?>"
                                <?php echo defined( 'crm-pro_API_KEY' ) ? 'readonly="readonly"' : ''; ?> />
                            <p class="description">
                                <?php echo esc_html__( 'The API key for connecting with your account, You\'ll find the API key under location level -> settings -> Company page
', 'crm-pro' ); ?>
                            </p>
                        </td>
                    </tr>

                </table>

                <?php submit_button(); ?>

            </form>

        </div>

        <!-- Sidebar -->
        <div class="crm-pro-sidebar crm-pro-col crm-pro-col-2">
            <?php require_once CRM_PRO_ABSPATH . '/admin/partials/sidebar.php'; ?>
        </div>
    </div>