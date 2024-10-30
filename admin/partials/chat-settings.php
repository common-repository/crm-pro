<?php

/**
 * Provide a chat settings admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://crm_prodevs.com/team/rakhi-sharma
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
                CRM PRO | <?php echo esc_html__( 'Chat Settings', 'crm-pro' ); ?>
            </h1>

            <?php
            settings_errors();
            ?>

            <form action="<?php echo admin_url( 'options.php' ); ?>" method="post">

                <?php settings_fields( 'crm_pro_settings' ); ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="crm_pro_enable_chat"><?php echo esc_html__( 'Enable Chat', 'crm-pro' ); ?></label></th>
                        <td>
                            <input type="hidden" id="crm_pro_enable_chat" name="crm_pro[enableChat]" value="0" />
                            <input type="checkbox" id="crm_pro_enable_chat" name="crm_pro[enableChat]" value="1"
                                <?php disabled(!$connected); checked($enable); ?> />
                            <p class="description">
                                <?php echo esc_html__( 'Enable if you want to add live chat to your website.', 'crm-pro' ); ?>
                            </p>
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label for="crm_pro_update_chat_settings"><?php echo esc_html__( 'Update Chat Settings', 'crm-pro' ); ?></label></th>
                        <td>
                           <?php
                           $nonce = wp_create_nonce("crm_pro_chat_update_nonce");
                           $link = admin_url('admin-ajax.php?action=crn_pro_chat_update&nonce='.$nonce);
                           ?>
                           <a id="crm_pro_update_chat_settings" class="button button-secondary" data-nonce="<?php echo $nonce ?>" href="<?php echo $link ?>" <?php disabled(!$connected) ?> ><?php _e('Refresh Now', 'crm-pro'); ?></a>
                           <span class="spinner"></span>
                        </td>
                    </tr> 
                </table>
                <?php submit_button(null, 'primary', 'submit', true, $disable); ?>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="crm-pro-sidebar crm-pro-col crm-pro-col-2">
            <?php require_once CRM_PRO_ABSPATH . '/admin/partials/sidebar.php'; ?>
        </div>
    </div>
</div>