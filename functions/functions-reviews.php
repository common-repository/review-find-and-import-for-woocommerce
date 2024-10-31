<?php

    // If this file is called directly, abort.
    if (! defined('WPINC')) {
       die;
    }

    function api_review_get_reviews($product_id, $existing_data, $dry_run, $query) {
        global $product;
        $product = wc_get_product( $product_id );
        $plugin_options = wp_parse_args( get_option( 'api_review_options' ), api_review_default_options());
        $api_key = sanitize_text_field($plugin_options['api_review_api_key']);
        $query_url = '';

        if ($existing_data == false) {
            if ($query !== '') {
                $api_endpoint = 'https://api.apigenius.io/reviews';
                $query_url = '?query=' . $query;
                $url = $api_endpoint . $query_url;
                $args = array(
                        'timeout'     => 30,
                        'headers' => array(
                        'ApiGenius_API_Key' => $api_key,
                   )
                );

                // Product data
                $product_title = get_the_title($product_id);

                echo '<strong>Product Titile:</strong> ' . $product_title . '<br />';
                echo '<strong>Product ID: </strong> <a href="/wp-admin/admin.php?page=api-review-dashboard&keyword&product_id=' .  esc_html($product_id) . '&status&sort_by&per_page&SearchProducts=Search" target="_blank">' . esc_html($product_id) , '</a><br />';
                echo '<strong>Query:</strong> ' . $query . '<br />';

                $response = wp_remote_get($url, $args);
                if (! is_wp_error($response)) {
                    $result = json_decode($response['body']);
                    $result_encode = json_encode($result);
                    $result_encode = wp_slash($result_encode);
                } else {
                    echo '<p><span style="color: red;">Error</span> Please refresh the page and try again.</p>';
                    $result = '';
                }
				
				$status_code = isset($result->{'statusCode'}) ? $result->{'statusCode'} : '';
				$message = isset($result->{'message'}) ? $result->{'message'} : '';

                if (!$api_key) {

                	echo 'Please enter your API Key on the plugin <a href="/wp-admin/admin.php?page=api-pv-settings" target="_blank">settings page</a>.';

                } elseif ($status_code == 401) {

                    $result = '';
                    echo '<p>Access Denied: Your API key is invalid. Please check the <a href="/wp-admin/admin.php?page=api-pp-settings" target="_blank">Plugin Settings Page</a>.</p>';
                    echo '<p><strong>Please Note:</strong>  If you have recently changed your subscription, you will need to use your New API Key which can be found on the<br /><a href="https://www.apigenius.io/keys-usage/" target="_blank">Apigenius.io Keys & Usage Page.</p>';
                    update_option('api_review_automation_count', 0);
                    update_option('api_review_total_products_start', 0);
                    update_option('api_review_automation_all', 'invalid_api_key');
                    update_option('api_review_automation_status', '');
                    update_option('api_review_automation_start_time', '');
                    EXIT;
                } elseif ($status_code == 403) {

                    // out of api calls
                    $result = '';
                    echo 'You are are out of monthly API credits.  You can check your balance and upgrade your account by <a href="https://www.apigenius.io/keys-usage/" target="_blank">Clicking Here</a>.';

                    update_option('api_review_automation_count', 0);
                    update_option('api_review_total_products_start', 0);
                    update_option('api_review_automation_all', 'call_limit_reached');
                    update_option('api_review_automation_status', '');
                    update_option('api_review_automation_start_time', '');
                    EXIT;
                }

                $reviews_api_count = get_option('api_review_reviews_api_count');
            	$reviews_api_count= $reviews_api_count + 1;
            	update_option('api_review_reviews_api_count', $reviews_api_count);
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_reviews_query', $query);
                }
            } else {
                echo '<strong>Update Failed:</strong> There was no Query provided.  Please select another option in the Query Type dropdown<br />';
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_updated_reviews', 'failed');
                }
            }
        } elseif ($existing_data == true) {
            $response = get_post_meta($product_id, 'api_review_reviews_json', true);
            $result = json_decode($response);
            echo 'Saved Data<br />';
        }

        if ($result !== '') {
            if (isset($result->{'reviews'})) {
                if ($dry_run !== false) {
                    update_post_meta($product_id, 'api_review_reviews_json', $result_encode);
                }
        		$reviews = $result->{'reviews'};
        		$count = count($reviews);
    		} elseif (isset($result->{'message'})) {
                echo '<strong>Update Failed:</strong> ' . $message = $result->{'message'} . ' Please try to run the update again with a different Identifier Type.<br />';
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_updated_reviews', 'failed');
                }
            } else {
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_updated_reviews', 'failed');
                }
            }
            if (!empty($reviews) && $count > 0) {
                api_review_update_reviews($product_id, $reviews, $dry_run);
                $current_time = date('Y-m-d H:i:s');
                update_post_meta($product_id, 'api_pda_last_updated', $current_time);
            }
        }
        $current_time = date('Y-m-d H:i:s');
        $product_update = array(
            'ID' => $product_id,
            'post_modified_gmt' => $current_time,
        );
        wp_update_post($product_update);
    }

    //  Get the reviews for a requested job
    function api_review_update_reviews($product_id, $reviews, $dry_run) {
        global $wpdb;
        global $product;
        $product = wc_get_product( $product_id );
        $existing_reviews_array = get_post_meta($product_id, 'api_review_review_ids', true);
        $plugin_options = wp_parse_args( get_option( 'api_review_options' ), api_review_default_options());
        $max_reviews = sanitize_text_field($plugin_options['api_review_max_reviews']);
        $product_data_link_found = false;
        $walmart_link_found = false;
        if ($max_reviews == '') {
            $max_reviews = 25;
        }
        $minimum_rating = sanitize_text_field($plugin_options['api_review_minimum_rating']);
        if ($minimum_rating == '') {
            $minimum_rating = 1;
        }
        if ($existing_reviews_array !== '') {
            $existing_reviews_array = json_decode($existing_reviews_array);
        } else {
            $existing_reviews_array = [];
        }
        if (!empty($reviews)) {
            $review_count = count($reviews);
            if ($review_count > $max_reviews) {
                $review_count = $max_reviews;
            }
            $review_id_array = [];
            if ($review_count > 0) {
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_updated_reviews', 'updated');
                }
                for ($i=0; $i < $review_count; $i++) {
                    $review_count_current = $product->get_review_count();
                    $comment_title = isset($reviews[$i]->{'review_title'}) ? $reviews[$i]->{'review_title'} : '';
                    $comment_author = isset($reviews[$i]->{'review_author'}) ? $reviews[$i]->{'review_author'} : '';
                    $link = isset($reviews[$i]->{'link'}) ? $reviews[$i]->{'link'} : '';
                    $affiliate_link = $link;
                    if ($product_data_link_found == false) {
                        $product_data_link = $link;
                        $product_data_link_found = true;
                    }
                    if (strpos($link, 'walmart') !== false && $walmart_link_found == false) {
                        $product_data_link = $link;
                        echo 'Walmart Link Updated: ' . esc_url($link) . '<br />';
                        $walmart_link_found = true;
                    }
                    $website_name = $link;
                    $website_name = str_replace('https://www.', '', $website_name);
                    $website_name = str_replace('https://', '', $website_name);
                    $website_name = str_replace('http://www.', '', $website_name);
                    $website_name = str_replace('http://', '', $website_name);
                    $website_name = substr($website_name, 0, strpos($website_name, '/'));
                    $website_name = ucwords($website_name);
                    $comment_author_lowercase = strtolower($comment_author);
                    if ($comment_author == '') {
                        $comment_author = 'Anonymous';
                    }
                    $rand_email = rand(1000, 100000);
                    if ($comment_author == 'Anonymous') {
                        $comment_author_email = $comment_author . $rand_email . '@gmail.com';
                    } else {
                        $comment_author_email = $comment_author . '@gmail.com';
                    }
                    $comment_date_id = isset($reviews[$i]->{'review_publish_date'}) ? $reviews[$i]->{'review_publish_date'} : '';
                    $comment_date_id = strtotime($comment_date_id);
                    $unique_id =  'review_' . $comment_author_lowercase . '_' . $comment_date_id . '_' . $i;
                    $comment_date = date('Y-m-d H:i:s', strtotime( '-'. mt_rand(30, 720) .' days'));
                    $comment_rating = isset($reviews[$i]->{'rating'}) ? $reviews[$i]->{'rating'} : 0;
                    if (isset($reviews[$i]->{'review_description'})) {
                        $comment_title = isset($reviews[$i]->{'review_title'}) ? $reviews[$i]->{'review_title'} : '';
                        $comment_content = isset($reviews[$i]->{'review_description'}) ? $reviews[$i]->{'review_description'} : '';
                        $comment_content = str_replace('...', '', $comment_content);
                        $comment_content = substr($comment_content, 0, strrpos( $comment_content, '.')) . '.';
                        // add the title
                        if ($comment_title !== '') {
                            $comment_content = $comment_title . ' - ' . $comment_content;
                        }
                    } else {
                        $comment_content = '';
                    }
                    echo '<div class="api-review-block">';
                    if ( ! in_array($unique_id, $existing_reviews_array) && $review_count_current < $max_reviews) {
                        if ($minimum_rating <= $comment_rating) {
                            if ($dry_run == false) {
                                echo '<h5 style="color: green;">Review ' . esc_html(($i + 1)) . ' - <u>Review Inserted</u></h5>';
                            } else {
                                echo '<h5 style="color: green;">Review ' . esc_html(($i + 1)) . '</u></h5>';
                            }
                            if ($dry_run == false) {
                                api_review_insert_review( $product_id, $unique_id, $comment_author, $comment_author_email, $comment_date, $comment_content, $comment_rating, $website_name, $link);
                            }
                        } else {
                            echo '<h5 style="color: red;">Review ' . esc_html(($i + 1)) . ' - <u>Review Skipped</u>, Rating ' . $comment_rating . ' < Minimum ' . $minimum_rating . '</h5>';
                        }
                        $existing_reviews_array = array_merge($existing_reviews_array, array($unique_id));
                    } else {
                        echo '<h5 style="color: red;">Review ' . esc_html(($i + 1)) . ' - <u>Review Skipped</u>, Already imported</h5>';
                    }
                    echo '
                        Name: ' . esc_html($comment_author) . '<br />
                        Email: ' . esc_html($comment_author_email) . '<br />
                        Date: ' . esc_html($comment_date) . '<br />
                        Rating: ' . esc_html($comment_rating) . '<br />
                        Review Text: ' . esc_html($comment_content) . '<br />
                        Review Source: ' . esc_html($website_name) . ' - <a href="' . $link . '" target="_blank">Link</a><br />
                    ';
                    echo '</div>';
                }
                // update the status and review ids
                $existing_reviews_array = json_encode($existing_reviews_array);
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_review_ids', $existing_reviews_array);
                }
                update_post_meta($product_id, 'api_review_product_data_link', $product_data_link);
            } else {
                if ($dry_run == false) {
                    update_post_meta($product_id, 'api_review_updated_reviews', 'failed');
                }
                echo 'No Reviews Found<br />';
            }
        }
        
    }

    function api_review_insert_review($product_id, $unique_id, $comment_author, $comment_author_email, $comment_date, $comment_content, $comment_rating, $website_name, $link) {
        global $wpdb;
        $user = $wpdb->get_results("SELECT id FROM $wpdb->users ORDER BY RAND() LIMIT 1");
        $user_id = $user[0]->id;

        $comment_agent_array = ['firerfox', 'chrome', 'edge'];
        $comment_agent = $comment_agent_array[mt_rand(0, count($comment_agent_array) - 1)];
        $ip = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
        $product_permalink = get_the_permalink( $product_id );
        $product_title = get_the_title( $product_id );
        $product_sku = get_post_meta( $product_id, '_sku', true );
        if (!$comment_date) {
            $comment_date = date('Y-m-d H:i:s', strtotime( '-'.mt_rand(0,365).' days'));
        }

        $comment_id = wp_insert_comment( array(
            'comment_post_ID'      => $product_id,
            'comment_author'       => $comment_author,
            'comment_author_email' => $comment_author_email,
            'comment_author_url'   => '',
            'comment_content'      => $comment_content,
            'comment_type'         => 'comment',
            'comment_parent'       => 0,
            'user_id'              => $user_id,
            'comment_author_IP'    => $ip,
            'comment_agent'        => $comment_agent,
            'comment_date'         => $comment_date,
            'comment_approved'     => 1,
        ) );

        update_comment_meta( $comment_id, 'rating', $comment_rating );
        update_comment_meta( $comment_id, 'api_review_review_source', $link );
        update_comment_meta( $comment_id, 'api_review_review_id', $unique_id );
        echo '<p>A product review from ' . $comment_author . ' with a rating of ' . $comment_rating . ' was imported for <a href="' . $product_permalink . '" target="_blank">' . $product_title . '</a>.</p>';

    }
