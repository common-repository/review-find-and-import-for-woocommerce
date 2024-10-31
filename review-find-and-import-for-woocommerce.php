<?php

/**
 * @link              https://www.apigenius.io/rgelhausen
 * @since             1.1.0
 * @package           review-find-and-import-for-woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Review Find and Import for Woocommerce
 * Plugin URI:        https://www.apigenius.io/software/review-find-and-import-for-woocommerce/
 * Description:       Search the web for product reviews and import them into your Woocommerce store.
 * Version:           1.4.0
 * Author:            ApiGenius.io
 * Author URI:        https://www.apigenius.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       review-find-and-import-for-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}


/* create menu pages */
// Top level menu - Product Dashboard
if (!function_exists ('api_review_dashboard')) {
	function api_review_dashboard() {
	   add_menu_page('Review Find and Import for Woocommerce', 'Product Reviews', 'manage_woocommerce', 'api-review-dashboard', 'api_review_dashboard_callback', 'dashicons-admin-comments', 102);
	}
	add_action('admin_menu', 'api_review_dashboard');
}
if (!function_exists ('api_review_dashboard_callback')) {
	function api_review_dashboard_callback() {
	   include(plugin_dir_path(__FILE__) . 'admin-pages/dashboard.php');
    }
}

// Automations admin page
if (!function_exists ('api_review_automation_page')) {
	function api_review_automation_page() {
	   add_submenu_page('api-review-dashboard', 'Automations', 'Automations','manage_options', 'api-review-automations', 'api_review_automation_page_callback');
	}
	add_action('admin_menu', 'api_review_automation_page');
}
if (!function_exists ('api_review_automation_page_callback')) {
	function api_review_automation_page_callback() {
	   include(plugin_dir_path(__FILE__) . 'admin-pages/automation-page.php');
	}
}

// Settings page
if (!function_exists ('api_review_settings')) {
	function api_review_settings() {
	   add_submenu_page('api-review-dashboard', 'Settings', 'Settings','manage_woocommerce', 'api-review-settings', 'api_review_settings_callback');
	}
	add_action('admin_menu', 'api_review_settings');
}

if (!function_exists ('api_review_settings_callback')) {
	function api_review_settings_callback() {
	   include(plugin_dir_path(__FILE__) . 'admin-pages/settings.php');
	}
}

// How to page
if (!function_exists ('api_review_how_to')) {
	function api_review_how_to() {
	   add_submenu_page('api-review-dashboard', 'How To & Help', 'How To & Help','manage_woocommerce', 'api-review-how_to', 'api_review_how_to_callback');
	}
	add_action('admin_menu', 'api_review_how_to');
}
if (!function_exists ('api_review_how_to_callback')) {
	function api_review_how_to_callback() {
	   include(plugin_dir_path(__FILE__) . 'admin-pages/how-to.php');
	}
}

// Other Plugins
if (!function_exists ('api_review_other_plugins')) {
	function api_review_other_plugins() {
	   add_submenu_page('api-review-dashboard', 'Other Great Plugins', 'Other Plugins','manage_woocommerce', 'api-review-other-plugins', 'api_review_other_plugins_callback');
	}
	add_action('admin_menu', 'api_review_other_plugins');
}
if (!function_exists ('api_review_other_plugins_callback')) {
	function api_review_other_plugins_callback() {
	   include(plugin_dir_path(__FILE__) . 'admin-pages/other-plugins.php');
	}
}

/* include pages */
include(plugin_dir_path(__FILE__) . 'functions/functions-shared.php');
include(plugin_dir_path(__FILE__) . 'functions/functions-automations.php');
include(plugin_dir_path(__FILE__) . 'functions/functions-reviews.php');
include(plugin_dir_path(__FILE__) . 'settings/settings-register.php');
include(plugin_dir_path(__FILE__) . 'settings/settings-callback.php');
include(plugin_dir_path(__FILE__) . 'settings/settings-validation.php');


/* Default options */
if (!function_exists ('api_review_default_options')) {
	function api_review_default_options() {
		return array(
			'api_review_api_key' => '',
			'api_review_brand_attribute' => '',
			'api_review_mpn_attribute' => '',
			'api_review_disable_automations' => '',
			'api_review_minimum_rating' => '',
		);
	}
}

function api_review_custom_cron( $schedules ){
    if(!isset($schedules['5min'])){
        $schedules['5min'] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    return $schedules;
}
add_filter( 'cron_schedules', 'api_review_custom_cron' );

// schedule event used for automations
function api_review_cron() {
	if (! wp_next_scheduled ('api_review_automation_hook')) {
		wp_schedule_event(time(), '5min', 'api_review_automation_hook');
	}
}
register_activation_hook(__FILE__, 'api_review_cron');

// deactivation
// remove plugin created events
if (!function_exists ('api_review_cron_deactivation')) {
	function api_review_cron_deactivation() {
		wp_clear_scheduled_hook('api_review_automation_hook');
	}
	register_deactivation_hook(__FILE__, 'api_review_cron_deactivation');
}

// create options
if (!function_exists ('api_review_create_options')) {
	function api_review_create_options() {
		add_option('api_review_automation_all');
		add_option('api_review_automation_count', 0);
	}
	register_activation_hook(__FILE__, 'api_review_create_options');
}
