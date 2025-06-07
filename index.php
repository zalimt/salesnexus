<?php
/**
 * Index Template
 * 
 * Fallback template for WordPress
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <div class="content-area">
            
            <?php if (have_posts()) : ?>
                
                <div class="posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                            
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content">
                                <h2 class="post-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="post-meta">
                                    <time datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </div>
                                
                                <div class="post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
                            </div>
                            
                        </article>
                        
                    <?php endwhile; ?>
                </div>
                
                <?php
                // Pagination
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('← Previous', 'salesnexus'),
                    'next_text' => __('Next →', 'salesnexus'),
                ));
                ?>
                
            <?php else : ?>
                
                <div class="no-posts">
                    <h1>No Posts Found</h1>
                    <p>Sorry, no posts were found. Please check back later.</p>
                </div>
                
            <?php endif; ?>
            
        </div>
    </div>
</main>

<?php get_footer(); ?> 