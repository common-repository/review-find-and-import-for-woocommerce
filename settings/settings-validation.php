<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}

if (!function_exists ('api_review_callback_validate_options')) {
    function api_review_callback_validate_options( $input ) {

         // if set, save the settings
        if ( isset( $input['api_review_api_key'] ) ) {
        	$input['api_review_api_key'] = sanitize_text_field( $input['api_review_api_key'] );
        }

        // review query type default option validation
        // upc attribute callback
        $query_type_reviews_default_select_options = array(
            'default' => '',
            'title' => 'Title',
            'mpn' => 'MPN',
            'mpn_title' => 'MPN Title',
            'brand_title' => 'Brand Title',
            'mpn_brand' => 'MPN Brand'
        );
        if ( ! isset( $input['api_review_query_type_reviews_default'] ) ) {
        	$input['api_review_query_type_reviews_default'] = null;
        }
        if ( ! array_key_exists( $input['api_review_query_type_reviews_default'], $query_type_reviews_default_select_options ) ) {
        	$input['api_review_query_type_reviews_default'] = null;
        }

        // if set, save the settings
       if ( isset( $input['api_review_minimum_rating'] ) ) {
           $input['api_review_minimum_rating'] = sanitize_text_field( $input['api_review_minimum_rating'] );
       }

        if ( isset( $input['api_review_max_reviews'] ) ) {
        	$input['api_review_max_reviews'] = sanitize_text_field( $input['api_review_max_reviews'] );
        }

        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $select_options = array( 'default' => '' );
        foreach ( $attribute_taxonomies as $taxonomy ) {
            $taxonomy_name = $taxonomy->attribute_name;
            $taxonomy_label = $taxonomy->attribute_label;
            $name_label_array = array( sanitize_text_field($taxonomy_name) => sanitize_text_field($taxonomy_label));
            $select_options = array_merge( $select_options, $name_label_array );
        }

        // mpn attribute callback
        if ( ! isset( $input['api_review_mpn_attribute'] ) ) {
        	$input['api_review_mpn_attribute'] = null;
        }
        if ( ! array_key_exists( $input['api_review_mpn_attribute'], $select_options ) ) {
        	$input['api_review_mpn_attribute'] = null;
        }
        // brand attribute callback
        if ( ! isset( $input['api_review_brand_attribute'] ) ) {
        	$input['api_review_brand_attribute'] = null;
        }
        if ( ! array_key_exists( $input['api_review_brand_attribute'], $select_options ) ) {
        	$input['api_review_brand_attribute'] = null;
        }

        return $input;
    }
}
