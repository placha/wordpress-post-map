<?php

namespace WordpressPostMap;

use JsonException;
use WP_Query;

class GeoJson
{
    private const POST_META_KEY = 'coordinates';

    public function __construct(
        private $postType,
        private $categorySlug = null,
    )
    {
    }

    public function render(): string
    {
        header('Content-Type: text/plain; charset=utf-8');

        try {
            return json_encode($this->getJsonData(), JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return '{}';
        }
    }

    private function getJsonData(): array
    {
        $queryArgs = [
            'post_type' => $this->postType,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => self::POST_META_KEY,
                    'compare' => 'EXISTS',
                ],
            ],
        ];
        if ($this->categorySlug !== null) {
            $queryArgs['category_name'] = $this->categorySlug;
        }
        $query = new WP_Query($queryArgs);
        $jsonData = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
        foreach ($query->posts as $post) {
            $permalink = get_post_permalink($post->ID) ?? '';
            $coordinates = get_post_meta($post->ID, self::POST_META_KEY)[0] ?? null;
            $postTitle = $post->post_title ?? '';
            if ($coordinates !== null) {
                $jsonData['features'][] = $this->getPointData(
                    $coordinates,
                    "<a href=\"$permalink\">$postTitle</a>"
                );
            }
        }

        return $jsonData;
    }

    private function getPointData(string $coordinates, string $popupText): array
    {
        return [
            "type" => "Feature",
            'properties' => [
                "popup-text" => $popupText,
            ],
            "geometry" => [
                "type" => "Point",
                "coordinates" => $this->getCoordinatesArray($coordinates),
            ],
        ];
    }

    /**
     * @return array{float, float}
     */
    private function getCoordinatesArray(string $coordinates): array
    {
        $coordinatesStr = trim($coordinates);
        [$lat, $lon] = explode(',', $coordinatesStr);

        return [
            (float)trim($lon),
            (float)trim($lat),
        ];
    }
}
