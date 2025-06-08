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
        
        // Mobile menu functionality
        initMobileMenu();
    }
    
    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenuPanel = document.querySelector('.mobile-menu-panel');
        const mobileMenuClose = document.querySelector('.mobile-menu-close');
        const body = document.body;
        
        if (mobileMenuToggle && mobileMenuPanel) {
            // Function to close menu
            function closeMobileMenu() {
                mobileMenuPanel.classList.remove('active');
                mobileMenuToggle.classList.remove('active');
                body.classList.remove('mobile-menu-open');
            }
            
            // Function to open menu
            function openMobileMenu() {
                mobileMenuPanel.classList.add('active');
                mobileMenuToggle.classList.add('active');
                body.classList.add('mobile-menu-open');
            }
            
            // Toggle menu
            mobileMenuToggle.addEventListener('click', function() {
                const isActive = mobileMenuPanel.classList.contains('active');
                
                if (isActive) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });
            
            // Close button functionality
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', function() {
                    closeMobileMenu();
                });
            }
            
            // Close menu when clicking on menu links
            const mobileMenuLinks = mobileMenuPanel.querySelectorAll('.mobile-menu-list a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
            
            // Close menu on window resize if it gets too wide
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768 && mobileMenuPanel.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
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