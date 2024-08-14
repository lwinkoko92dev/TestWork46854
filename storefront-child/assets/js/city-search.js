jQuery(document).ready(function($) {
    $('#city-search-form').on('submit', function(e) {
        e.preventDefault();
        
        var searchTerm = $('#city-search').val();
        
        $.ajax({
            url: ajax_object.ajaxurl, // Use localized variable
            type: 'POST',
            data: {
                action: 'city_search',
                search_term: searchTerm
            },
            success: function(response) {
                $('#cities-table').html(response);
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error: ' + status + ' - ' + error);
            }
        });
    });
});