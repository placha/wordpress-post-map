<?php

/*
Plugin Name: Wordpress Post Map
Description: Generates geojson file from portfolio posts (meta: coordinates) and provide shortcode: [post-map]
Version: 1.0.0
Author: Kacper Placha
Author URI: https://placha.pl/
License: MIT
*/

function main()
{
}

add_shortcode('post-map', 'postMapShortcode');
function postMapShortcode($attr): string
{
    $result = do_shortcode('[leaflet-map zoom="12" lat="50.06172102288047" lng="19.93735195760001" height="500" width="100%" min_zoom="8" max_zoom="16" zoomcontrol fitbounds !tap]');
    $result .= do_shortcode('[leaflet-geojson src=' . get_site_url() . '/wp-content/plugins/wordpress-post-map/geojson-render.php]{popup-text}[/leaflet-geojson]');
    return $result;
}

