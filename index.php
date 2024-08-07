<?php
/**
 * Plugin Name:       WordPress Post Map
 * Plugin URI:        https://github.com/placha/wordpress-post-map
 * Description:       Generates geojson file from posts and provides the shortcode to display map.
 * Version:           1.4.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Kacper Placha
 * Author URI:        https://placha.pl/
 * Text Domain:       wordpress-post-map
 * License:           MIT
 * Update URI:        https://raw.githubusercontent.com/placha/wordpress-post-map/main/current.json
 */

if (($_GET['debug'] ?? false) === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

function main()
{
}

function postMapShortcode($attr): string
{
    $postType = $attr['post_type'] ?? 'post';
    $categorySlug = $attr['category_slug'] ?? '';

    if( !function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_data = get_plugin_data(__FILE__);
    $result = do_shortcode('[leaflet-map zoom="12" lat="50.06172102288047" lng="19.93735195760001" height="500" width="100%" min_zoom="8" max_zoom="16" zoomcontrol fitbounds !tap]');
    $result .= do_shortcode('[leaflet-geojson src=' . get_site_url() . '/wp-content/plugins/' . $plugin_data['TextDomain'] . '/api-geojson-render.php?post_type=' . $postType . '&category_slug=' . $categorySlug . ']{popup-text}[/leaflet-geojson]');

    return $result;
}
add_shortcode('post-map', 'postMapShortcode');
