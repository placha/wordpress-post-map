# WordPress Post Map Plugin

Generates geojson file from posts (meta: `coordinates`) and provides the shortcode `[post-map]` to display map.

## Requirements

Leaflet Map - https://wordpress.org/plugins/leaflet-map/

## How to use

1. Download the plugin as a zip file
2. Install and activate the plugin on your WordPress site
3. Add meta `coordinates` to your posts with the value in the format `latitude, longitude`
4. Use the shortcode `[post-map]` in your post content to display the map. Other examples:
- `[post-map post_type="portfolio"]`
- `[post-map category_slug="custom-category-slug"]`
