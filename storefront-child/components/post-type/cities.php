<?php

/**
 * Create Custom Post Type 'Cities'
 */

add_action('init', 'create_custom_post_type_cities');

function create_custom_post_type_cities() {
    $labels = array(
        'name'               => _x('Cities', 'post type general name', 'textdomain'),
        'singular_name'      => _x('City', 'post type singular name', 'textdomain'),
        'menu_name'          => _x('Cities', 'admin menu', 'textdomain'),
        'name_admin_bar'     => _x('City', 'add new on admin bar', 'textdomain'),
        'add_new'            => _x('Add New', 'city', 'textdomain'),
        'add_new_item'       => __('Add New City', 'textdomain'),
        'new_item'           => __('New City', 'textdomain'),
        'edit_item'          => __('Edit City', 'textdomain'),
        'view_item'          => __('View City', 'textdomain'),
        'all_items'          => __('All Cities', 'textdomain'),
        'search_items'       => __('Search Cities', 'textdomain'),
        'parent_item_colon'  => __('Parent Cities:', 'textdomain'),
        'not_found'          => __('No cities found.', 'textdomain'),
        'not_found_in_trash' => __('No cities found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'city'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('cities', $args);
}