<?php

require_once STOREFRONT_CHILD_ROOT . '/utils/weather-class.php';

class Cities {

    public function __construct() {
        // Hook into AJAX actions
        add_action('wp_ajax_city_search', array($this, 'ajax_city_search'));
        add_action('wp_ajax_nopriv_city_search', array($this, 'ajax_city_search'));

        // Enqueue the script
        add_action('wp_enqueue_scripts', array($this, 'enqueue_city_search_script'));
    }

    public function get_cities_table() {
        global $wpdb;

        // Query custom post type 'cities' to get country, city, latitude, and longitude
        $query = "
            SELECT p.ID, p.post_title AS city_name, 
                   pm_lat.meta_value AS latitude, 
                   pm_lon.meta_value AS longitude
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_lat ON p.ID = pm_lat.post_id AND pm_lat.meta_key = '_latitude'
            LEFT JOIN {$wpdb->postmeta} pm_lon ON p.ID = pm_lon.post_id AND pm_lon.meta_key = '_longitude'
            WHERE p.post_type = 'cities' AND p.post_status = 'publish'
        ";

        $cities = $wpdb->get_results($query);

        if (empty($cities)) {
            return '<p>No data found.</p>';
        }

        $output = '<table>';
        $output .= '<thead><tr><th>Country</th><th>City</th><th>Temperature (°C)</th></tr></thead>';
        $output .= '<tbody>';

        foreach ($cities as $city) {
            // Get the country term(s) for the city
            $terms = wp_get_post_terms($city->ID, 'countries'); // Replace 'countries' with your taxonomy slug

            $country_names = array();
            foreach ($terms as $term) {
                $country_names[] = esc_html($term->name);
            }
            $country_names_str = implode(', ', $country_names);

            $weather = new Weather();

            // Fetch the temperature from the weather API
            $weather_data = $weather->get_weather_data($city->latitude, $city->longitude);
            $temperature = $weather_data['main']['temp'] - 273.15; // Convert from Kelvin to Celsius
            $temperature = round($temperature, 1);

            $output .= '<tr>';
            $output .= '<td>' . $country_names_str . '</td>';
            $output .= '<td>' . esc_html($city->city_name) . '</td>';
            $output .= '<td>' . ($temperature !== false ? round($temperature, 1) . '°C' : 'N/A') . '</td>';
            $output .= '</tr>';
        }

        $output .= '</tbody></table>';

        return $output;
    }

    public function ajax_city_search() {
        global $wpdb;

        $search_term = sanitize_text_field($_POST['search_term']);

        // Query custom post type 'cities' to get country, city, latitude, and longitude
        $query = $wpdb->prepare("
            SELECT p.ID, p.post_title AS city_name, 
                   pm_lat.meta_value AS latitude, 
                   pm_lon.meta_value AS longitude
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} pm_lat ON p.ID = pm_lat.post_id AND pm_lat.meta_key = '_latitude'
            LEFT JOIN {$wpdb->postmeta} pm_lon ON p.ID = pm_lon.post_id AND pm_lon.meta_key = '_longitude'
            WHERE p.post_type = 'cities' AND p.post_status = 'publish'
              AND p.post_title LIKE %s
        ", '%' . $wpdb->esc_like($search_term) . '%');

        $cities = $wpdb->get_results($query);

        if (empty($cities)) {
            echo '<p>No data found.</p>';
        } else {
            $output = '<table>';
            $output .= '<thead><tr><th>Country</th><th>City</th><th>Temperature (°C)</th></tr></thead>';
            $output .= '<tbody>';

            foreach ($cities as $city) {
                // Get the country term(s) for the city
                $terms = wp_get_post_terms($city->ID, 'countries'); // Replace 'countries' with your taxonomy slug

                $country_names = array();
                foreach ($terms as $term) {
                    $country_names[] = esc_html($term->name);
                }
                $country_names_str = implode(', ', $country_names);

                $weather = new Weather();

                // Fetch the temperature from the weather API
                $weather_data = $weather->get_weather_data($city->latitude, $city->longitude);
                $temperature = $weather_data['main']['temp'] - 273.15; // Convert from Kelvin to Celsius
                $temperature = round($temperature, 1);

                $output .= '<tr>';
                $output .= '<td>' . $country_names_str . '</td>';
                $output .= '<td>' . esc_html($city->city_name) . '</td>';
                $output .= '<td>' . ($temperature !== false ? round($temperature, 1) . '°C' : 'N/A') . '</td>';
                $output .= '</tr>';
            }

            $output .= '</tbody></table>';
            echo $output;
        }

        wp_die(); // Required to terminate immediately and return a proper response
    }

    public function enqueue_city_search_script() {
        wp_enqueue_script('city-search', get_stylesheet_directory_uri() . '/assets/js/city-search.js', array('jquery'), null, true);
        wp_localize_script('city-search', 'ajax_object', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}

// Instantiate the class
new Cities();
