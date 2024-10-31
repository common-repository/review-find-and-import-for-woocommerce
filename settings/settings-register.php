<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

// register plugin settings
if (!function_exists ('api_review_register_settings')) {
    function api_review_register_settings() {
        $allowed_html = array(
            'span' => array(
                'style' => array()
            ),
            'br' => array(),
            'style' => array()
        );

    	// Register the plugin settings
    	register_setting(
    		'api_review_options',
    		'api_review_options',
    		'api_review_validate_options'
    	);
        // Add the settings page sections
    	add_settings_section(
    		'api_review_general_options_section',
    		'API Options',
    		'api_review_general_options_section_callback',
    		'api-review-settings'
    	);
        add_settings_section(
    		'api_review_section_reviews',
    		'Review Import Options',
    		'api_review_section_reviews_callback',
    		'api-review-settings'
    	);
    	add_settings_section(
    		'api_review_assign_attributes_section',
    		'Assign Attribute Options',
    		'api_review_assign_attributes_section_callback',
    		'api-review-settings'
    	);

        // register settings
        add_settings_field(
    		'api_review_api_key',
            esc_html__('API Key', 'review-find-and-import-for-woocommerce'),
    		'api_review_text_field_callback',
    		'api-review-settings',
    		'api_review_general_options_section',
    		[ 'id' => 'api_review_api_key', 'label' => 'You can obtain your API Key by logging in or registering an account on <u><a href="https://www.apigenius.io/software/find-import-reviews-woocommerce/" target="_blank">ApiGenius.io</a></u>.' ]
    	);

        add_settings_field(
    		'api_review_max_reviews',
            esc_html__('Max Reviews', 'review-find-and-import-for-woocommerce'),
    		'api_review_text_field_callback',
    		'api-review-settings',
    		'api_review_section_reviews',
    		[ 'id' => 'api_review_max_reviews', 'label' => 'The maximum number of reviews to import.' ]
    	);

        add_settings_field(
    		'api_review_minimum_rating',
            esc_html__('Minimum Rating', 'review-find-and-import-for-woocommerce'),
    		'api_review_text_field_callback',
    		'api-review-settings',
    		'api_review_section_reviews',
    		[ 'id' => 'api_review_minimum_rating', 'label' => '' ]
    	);

        add_settings_field(
    		'api_review_brand_attribute',
            esc_html__('Brand', 'review-find-and-import-for-woocommerce'),
    		'api_review_select_callback',
            'api-review-settings',
            'api_review_assign_attributes_section',
    		[ 'id' => 'api_review_brand_attribute', 'label' => '' ]
    	);

        add_settings_field(
    		'api_review_mpn_attribute',
            esc_html__('MPN', 'review-find-and-import-for-woocommerce'),
    		'api_review_select_callback',
            'api-review-settings',
            'api_review_assign_attributes_section',
    		[ 'id' => 'api_review_mpn_attribute', 'label' => '' ]
    	);

    }
    add_action( 'admin_init', 'api_review_register_settings' );
}
