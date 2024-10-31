<?php

    // If this file is called directly, abort.
    if (! defined('WPINC')) {
       die;
    }


        $per_page = '';

?>

    <h1 style="text-align: center;"><?php echo esc_html(get_admin_page_title()); ?></h1>

<style>
    /* css for form area */
    .api-form, .api-div-admin-notice {
        padding: 10px;
        border: 1px solid #d8d8d8;
        background: #fff;
        margin: auto;
        display: block;
        width: 625px;
        max-width: 100%;
    }
    .api-div-admin-notice {
        width: 1000px;
    }
    .api-form-action {
        width: 450px;
        max-width: 100%;
    }
    .api-button-black {
        background: #33363B !important;
        color: #fff !important;
    }
    .api-button-refresh {
        float: right;
    }
    /* css for product table */
    .api-table-dashboard-forms, .api-dashboard-table, .api-sort-table {
        width: 90%;
        max-width: 100%;
        margin: 25px auto;
    }
    .api-dashboard-table {
        width: 1400px;
        max-width: 100%;
        margin: 25px auto;
        background: #fff;
        border: 1px solid #d8d8d8;
    }
    .api-dashboard-table th, .api-dashboard-table td {
        border: 1px solid #d8d8d8 !important;
        padding: 5px 15px;
    }
    .api-select-all {
        margin: 10px 0 0 5px !important;
        display: inline-block;
    }
    .div-navigation {
        margin: auto !important;
        display: block;
        width: 1200px;
        max-width: 100%;
    }
    .div-navigation a {
        padding: 10px;
        border: 1px solid #d8d8d8;
        background: #fff;
        margin: auto 10px;
    }
    .api-total-products {
        padding: 10px;
        border: 1px solid #d8d8d8;
        background: #fff;
        border-radius: 5px;
        width: 125px;
        text-align: center;
    }
    .tooltip {
        position: relative;
        display: inline-block;
    }
    .tooltip .tooltiptext {
        visibility: hidden;
        width: 250px;
        background-color: #f8f8f8;
        border: 1px solid #d8d8d8;
        color: #33363B;
        padding: 10px;
        border-radius: 6px;
        position: absolute;
        z-index: 1;
    }
    .tooltip .tooltiptext-end {
        width: 150px;
    }
    .tooltip-table {
        width: 500px !important;
    }
    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
    .api-about-symbol {
        border-radius: 50%;
        padding: 5px;
        margin-right: 15px;
        border: 1px solid #d8d8d8;
        background: #f8f8f8;
    }
    .api-video-block {
        border: 1px solid #d8d8d8;
        padding: 20px;
        margin: 10px auto;
        background: #fff;
        height: auto;
    }
    .api-table-image {
        border: 1px solid #d8d8d8;
        padding: 5px;
        background: #f8f8f8;
        max-height: 200px;
        width: 100px;
        height: auto;
    }
    /* css for the total updates report table */
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
    /* css for update information */
    .api-div-found-video {
        height: 95px;
        border-top: 1px solid #d8d8d8;
        border-bottom: 1px solid #d8d8d8;
    }
    .api-success-img {
        margin: 5px;
        border: 1px solid #d8d8d8;
        padding: 5px;
        background: #f8f8f8;
        width: auto;
        height: 70px;
    }
    .pagination, .api-review-api-totals {
        border: 1px solid #d8d8d8;
        padding: 10px;
        margin: 10px;
        background: #fff;
        font-size: 16px;
    }
    .api-review-api-totals {
        font-size: 12px;
    }
    .api-updated {
        color: green;
    }
    .api-no {
        color: red;
    }
</style>
    <a name="top"></a>
    <div class='wrap'>
        <table class="api-table-dashboard-forms">
            <tr>
                <td width="15%">
                    <p><a class="button api-button-black" href="/wp-admin/admin.php?page=api-review-dashboard&keyword&product_id&status&stock_status&sort_by=modified&per_page&SearchProducts=Search">Recently Updated</a></p>
                    <!-- update status form -->
                    <?php
                        if (isset($_POST['get_update_stats'])) {
                            if (isset($_POST['nonce_update_stats'])) {
                                $nonce_update_stats = sanitize_text_field($_POST['nonce_update_stats']);
                            } else {
                                $nonce_update_stats = false;
                            }
                            if (!wp_verify_nonce($nonce_update_stats, 'action_update_stats')) {
                                wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
                            }
                        }
                        api_review_api_count_block();
                    ?>
                </td>
                <td width="50%">
                    <h4 style="text-align: center;">Search Products</h4>
                    <!-- search products form -->
                    <form class="api-form api-form-search" action="/wp-admin/admin.php" method="get">
                        <input type="hidden" name="page" value="api-review-dashboard" />
                        <input value="<?php if(isset($_GET['keyword'])) { $keyword = sanitize_text_field($_GET['keyword']); echo esc_html($keyword); } else { $keyword = ''; } ?>" type="text" name="keyword" placeholder="Keyword">
                        <input value="<?php if(isset($_GET['product_id'])) { $product_id_search = sanitize_text_field($_GET['product_id']); echo esc_html($product_id_search); } else { $product_id_search = ''; } ?>" type="text" name="product_id" placeholder="Product ID">
                        <select name="status">
                            <option value="<?php if(isset($_GET['status'])) { echo $status = esc_html(sanitize_text_field($_GET['status'])); } else { $status = ''; } ?>">
                                <?php

                                ?>
                            </option>
                            <option value="updated_reviews">Updated - Reviews</option>
                            <option value="failed_reviews">Failed - Reviews</option>
                            <option value="never_reviews">Never - Reviews</option>
                        </select>
                        <select class="api-select-modified" name="sort_by">
                            <option value="<?php if(isset($_GET['sort_by'])) { echo $sort_by = esc_html(sanitize_text_field($_GET['sort_by'])); } else { $sort_by = ''; } ?>">
                                <?php
                                    if($sort_by !== '') {
                                        if($sort_by == 'modified') {
                                            echo 'Modified';
                                        } elseif($sort_by == 'ID') {
                                            echo 'ID';
                                        }
                                    } else {
                                        echo 'Sort By';
                                    }
                                ?>
                            </option>
                            <option value="modified">Modified</option>
                            <option value="ID">ID</option>
                        </select>
                        <select name="per_page">
                            <option value="<?php if(isset($_GET['per_page'])) { echo $per_page = esc_html(sanitize_text_field($_GET['per_page'])); } else { $per_page = 10; } ?>">
                                <?php
                                    if($per_page !== '') {
                                        echo esc_html($per_page);
                                    } else {
                                        echo 'Per Page';
                                    }
                                ?>
                            </option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </select>
                        <input class="button api-button-black" name="SearchProducts" type="submit" value="Search">
                        <a class="button" href="/wp-admin/admin.php?page=api-review-dashboard">Clear Search</a>
                        <?php wp_nonce_field('action_search_products', 'nonce_search_products', false); ?>
                    </form>
                    <?php
                        if (isset($_GET['nonce_search_products'])) {
                            $nonce_search_products = sanitize_text_field($_GET['nonce_search_products']);
                        } else {
                            $nonce_search_products = wp_create_nonce( 'action_search_products' );
                        }
                        if (!wp_verify_nonce($nonce_search_products, 'action_search_products')) {
                            wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
                        }
                    ?>
                </td>
                <td width="5%"></td>
                <td width="30%">
                    <h4 style="text-align: center;">Update Products</h4>
                    <!-- update products form -->
                    <form class="api-form api-form-action" name="api-form-action" method="POST" action="">
                        <div class="div-action-form">
                            <select name="update_type">
                                <option value="<?php if(isset($_POST['update_type'])) { echo $update_type = esc_html(sanitize_text_field($_POST['update_type'])); } else { $update_type = ''; } ?>">
                                    <?php
                                        if($update_type !== '') {
                                            echo esc_html(ucwords($update_type));
                                        } else {
                                            echo 'Update Type';
                                        }
                                    ?>
                                </option>
                                <!-- dropdown options -->
                                <option value="update_reviews">Reviews</option>
                            </select>
                            <select name="query_type">
                                <option value="<?php if(isset($_POST['query_type'])) { echo $query_type = esc_html(sanitize_text_field($_POST['query_type'])); } else { $query_type = ''; } ?>">
                                    <?php
                                        if($query_type !== '') {
                                            echo esc_html(ucwords($query_type));
                                        } else {
                                            echo 'Query Type';
                                        }
                                    ?>
                                </option>
                                <!-- dropdown options -->
                                <option value="title">Title</option>
                                <option value="mpn">MPN</option>
                                <option value="mpn_title">MPN Title</option>
                                <option value="brand_title">Brand Title</option>
                                <option value="mpn_brand">MPN Brand</option>
                            </select>
                            <label><input class="dry-run" type="checkbox" name="dry_run" value="dry_run">Dry Run</label>
                            <label><input class="existing-data" type="checkbox" name="existing_data" value="existing_data">Use Existing Data</label>
                            <hr />
                            <input class="button api-button api-button-black" style="margin-top:5px;"  name="UpdateAction" type="submit" value="Update Products">
                            <lable class="api-select-all"><input type="checkbox" id="selectall" onClick="selectAll(this)" /> Select All Products On Page</lable>
                            <input class="button api-button api-button-refresh" style="margin-top:5px;"  name="RefreshPage" type="submit" value="Refresh">
                            <?php wp_nonce_field('action_update_products', 'nonce_update_products', false); ?>
                        </div>
                </td>
            </tr>
        </table>
    </div>

    <?php
    if (isset($_POST['UpdateAction'])) {
        if (isset($_POST['UpdateAction'])) {
            $nonce_update_products = sanitize_text_field($_POST['nonce_update_products']);
        } else {
            $nonce_update_products = false;
        }
        if (!wp_verify_nonce($nonce_update_products, 'action_update_products')) {
            wp_die('The nonce submitted by the form is incorrect! Please refresh the page and try again.');
        }
    }
    ?>

    <?php
        // handle reshesh buttom click
        if (isset($_POST['RefreshPage'])) {
            header("Refresh:0");
        }
    ?>

    <div class="api-update-stats-div">
        <?php
            if (isset($_POST['get_update_stats'])) {
                api_review_get_update_totals();
            }
        ?>
    </div>

    <!-- Check box javascript -->
    <script language="JavaScript">
        function selectAll(source) {
            checkboxes = document.getElementsByClassName('check-box');
            for(var i in checkboxes)
                checkboxes[i].checked = source.checked;
        }
    </script>

    <a style="padding:10px;background:#fff;border:1px solid #d8d8d8;border-radius: 5px;text-decoration:none;float:right;margin-right:10px;" href="#bottom">Bottom of Page</a>
    <!-- products table -->
    <table style="font-size: 12px;" class="api-dashboard-table widefat">
        <thead>
            <th width="5%"><input type="checkbox" id="selectall" onClick="selectAll(this)" /></th>
            <th "15%">Image</th>
            <th width="30%">Update Status</th>
            <th width="50%">Product Overview</th>
        </thead>

    <?php

    // Page the results
    $paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;

    if ($per_page == '') {
        $per_page = 10;
    }
    // Base query args
    $query_args = array(
        'post_type'			          =>	'product',
        'paged'                       =>    $paged,
        'post_status'                 =>    array('publish', 'draft'),
        'posts_per_page'              =>    $per_page
    );

    if ($product_id_search !== '') {
        $product_id_args = array(
            'p' => $product_id_search
        );
        $query_args = array_merge($query_args, $product_id_args);
    }

    if ($keyword !== '') {
        $product_id_args = array(
            's' => $keyword
      );
       $query_args = array_merge($query_args, $product_id_args);
    }

    // If a keyword was provided, include in query
    $sort_by_args = array(
        'orderby' => $sort_by,
        'order' => 'desc',
    );
    $query_args = array_merge($query_args, $sort_by_args);

    $meta_args_all = [];
    $status_args = [];

    // search by update status
    if($status !== '') {
        if($status == 'updated_reviews') {
            $status_args = array(
                'key' => 'api_review_updated_reviews',
                'value' => 'updated',
                'compare' => '=',
            );
        } elseif($status == 'failed_reviews') {
            $status_args = array(
                'key' => 'api_review_updated_reviews',
                'value' => 'failed',
                'compare' => '=',
            );
        } elseif($status == 'never_reviews') {
            $status_args = array(
                'key' => 'api_review_updated_reviews',
                'compare' => 'NOT EXISTS',
            );
        }
        array_push($meta_args_all, $status_args);
    }
    array_push($meta_args_all, $status_args);

    // insert the relation if needed
    if (is_array($meta_args_all)) {
        $args_count = count($meta_args_all);
        if ($args_count > 2) {
            $meta_args_all = array('relation' => 'AND');
        } elseif ($args_count > 0) {
            $meta_args = array(
                'meta_query' => $meta_args_all
            );
            $query_args = array_merge($query_args, $meta_args);
        }
    }

    $the_query = new WP_Query($query_args);

    // plugin options
    $plugin_options = wp_parse_args(get_option('api_review_options'), api_review_default_options());
    $identifier_error = false;
    if (isset($_POST['existing_data'])) {
        $existing_data = true;
    } else {
        $existing_data = false;
    }
    if($the_query->have_POSTs()) {
        // total products
        $total_products = $the_query->found_posts;
        echo '<h4 class="api-total-products">Total Products: ' . esc_html($total_products) . '</h4>';
        // product count while loop
        $i = 1;
        $all_product_ids = [];
        while ($the_query->have_POSTs()) {
            $the_query->the_POST();
            global $product;
            $product_id = $product->get_id();
            if(isset($_POST['UpdateAction'])) {
                $products_checked = 'update_product_' . $product_id;
                if(isset($_POST[$products_checked])) {
                    echo '<div class="api-div-admin-notice">';
                        if(isset($_POST['dry_run']) && $update_type == 'update_reviews') {
                            $dry_run = true;
                            echo '<strong>Dry Run:</strong> True, No data was imported.<br />';
                        } elseif (!isset($_POST['dry_run']) && $update_type == 'update_reviews') {
                            $dry_run = false;
                            echo '<strong>Dry Run:</strong> False, Data was imported.<br />';
                        }
                        // dropdown options
                        if ($query_type !== '') {
                            $query = api_review_build_query($product_id, $query_type);
                        } elseif ($query_type == '') {
                            $query_type == '';
                            $query = api_review_build_query($product_id, $query_type);
                        }
                        if ($update_type == 'update_reviews') {
                            echo '<strong>Query:</strong> ' . esc_html(ucwords($query)) . '<br />';
                            api_review_get_reviews($product_id, $existing_data, $dry_run, $query);
                        }

                    echo '</div>';
                }
            }

            // product data
            $product_image_url = get_the_post_thumbnail_url($product_id, $size = 'post-thumbnail');
            $last_updated = get_post_meta($product_id, 'api_review_last_updated', true);
            $sku = get_post_meta($product_id, '_sku', true);

            // product plugin statuses
            $status_reviews = get_post_meta($product_id, 'api_review_updated_reviews', true);
            if ($status_reviews == '') {
                $status_reviews = 'no';
            }
            $status_reviews = ucwords($status_reviews);
            $review_count = $product->get_review_count();
            $review_average = $product->get_average_rating();

            ?>
                <tr>
                    <td><input class="check-box" type="checkbox" name="update_product_<?php echo esc_html($product_id); ?>" value="<?php echo esc_html($product_id); ?>"></td>
                    <td>
                        <?php
                            if ($product_image_url) {
                                echo '<img class="api-table-image aligncenter" src="' . esc_url($product_image_url) . '" />';
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            // update statuses
                            echo '<span class="api-' . esc_html($status_reviews) . '">Reviews: ' . esc_html($status_reviews) . '</span><br />';
                            if ($review_count > 0) {
                                echo 'Reviews: ' . esc_html($review_count) . ' Total Reviews / ' . esc_html($review_average) . ' Avg. Rating<br />';
                            }

                        ?>
                    </td>
                    <td>
                        <a href="<?php echo get_permalink($product_id); ?>" target="_blank"><?php echo get_the_title($product_id); ?></a> - <a href="/wp-admin/post.php?post=<?php echo esc_html($product_id); ?>&action=edit" target="_blank"><u>Edit Page</u></a>
                        <br />
                        <strong>Product ID: </strong> <a href="/wp-admin/admin.php?page=api-review-dashboard&keyword&product_id=<?php echo esc_html($product_id); ?>&status&sort_by&per_page&SearchProducts=Search" target="_blank"><?php echo esc_html($product_id); ?></a><br />
                        <?php
                            $regular_price = get_post_meta($product_id, '_regular_price', true);
                            $sale_price = get_post_meta($product_id, '_sale_price', true);
                            if ($sale_price !== '') {
                                echo '<strong>Sale Price</strong> $' . esc_html(round($sale_price, 2)) . '<br />';
                            }
                            if ($regular_price !== '') {
                                echo '<strong>Regular Price</strong> $' . esc_html(round($regular_price, 2)) . '<br />';
                            }
                            if ($sku !== '') {
                                echo '<strong>Sku:</strong> ' . esc_html($sku) . '<br />';
                            }
                            $identifier_array = array('mpn', 'brand', 'asin');
                            foreach ($identifier_array as $identifier_type) {
                                $identifier = api_review_get_identifier($product_id, $identifier_type);
                                if ($identifier !== '') {
                                    if ($identifier_type == 'brand') {
                                        echo wp_kses_post(ucwords($identifier_type) . ': ' . $identifier . '<br />');
                                    } else {
                                        echo wp_kses_post(strtoupper($identifier_type) . ': ' . $identifier . '<br />');
                                    }
                                }
                            }
                        ?>
                    </td>
                </tr>
            <?php
        }
        // increase the product while loop counter by 1
        $i++;
    }

    // Reset Query
    wp_reset_query();

    ?>

        </tr>
    </table>

    <!-- Pagination html -->
    <div class="div-navigation tablenav">
        <div class="alignleft tablenav-pages">
              <?php
                  $base = str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999)));
                  $base = htmlspecialchars_decode($base);
                  echo paginate_links(array(
                      'base'         => $base,
                      'total'        => $the_query->max_num_pages,
                      'current'      => $paged,
                      'format'       => '?page=%#%',
                      'show_all'     => false,
                      'type'         => 'plain',
                      'end_size'     => 2,
                      'mid_size'     => 1,
                      'prev_next'    => false,
                      'prev_text'    => sprintf('<i></i> %1$s', __('Newer Posts', 'text-domain')),
                      'next_text'    => sprintf('%1$s <i></i>', __('Older Posts', 'text-domain')),
                      'add_args'     => false,
                      'add_fragment' => '',
                ));
              ?>
            </nav>
        </div>
        </form>
    </div>
    <a style="padding:10px;background:#fff;border:1px solid #d8d8d8;border-radius: 5px;text-decoration:none;float:right;margin-right:10px;" href="#top">Top of Page</a>
    <a name="bottom"></a>
