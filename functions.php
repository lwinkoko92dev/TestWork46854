<?php

/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
 
// Define the child theme root URL
define('STOREFRONT_CHILD_ROOT', get_stylesheet_directory());

// Reusing Style of Parent Theme
function theme_enqueue_styles() {
	$parent_style = 'parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

// Hook to the template_include filter to load templates from the 'templates' directory
function load_custom_templates($template) {
    $custom_templates_path = get_stylesheet_directory() . '/templates/';
    $template_name = basename($template);

    // Check if the template is in the custom 'templates' directory
    if (file_exists($custom_templates_path . $template_name)) {
        return $custom_templates_path . $template_name;
    }

    // Return the default template if the custom template does not exist
    return $template;
}
add_filter('template_include', 'load_custom_templates');

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */
 
/**
 * Create Option Page to Handle Weather API
 */
require_once STOREFRONT_CHILD_ROOT . '/utils/admin/weather-api-settings-class.php';

/**
 * Create Custom Post Type 'Cities'
 */
require_once STOREFRONT_CHILD_ROOT . '/components/post-type/cities.php';

/**
 * Create a Custom Taxonomy 'Countries' for 'Cities'
 */
require_once STOREFRONT_CHILD_ROOT . '/components/taxonomy/countries.php';

/**
 * Create a Meta Box with Fields 'Latitude' and 'Longitude'
 */
require_once STOREFRONT_CHILD_ROOT . '/components/metabox/latitude-longitude-metabox.php';

/**
 * Create Custom Widget For 'City Temperature'
 */
require_once STOREFRONT_CHILD_ROOT . '/components/widgets/city-temperature-widget.php';

/**
 * Create Utilities Functions for 'City Table' template
 */
require_once STOREFRONT_CHILD_ROOT . '/utils/cities-class.php';

