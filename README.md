# ATX REST Menus WordPress Plugin

This is a simple plugin that demonstrates using the WordPress REST API to get menu locations and menu items

## Installation

Copy all files in this repo into a folder named `atx-rest-menus` to your plugins folder:
`wp-content/plugins/atx-rest-menus/`

## Usage

There are two endpoints added with this plugin

```
/wp-json/atx/v1/menus/
```

This returns all of the menu locations for your WordPress site.

```
/wp-json/atx/v1/menus/{menu_name}/
```

This returns all of the menu items for that location.
