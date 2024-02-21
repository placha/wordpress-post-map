<?php
/**
 * WordPress Post Map
 *
 * @package           placha/wordpress-post-map
 * @author            Kacper Placha
 * @copyright         2024 Kacper Placha
 * @license           MIT
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Post Map
 * Plugin URI:        https://github.com/placha/wordpress-post-map
 * Description:       Generates geojson file from portfolio posts (meta: coordinates) and provide shortcode: [post-map]
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Kacper Placha
 * Author URI:        https://placha.pl/
 * Text Domain:       wordpress-post-map
 * License:           MIT
 * Update URI:        https://raw.githubusercontent.com/placha/wordpress-post-map/main/current.json
 */

function main()
{
}

add_shortcode('post-map', 'postMapShortcode');
function postMapShortcode($attr): string
{
    $plugin_data = get_plugin_data( __FILE__ );
    $result = do_shortcode('[leaflet-map zoom="12" lat="50.06172102288047" lng="19.93735195760001" height="500" width="100%" min_zoom="8" max_zoom="16" zoomcontrol fitbounds !tap]');
    $result .= do_shortcode('[leaflet-geojson src=' . get_site_url() . '/wp-content/plugins/'.$plugin_data['TextDomain'].'/geojson-render.php]{popup-text}[/leaflet-geojson]');

    return $result;
}

