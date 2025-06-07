<?php
/**
 * Category Archive Template
 * 
 * Template for displaying category archives
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
    <article class="blog-page-content">
        
        <!-- Category Archive Header Section -->
        <section class="blog-header-section">
            <div class="container">
                <?php 
                $category = get_queried_object();
                $category_count = $category->count;
                ?>
                <h1 class="blog-heading">Category: <strong><?php echo esc_html($category->name); ?></strong></h1>
                
                <?php if ($category->description) : ?>
                    <p class="blog-subheading"><?php echo wp_kses_post($category->description); ?></p>
                <?php else : ?>
                    <p class="blog-subheading">Browse all posts in the <?php echo esc_html($category->name); ?> category. Found <?php echo $category_count; ?> post<?php echo ($category_count != 1) ? 's' : ''; ?> in this category.</p>
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

        <!-- Category Posts Section -->
        <section class="blog-posts-section">
            <div class="container">
                <?php if (have_posts()) : ?>
                    <div class="blog-posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
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
                    <?php if ($wp_query->max_num_pages > 1) : ?>
                        <div class="blog-pagination">
                            <?php
                            echo paginate_links(array(
                                'total' => $wp_query->max_num_pages,
                                'current' => max(1, get_query_var('paged')),
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
                        <p>There are no posts in this category yet. Check back soon for new content!</p>
                        <p><a href="<?php echo home_url('/blog/'); ?>" class="button">Browse All Posts</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
    </article>
</main>

<?php include get_stylesheet_directory() . '/parts/footer.html'; ?>

<?php wp_footer(); ?>
</body>
</html> 