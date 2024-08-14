<?php

/**
 * Create a Custom Taxonomy 'Countries' for 'Cities'
 */

add_action('init', 'create_custom_taxonomy_countries');

function create_custom_taxonomy_countries() {
    $labels = array(
        'name'              => _x('Countries', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Country', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Countries', 'textdomain'),
        'all_items'         => __('All Countries', 'textdomain'),
        'parent_item'       => __('Parent Country', 'textdomain'),
        'parent_item_colon' => __('Parent Country:', 'textdomain'),
        'edit_item'         => __('Edit Country', 'textdomain'),
        'update_item'       => __('Update Country', 'textdomain'),
        'add_new_item'      => __('Add New Country', 'textdomain'),
        'new_item_name'     => __('New Country Name', 'textdomain'),
        'menu_name'         => __('Countries', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'country'),
    );

    register_taxonomy('countries', array('cities'), $args);
}