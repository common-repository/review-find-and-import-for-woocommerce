<?php

// If this file is called directly, abort.
if (! defined('WPINC')) {
   die;
}

// setting section description callbacks
if (!function_exists ('api_review_general_options_section_callback')) {
    function api_review_general_options_section_callback() {
    	echo wp_kses_post('<p>General settings for the plugin.  Get your free API key via the link below.</p>');
    }
}
if (!function_exists ('api_review_assign_attributes_section_callback')) {
    function api_review_assign_attributes_section_callback() {
    	echo wp_kses_post('<p>With these options you can assign attributes to there correct attribute slug.</p>');
    }
}
if (!function_exists ('api_review_section_reviews_callback')) {
    function api_review_section_reviews_callback() {
    	echo wp_kses_post('<p>Options that controle the Review Import functionality.</p>');
    }
}

// callback: text field
if (!function_exists ('api_review_text_field_callback')) {
    function api_review_text_field_callback($args) {
    	$plugin_options = get_option('api_review_options', api_review_default_options());
    	$id    = isset($args['id'])    ? sanitize_text_field($args['id'])    : '';
    	$label = isset($args['label']) ? wp_kses_post($args['label']) : '';
    	$value = isset($plugin_options[$id]) ? sanitize_text_field($plugin_options[$id]) : '';
    	echo '<input id="api_review_options_'. esc_html($id) .'" name="api_review_options['. esc_html($id) .']" type="text" size="40" value="'. esc_html($value) .'"><br />';
    	echo '<label for="api_review_options_'. esc_html($id) .'">'. wp_kses_post($label) .'</label>';
    }
}

// callback: textarea
if (!function_exists ('api_review_textarea_field_callback')) {
    function api_review_textarea_field_callback($args) {
        // allowed html tags for descriptions
        $allowed_html = array(
            'br' => array(),
            'strong' => array(),
            'u' => array()
        );
    	$plugin_options = get_option('api_review_options', api_review_default_options());
    	$id    = isset($args['id'])    ? sanitize_text_field($args['id'])    : '';
    	$label = isset($args['label']) ? wp_kses_post($args['label']) : '';
    	$value = isset($plugin_options[$id]) ? wp_kses(stripslashes_deep($plugin_options[$id]), $allowed_html) : '';
    	echo '<textarea id="api_review_options_'. esc_html($id) .'" name="api_review_options['. esc_html($id) .']" rows="5" cols="50">'. esc_textarea($value) .'</textarea><br />';
        echo '<label for="api_review_options_'. esc_html($id) .'">'. wp_kses_post($label) .'</label>';
    }
}

// callback: checkbox field
if (!function_exists ('api_review_check_box_callback')) {
    function api_review_check_box_callback($args) {
    	$plugin_options = get_option('api_review_options', api_review_default_options());
    	$id    = isset($args['id'])    ? sanitize_text_field($args['id'])    : '';
    	$label = isset($args['label']) ? wp_kses_post($args['label']) : '';
    	$checked = isset($plugin_options[$id]) ? checked($plugin_options[$id], 1, false) : '';
    	echo '<input id="api_review_options_'. esc_html($id) .'" name="api_review_options['. esc_html($id) .']" type="checkbox" value="1"'. esc_html($checked) .'> ';
    	echo '<label for="api_review_options_'. esc_html($id) .'">'. wp_kses_post($label) .'</label>';
    }
}

// callback: select field
if (!function_exists ('api_review_select_callback')) {
    function api_review_select_callback($args) {
    	$plugin_options = get_option('api_review_options', api_review_default_options());
    	$id    = isset($args['id'])    ? sanitize_text_field($args['id'])    : '';
    	$label = isset($args['label']) ? sanitize_text_field($args['label']) : '';
    	$selected_option = isset($plugin_options[$id]) ? sanitize_text_field($plugin_options[$id]) : '';
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $select_options = array('' => '');
        foreach ($attribute_taxonomies as $taxonomy) {
            $taxonomy_name = $taxonomy->attribute_name;
            $taxonomy_label = $taxonomy->attribute_label;
            $name_label_array = array($taxonomy_name => $taxonomy_label);
            $select_options = array_merge($select_options, $name_label_array);
        }
    	echo '<select id="api_review_options_'. esc_html($id) .'" name="api_review_options['. esc_html($id) .']">';
    	foreach ($select_options as $value => $option) {
    		$selected = selected($selected_option === $value, true, false);
    		echo '<option value="'. esc_html($value) .'"'. esc_html($selected) .'>'. esc_html($option) .'</option>';
    	}
    	echo '</select> <label for="api_review_options_'. esc_html($id) .'">'. esc_html($label) .'</label>';
    }
}
