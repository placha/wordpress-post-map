<?php

//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);

// Wordpress init

if (!isset($wp_did_header)) {
    $wp_did_header = true;
    require_once(__DIR__ . '/../../../wp-load.php');
    wp();
    require_once(__DIR__ . '/../../../wp-includes/template-loader.php');
}

require_once __DIR__ . '/GeoJson.php';
(new WordpressPostMap\GeoJson())->render();
