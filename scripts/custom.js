/**
 * SalesNexus Custom Scripts
 * 
 * Add your custom JavaScript here
 */

(function($) {
    'use strict';
    
    // Document ready function
    $(document).ready(function() {
        console.log('SalesNexus theme loaded successfully!');
        
        // Initialize custom functionality
        initSalesNexusFeatures();
    });
    
    /**
     * Initialize SalesNexus custom features
     */
    function initSalesNexusFeatures() {
        // Example: Smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        });
        
        // Example: Custom button interactions
        $('.btn-salesnexus').on('click', function() {
            console.log('SalesNexus button clicked!');
            // Add your custom button functionality here
        });
        
        // Example: Mobile menu enhancements
        if (window.innerWidth <= 768) {
            // Add mobile-specific functionality here
            console.log('Mobile view detected');
        }
    }
    
    /**
     * Window resize handler
     */
    $(window).on('resize', function() {
        // Handle responsive behavior
        if (window.innerWidth <= 768) {
            // Mobile specific code
        } else {
            // Desktop specific code
        }
    });
    
})(jQuery); 