# ATX REST WordPress Plugin

This is a simple plugin that demonstrates using the WordPress REST API to get menu locations and menu items

## Installation

Copy all files in this repo into a folder named `atx-rest` to your plugins folder:
`wp-content/plugins/atx-rest/`

## Usage

### Menus

There are two endpoints that reveal menu information

```
/wp-json/atx/v1/menus/
```

This returns all of the menu locations for your WordPress site.

```
/wp-json/atx/v1/menus/{menu_name}/
```

This returns all of the menu items for that location.

### Shortcode

This plugin provides a simple shortcode for showing posts from a valid REST URL.

The request should return an array of post objects.

Usage:

```
[atx_rest url="http://atxrest.wpengine.com/wp-json/wp/v2/posts"]
```

### Images

There is one endpoint that will deliver images in the WordPress site

```
/wp-json/atx/v1/image/{image_id}/
```

If the image ID is a valid attachment ID in WordPress, then the response will be the image file itself.
