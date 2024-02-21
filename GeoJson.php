<?php

namespace WordpressPostMap;

use JsonException;
use WP_Query;

class GeoJson
{
    private array $jsonData;

    public function __construct($postType = 'portfolio', $postStatus = 'publish')
    {
        $query = new WP_Query([
            'post_type' => $postType,
            'post_status' => $postStatus,
        ]);
        $this->jsonData = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];
        foreach ($query->posts as $post) {
            $permalink = get_post_permalink($post->ID) ?? '';
            $coordinates = get_post_meta($post->ID, 'coordinates')[0] ?? null;
            if ($coordinates !== null) {
                $this->jsonData['features'][] = $this->getPointData($coordinates, $post->post_title ?? '', $permalink);
            }
        }
    }

    /**
     * @throws JsonException
     */
    public function render():void{
        header('Content-Type: text/plain; charset=utf-8');
        echo json_encode($this->jsonData, JSON_THROW_ON_ERROR);
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
