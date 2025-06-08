<?php
/**
 * Single Post Template
 * 
 * Template for displaying single blog posts
 */

get_header(); ?>

<main id="main" class="site-main single-post">
    <?php while (have_posts()) : the_post(); ?>
        
        <!-- Featured Image Hero Section -->
        <?php if (has_post_thumbnail()) : ?>
            <section class="single-post-hero" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'hero-image') ?: get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>');">
                <div class="hero-overlay">
                    <div class="container">
                        <div class="hero-content">
                            <h1 class="single-post-title"><?php the_title(); ?></h1>
                            
                            <!-- Back Button and Categories under title -->
                            <div class="hero-meta">
                                <div class="meta-top-row">
                                    <button onclick="history.back()" class="back-button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        Back
                                    </button>
                                    
                                    <div class="post-categories">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) {
                                            foreach (array_slice($categories, 0, 3) as $category) {
                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-tag category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Post Content Section -->
        <section class="single-post-content">
            <div class="container">
                <div class="single-post-wrapper">
                    
                    <!-- Main Content -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-main'); ?>>
                        
                        <!-- Post Date -->
                        <div class="post-meta-header">
                            <div class="post-date">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </div>
                        </div>
                        
                        <!-- Post Title (for posts without featured image) -->
                        <?php if (!has_post_thumbnail()) : ?>
                            <h1 class="single-post-title-fallback"><?php the_title(); ?></h1>
                        <?php endif; ?>
                        
                        <!-- Post Content -->
                        <div class="post-content-text">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Post Tags -->
                        <?php if (has_tag()) : ?>
                            <div class="post-tags">
                                <strong>Tags: </strong>
                                <?php the_tags('', ', ', ''); ?>
                            </div>
                        <?php endif; ?>
                        
                    </article>
                    
                    <!-- Sidebar -->
                    <aside class="single-post-sidebar">
                        <div class="sidebar-content">
                            <h3 class="sidebar-title">You might also like...</h3>
                            
                            <?php
                            // Get related posts based on categories
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                $category_ids = array();
                                foreach ($categories as $category) {
                                    $category_ids[] = $category->term_id;
                                }
                                
                                $related_posts = get_posts(array(
                                    'category__in' => $category_ids,
                                    'post__not_in' => array(get_the_ID()),
                                    'posts_per_page' => 3,
                                    'orderby' => 'rand'
                                ));
                                
                                if ($related_posts) : ?>
                                    <div class="related-posts">
                                        <?php foreach ($related_posts as $related_post) : 
                                            setup_postdata($related_post); ?>
                                            <article class="related-post-card">
                                                <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                                    <div class="related-post-thumbnail">
                                                        <a href="<?php echo get_permalink($related_post->ID); ?>">
                                                            <?php echo get_the_post_thumbnail($related_post->ID, 'medium', array('class' => 'related-post-image')); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="related-post-content">
                                                    <div class="related-post-categories">
                                                        <?php
                                                        $post_categories = get_the_category($related_post->ID);
                                                        if (!empty($post_categories)) {
                                                            foreach (array_slice($post_categories, 0, 2) as $category) {
                                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-tag category-' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</a>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    
                                                    <h4 class="related-post-title">
                                                        <a href="<?php echo get_permalink($related_post->ID); ?>">
                                                            <?php echo get_the_title($related_post->ID); ?>
                                                        </a>
                                                    </h4>
                                                    
                                                    <p class="related-post-excerpt">
                                                        <?php echo wp_trim_words(get_the_excerpt($related_post->ID), 15, '...'); ?>
                                                    </p>
                                                    
                                                    <a href="<?php echo get_permalink($related_post->ID); ?>" class="read-more-btn">Read Article</a>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                        <?php wp_reset_postdata(); ?>
                                    </div>
                                <?php else : ?>
                                    <p>No related posts found.</p>
                                <?php endif;
                            }
                            ?>
                        </div>
                    </aside>
                    
                </div>
            </div>
        </section>
        
    <?php endwhile; ?>
</main>

<?php get_footer(); ?> 