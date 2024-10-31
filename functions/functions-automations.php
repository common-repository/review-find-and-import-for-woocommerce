<?php

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

// check to see if there is an active automation
if (!function_exists ('api_review_automation_function')) {
	function api_review_automation_function() {
		api_review_automation();
	}
	add_action('api_review_automation_hook', 'api_review_automation_function');
}

// run the automation on specified products
if (!function_exists ('api_review_automation')) {
	function api_review_automation() {
		// get automation stats
		$automation_status = get_option('api_review_automation_status');
		$total_products_start = get_option('api_review_total_products_start');
		$automation_all = get_option('api_review_automation_all');
		if ($automation_status == 'active' || $automation_status == 'queued') {
			$automation_all = json_decode($automation_all, true);
		}
		$automation_start_time = get_option('api_review_automation_start_time');

		if (is_array($automation_all)) {

			foreach ($automation_all as $automation) {
				// specific automation details
				$automation_type = isset($automation['automation_type']) ? sanitize_text_field($automation['automation_type']) : '';
				$update_status = isset($automation['update_status']) ? sanitize_text_field($automation['update_status']) : '';
				$publish_status = isset($automation['publish_status']) ? sanitize_text_field($automation['publish_status']) : '';
				$existing_data = isset($automation['existing_data']) ? sanitize_text_field($automation['existing_data']) : 'false';
				if ($automation_status == 'active' || $automation_status == 'queued') {
					$paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
					// how many products processed per automation
					$total_products_store = get_option('api_review_total_products_store');
					if ($total_products_store < 14000) {
					    $posts_per_page = 50;
					} elseif ($total_products_store > 14001 && $total_products_store < 50000) {
					    $posts_per_page = 100;
					} else {
					    $posts_per_page = 50;
					}

					// Base query args
					$query_args = array(
					   'post_type'			          =>   'product',
					   'paged'                       =>    $paged,
					   'orderby'                     =>    'modified',
					   'order'                       =>    'asc',
					   'posts_per_page'              =>    $posts_per_page
					);

					if ($publish_status !== 'all' && $publish_status !== '') {
						$publish_status_args = array(
							'publish_status' => $publish_status
						);
						$query_args = array_merge($query_args, $publish_status_args);
					}

					// reltion arguments for meta query
					$meta_args_all = [];

					// update_status argumants
					if ($update_status == 'never_updated') {
				        $meta_args = array(
				            'key' => 'api_review_last_updated',
				            'compare' => 'NOT EXISTS',
				        );
				        array_push($meta_args_all, $meta_args);
				    } elseif ($update_status == 'already_updated') {
				        $meta_args = array(
				            'key' => 'api_review_updated_reviews',
				            'value' => 'updated',
				            'compare' => '=',
				       );
				        array_push($meta_args_all, $meta_args);
				    } elseif ($update_status == 'failed') {
				        $meta_args = array(
				            'key' => 'api_review_updated_reviews',
				            'value' => 'failed',
				            'compare' => '=',
				       );
				        array_push($meta_args_all, $meta_args);
				    }

					// insert the relation if needed
					if (is_array($meta_args_all)) {
						$args_count = count($meta_args_all);
						if ($args_count > 1) {
							if ($update_status == 'all') {
							   $meta_args_compare = array('relation' => 'OR');
							} else {
							   $meta_args_compare = array('relation' => 'AND');
							}
							$meta_args_all = array_merge($meta_args_all, $meta_args_compare);
						}
						$args = array(
							'meta_query' => $meta_args_all
						);
						$query_args = array_merge($query_args, $args);
					}

					$the_query = new WP_Query($query_args);

					$found_products = $the_query->found_posts;

					if ($total_products_start == 0) {
						$total_products_start = $found_products;
						update_option('api_review_total_products_start', $total_products_start);
					} elseif ($found_products == 0) {
						update_option('api_review_total_products_start', $total_products_start);
					}

					if ($found_products == 0) {
						update_option('api_review_automation_status', 'complete');
						update_option('api_review_automation_count', 0);
					}

					// update the number of products per automation instance
					update_option('api_review_automation_products_per', $posts_per_page);

					if($the_query->have_POSTs()) {
						while ($the_query->have_POSTs()) {
							$the_query->the_POST();
							global $product;
							echo $product_id = $product->get_id();
							echo '<br />';
							$current_time = date('Y-m-d H:i:s');
		                    update_post_meta($product_id, 'api_review_last_updated', $current_time);

							$automation_count = get_option('api_review_automation_count');
							$automation_count++;
							update_option('api_review_automation_count', $automation_count);

							if ($total_products_start > $automation_count) {

								update_option('api_review_automation_status', 'active');
                                $product_title = get_the_title($product_id);
								api_review_get_reviews($product_id, $existing_data = false, $dry_run = false, $product_title);

							} else {
								update_option('api_review_automation_status', 'complete');
							}
							sleep(2);
						}
					}
					// Reset Query
					wp_reset_query();
				}
			}
		}
	}
}
