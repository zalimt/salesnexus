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
                                $hero_slider_index = $hero_slider_index ?? 0;
                                $hero_slider_index++;
                                ?>
                                <section class="hero-slider-section">
                                    <div class="hero-slider-container">
                                        <?php 
                                        $slides = $section['hero_slider_slide'];
                                        if ($slides) : ?>
                                            <div class="hero-slider" id="hero-slider-<?php echo $hero_slider_index; ?>">
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
                                                                                   class="btn-orange btn-demo t-20 fw-500">
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
                                                                            <img src="<?php echo esc_url($slide['image_hero_slider_slide']['sizes']['hero-image'] ?: $slide['image_hero_slider_slide']['sizes']['high-quality-large'] ?: $slide['image_hero_slider_slide']['url']); ?>" 
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
                                
                            case 'tabs':
                                ?>
                                <section class="tabs-section">
                                    <div class="container">
                                        <?php 
                                        $tabs = $section['tabs_tab'];
                                        if ($tabs && is_array($tabs)) : ?>
                                            <div class="tabs-wrapper">
                                                <!-- Tab Navigation -->
                                                <div class="tabs-nav">
                                                    <?php foreach ($tabs as $index => $tab) : ?>
                                                        <button class="tab-btn t-20 fw-500 font-lexend <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                                data-tab="<?php echo $index; ?>">
                                                            <?php echo esc_html($tab['tabs_tab_btn_text'] ?: 'Tab ' . ($index + 1)); ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <!-- Tab Content -->
                                                <div class="tabs-content">
                                                    <?php foreach ($tabs as $index => $tab) : ?>
                                                        <div class="tab-pane <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                             data-tab-content="<?php echo $index; ?>">
                                                            
                                                            <?php if (!empty($tab['tabs_tab_cards']) && is_array($tab['tabs_tab_cards'])) : ?>
                                                                <div class="tab-cards-grid">
                                                                    <?php foreach ($tab['tabs_tab_cards'] as $card) : ?>
                                                                        <div class="tab-card">
                                                                            <div class="tab-card-left">
                                                                                <?php if (!empty($card['tabs_tab_card_image'])) : ?>
                                                                                    <div class="tab-card-icon">
                                                                                        <img src="<?php echo esc_url($card['tabs_tab_card_image']['sizes']['thumbnail'] ?: $card['tabs_tab_card_image']['url']); ?>" 
                                                                                             alt="<?php echo esc_attr($card['tabs_tab_card_image']['alt'] ?: $card['tabs_tab_card_title']); ?>">
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                            
                                                                            <div class="tab-card-right">
                                                                                <?php if (!empty($card['tabs_tab_card_title'])) : ?>
                                                                                    <h3 class="tab-card-title t-18 fw-500 font-lexend">
                                                                                        <?php echo esc_html($card['tabs_tab_card_title']); ?>
                                                                                    </h3>
                                                                                <?php endif; ?>
                                                                                
                                                                                <?php if (!empty($card['tabs_tab_card_description'])) : ?>
                                                                                    <p class="tab-card-description t-14 fw-300 font-lexend">
                                                                                        <?php echo wp_kses_post(nl2br($card['tabs_tab_card_description'])); ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($tab['tabs_tab_paragraph'])) : ?>
                                                                <div class="tab-paragraph t-40 fw-700 font-caladea">
                                                                    <p><?php echo wp_kses_post(nl2br($tab['tabs_tab_paragraph'])); ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'text_block_with_image':
                                ?>
                                <section class="text-block-with-image-section">
                                    <div class="container">
                                        <div class="tbwi-wrapper<?php echo !empty($section['direction_tbwi']) ? ' ' . esc_attr($section['direction_tbwi']) : ''; ?>">
                                            <div class="tbwi-content">
                                                <?php if (!empty($section['heading_tbwi'])) : ?>
                                                    <h2 class="tbwi-heading t-45 fw-700">
                                                        <?php echo esc_html($section['heading_tbwi']); ?>
                                                    </h2>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['paragraph_tbwi'])) : ?>
                                                    <div class="tbwi-paragraph t-18 fw-300">
                                                        <p><?php echo wp_kses_post(nl2br($section['paragraph_tbwi'])); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['btn_1_text_tbwi']) || !empty($section['btn_2_text_tbwi'])) : ?>
                                                    <div class="tbwi-buttons">
                                                        <?php if (!empty($section['btn_1_text_tbwi'])) : ?>
                                                            <a href="<?php echo esc_url($section['btn_1_link_tbwi'] ?: '#'); ?>" 
                                                               class="btn-orange tbwi-btn-primary t-20 fw-500">
                                                                <?php echo esc_html($section['btn_1_text_tbwi']); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($section['btn_2_text_tbwi'])) : ?>
                                                            <a href="<?php echo esc_url($section['btn_2_link_tbwi'] ?: '#'); ?>" 
                                                               class="btn btn-link tbwi-btn-link t-19 fw-500">
                                                                <?php echo esc_html($section['btn_2_text_tbwi']); ?>
                                                                <span class="arrow">→</span>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if (!empty($section['image_tbwi'])) : ?>
                                                <div class="tbwi-image">
                                                    <div class="tbwi-image-container">
                                                        <img src="<?php echo esc_url($section['image_tbwi']['sizes']['large'] ?: $section['image_tbwi']['url']); ?>" 
                                                             alt="<?php echo esc_attr($section['image_tbwi']['alt'] ?: 'CRM Automation'); ?>"
                                                             class="tbwi-image-main">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'text_block_with_cards_and_image':
                                ?>
                                <section class="text-block-with-cards-and-image-section">
                                    <div class="container">
                                        <?php 
                                        $slides = $section['slide_tbci'];
                                        if ($slides && is_array($slides)) : ?>
                                            <div class="tbci-slider-wrapper">
                                                <div class="tbci-slider" id="tbci-slider">
                                                    <?php foreach ($slides as $index => $slide) : ?>
                                                        <div class="tbci-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                                                            <div class="tbci-content-wrapper">
                                                                <div class="tbci-content">
                                                                    <?php if (!empty($slide['logo_tbci'])) : ?>
                                                                        <div class="tbci-logo">
                                                                            <img src="<?php echo esc_url($slide['logo_tbci']['sizes']['medium'] ?: $slide['logo_tbci']['url']); ?>" 
                                                                                 alt="<?php echo esc_attr($slide['logo_tbci']['alt'] ?: 'Company Logo'); ?>">
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['heading_tbci'])) : ?>
                                                                        <h2 class="tbci-heading font-lexend t-32 fw-700">
                                                                            <?php echo esc_html($slide['heading_tbci']); ?>
                                                                        </h2>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['paragraph_tbci'])) : ?>
                                                                        <div class="tbci-paragraph font-lexend t-17 fw-300">
                                                                            <p><?php echo wp_kses_post(nl2br($slide['paragraph_tbci'])); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <div class="tbci-cards">
                                                                        <?php for ($i = 1; $i <= 4; $i++) : 
                                                                            $number = $slide["card_{$i}_number_tbci"] ?? '';
                                                                            $description = $slide["card_{$i}_description_tbci"] ?? '';
                                                                            if (!empty($number) || !empty($description)) : ?>
                                                                                <div class="tbci-card">
                                                                                    <?php if (!empty($number)) : ?>
                                                                                        <div class="tbci-card-number t-31 fw-700">
                                                                                            <?php echo esc_html($number); ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                    <?php if (!empty($description)) : ?>
                                                                                        <div class="tbci-card-description t-14 fw-400">
                                                                                            <?php echo wp_kses_post(nl2br($description)); ?>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endif;
                                                                        endfor; ?>
                                                                    </div>
                                                                    
                                                                    <div class="tbci-buttons">
                                                                        <?php if (!empty($slide['btn_1_text_tbci'])) : ?>
                                                                            <a href="<?php echo esc_url($slide['btn_1_link_tbci'] ?: '#'); ?>" 
                                                                               class="btn-orange tbci-btn-primary t-19 fw-500">
                                                                                <?php echo esc_html($slide['btn_1_text_tbci']); ?>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($slide['btn_2_text_tbci'])) : ?>
                                                                            <button class="tbci-btn-next t-19 fw-600" data-next-slide>
                                                                                <?php echo esc_html($slide['btn_2_text_tbci']); ?>
                                                                                <span class="arrow">→</span>
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <?php if (!empty($slide['image_tbci'])) : ?>
                                                                    <div class="tbci-image">
                                                                        <img src="<?php echo esc_url($slide['image_tbci']['sizes']['large'] ?: $slide['image_tbci']['url']); ?>" 
                                                                             alt="<?php echo esc_attr($slide['image_tbci']['alt'] ?: 'Case Study Image'); ?>"
                                                                             class="tbci-image-main">
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'text_block_with_image_slider':
                                $tbwis_index = $tbwis_index ?? 0;
                                $tbwis_index++;
                                ?>
                                <section class="text-block-with-image-slider-section">
                                    <div class="container">
                                        <?php 
                                        $slides = $section['text_block_with_image_slider_slide'];
                                        if ($slides && is_array($slides)) : ?>
                                            <div class="tbwis-slider-wrapper">
                                                <div class="tbwis-slider" id="tbwis-slider-<?php echo $tbwis_index; ?>">
                                                    <?php foreach ($slides as $index => $slide) : ?>
                                                        <div class="tbwis-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                                                            <div class="tbwis-content-wrapper">
                                                                <div class="tbwis-content">
                                                                    <?php if (!empty($slide['heading_tbwi'])) : ?>
                                                                        <h2 class="tbwis-heading t-45 fw-700">
                                                                            <?php echo esc_html($slide['heading_tbwi']); ?>
                                                                        </h2>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if (!empty($slide['paragraph_tbwi'])) : ?>
                                                                        <div class="tbwis-paragraph t-18 fw-300">
                                                                            <p><?php echo wp_kses_post(nl2br($slide['paragraph_tbwi'])); ?></p>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <div class="tbwis-buttons">
                                                                        <?php if (!empty($slide['btn_1_text_tbwi'])) : ?>
                                                                            <a href="<?php echo esc_url($slide['btn_1_link_tbwi'] ?: '#'); ?>" 
                                                                               class="btn-orange tbwis-btn-primary t-20 fw-500">
                                                                                <?php echo esc_html($slide['btn_1_text_tbwi']); ?>
                                                                            </a>
                                                                        <?php endif; ?>
                                                                        
                                                                        <?php if (!empty($slide['btn_2_text_tbwi'])) : ?>
                                                                            <button class="tbwis-btn-next t-19 fw-500" data-next-slide-tbwis>
                                                                                <?php echo esc_html($slide['btn_2_text_tbwi']); ?>
                                                                                <span class="arrow">→</span>
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <?php if (!empty($slide['image_tbwi'])) : ?>
                                                                    <div class="tbwis-image">
                                                                        <img src="<?php echo esc_url($slide['image_tbwi']['sizes']['large'] ?: $slide['image_tbwi']['url']); ?>" 
                                                                             alt="<?php echo esc_attr($slide['image_tbwi']['alt'] ?: 'Featured Image'); ?>"
                                                                             class="tbwis-image-main">
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'testimonials':
                                $testimonials_index = $testimonials_index ?? 0;
                                $testimonials_index++;
                                ?>
                                <section class="testimonials-section">
                                    <div class="container">
                                        <?php 
                                        $testimonials = $section['testimonial'];
                                        if ($testimonials && is_array($testimonials)) : ?>
                                            <div class="testimonials-slider-wrapper">
                                                <div class="testimonials-slider" id="testimonials-slider-<?php echo $testimonials_index; ?>">
                                                    <?php foreach ($testimonials as $index => $testimonial) : ?>
                                                        <div class="testimonial-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                                                            <div class="testimonial-content">
                                                                <?php if (!empty($testimonial['testimonial_photo'])) : ?>
                                                                    <div class="testimonial-photo">
                                                                        <img src="<?php echo esc_url($testimonial['testimonial_photo']['sizes']['medium'] ?: $testimonial['testimonial_photo']['url']); ?>" 
                                                                             alt="<?php echo esc_attr($testimonial['testimonial_photo']['alt'] ?: $testimonial['testimonial_name']); ?>"
                                                                             class="testimonial-photo-img">
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if (!empty($testimonial['testimonial_paragraph'])) : ?>
                                                                    <div class="testimonial-text t-28 fw-300">
                                                                        <p><?php echo wp_kses_post(nl2br($testimonial['testimonial_paragraph'])); ?></p>
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if (!empty($testimonial['testimonial_name'])) : ?>
                                                                    <div class="testimonial-author">
                                                                        <h4 class="testimonial-name t-31 fw-700 font-caladea"><?php echo esc_html($testimonial['testimonial_name']); ?></h4>
                                                                        <?php if (!empty($testimonial['testimonial_position'])) : ?>
                                                                            <p class="testimonial-position font-caladea t-25 fw-400"><?php echo esc_html($testimonial['testimonial_position']); ?></p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <?php if (count($testimonials) > 1) : ?>
                                                    <!-- Testimonials Navigation -->
                                                    <div class="testimonials-nav">
                                                        <div class="testimonials-dots">
                                                            <?php foreach ($testimonials as $index => $testimonial) : ?>
                                                                <button class="testimonial-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                                                        data-slide="<?php echo $index; ?>"
                                                                        aria-label="Go to testimonial <?php echo $index + 1; ?>"></button>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'centered_text_block':
                                ?>
                                <section class="centered-text-block-section">
                                    <div class="container">
                                        <div class="ctb-wrapper">
                                            <!-- Floating Stats Cards -->
                                            <div class="ctb-floating-cards">
                                                <?php for ($i = 1; $i <= 6; $i++) : 
                                                    $title = $section["ctb_card_title_{$i}"] ?? '';
                                                    $description = $section["ctb_card_description_{$i}"] ?? '';
                                                    $year = $section["ctb_card_year_{$i}"] ?? '';
                                                    
                                                    if (!empty($title) || !empty($description)) : ?>
                                                        <div class="ctb-card ctb-card-<?php echo $i; ?>">
                                                            <?php if (!empty($title)) : ?>
                                                                <div class="ctb-card-title t-40 fw-700 font-caladea">
                                                                    <?php echo esc_html($title); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($description)) : ?>
                                                                <div class="ctb-card-description t-14 fw-500">
                                                                    <?php echo esc_html($description); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($year)) : ?>
                                                                <div class="ctb-card-year">
                                                                    <span class="year-badge t-12 fw-700">
                                                                        <?php echo esc_html($year); ?>
                                                                    </span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif;
                                                endfor; ?>
                                            </div>
                                            
                                            <!-- Central Content -->
                                            <div class="ctb-central-content">
                                                <?php if (!empty($section['heading_ctb'])) : ?>
                                                    <h2 class="ctb-heading t-56 fw-700 font-caladea">
                                                        <?php echo wp_kses_post(nl2br($section['heading_ctb'])); ?>
                                                    </h2>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['paragraph_ctb'])) : ?>
                                                    <div class="ctb-paragraph t-20 fw-300">
                                                        <p><?php echo wp_kses_post(nl2br($section['paragraph_ctb'])); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['btn_1_text_ctb']) || !empty($section['btn_2_text_ctb'])) : ?>
                                                    <div class="ctb-buttons">
                                                        <?php if (!empty($section['btn_1_text_ctb'])) : ?>
                                                            <a href="<?php echo esc_url($section['bnt_1_link_ctb'] ?: '#'); ?>" 
                                                               class="btn-orange ctb-btn-primary t-20 fw-500">
                                                                <?php echo esc_html($section['btn_1_text_ctb']); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($section['btn_2_text_ctb'])) : ?>
                                                            <a href="<?php echo esc_url($section['btn_2_link_ctb'] ?: '#'); ?>" 
                                                               class="btn btn-secondary ctb-btn-secondary t-20 fw-500">
                                                                <?php echo esc_html($section['btn_2_text_ctb']); ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['span_ctb'])) : ?>
                                                    <div class="ctb-span t-16 fw-300">
                                                        <p><?php echo wp_kses_post(nl2br($section['span_ctb'])); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'hero_centered':
                                ?>
                                <section class="hero-centered-section">
                                    <div class="container">
                                        <div class="hero-centered-wrapper">
                                            <?php if (!empty($section['heading_hc'])) : ?>
                                                <h1 class="hero-centered-heading t-56 fw-700 font-caladea">
                                                    <?php echo wp_kses_post(nl2br($section['heading_hc'])); ?>
                                                </h1>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($section['paragraph_hc'])) : ?>
                                                <div class="hero-centered-paragraph t-20 fw-300 font-lexend">
                                                    <p><?php echo wp_kses_post(nl2br($section['paragraph_hc'])); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($section['image_hc'])) : ?>
                                                <div class="hero-centered-image">
                                                    <img src="<?php echo esc_url($section['image_hc']['sizes']['large'] ?: $section['image_hc']['url']); ?>" 
                                                         alt="<?php echo esc_attr($section['image_hc']['alt'] ?: 'CRM Interface'); ?>"
                                                         class="hero-centered-image-main">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'pricing_table':
                                ?>
                                <section class="pricing-table-section">
                                    <div class="container">
                                        <div class="pricing-table-wrapper">
                                            <?php if (!empty($section['heading_pt'])) : ?>
                                                <h2 class="pricing-table-heading t-56 fw-700 font-caladea">
                                                    <?php echo wp_kses_post(nl2br($section['heading_pt'])); ?>
                                                </h2>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($section['description_pt'])) : ?>
                                                <div class="pricing-table-description t-20 fw-300 font-lexend">
                                                    <p><?php echo wp_kses_post(nl2br($section['description_pt'])); ?></p>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="pricing-cards-grid">
                                                <?php for ($i = 1; $i <= 3; $i++) : ?>
                                                    <div class="pricing-card <?php echo $i === 2 ? 'featured' : ''; ?>">
                                                        <?php if (!empty($section["card_tooltip_pt_{$i}"])) : ?>
                                                            <div class="pricing-card-title t-28 fw-600 font-lexend">
                                                                <?php echo esc_html($section["card_tooltip_pt_{$i}"]); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($section["card_price_pt_{$i}"])) : ?>
                                                            <div class="pricing-card-price">
                                                                <?php echo wp_kses_post(nl2br($section["card_price_pt_{$i}"])); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="pricing-card-action-row">
                                                            <?php if (!empty($section["card_btn_text_pt_{$i}"])) : ?>
                                                                <a href="<?php echo esc_url($section["card_btn_link_pt_{$i}"] ?: '#'); ?>" 
                                                                   class="pricing-card-btn btn-orange t-18 fw-700 font-lexend">
                                                                    <?php echo esc_html($section["card_btn_text_pt_{$i}"]); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($section["card_seat_pt_{$i}"])) : ?>
                                                                <div class="pricing-card-seat t-16 fw-400 font-lexend">
                                                                    <?php echo esc_html($section["card_seat_pt_{$i}"]); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <?php if (!empty($section["card_for_pt_{$i}"])) : ?>
                                                            <div class="pricing-card-for t-19 fw-600 font-lexend">
                                                                <?php echo esc_html($section["card_for_pt_{$i}"]); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($section["card_includes_pt_{$i}"])) : ?>
                                                            <div class="pricing-card-includes t-15 fw-500 font-lexend">
                                                                <?php echo esc_html($section["card_includes_pt_{$i}"]); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if (!empty($section["card_bullets_pt_{$i}"])) : ?>
                                                            <div class="pricing-card-features t-15 fw-500 font-lexend">
                                                                <?php echo wp_kses_post($section["card_bullets_pt_{$i}"]); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="pricing-card-bottom">
                                                            <?php if (!empty($section["card_btn_2_text_pt_{$i}"])) : ?>
                                                                <a href="<?php echo esc_url($section["card_btn_2_link_pt_{$i}"] ?: '#'); ?>" 
                                                                   class="pricing-card-btn-2 btn-orange t-18 fw-700 font-lexend">
                                                                    <?php echo esc_html($section["card_btn_2_text_pt_{$i}"]); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($section["card_span_pt_{$i}"])) : ?>
                                                                <div class="pricing-card-span t-16 fw-400 font-lexend">
                                                                    <?php echo wp_kses_post(nl2br($section["card_span_pt_{$i}"])); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'resources_header':
                                ?>
                                <section class="resources-header-section" style="<?php 
                                    if (!empty($section['resources_background_image'])) {
                                        echo 'background-image: url(' . esc_url($section['resources_background_image']['sizes']['high-quality-large'] ?: $section['resources_background_image']['sizes']['full'] ?: $section['resources_background_image']['url']) . ');';
                                    }
                                ?>">
                                    <div class="container">
                                        <div class="resources-header-content">
                                            <?php if (!empty($section['resources_heading'])) : ?>
                                                <h2 class="resources-heading font-caladea t-56 fw-700">
                                                    <?php echo wp_kses_post(nl2br($section['resources_heading'])); ?>
                                                </h2>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($section['resources_description'])) : ?>
                                                <div class="resources-description font-lexend t-20 fw-300">
                                                    <?php echo wp_kses_post(nl2br($section['resources_description'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'resources_cards':
                                $section_bg_class = !empty($section['resources_cards_section_bg']) ? $section['resources_cards_section_bg'] : '';
                                ?>
                                <section class="resources-cards-section <?php echo esc_attr($section_bg_class); ?>">
                                    <div class="container">
                                        <div class="resources-cards-header">
                                            <div class="left">
                                                <?php if (!empty($section['resources_cards_section_title'])) : ?>
                                                    <h2 class="resources-cards-title font-lexend t-24 fw-600">
                                                        <?php echo esc_html($section['resources_cards_section_title']); ?>
                                                    </h2>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['resources_cards_section_subtitle'])) : ?>
                                                    <div class="resources-cards-subtitle font-lexend t-20 fw-300">
                                                        <?php echo wp_kses_post(nl2br($section['resources_cards_section_subtitle'])); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="right">
                                                <?php if (!empty($section['resources_cards_section_btn_text'])) : ?>
                                                    <div class="resources-cards-header-btn">
                                                        <a href="<?php echo esc_url($section['resources_cards_section_btn_link'] ?: '#'); ?>" 
                                                           class="btn-white-outline font-lexend t-17 fw-500">
                                                            <?php echo esc_html($section['resources_cards_section_btn_text']); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php 
                                        $cards = $section['resources_cards_card'];
                                        if ($cards && is_array($cards)) : ?>
                                            <div class="resources-cards-grid">
                                                <?php foreach ($cards as $card) : ?>
                                                    <div class="resources-card">
                                                        <div class="resources-card-content">
                                                            <?php if (!empty($card['resources_cards_card_title'])) : ?>
                                                                <h3 class="resources-card-title font-lexend t-20 fw-500">
                                                                    <?php echo esc_html($card['resources_cards_card_title']); ?>
                                                                </h3>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($card['resources_cards_card_description'])) : ?>
                                                                <div class="resources-card-description font-lexend t-16 fw-300">
                                                                    <?php echo wp_kses_post(nl2br($card['resources_cards_card_description'])); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if (!empty($card['resources_cards_card_btn_text'])) : ?>
                                                                <div class="resources-card-button">
                                                                    <a href="<?php echo esc_url($card['resources_cards_card_btn_link'] ?: '#'); ?>" 
                                                                       class="btn-orange-outline font-lexend t-15 fw-500">
                                                                        <?php echo esc_html($card['resources_cards_card_btn_text']); ?>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'faq_section':
                                ?>
                                <section class="faq-section">
                                    <div class="container">
                                        <div class="faq-header">
                                            <div class="left">
                                                <?php if (!empty($section['faq_heading'])) : ?>
                                                    <h2 class="faq-title font-lexend t-24 fw-600">
                                                        <?php echo esc_html($section['faq_heading']); ?>
                                                    </h2>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($section['faq_subheading'])) : ?>
                                                    <div class="faq-subtitle font-lexend t-20 fw-300">
                                                        <?php echo wp_kses_post(nl2br($section['faq_subheading'])); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="right">
                                                <?php if (!empty($section['faq_btn_text'])) : ?>
                                                    <div class="faq-header-btn">
                                                        <a href="<?php echo esc_url($section['faq_btn_link'] ?: '#'); ?>" 
                                                           class="btn-orange-outline font-lexend t-17 fw-500">
                                                            <?php echo esc_html($section['faq_btn_text']); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <?php 
                                        $faqs = $section['faq-qa'];
                                        if ($faqs && is_array($faqs)) : ?>
                                            <div class="faq-accordion">
                                                <?php foreach ($faqs as $index => $faq) : ?>
                                                    <div class="faq-item <?php echo $index === 1 ? 'active' : ''; ?>">
                                                        <div class="faq-question">
                                                            <h3 class="faq-question-text font-lexend t-21 fw-600">
                                                                <?php echo wp_kses_post(nl2br($faq['faq-q'])); ?>
                                                            </h3>
                                                            <div class="faq-toggle">
                                                                <span class="faq-icon"></span>
                                                            </div>
                                                        </div>
                                                        <div class="faq-answer">
                                                            <div class="faq-answer-content font-lexend t-17 fw-400">
                                                                <?php echo wp_kses_post(nl2br($faq['faq-a'])); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
                            case 'youtube':
                                ?>
                                <section class="youtube-section">
                                    <div class="container">
                                        <?php 
                                        // Debug: Check what's in the section
                                        if (defined('WP_DEBUG') && WP_DEBUG) {
                                            echo '<!-- DEBUG: Section data: ' . print_r($section, true) . ' -->';
                                        }
                                        
                                        // Get the YouTube fields
                                        $youtube_title = $section['youtube_title'] ?? '';
                                        $youtube_subtitle = $section['youtube_subtitle'] ?? '';
                                        $youtube_data = $section['youtube_section'] ?? $section['wysiwyg_section'] ?? '';
                                        
                                        // Debug the data
                                        if (defined('WP_DEBUG') && WP_DEBUG) {
                                            echo '<!-- DEBUG: YouTube data type: ' . gettype($youtube_data) . ' -->';
                                            echo '<!-- DEBUG: YouTube data: ' . esc_html(is_string($youtube_data) ? $youtube_data : print_r($youtube_data, true)) . ' -->';
                                        }
                                        ?>
                                        
                                        <!-- YouTube Title and Subtitle -->
                                        <?php if (!empty($youtube_title) || !empty($youtube_subtitle)) : ?>
                                            <div class="youtube-header">
                                                <?php if (!empty($youtube_title)) : ?>
                                                    <h2 class="youtube-title font-caladea t-40 fw-700">
                                                        <?php echo wp_kses_post(nl2br($youtube_title)); ?>
                                                    </h2>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($youtube_subtitle)) : ?>
                                                    <div class="youtube-subtitle font-lexend t-18 fw-400">
                                                        <?php echo wp_kses_post($youtube_subtitle); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- YouTube Video -->
                                        <?php if (!empty($youtube_data)) : ?>
                                            <div class="youtube-content">
                                                <div class="video-wrapper">
                                                    <?php 
                                                    // Handle different data types that ACF oEmbed field might return
                                                    $oembed_html = '';
                                                    $youtube_url = '';
                                                    
                                                    if (is_string($youtube_data)) {
                                                        // Check if it's already HTML (iframe) or a URL
                                                        if (strpos($youtube_data, '<iframe') !== false) {
                                                            // It's already oEmbed HTML from ACF
                                                            $oembed_html = $youtube_data;
                                                        } else {
                                                            // It's a URL, process it
                                                            $youtube_url = trim($youtube_data);
                                                        }
                                                    } elseif (is_array($youtube_data) && isset($youtube_data['url'])) {
                                                        // Handle ACF oEmbed field array format
                                                        $youtube_url = trim($youtube_data['url']);
                                                    }
                                                    
                                                    // If we have HTML already, use it
                                                    if ($oembed_html) {
                                                        echo $oembed_html;
                                                    } elseif ($youtube_url) {
                                                        // Try to get oEmbed HTML from URL
                                                        $oembed_result = wp_oembed_get($youtube_url, array('width' => 800, 'height' => 450));
                                                        
                                                        if ($oembed_result) {
                                                            echo $oembed_result;
                                                        } else {
                                                            // Manual iframe fallback for YouTube
                                                            $video_id = '';
                                                            
                                                            // Extract YouTube video ID from various URL formats
                                                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                                                                $video_id = $matches[1];
                                                            }
                                                            
                                                            if ($video_id) {
                                                                echo '<iframe width="800" height="450" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                            } else {
                                                                echo '<div class="video-error">';
                                                                echo '<p>Unable to load video. Please check the URL.</p>';
                                                                echo '<p style="font-size: 12px; color: #666; word-break: break-all;">URL: ' . esc_html($youtube_url) . '</p>';
                                                                echo '<p style="font-size: 12px; color: #666;">Make sure you\'re using a valid YouTube URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)</p>';
                                                                echo '</div>';
                                                            }
                                                        }
                                                    } else {
                                                        echo '<p>No video URL provided.</p>';
                                                    }
                                                    
                                                    // Debug info
                                                    if (defined('WP_DEBUG') && WP_DEBUG) {
                                                        echo '<!-- DEBUG: Final URL: ' . esc_html($youtube_url) . ' -->';
                                                        echo '<!-- DEBUG: Has HTML: ' . ($oembed_html ? 'YES' : 'NO') . ' -->';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border: 1px solid #ccc;">
                                                <p><strong>Debug Info (YouTube Section):</strong></p>
                                                <p>No YouTube video found.</p>
                                                <p>YouTube Title: "<?php echo esc_html($youtube_title); ?>"</p>
                                                <p>YouTube Subtitle: "<?php echo esc_html(strip_tags($youtube_subtitle)); ?>"</p>
                                                <p>Available section keys: <?php echo implode(', ', array_keys($section)); ?></p>
                                                <p>Section layout: <?php echo esc_html($layout); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </section>
                                <?php
                                break;
                                
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
    // ===================================
    // HERO SLIDER FUNCTIONALITY
    // ===================================

    // Find all hero sliders and initialize each one
    const heroSliders = document.querySelectorAll('.hero-slider');
    
    heroSliders.forEach((heroSlider) => {
        if (!heroSlider) return;
        
        const slides = heroSlider.querySelectorAll('.hero-slide');
        const heroSliderContainer = heroSlider.closest('.hero-slider-container');
        const dots = heroSliderContainer ? heroSliderContainer.querySelectorAll('.slider-dot') : [];
        const prevButton = heroSliderContainer ? heroSliderContainer.querySelector('.slider-prev') : null;
        const nextButton = heroSliderContainer ? heroSliderContainer.querySelector('.slider-next') : null;
        
        if (slides.length <= 1) return; // No need for slider functionality with one slide
        
        let currentSlide = 0;
        let slideInterval;
        
        function showSlide(index) {
            // Hide all slides in this specific slider
            slides.forEach(slide => slide.classList.remove('active'));
            
            // Remove active class from all dots in this specific slider
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (dots[index]) {
                dots[index].classList.add('active');
            }
            
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
        
        // Event listeners for this specific slider
        if (nextButton) nextButton.addEventListener('click', () => { stopAutoSlide(); nextSlide(); startAutoSlide(); });
        if (prevButton) prevButton.addEventListener('click', () => { stopAutoSlide(); prevSlide(); startAutoSlide(); });
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();
                showSlide(index);
                startAutoSlide();
            });
        });
        
        // Pause auto-slide on hover for this specific slider
        heroSlider.addEventListener('mouseenter', stopAutoSlide);
        heroSlider.addEventListener('mouseleave', startAutoSlide);
        
        // Start auto-slide for this specific slider
        startAutoSlide();
    });

    // ===================================
    // TABS FUNCTIONALITY
    // ===================================
    
    // Find all tabs wrappers and initialize each one
    const tabsWrappers = document.querySelectorAll('.tabs-wrapper');
    
    tabsWrappers.forEach((tabsWrapper) => {
        if (!tabsWrapper) return;
        
        const tabButtons = tabsWrapper.querySelectorAll('.tab-btn');
        const tabPanes = tabsWrapper.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabIndex = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and panes in this specific wrapper
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane in this wrapper
                this.classList.add('active');
                const targetPane = tabsWrapper.querySelector(`[data-tab-content="${tabIndex}"]`);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });
    });
    
    // ===================================
    // TEXT BLOCK WITH CARDS AND IMAGE SLIDER
    // ===================================
    
    // Initialize TBCI slider
    const tbciSlider = document.getElementById('tbci-slider');
    if (tbciSlider) {
        const tbciSlides = tbciSlider.querySelectorAll('.tbci-slide');
        const nextButtons = document.querySelectorAll('[data-next-slide]');
        
        let currentTbciSlide = 0;
        
        function showTbciSlide(index) {
            // Hide all slides
            tbciSlides.forEach(slide => slide.classList.remove('active'));
            
            // Show current slide
            if (tbciSlides[index]) {
                tbciSlides[index].classList.add('active');
            }
            
            currentTbciSlide = index;
        }
        
        function nextTbciSlide() {
            const next = (currentTbciSlide + 1) % tbciSlides.length;
            showTbciSlide(next);
        }
        
        // Add click event to all next buttons
        nextButtons.forEach(button => {
            button.addEventListener('click', () => {
                nextTbciSlide();
            });
        });
    }
    
    // ===================================
    // TEXT BLOCK WITH IMAGE SLIDER (TBWIS)
    // ===================================

    // Find all TBWIS sliders and initialize each one
    const tbwisSliders = document.querySelectorAll('.tbwis-slider');
    
    tbwisSliders.forEach((tbwisSlider) => {
        if (!tbwisSlider) return;
        
        const tbwisSlides = tbwisSlider.querySelectorAll('.tbwis-slide');
        const tbwisWrapper = tbwisSlider.closest('.tbwis-slider-wrapper');
        const tbwisNextButtons = tbwisWrapper ? tbwisWrapper.querySelectorAll('[data-next-slide-tbwis]') : [];
        
        let currentTbwisSlide = 0;
        
        function showTbwisSlide(index) {
            // Hide all slides in this specific slider
            tbwisSlides.forEach(slide => slide.classList.remove('active'));
            
            // Show current slide
            if (tbwisSlides[index]) {
                tbwisSlides[index].classList.add('active');
            }
            
            currentTbwisSlide = index;
        }
        
        function nextTbwisSlide() {
            const next = (currentTbwisSlide + 1) % tbwisSlides.length;
            showTbwisSlide(next);
        }
        
        // Add click event to all next buttons in this specific slider
        tbwisNextButtons.forEach(button => {
            button.addEventListener('click', () => {
                nextTbwisSlide();
            });
        });
    });

    // ===================================
    // TESTIMONIALS FUNCTIONALITY
    // ===================================

    // Find all testimonials sliders and initialize each one
    const testimonialsSliders = document.querySelectorAll('.testimonials-slider');
    
    testimonialsSliders.forEach((testimonialsSlider) => {
        if (!testimonialsSlider) return;
        
        const testimonialsSlides = testimonialsSlider.querySelectorAll('.testimonial-slide');
        const testimonialsWrapper = testimonialsSlider.closest('.testimonials-slider-wrapper');
        const testimonialsNav = testimonialsWrapper ? testimonialsWrapper.querySelector('.testimonials-nav') : null;
        const testimonialsDots = testimonialsWrapper ? testimonialsWrapper.querySelectorAll('.testimonial-dot') : [];

        let currentTestimonial = 0;
        let testimonialInterval;

        function showTestimonial(index) {
            // Hide all slides in this specific slider
            testimonialsSlides.forEach(slide => slide.classList.remove('active'));
            
            // Remove active class from all dots in this specific slider
            testimonialsDots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide
            if (testimonialsSlides[index]) {
                testimonialsSlides[index].classList.add('active');
            }
            if (testimonialsDots[index]) {
                testimonialsDots[index].classList.add('active');
            }
            
            currentTestimonial = index;
        }

        function nextTestimonial() {
            const next = (currentTestimonial + 1) % testimonialsSlides.length;
            showTestimonial(next);
        }

        function prevTestimonial() {
            const prev = (currentTestimonial - 1 + testimonialsSlides.length) % testimonialsSlides.length;
            showTestimonial(prev);
        }

        function startTestimonialAutoSlide() {
            testimonialInterval = setInterval(nextTestimonial, 5000); // Change testimonial every 5 seconds
        }

        function stopTestimonialAutoSlide() {
            clearInterval(testimonialInterval);
        }

        // Event listeners for this specific slider
        if (testimonialsNav) {
            testimonialsNav.addEventListener('click', function(e) {
                e.preventDefault();
                const target = e.target.closest('.testimonial-dot');
                if (target) {
                    stopTestimonialAutoSlide();
                    const index = parseInt(target.getAttribute('data-slide'));
                    showTestimonial(index);
                    startTestimonialAutoSlide();
                }
            });
        }

        // Start auto-slide for this specific slider
        if (testimonialsSlides.length > 1) {
            startTestimonialAutoSlide();
        }
    });

    // ===================================
    // FAQ ACCORDION FUNCTIONALITY
    // ===================================
    
    // Find all FAQ items and add click functionality
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        if (question && answer) {
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                
                // Close all other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                if (isActive) {
                    item.classList.remove('active');
                } else {
                    item.classList.add('active');
                }
            });
        }
    });
});
</script>

<?php include get_stylesheet_directory() . '/parts/footer.html'; ?>

<?php wp_footer(); ?>
</body>
</html> 