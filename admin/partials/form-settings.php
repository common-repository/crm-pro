<?php

/**
 * Provide a form settings admin area view for the plugin
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
                CRM PRO | <?php echo esc_html__( 'Forms', 'crm-pro' ); ?>
            </h1>

            <?php
            settings_errors();
            ?>        
            <table class="form-table">       
                <tr valign="top">
                    <th><?php echo esc_html__( 'Form Name', 'crm-pro' ); ?></th>
                    <th><?php echo esc_html__( 'ShortCode', 'crm-pro' ); ?></th>
                </tr>

                <?php

                if(is_array($forms) && count($forms)){
                    foreach($forms as $form){
                        $name = isset($form['name']) ? esc_attr($form['name']) : '';
                        $form_id = isset($form['id']) ? esc_attr($form['id']) : '';
                        ?>
                        <tr>
                            <td><?php esc_attr_e($name);?></td>
                            <td><?php esc_attr_e('[crmpro_form id="'.$form_id.'"]'); ?></td>
                        </tr>
                        <?php
                    }
                }else{
                    // no forms in the account.
                    if($connected):
                    ?>
                    <tr>
                        <td colspan="2"><?php echo esc_html__( 'No Forms Found! Please create form in your CRM account.', 'crm-pro' ); ?></td>
                    </tr>
                    <?php
                    endif;
                }

                ?>
            </table>
        </div>

        <!-- Sidebar -->
        <div class="crm-pro-sidebar crm-pro-col crm-pro-col-2">
            <?php require_once CRM_PRO_ABSPATH . '/admin/partials/sidebar.php'; ?>
        </div>
    </div>
</div>