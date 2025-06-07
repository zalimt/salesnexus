<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-wrapper">
            <!-- Logo Section -->
            <div class="site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo-link">
                    <div class="site-logo"></div>
                </a>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="main-navigation" role="navigation">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'primary-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                } else {
                    echo '<ul class="primary-menu">';
                    echo '<li><a href="#">Product</a></li>';
                    echo '<li><a href="#">How it Works</a></li>';
                    echo '<li><a href="#">Resources</a></li>';
                    echo '<li><a href="#">Pricing</a></li>';
                    echo '</ul>';
                }
                ?>
            </nav>
            
            <!-- Header Action Buttons -->
            <div class="header-actions">
                <a href="#" class="btn-demo btn-orange t-17 fw-500">Get a Demo</a>
                <a href="#" class="btn-trial t-17 fw-500">Start Free Trial</a>
            </div>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </div>
</header> 