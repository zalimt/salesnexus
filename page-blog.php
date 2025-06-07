<?php
/**
 * Template Name: Blog
 * 
 * A custom page template for the Blog page
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

<main id="main" class="site-main blog-page">
    <?php while (have_posts()) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-page-content'); ?>>
            
            <!-- Blog Header Section -->
            <section class="blog-header-section">
                <div class="container">
                    <?php 
                    // Get ACF fields for blog header
                    $blog_heading = get_field('blog_headeing');
                    $blog_subheading = get_field('blog_subheading');
                    ?>
                    
                    <?php if ($blog_heading) : ?>
                        <h1 class="blog-heading"><?php echo wp_kses_post(nl2br($blog_heading)); ?></h1>
                    <?php else : ?>
                        <h1 class="blog-heading">Welcome to <strong>BlogNexus</strong></h1>
                    <?php endif; ?>
                    
                    <?php if ($blog_subheading) : ?>
                        <p class="blog-subheading"><?php echo wp_kses_post(nl2br($blog_subheading)); ?></p>
                    <?php else : ?>
                        <p class="blog-subheading">Let's face it - most CRM's feel like they were built in a boardroom by people who've never closed a deal. But not here. We're flipping the script. This blog is your backstage pass to smarter selling, real-world tips, and a whole lot of personality.</p>
                    <?php endif; ?>
                    
                    <!-- Search Bar -->
                    <div class="blog-search">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="search-input-wrapper">
                                <input type="search" class="search-field" placeholder="Search the Blog" value="<?php echo get_search_query(); ?>" name="s" />
                                <button type="submit" class="search-submit">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Blog Posts Section -->
            <section class="blog-posts-section">
                <div class="container">
                    <?php
                    // Query blog posts
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $blog_posts = new WP_Query(array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'posts_per_page' => 9,
                        'paged' => $paged,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($blog_posts->have_posts()) : ?>
                        <div class="blog-posts-grid">
                            <?php while ($blog_posts->have_posts()) : $blog_posts->the_post(); ?>
                                <article class="blog-post-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array('class' => 'post-featured-image')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content">
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
                                        
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="post-excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                                        </div>
                                        
                                        <div class="post-meta">
                                            <time datetime="<?php echo get_the_date('c'); ?>" class="post-date">
                                                <?php echo get_the_date(); ?>
                                            </time>
                                        </div>
                                        
                                        <div class="read-more-wrapper">
                                            <a href="<?php the_permalink(); ?>" class="read-more-btn">Read Article</a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($blog_posts->max_num_pages > 1) : ?>
                            <div class="blog-pagination">
                                <?php
                                echo paginate_links(array(
                                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                                    'total' => $blog_posts->max_num_pages,
                                    'current' => max(1, $paged),
                                    'format' => '?paged=%#%',
                                    'show_all' => false,
                                    'type' => 'plain',
                                    'end_size' => 2,
                                    'mid_size' => 1,
                                    'prev_text' => '← Previous',
                                    'next_text' => 'Next →',
                                    'add_args' => false,
                                    'add_fragment' => '',
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else : ?>
                        <div class="no-posts-message">
                            <h3>No posts found</h3>
                            <p>It looks like there are no blog posts yet. Check back soon for new content!</p>
                            <?php if (current_user_can('manage_options')) : ?>
                                <p><a href="<?php echo admin_url('edit.php?post_type=post&create_sample_posts=1'); ?>" class="button">Create Sample Posts</a></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                </div>
            </section>
            
        </article>
        
    <?php endwhile; ?>
</main>

<?php include get_stylesheet_directory() . '/parts/footer.html'; ?>

<?php wp_footer(); ?>
</body>
</html> 