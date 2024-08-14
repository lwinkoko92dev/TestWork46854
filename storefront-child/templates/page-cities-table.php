<?php
/*
Template Name: Cities Table
*/

get_header(); ?>

<div id="cities-table-container">
	<div class="col">
		<?php
		// Custom action hook before the table
		do_action('before_cities_table');

		// Display the search form
		?>
		<form id="city-search-form">
			<input type="text" id="city-search" name="search_term" placeholder="Search cities">
			<button type="submit">Search</button>
		</form>

		<?php
		// Custom action hook before the table
		do_action('after_city_search_form');
		
		// Display the table
		?>
		<div id="cities-table">
			<?php 
				$city_table = new Cities();
				echo $city_table->get_cities_table(); 
			?>
		</div>
		
		<?php
		// Custom action hook after the table
		do_action('after_cities_table');
		?>
	</div>
	<div class="col">
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
