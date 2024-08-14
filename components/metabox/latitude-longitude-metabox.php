<?php

/**
 * Create a Meta Box with Fields 'Latitude' and 'Longitude'
 */

add_action('add_meta_boxes', 'add_cities_meta_box');
add_action('save_post', 'save_cities_meta_box_data');

function add_cities_meta_box() {
    add_meta_box(
        'cities_meta_box', // ID
        'City Details', // Title
        'display_cities_meta_box', // Callback
        'cities', // Screen (post type)
        'side', // Context
        'high' // Priority
    );
}

function display_cities_meta_box($post) {
    // Retrieve existing values from the database
    $latitude = get_post_meta($post->ID, '_latitude', true);
    $longitude = get_post_meta($post->ID, '_longitude', true);

    // Nonce field for security
    wp_nonce_field(basename(__FILE__), 'cities_meta_box_nonce');

    echo '<p>';
    echo '<label for="latitude">Latitude</label>';
    echo '<input type="text" id="latitude" name="latitude" value="' . esc_attr($latitude) . '" size="25" />';
    echo '</p>';
    echo '<p>';
    echo '<label for="longitude">Longitude</label>';
    echo '<input type="text" id="longitude" name="longitude" value="' . esc_attr($longitude) . '" size="25" />';
    echo '</p>';
}

function save_cities_meta_box_data($post_id) {
    // Check if our nonce is set.
    if (!isset($_POST['cities_meta_box_nonce']) || !wp_verify_nonce($_POST['cities_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check the user's permissions
    if ('cities' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    } else {
        return $post_id;
    }

    // Sanitize and save the data
    $latitude = sanitize_text_field($_POST['latitude']);
    $longitude = sanitize_text_field($_POST['longitude']);

    update_post_meta($post_id, '_latitude', $latitude);
    update_post_meta($post_id, '_longitude', $longitude);
}