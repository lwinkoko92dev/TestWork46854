<?php

class Weather_API_Settings {

    public function __construct() {
        // Hook into the admin menu
        add_action('admin_menu', array($this, 'add_menu_page'));
        // Register settings and fields
        add_action('admin_init', array($this, 'initialize_settings'));
    }

    public function add_menu_page() {
        add_options_page(
            'Weather API Settings',        // Page title
            'Weather API',                 // Menu title
            'manage_options',              // Capability
            'weather-api-settings',        // Menu slug
            array($this, 'render_settings_page') // Callback function
        );
    }

    public function initialize_settings() {
        // Register the setting
        register_setting('weather_api_settings_group', 'weather_api_key');

        // Add a section
        add_settings_section(
            'weather_api_settings_section',
            'API Key Settings',
            array($this, 'settings_section_callback'),
            'weather-api-settings'
        );

        // Add the field
        add_settings_field(
            'weather_api_key_field',
            'API Key',
            array($this, 'settings_field_callback'),
            'weather-api-settings',
            'weather_api_settings_section'
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Weather API Settings</h1>
            <form method="post" action="options.php">
                <?php
                // Output security fields for the registered setting
                settings_fields('weather_api_settings_group');
                // Output setting sections and their fields
                do_settings_sections('weather-api-settings');
                // Output save settings button
                submit_button('Save API Key');
                ?>
            </form>
        </div>
        <?php
    }

    public function settings_section_callback() {
        echo 'Enter your API key below:';
    }

    public function settings_field_callback() {
        $api_key = get_option('weather_api_key');
        echo '<input type="text" id="weather_api_key" name="weather_api_key" value="' . esc_attr($api_key) . '" />';
    }
}

// Instantiate the class
new Weather_API_Settings();
