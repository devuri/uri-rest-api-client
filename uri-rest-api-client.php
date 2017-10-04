<?php
/*
Plugin Name: URI REST API Client
Description: Plugin to display a JSON feed from api.uri.edu as an HTML table using WordPress shortcode.
Version: 0.1
Author: Heath Loder
*/

// -------------------------------------------------------------
// Force requests to go through WordPress
// -------------------------------------------------------------
if (!function_exists('add_action') || !defined('ABSPATH')) {
   echo 'This plugin must be called from within Wordpress.';
   exit;
}
// -------------------------------------------------------------
// Import plugin class and settings files
// -------------------------------------------------------------
// Import primary plugin class file
if(!class_exists('UriRestApiClient')){
   require_once plugin_dir_path( __FILE__ ) . '/class-uri-rest-api-client.php';
}
// Import settings page for plugin
require_once plugin_dir_path( __FILE__ ) . '/options.php';

// -------------------------------------------------------------
// Execute plugin
// -------------------------------------------------------------
// instantiate plugin class
$uriRestApiClient = new UriRestApiClient();
?>
