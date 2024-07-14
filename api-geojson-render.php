<?php

if (($_GET['debug'] ?? false) === 'true') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

// WordPress init
if (!isset($wp_did_header)) {
    $wp_did_header = true;
    require_once(__DIR__ . '/../../../wp-load.php');
    wp();
    require_once(__DIR__ . '/../../../wp-includes/template-loader.php');
}

require_once __DIR__ . '/GeoJson.php';

function getFilteredQueryParameter(string $name): ?string
{
    $value = $_GET[$name] ?? null;
    if ($value === null) {
        return null;
    }
    $value = trim($value);
    if (empty($value)) {
        return null;
    }

    return $value;
}

$postType = getFilteredQueryParameter('post_type');
$categorySlug = getFilteredQueryParameter('category_slug');
if ($postType === null) {
    $postType = 'post';
}

$geoJson = new \WordpressPostMap\GeoJson(
    $postType,
    $categorySlug,
);
echo $geoJson->render();
