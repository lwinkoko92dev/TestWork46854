<?php

class Weather {
    private $api_key;

    public function __construct() {
        // Initialize the API key from options
        $this->api_key = get_option('weather_api_key');
    }

    public function get_weather_data($latitude, $longitude) {
        $response = wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&appid={$this->api_key}");

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['main']['temp'])) {
            return $data;
        }

        return false;
    }
}
