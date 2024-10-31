<?php

// If this file is called directly, abort.
if (! defined('WPINC')) {
   die;
}

?>
<!-- css styles for page -->
<style>
    .api-automation-table {
        width: 100%;
        max-width: 100%;
        margin: auto;
    }
    .api-pda-form-basic, .api-pda-kill-updates {
        padding: 20px;
        border: 1px solid #d8d8d8;
        background: #f8f8f8;
        margin: 10px auto;
        display: block;
        width: 850px;
        max-width: 100%;
    }
    .api-pda-kill-updates {
        border: none;
        background: transparent;
    }
    .api-div-admin-notice {
        padding: 10px;
        border: 1px solid #d8d8d8;
        background: #f8f8f8;
        margin-top: 10px;
        max-width: 100%;
        width: 800px;
        margin: auto;
        display: block;
    }
    .api-table h4 {
        text-align: center;
        font-size: 14px;
        font-family: 'Aldrich', sans-serif;
    }
    .api-table p {
        text-align: center;
        font-size: 15px;
        font-family: 'Aldrich', sans-serif;
    }
    .api-update-stats-div {
        width: 90%;
        max-width: 1650px;
        margin: 25px auto;
    }
    .api-update-report-table {
        margin: 25px auto;
    }
    .api-update-report-table td {
        border: 1px solid #d8d8d8;
        background: #fff;
        padding: 10px;
    }
    .api-update-div {
        padding: 10px;
        border: 1px solid #d8d8d8;
        border-radius: 10px;
        background: #f8f8f8;
        min-height: 150px;
    }
    .tooltip {
        position: relative;
        display: inline;
        border: 1px solid #d8d8d8;
        border-radius: 50px;
        padding: 5px 2.5px 5px 5px;
        margin: 5px;
        background: #fff;
    }
    .tooltip .tooltiptext {
        visibility: hidden;
        width: 500px;
        background-color: #fff;
        border: 1px solid #d8d8d8;
        color: #33363B;
        padding: 10px;
        border-radius: 6px;

        /* Position the tooltip text - see examples below! */
        position: absolute;
        z-index: 1;
    }
    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>
<!-- form that creates the automation -->
<div class="api-pda-settings-div">
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form class="api-pda-form api-pda-form-basic" action="" method="post">
            <br />
            <select id="automation_type" class="ea-search-input ea-search-form-select" name="automation_type">
                <option value="<?php
                    if(isset($_POST['automation_type'] ) ) {
                        $automation_type = sanitize_text_field($_POST['automation_type'] );
                    } else {
                        $automation_type = '';
                    }
                    ?>"><?php
                            if(isset($_POST['automation_type'] ))  {
                                $automation_type = sanitize_text_field($_POST['automation_type']);
                                if($automation_type ) {
                                    $automation_type_text = str_replace('_', ' ', $automation_type );
                                    $automation_type_text = ucwords($automation_type_text );
                                    echo esc_html(ucwords($automation_type_text ));
                                }
                            } else {
                                echo 'Automation Type';
                            }
                        ?>
                </option>
                <option value="queue_product_data">Reviews</option>
           </select>
            <select id="api-select" class="api-search-input api-search-form-select" name="update_status" required>
                <option value="<?php
                    if(isset($_POST['update_status'])) {
                        $update_status = sanitize_text_field($_POST['update_status']);
                    } else {
                        $update_status = '';
                    }
                    ?>"><?php
                            if(isset($_POST['update_status']))  {
                                $update_status = sanitize_text_field($_POST['update_status']);
                                if($update_status) {
                                    $update_status_text = str_replace('_', ' ', $update_status);
                                    $update_status_text = ucwords($update_status_text);
                                    echo esc_html(ucwords($update_status_text));
                                }
                            } else {
                                echo 'Products to Update';
                            }
                        ?>
                </option>
                <option value="all">All Products</option>
                <option value="already_updated">Already Updated</option>
                <option value="never_updated">Never Updated</option>
                <option value="failed">Failed</option>
             </select>
             <select id="api-select" class="api-search-input api-search-form-select" name="publish_status" required>
                 <option value="<?php
                     if(isset($_POST['publish_status'])) {
                         $publish_status = sanitize_text_field($_POST['publish_status']);
                     } else {
                         $publish_status = '';
                     }
                     ?>"><?php
                             if(isset($_POST['publish_status']))  {
                                 $publish_status = sanitize_text_field($_POST['publish_status']);
                                 if($publish_status) {
                                     $publish_status_text = str_replace('_', ' ', $publish_status);
                                     $publish_status_text = ucwords($publish_status_text);
                                     echo esc_html(ucwords($publish_status_text));
                                 }
                             } else {
                                 echo 'Product Status';
                                 $publish_status = 'published';
                             }
                         ?>
                 </option>
                 <option value="all">All</option>
                 <option value="publish">Published</option>
                 <option value="draft">Draft</option>
              </select>
              <input value="<?php if(isset($_POST['automation_limit'])) { echo $automation_limit = esc_html($_POST['automation_limit']); } else { $automation_limit = ''; } ?>" type="text" name="automation_limit" placeholder="Automation Limit">
            <br /><br />
           <br /><br />
            <input class="button" name="create_automation" type="submit" value="Create Automation">
            <?php wp_nonce_field('action_automation', 'nonce_automation', false); ?>
        </form>
        <!-- stop updates form -->
        <form class="api-pda-form api-pda-form-button api-pda-kill-updates" action="" method="post">
            <input class="button" style="float: right;background: #fff;min-width: 200px;color: #333;border: 1px solid #d8d8d8 !important;margin: 0px auto;" name="stop_updates" type="submit" value="Stop Auotmations">
            <?php wp_nonce_field('action_stop_updates', 'nonce_stop_updates', false); ?>
        </form>
<br><br>

<?php

    // handle stop updates form submision
    if(isset($_POST['stop_updates'])) {
        if (isset($_POST['nonce_stop_updates'])) {
            $nonce_stop_updates = sanitize_text_field($_POST['nonce_stop_updates']);
        } else {
            $nonce_stop_updates = false;
        }
        if (!wp_verify_nonce($nonce_stop_updates, 'action_stop_updates')) {
            wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
        } else {
            update_option('api_review_automation_count', 0);
            update_option('api_review_total_products_start', 0);
            update_option('api_review_automation_all', '');
            update_option('api_review_automation_status', '');
            update_option('api_review_automation_start_time', '');
        }
    }

    $automation_info = array();
    $automation_all = '';
    echo '<div class="api-div-admin-notice">';

    // create the automation
    if(isset($_POST['create_automation'])) {
        if (isset($_POST['nonce_automation'])) {
            $nonce_automation = sanitize_text_field($_POST['nonce_automation']);
        } else {
            $nonce_automation = false;
        }
        if (!wp_verify_nonce($nonce_automation, 'action_automation')) {
            wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
        } else {

            // update the automation totals
            update_option('api_review_automation_count', 0);
            update_option('api_review_automation_status', 'queued');
            update_option('api_review_automation_start_time', date('Y-m-d H:i:s' ));
            if ($automation_limit !== '') {
                update_option('api_review_total_products_start', $automation_limit);
            } else {
                update_option('api_review_total_products_start', 0);
            }
            if (isset($_POST['update_status'])) {
                $update_status = sanitize_text_field($_POST['update_status']);
                $update_status_array = array(
                    'update_status' => $update_status
               );
                $automation_info = array_merge($automation_info, $update_status_array);
            }
            if (isset($_POST['publish_status'])) {
                $automoation_type = sanitize_text_field($_POST['publish_status']);
                $publish_status_array = array(
                    'publish_status' => $publish_status
               );
                $automation_info = array_merge($automation_info, $publish_status_array);
            }

            $automation_all = array(
                'automation' => $automation_info
            );
            // update the automation option
            $automation_all = json_encode($automation_all);
            update_option('api_review_automation_all', $automation_all);

        }
    }

    // get the saved automation information
    $total_products_start = get_option('api_review_total_products_start');
    $automation_count = get_option('api_review_automation_count');
    $automation_products_per = get_option('api_review_automation_products_per');
    $automation_all = get_option('api_review_automation_all');
    $automation_status = get_option('api_review_automation_status');
    $automation_start_time = get_option('api_review_automation_start_time');
    if (! is_wp_error($automation_all) && $automation_all !== 'call_limit_reached') {
        $automation_all = json_decode($automation_all, true);
    }

    if (is_array($automation_all)) {
        $count = count($automation_all);
    } else {
        $count = 0;
    }

    // display the automation information
    if ($automation_all == 'call_limit_reached') {
        echo '<p>
            <h3><span style="color: red;">Attention:</span> You have exceeded the number of monthly API calls available with your plan.</h3><br />
            1. You will need to go to your <a href="https://www.apigenius.io/account/" target="_blank">Subscriptions Page on ApiGenius.io</a> and upgrade your plan.<br />
            2. You will be given a new API key for your new plan. You will need to enter it on the plugin settings page.<br />
            3. You will need to Click the Stop Automations button to clear the current automation.  You will then need to create a new automation.<br /><br />
            Please feel free to <a href="https://www.apigenius.io/my-tickets/" target="_blank">Open a Support Ticket</a> if you have any questions.
        </p>';
    } elseif ($automation_all == '') {
        ?>
            <h3>About Automations</h3>
            <p>With the automation feature you can automatically run product updates to import reviews.</p>
            <p><span style="color: red;">Please Note:</span> We highly recommend that you backup your website site before running an automation.  We also recommend that you test different settings while manually updating products to make sure you have configured the plugin to provide optimal results.  Every store uses different product title configurations and thus different plugin settings will provide better results for stores.</p>
        <?php
    } else {
        if ($count > 0) {
            echo '<h3 style="text-align: center;">Current Automation</h3>';
            foreach ($automation_all as $automation) {
                echo '<hr />';
                if(is_array($automation)) {
                    echo '<p>Please Note:</strong> If your website has little to no traffic, you will need to follow this guilde to set up a manual cron job. <a href="https://www.siteground.com/tutorials/wordpress/real-cron-job/" target="_blank">Replace WP Cron</a><br />
                    - Your hosting provider should be able to do it in <u>less than 5 minutes if needed</u>.<br />';
                    ?>
                        <div class="div-api-automation">
                            <?php
                                // message for stopped automation
                                if(isset($_POST['stop_updates'])) {
                                    echo '<p>The automation was stopped.</p>';
                                }
                            ?>
                            <table class="api-automation-table">
                                <tr>
                                    <td width="70%">
                                        <?php
                                            // automation information
                                            // automation information
                                            if ($automation_status == 'queued') {
                                                echo '<strong>About Automation:</strong> This automation imports reviews.<br /><br />';
                                            }
                                            if ($automation_status !== '') {
                                                $automation_status_text = str_replace('_', ' ', $automation_status);
                                                echo '<strong>Automation Status</strong> ' . esc_html(ucwords($automation_status_text)) . '<br />';
                                            }
                                            $current_time = date('Y-m-d H:i:s' );
                                            echo '<strong>Automation Start Time:</strong> ' . esc_html($automation_start_time) . '<br />';
                                            if (isset($automation['update_status'])) {
                                                $update_status = sanitize_text_field($automation['update_status']);
                                                if ($update_status == 'already_updated') {
                                                    $update_status_text = 'Products that have been successfully updated.';
                                                } elseif ($update_status == 'never_updated') {
                                                    $update_status_text = 'Products that have never been updated.';
                                                } elseif ($update_status == 'failed') {
                                                    $update_status_text = 'Products we could not find reviews for on the previous update.';
                                                } else {
                                                    $update_status_text = ucwords(str_replace('_', ' ', $update_status)) . ' Products';
                                                }
                                                echo '<strong>Products to Update:</strong> ' . esc_html($update_status_text) . '<br />';
                                            }
                                            if (isset($automation['publish_status'])) {
                                                $publish_status = sanitize_text_field($automation['publish_status']);
                                                if ($publish_status == 'draft') {
                                                    $publish_status_text = 'Draft';
                                                } elseif ($publish_status == 'all') {
                                                    $publish_status_text = 'All';
                                                } else {
                                                    $publish_status_text = 'Published';
                                                }
                                                echo '<strong>Product Status to Update:</strong> ' . esc_html($publish_status_text) . '<br />';
                                            }
                                        ?>
                                        <?php
                                            if ($automation_products_per !== '') {
                                                echo '<p>The automation will process ' . esc_html($automation_products_per) . ' products every 5 minutes.</p>';
                                            }
                                        ?>
                                    </td>
                                    <td width="30%">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php
                }
                echo '<hr />';
            }
            if ($automation_count == 0 && $automation_status !== 'complete') {
                // displayed before automation is started
                echo '<h4><strong>Update Status:</strong> The automation will begin in 5 minutes or less.</h4>';
                echo '<p><strong>Get Update Details:</strong> At any time you can click the Get Update Details button to see if videos were found, met your settings criteria and filter those products to get more details.</p>';
            } else {
                // displayed while automation is running
                ?>
                    <table width="100%" class="api-table">
                        <tr>
                            <td width="20%">
                                <div class="api-update-div">
                                    <h4>Automation Type</h4>
                                    <p>
                                        <?php
                                        $automation_type_text = str_replace('_', ' ', $automation_type);
                                        echo esc_html(ucwords($automation_type_text)) . '<br />';
                                        ?>
                                    </p>
                                </div>
                            </td>
                            <td width="20%">
                                <div class="api-update-div">
                                    <h4>Current Automation Status</h4>
                                    <p>
                                        <?php
                                            $automation_status_text = str_replace('_', ' ', $automation_status);
                                            echo esc_html(ucwords($automation_status_text)) . '<br />';
                                        ?>
                                    </p>
                                </div>
                            </td>
                            <td width="20%">
                                <div class="api-update-div">
                                    <h4>Current Automation<br />Update Count</h4>
                                    <p><?php echo esc_html($automation_count); ?></p>
                                </div>
                            </td>
                            <td width="20%">
                                <div class="api-update-div">
                                    <h4>Remaining Automation Updates</h4>
                                    <p><?php echo esc_html(($total_products_start - $automation_count)); ?></p>
                                <div>
                            </td>
                            <td width="20%">
                                <div class="api-update-div">
                                    <h4>Total Automation<br />Updates Scheduled</h4>
                                    <p><?php echo esc_html($total_products_start); ?></p>
                                </div>
                            </td>
                        </tr>
                    </table>
                <?php
            }
        }
    }

    echo '<div>';

    // form to retrieve update statuses
    ?>
        <form class="api-pda-form api-pda-form-button api-pda-get-stats" action="" method="post">
            <p><input class="button" name="get_update_stats" type="submit" value="Get Update Details"></p>
            <?php wp_nonce_field('action_updat_stats', 'nonce_update_stats', false); ?>
        </form>

        <div class="api-update-stats-div">
            <?php
                if (isset($_POST['get_update_stats'])) {
                    if (isset($_POST['nonce_update_stats'])) {
                        $nonce_update_stats = sanitize_text_field($_POST['nonce_update_stats']);
                    } else {
                        $nonce_update_stats = false;
                    }
                    if (!wp_verify_nonce($nonce_update_stats, 'action_updat_stats')) {
                        wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
                    } else {
                        api_review_get_update_totals();
                    }
                }
            ?>
        </div>

    <?php
