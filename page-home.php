<?php
/**
 * Template Name: Home Page
 * 
 * Custom template for the home page with ACF Flexible Content
 * 
 * @package SalesNexus
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php include get_stylesheet_directory() . '/parts/header.html'; ?>

<main id="main" class="site-main home-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('home-page-content'); ?>>
            
            <?php
            // Check if ACF flexible content field exists
            if (function_exists('get_field')) {
                $sections = get_field('salesnexus_sections_copy');
                
                if ($sections) {
                    echo '<div class="salesnexus-sections">';
                    
                    foreach ($sections as $section) {
                        $layout = $section['acf_fc_layout'];
                        
                        switch ($layout) {
                            case 'hero_slider':
                                ?>
                                <section class="hero-slider-section">
                                    <div class="hero-slider-container">
                                        <?php 
                                        $slides = $section['hero_slider_slide'];
                                        if ($slides) : ?>
                                            <div class="hero-slider" id="hero-slider">
                                                <?php foreach ($slides as $index => $slide) : ?>
                                                    <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                                        <div class="container">
                                                            <div class="hero-content-wrapper">
                                                                <div class="hero-text-content">
                                                                    <?php if (!empty($slide['header_hero_slider_slide'])) : ?>
                                                                        <h1 class="hero-main-heading font-caladea t-56 fw-700">
                                                                            <?php echo esc_html($slide['header_hero_slider_slide']); ?>
                                                                        </h1>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['subheading_hero_slider_slide'])) : ?>
                                                                        <div class="hero-subheading font-lexend t-20 fw-300">
                                                                            <?php echo wp_kses_post(nl2br($slide['subheading_hero_slider_slide'])); ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['btn_1_text_hero_slider_slide']) || !empty($slide['btn_2_text_hero_slider_slide'])) : ?>
                                                                        <div class="hero-buttons">
                                                                            <?php if (!empty($slide['btn_1_text_hero_slider_slide'])) : ?>
                                                                                <a href="<?php echo esc_url($slide['btn_1_link_hero_slider_slide'] ?: '#'); ?>" 
                                                                                   class="btn btn-primary btn-demo">
                                                                                    <?php echo esc_html($slide['btn_1_text_hero_slider_slide']); ?>
                                                                                </a>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($slide['btn_2_text_hero_slider_slide'])) : ?>
                                                                                <a href="<?php echo esc_url($slide['btn_2_link_hero_slider_slide'] ?: '#'); ?>" 
                                                                                   class="btn btn-secondary btn-trial">
                                                                                    <?php echo esc_html($slide['btn_2_text_hero_slider_slide']); ?>
                                                                                </a>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['paragrpah_hero_slider_slide'])) : ?>
                                                                        <div class="hero-deal-text font-lexend t-15">
                                                                            <?php echo wp_kses_post(nl2br($slide['paragrpah_hero_slider_slide'])); ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                
                                                                <?php if (!empty($slide['image_hero_slider_slide'])) : ?>
                                                                    <div class="hero-image-content">
                                                                        <div class="hero-mockup-container">
                                                                            <img src="<?php echo esc_url($slide['image_hero_slider_slide']['sizes']['large'] ?: $slide['image_hero_slider_slide']['url']); ?>" 
                                                                                 alt="<?php echo esc_attr($slide['image_hero_slider_slide']['alt'] ?: 'SalesNexus CRM Interface'); ?>"
                                                                                 class="hero-mockup-image">
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            
                                            <?php if (count($slides) > 1) : ?>
                                                <!-- Slider Navigation -->
                                                <div class="hero-slider-nav">
                                                    <button class="slider-prev" aria-label="Previous slide">‹</button>
                                                    <div class="slider-dots">
                                                        <?php foreach ($slides as $index => $slide) : ?>
                                                            <button class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                                    data-slide="<?php echo $index; ?>"
                                                                    aria-label="Go to slide <?php echo $index + 1; ?>"></button>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <button class="slider-next" aria-label="Next slide">›</button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'logos_slider':
                                ?>
                                <section class="logos-slider-section">
                                    <div class="container">
                                        <?php 
                                        // Try different possible field names
                                        $logos = null;
                                        if (isset($section['logos_slide'])) {
                                            $logos = $section['logos_slide'];
                                        } elseif (isset($section['logos_slider'])) {
                                            $logos = $section['logos_slider'];
                                        } elseif (isset($section['logos'])) {
                                            $logos = $section['logos'];
                                        } elseif (isset($section['logo_items'])) {
                                            $logos = $section['logo_items'];
                                        } elseif (isset($section['logos_repeater'])) {
                                            $logos = $section['logos_repeater'];
                                        }
                                        
                                        if ($logos && is_array($logos)) : ?>
                                            <div class="logos-slider-wrapper">
                                                <div class="logos-slider-track" id="logos-slider-track">
                                                    <?php 
                                                    // Duplicate logos for seamless infinite scroll
                                                    for ($i = 0; $i < 2; $i++) :
                                                        foreach ($logos as $logo) : ?>
                                                            <div class="logo-item">
                                                                <?php 
                                                                // Try different possible image field names
                                                                $logo_image = null;
                                                                if (isset($logo['logos_slide_logo'])) {
                                                                    $logo_image = $logo['logos_slide_logo'];
                                                                } elseif (isset($logo['logo_image'])) {
                                                                    $logo_image = $logo['logo_image'];
                                                                } elseif (isset($logo['image'])) {
                                                                    $logo_image = $logo['image'];
                                                                } elseif (isset($logo['logo'])) {
                                                                    $logo_image = $logo['logo'];
                                                                }
                                                                
                                                                if (!empty($logo_image)) : ?>
                                                                    <img src="<?php echo esc_url($logo_image['sizes']['medium'] ?: $logo_image['url']); ?>" 
                                                                         alt="<?php echo esc_attr($logo_image['alt'] ?: 'Partner Logo'); ?>"
                                                                         class="logo-image">
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endforeach;
                                                    endfor; ?>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="logos-debug" style="padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">
                                                <p><strong>Logos Slider Debug Info:</strong></p>
                                                <p>Available section fields: <?php echo implode(', ', array_keys($section)); ?></p>
                                                <p>Please check your ACF field structure and update the field name in the template.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            // Add more layout cases here as you create them
                            default:
                                ?>
                                <section class="unknown-section">
                                    <p>Unknown section layout: <?php echo esc_html($layout); ?></p>
                                </section>
                                <?php
                                break;
                        }
                    }
                    
                    echo '</div>';
                } else {
                    // Fallback if no ACF sections are set
                    ?>
                    <div class="page-content">
                        <div class="container">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Fallback if ACF is not active
                ?>
                <div class="page-content">
                    <div class="container">
                        <p><strong>ACF Plugin Required:</strong> This template requires Advanced Custom Fields to display dynamic content.</p>
                        <?php the_content(); ?>
                    </div>
                </div>
                <?php
            }
            ?>
            
        </article>
        
    <?php endwhile; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroSlider = document.getElementById('hero-slider');
    if (!heroSlider) return;
    
    const slides = heroSlider.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevButton = document.querySelector('.slider-prev');
    const nextButton = document.querySelector('.slider-next');
    
    if (slides.length <= 1) return; // No need for slider functionality with one slide
    
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => slide.classList.remove('active'));
        
        // Remove active class from all dots
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Show current slide
        slides[index].classList.add('active');
        if (dots[index]) dots[index].classList.add('active');
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    function startAutoSlide() {
        slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }
    
    function stopAutoSlide() {
        clearInterval(slideInterval);
    }
    
    // Event listeners
    if (nextButton) nextButton.addEventListener('click', () => { stopAutoSlide(); nextSlide(); startAutoSlide(); });
    if (prevButton) prevButton.addEventListener('click', () => { stopAutoSlide(); prevSlide(); startAutoSlide(); });
    
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });
    
    // Pause auto-slide on hover
    heroSlider.addEventListener('mouseenter', stopAutoSlide);
    heroSlider.addEventListener('mouseleave', startAutoSlide);
    
    // Start auto-slide
    startAutoSlide();
});
</script>

<?php include get_stylesheet_directory() . '/parts/footer.html'; ?>

<?php wp_footer(); ?>
</body>
</html> 