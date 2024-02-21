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

    /**
     * @throws JsonException
     */
    public function render(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        echo json_encode($this->getJsonData(), JSON_THROW_ON_ERROR);
    }

    public function getJsonData(): array
    {
        $queryArgs = [
            'post_type' => $this->postType,
            'post_status' => 'publish',
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
            if ($coordinates !== null) {
                $jsonData['features'][] = $this->getPointData($coordinates, $post->post_title ?? '', $permalink);
            }
        }

        return $jsonData;
    }

    private function getPointData(string $coordinates, string $title, string $permalink): array
    {
        return [
            "type" => "Feature",
            'properties' => [
                "popup-text" => "<a href=\"$permalink\">$title</a>",
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
