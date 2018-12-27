(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    document.getElementById('output').innerHTML = location.search;

    $(".chosen-select").chosen();


    $(window).load(function() {
        $("#specific-products").hide();
        $("#categories").hide();
    });

    $('#apply_to').on('change', function() {
        var selectedOption = $(this).children("option:selected").val();
        if ('Specific Product' === selectedOption) {
            $("#specific-products").show();
            $("#categories").hide();
        } else if ('Specific Category' === selectedOption) {
            $("#specific-products").hide();
            $("#categories").show();
        } else if ('All Products' === selectedOption) {
            $("#specific-products").hide();
            $("#categories").hide();
        }
    });

})(jQuery);