<?php

require_once STOREFRONT_CHILD_ROOT . '/utils/weather-class.php';

class City_Temperature_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'city_temperature_widget', // Base ID
            __('City Temperature Widget', 'text_domain'), // Name
            array('description' => __('A Widget to display the city name and current temperature', 'text_domain'),) // Args
        );
    }

    public function widget($args, $instance) {
		// Output the content of the widget
		echo $args['before_widget'];

		if (!empty($instance['city_id'])) {
			$city_id = $instance['city_id'];
			$city = get_post($city_id);

			if ($city) {
				$latitude = get_post_meta($city_id, '_latitude', true);
				$longitude = get_post_meta($city_id, '_longitude', true);

				if ($latitude && $longitude) {
					$weather = new Weather();
                    $weather_data = $weather->get_weather_data($latitude, $longitude);

					if ($weather_data) {
						$temperature = $weather_data['main']['temp'] - 273.15; // Convert from Kelvin to Celsius
						$temperature = round($temperature, 1);
						$city_name = esc_html($city->post_title);

						echo $args['before_title'] . $city_name . $args['after_title'];
						echo '<p>Current Temperature: ' . $temperature . '°C</p>';
					} else {
						echo '<p>Unable to retrieve weather data.</p>';
					}
				} else {
					echo '<p>Latitude and Longitude not set for this city.</p>';
				}
			} else {
				echo '<p>City not found.</p>';
			}
		} else {
			echo '<p>No city selected.</p>';
		}

		echo $args['after_widget'];
	}

    public function form($instance) {
		// Get all cities
		$cities = get_posts(array(
			'post_type'   => 'cities',
			'numberposts' => -1,
		));

		$city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id('city_id'); ?>"><?php _e('Select City:'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('city_id'); ?>" name="<?php echo $this->get_field_name('city_id'); ?>">
				<option value=""><?php _e('Select a city'); ?></option>
				<?php foreach ($cities as $city) : ?>
					<option value="<?php echo esc_attr($city->ID); ?>" <?php selected($city_id, $city->ID); ?>>
						<?php echo esc_html($city->post_title); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

    public function update($new_instance, $old_instance) {
        // Sanitize and save widget form values
        $instance = array();
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? strip_tags($new_instance['city_id']) : '';

        return $instance;
    }
}

// Register the widget
function register_city_temperature_widget() {
    register_widget('City_Temperature_Widget');
}
add_action('widgets_init', 'register_city_temperature_widget');