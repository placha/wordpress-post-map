<?php

// WordPress init
if (!isset($wp_did_header)) {
    $wp_did_header = true;
    require_once(__DIR__ . '/../../../wp-load.php');
    wp();
    require_once(__DIR__ . '/../../../wp-includes/template-loader.php');
}

require_once __DIR__ . '/GeoJson.php';

$geoJson = new WordpressPostMap\GeoJson();
$geoJson->render();
