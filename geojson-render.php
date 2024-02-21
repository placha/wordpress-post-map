<?php

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
    exit;
}

$geoJson = new WordpressPostMap\GeoJson(
    $postType,
    $categorySlug,
);
$geoJson->render();
