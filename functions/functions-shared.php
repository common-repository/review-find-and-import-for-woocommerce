<?php

    // This file contains the hooks that can be used to modify the functionality of the plugin

    // If this file is called directly, abort.
    if (! defined('WPINC')) {
    	die;
    }

    function api_review_build_query($product_id, $query_type) {
        $title = get_the_title($product_id);
        $title_lowercase = strtolower($title);
        $title_lowercase = preg_replace('/[^\da-z ]/i', '', $title_lowercase);
        $mpn = api_review_get_identifier($product_id, 'mpn');
        $mpn_lowercase = strtolower($mpn);
        $brand = api_review_get_identifier($product_id, 'brand');
        $brand_lowercase = strtolower($brand);
        $identifier = '';
        if ($query_type == 'title') {
            $identifier = $title;
        } elseif ($query_type == 'mpn') {
            $identifier = $mpn;
        } elseif ($query_type == 'mpn_title' && $mpn !== '') {
            $identifier = $mpn . ' ' . $title;
        } elseif ($query_type == 'mpn_title' && $mpn == '') {
            $identifier = $mpn . $title;
        } elseif ($query_type == 'brand_title' && $brand !== '') {
            $identifier = $brand . ' ' . $title;
        } elseif ($query_type == 'brand_title' && $brand == '') {
            $identifier = $brand . $title;
        } elseif ($query_type == 'mpn_brand' && $brand !== '') {
            $identifier = $mpn . ' ' . $brand;
        } elseif ($query_type == 'mpn_brand' && $brand == '') {
            $identifier = $mpn . $brand;
        } else {
            $identifier = $title;
        }
        return $identifier;
    }

    function api_review_api_count_block() {
        // api call history
        $reviews_api_count = get_option('api_review_reviews_api_count');
        $reviews_api_count == '' ? $reviews_api_count : 0;

        $api_total = $reviews_api_count;

        echo '<div class="api-review-api-totals">';
            if ($reviews_api_count !== '') {
                echo '<strong>Reviews API</strong>: ' . esc_html($reviews_api_count) . '<br />';
            }
        echo '</div>';
        ?>
        <!-- stop updates form -->
        <form class="api-review-form api-review-form-button api-review-reset-count" action="" method="post">
            <input class="button" style="background: #fff;min-width: 200px;color: #333;border: 1px solid #d8d8d8 !important;margin: 0px auto;" name="reset_count" type="submit" value="Reset Counter">
            <?php wp_nonce_field('action_reset_count', 'nonce_reset_count', false); ?>
        </form>
        <?php
        // handle stop updates form submision
        if(isset($_POST['reset_count'])) {
            if (isset($_POST['nonce_reset_count'])) {
                $nonce_reset_count = sanitize_text_field($_POST['nonce_reset_count']);
            } else {
                $nonce_reset_count = false;
            }
            if (!wp_verify_nonce($nonce_reset_count, 'action_reset_count')) {
                wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
            } else {
                update_option('api_review_reviews_api_count', 0);
                header("Refresh:0");
            }
        }
    }

    if (!function_exists ('api_review_get_identifier')) {
        function api_review_get_identifier($product_id, $identifier_type) {
            global $product;
            $product = wc_get_product($product_id);
            $plugin_options = wp_parse_args(get_option('api_review_options'), api_review_default_options());
            if ($identifier_type == 'asin') {
                $identifier = get_post_meta($product_id, 'api_review_asin', true);
                if ($identifier == '') {
                    $identifier = '';
                }
            } else {
                $option_slug = 'api_review_' . $identifier_type . '_attribute';
                $identifier_slug = sanitize_text_field($plugin_options[$option_slug]);
                if ($identifier_slug !== '') {
                    $identifier = $product->get_attribute('pa_' . $identifier_slug);
                    if ($identifier == '') {
                        // if there was no identifier returned
                        $identifier = '';
                    }
                } else {
                    $identifier = '';
                }
            }

            return sanitize_text_field($identifier);
        }
    }
