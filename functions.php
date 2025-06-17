<?php
/**
 * SalesNexus Child Theme Functions
 * 
 * @package SalesNexus
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Google Fonts
 */
function salesnexus_enqueue_google_fonts() {
    // Google Fonts URL with Lexend Deca and Caladea
    $fonts_url = 'https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;500;600;700&family=Caladea:wght@400;700&display=swap';
    
    wp_enqueue_style(
        'salesnexus-google-fonts',
        $fonts_url,
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'salesnexus_enqueue_google_fonts');

/**
 * Check if SCSS files need recompilation
 */
function salesnexus_needs_scss_recompilation() {
    $scss_dir = get_stylesheet_directory() . '/styles/scss/';
    $compiled_css = get_stylesheet_directory() . '/styles/compiled/main.css';
    
    // If compiled CSS doesn't exist, we need to compile
    if (!file_exists($compiled_css)) {
        return true;
    }
    
    $compiled_time = filemtime($compiled_css);
    
    // Check all SCSS files for modifications
    $scss_files = glob($scss_dir . '*.scss');
    foreach ($scss_files as $scss_file) {
        if (filemtime($scss_file) > $compiled_time) {
            return true;
        }
    }
    
    return false;
}

/**
 * SCSS Compilation Function
 */
function salesnexus_compile_scss() {
    // Check if scssphp library exists
    $scss_lib_path = get_stylesheet_directory() . '/vendor/scssphp/scssphp/scss.inc.php';
    
    if (!file_exists($scss_lib_path)) {
        return false;
    }
    
    require_once $scss_lib_path;
    
    $scss = new ScssPhp\ScssPhp\Compiler();
    
    // Set import paths
    $scss->addImportPath(get_stylesheet_directory() . '/styles/scss/');
    
    // SCSS file path
    $scss_file = get_stylesheet_directory() . '/styles/scss/main.scss';
    $css_file = get_stylesheet_directory() . '/styles/compiled/main.css';
    
    // Create compiled directory if it doesn't exist
    $compiled_dir = get_stylesheet_directory() . '/styles/compiled/';
    if (!file_exists($compiled_dir)) {
        wp_mkdir_p($compiled_dir);
    }
    
    if (file_exists($scss_file)) {
        try {
            $scss_content = file_get_contents($scss_file);
            $compiled_css = $scss->compile($scss_content);
            
            // Write compiled CSS to file
            file_put_contents($css_file, $compiled_css);
            
            return true;
        } catch (Exception $e) {
            error_log('SCSS Compilation Error: ' . $e->getMessage());
            return false;
        }
    }
    
    return false;
}

/**
 * Get cache busting version for CSS files
 */
function salesnexus_get_css_version() {
    $scss_dir = get_stylesheet_directory() . '/styles/scss/';
    $latest_time = 0;
    
    // Get the latest modification time from all SCSS files
    $scss_files = glob($scss_dir . '*.scss');
    foreach ($scss_files as $scss_file) {
        $file_time = filemtime($scss_file);
        if ($file_time > $latest_time) {
            $latest_time = $file_time;
        }
    }
    
    return $latest_time ?: time();
}

/**
 * Enqueue parent and child theme styles
 */
function salesnexus_enqueue_styles() {
    // Always check if SCSS needs recompilation for instant updates
    if (salesnexus_needs_scss_recompilation()) {
        salesnexus_compile_scss();
    }
    
    // Enqueue parent theme style
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );
    
    // Enqueue child theme style
    wp_enqueue_style(
        'salesnexus-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue compiled SCSS if it exists
    $compiled_css_file = get_stylesheet_directory() . '/styles/compiled/main.css';
    if (file_exists($compiled_css_file)) {
        wp_enqueue_style(
            'salesnexus-compiled-scss',
            get_stylesheet_directory_uri() . '/styles/compiled/main.css',
            array('salesnexus-style', 'salesnexus-google-fonts'),
            salesnexus_get_css_version() // Use dynamic version for cache busting
        );
    }
    
    // Enqueue custom styles if they exist
    if (file_exists(get_stylesheet_directory() . '/styles/custom.css')) {
        wp_enqueue_style(
            'salesnexus-custom-styles',
            get_stylesheet_directory_uri() . '/styles/custom.css',
            array('salesnexus-style'),
            filemtime(get_stylesheet_directory() . '/styles/custom.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'salesnexus_enqueue_styles');

/**
 * Enqueue custom scripts
 */
function salesnexus_enqueue_scripts() {
    // Enqueue custom scripts if they exist
    if (file_exists(get_stylesheet_directory() . '/scripts/custom.js')) {
        wp_enqueue_script(
            'salesnexus-custom-scripts',
            get_stylesheet_directory_uri() . '/scripts/custom.js',
            array('jquery'),
            filemtime(get_stylesheet_directory() . '/scripts/custom.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'salesnexus_enqueue_scripts');

/**
 * Add theme support
 */
function salesnexus_theme_setup() {
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for custom logo
    add_theme_support('custom-logo');
    
    // Add support for navigation menus
    add_theme_support('menus');
    
    // Add support for widgets
    add_theme_support('widgets');
    
    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add custom image sizes for better quality
    add_image_size('high-quality-large', 1920, 1080, false); // High quality large images
    add_image_size('high-quality-medium', 1200, 800, false); // High quality medium images
    add_image_size('blog-thumbnail', 600, 400, true); // Blog post thumbnails
    add_image_size('hero-image', 1920, 1080, true); // Hero section images
    
    // Add support for title tag
    add_theme_support('title-tag');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'salesnexus'),
        'header' => __('Header Menu', 'salesnexus'),
        'footer-1' => __('Footer 1 (Why SalesNexus)', 'salesnexus'),
        'footer-2' => __('Footer 2 (Features)', 'salesnexus'),
        'footer-3' => __('Footer 3 (FAQ)', 'salesnexus'),
        'footer-4' => __('Footer 4 (Resources)', 'salesnexus'),
        'mobile' => __('Mobile Menu', 'salesnexus'),
    ));
}
add_action('after_setup_theme', 'salesnexus_theme_setup');

/**
 * Register widget areas
 */
function salesnexus_widgets_init() {
    // Primary sidebar
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'salesnexus'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here for the primary sidebar.', 'salesnexus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Footer widget area 1
    register_sidebar(array(
        'name'          => __('Footer 1', 'salesnexus'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here for the first footer column.', 'salesnexus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer widget area 2
    register_sidebar(array(
        'name'          => __('Footer 2', 'salesnexus'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here for the second footer column.', 'salesnexus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer widget area 3
    register_sidebar(array(
        'name'          => __('Footer 3', 'salesnexus'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here for the third footer column.', 'salesnexus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer widget area 4
    register_sidebar(array(
        'name'          => __('Footer 4', 'salesnexus'),
        'id'            => 'footer-4',
        'description'   => __('Add widgets here for the fourth footer column.', 'salesnexus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'salesnexus_widgets_init');

/**
 * Clear any WordPress caching for development
 */
function salesnexus_disable_caching_in_dev() {
    if (WP_DEBUG) {
        // Disable WordPress object cache for development
        if (!defined('WP_CACHE')) {
            define('WP_CACHE', false);
        }
        
        // Add no-cache headers for CSS files in development
        add_action('wp_head', function() {
            echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />';
            echo '<meta http-equiv="Pragma" content="no-cache" />';
            echo '<meta http-equiv="Expires" content="0" />';
        });
    }
}
add_action('init', 'salesnexus_disable_caching_in_dev');

/**
 * Admin notice for SCSS setup
 */
function salesnexus_scss_admin_notice() {
    $scss_lib_path = get_stylesheet_directory() . '/vendor/scssphp/scssphp/scss.inc.php';
    
    if (!file_exists($scss_lib_path)) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>SalesNexus Theme:</strong> SCSS functionality requires scssphp library. ';
        echo 'Run <code>composer require scssphp/scssphp</code> in the theme directory or install manually.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'salesnexus_scss_admin_notice');

/**
 * Force recompile SCSS via admin action
 */
function salesnexus_force_scss_recompile() {
    if (isset($_GET['force_scss_recompile']) && current_user_can('manage_options')) {
        if (salesnexus_compile_scss()) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>SalesNexus Theme:</strong> SCSS files recompiled successfully!</p>';
                echo '</div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error is-dismissible">';
                echo '<p><strong>SalesNexus Theme:</strong> SCSS compilation failed. Check error logs.</p>';
                echo '</div>';
            });
        }
    }
}
add_action('admin_init', 'salesnexus_force_scss_recompile');

/**
 * ACF JSON Support
 * This enables field group synchronization via JSON files
 */

/**
 * Set ACF JSON save path
 */
function salesnexus_acf_json_save_point($path) {
    return get_stylesheet_directory() . '/acf-json';
}
add_filter('acf/settings/save_json', 'salesnexus_acf_json_save_point');

/**
 * Set ACF JSON load path
 */
function salesnexus_acf_json_load_point($paths) {
    // Remove original path (optional)
    unset($paths[0]);
    
    // Add theme path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    
    return $paths;
}
add_filter('acf/settings/load_json', 'salesnexus_acf_json_load_point');

/**
 * Auto-create ACF JSON directory if it doesn't exist
 */
function salesnexus_create_acf_json_directory() {
    $acf_json_dir = get_stylesheet_directory() . '/acf-json';
    
    if (!file_exists($acf_json_dir)) {
        wp_mkdir_p($acf_json_dir);
        
        // Create .gitignore file for better version control
        $gitignore_content = "# ACF JSON files are auto-generated\n# Remove this file if you want to version control ACF fields\n";
        file_put_contents($acf_json_dir . '/.gitignore', $gitignore_content);
    }
}
add_action('admin_init', 'salesnexus_create_acf_json_directory');

/**
 * SVG Upload Support
 * Enable SVG uploads with security checks
 */

/**
 * Add SVG to allowed upload mime types
 */
function salesnexus_allow_svg_uploads($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'salesnexus_allow_svg_uploads');

/**
 * Fix WordPress handling of SVG files
 */
function salesnexus_fix_svg_uploads($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    
    if ($filetype['ext'] === 'svg' || $filetype['type'] === 'image/svg+xml') {
        $data['ext'] = 'svg';
        $data['type'] = 'image/svg+xml';
        $data['proper_filename'] = $filename;
    }
    
    return $data;
}
add_filter('wp_check_filetype_and_ext', 'salesnexus_fix_svg_uploads', 10, 4);

/**
 * Display SVG files properly in WordPress admin
 */
function salesnexus_fix_svg_display($response, $attachment, $meta) {
    if ($response['type'] === 'image' && $response['subtype'] === 'svg+xml') {
        $response['image'] = array(
            'src' => $response['url'],
            'width' => 300,
            'height' => 150,
        );
        
        $response['thumb'] = array(
            'src' => $response['url'],
            'width' => 150,
            'height' => 75,
        );
        
        $response['sizes'] = array(
            'full' => array(
                'url' => $response['url'],
                'width' => 300,
                'height' => 150,
                'orientation' => 'landscape',
            )
        );
    }
    
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'salesnexus_fix_svg_display', 10, 3);

/**
 * Sanitize SVG uploads for security
 */
function salesnexus_sanitize_svg($file) {
    // Only process SVG files
    if ($file['type'] !== 'image/svg+xml') {
        return $file;
    }
    
    // Read the SVG file content
    $svg_content = file_get_contents($file['tmp_name']);
    
    if ($svg_content === false) {
        $file['error'] = 'Could not read SVG file';
        return $file;
    }
    
    // Basic SVG sanitization - remove potentially dangerous elements
    $dangerous_tags = array(
        'script',
        'object',
        'embed',
        'link',
        'style',
        'iframe',
        'frame',
        'frameset',
        'form',
        'input',
        'button',
        'textarea',
        'select'
    );
    
    $dangerous_attributes = array(
        'onload',
        'onerror',
        'onclick',
        'onmouseover',
        'onmouseout',
        'onmousedown',
        'onmouseup',
        'onkeydown',
        'onkeyup',
        'onkeypress',
        'onfocus',
        'onblur',
        'onchange',
        'onsubmit',
        'onreset',
        'onselect',
        'onabort',
        'onunload'
    );
    
    // Remove dangerous tags
    foreach ($dangerous_tags as $tag) {
        $svg_content = preg_replace('/<' . $tag . '.*?<\/' . $tag . '>/is', '', $svg_content);
        $svg_content = preg_replace('/<' . $tag . '.*?\/>/is', '', $svg_content);
    }
    
    // Remove dangerous attributes
    foreach ($dangerous_attributes as $attr) {
        $svg_content = preg_replace('/' . $attr . '="[^"]*"/i', '', $svg_content);
        $svg_content = preg_replace('/' . $attr . "='[^']*'/i", '', $svg_content);
    }
    
    // Remove javascript: protocols
    $svg_content = preg_replace('/javascript:/i', '', $svg_content);
    
    // Remove data: protocols that could contain javascript
    $svg_content = preg_replace('/data:(?!image\/)/i', '', $svg_content);
    
    // Write the sanitized content back to the file
    file_put_contents($file['tmp_name'], $svg_content);
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'salesnexus_sanitize_svg');

/**
 * Add CSS to display SVG thumbnails properly in admin
 */
function salesnexus_svg_admin_styles() {
    echo '<style>
        .attachment-preview .thumbnail img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
        }
        
        .media-icon img[src$=".svg"] {
            width: 100%;
            height: auto;
        }
        
        table.media .column-title .media-icon img[src$=".svg"] {
            width: 60px;
            height: 60px;
        }
    </style>';
}
add_action('admin_head', 'salesnexus_svg_admin_styles');

/**
 * Create Sample Blog Posts
 */
function salesnexus_create_sample_posts() {
    // Check if we already have sample posts
    $existing_posts = get_posts(array(
        'post_type' => 'post',
        'meta_key' => '_sample_post',
        'meta_value' => 'yes',
        'numberposts' => 1
    ));
    
    if (!empty($existing_posts)) {
        return; // Sample posts already exist
    }
    
    // Sample posts data
    $posts_data = array(
        array(
            'title' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'categories' => array('CRM', 'Sales', 'Marketing'),
            'date' => '2024-01-15'
        ),
        array(
            'title' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco',
            'content' => 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'categories' => array('Sales', 'Marketing'),
            'date' => '2024-01-10'
        ),
        array(
            'title' => 'The Top Types of AI-Generated Content in Marketing',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',
            'categories' => array('Marketing'),
            'date' => '2024-01-08'
        ),
        array(
            'title' => 'Ex ea commodo consequat. Duis aute irure dolor in repre',
            'content' => 'Ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'categories' => array('CRM'),
            'date' => '2024-01-05'
        ),
        array(
            'title' => 'Hendrerit in voluptate velit esse cillum dolore eu fugiat',
            'content' => 'Hendrerit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.',
            'categories' => array('Sales'),
            'date' => '2024-01-03'
        ),
        array(
            'title' => 'Nulla pariatur. Excepteur sint occaecat cupidatat non proident',
            'content' => 'Nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.',
            'categories' => array('CRM', 'Marketing'),
            'date' => '2024-01-01'
        )
    );
    
    foreach ($posts_data as $post_data) {
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_date' => $post_data['date'],
            'post_author' => 1
        ));
        
        if (!is_wp_error($post_id)) {
            // Mark as sample post
            update_post_meta($post_id, '_sample_post', 'yes');
            
            // Assign categories
            foreach ($post_data['categories'] as $category_name) {
                $category = get_term_by('name', $category_name, 'category');
                if (!$category) {
                    // Create category if it doesn't exist
                    $category = wp_insert_term($category_name, 'category');
                    if (!is_wp_error($category)) {
                        wp_set_post_categories($post_id, array($category['term_id']), true);
                    }
                } else {
                    wp_set_post_categories($post_id, array($category->term_id), true);
                }
            }
        }
    }
}

// Hook to create sample posts on theme activation
add_action('after_switch_theme', 'salesnexus_create_sample_posts');

// Admin action to manually create sample posts
function salesnexus_create_posts_admin_action() {
    if (isset($_GET['create_sample_posts']) && current_user_can('manage_options')) {
        salesnexus_create_sample_posts();
        wp_redirect(admin_url('edit.php?post_type=post&posts_created=1'));
        exit;
    }
}
add_action('admin_init', 'salesnexus_create_posts_admin_action');

// Show admin notice with option to create posts
function salesnexus_posts_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id === 'edit-post' && wp_count_posts()->publish == 0) {
        $url = admin_url('edit.php?post_type=post&create_sample_posts=1');
        echo '<div class="notice notice-info">
            <p><strong>No blog posts found.</strong> <a href="' . esc_url($url) . '" class="button">Create 6 Sample Posts</a> to test your blog template.</p>
        </div>';
    }
    
    if (isset($_GET['posts_created'])) {
        echo '<div class="notice notice-success is-dismissible">
            <p><strong>Success!</strong> 6 sample blog posts have been created.</p>
        </div>';
    }
}
add_action('admin_notices', 'salesnexus_posts_admin_notice');

/**
 * ===================================
 * IMAGE QUALITY IMPROVEMENTS
 * ===================================
 */

/**
 * Increase JPEG image quality to 95% (default is 82%)
 */
function salesnexus_jpeg_quality($quality, $context) {
    return 95;
}
add_filter('jpeg_quality', 'salesnexus_jpeg_quality', 10, 2);
add_filter('wp_editor_set_quality', 'salesnexus_jpeg_quality', 10, 2);

/**
 * Disable WordPress big image size threshold (prevents auto-resizing large images)
 */
function salesnexus_disable_big_image_size_threshold() {
    return false; // Disable the 2560px threshold
}
add_filter('big_image_size_threshold', 'salesnexus_disable_big_image_size_threshold');

/**
 * Completely disable automatic WebP generation
 */
function salesnexus_disable_webp_generation() {
    return false;
}
add_filter('wp_image_editors', function($editors) {
    // Remove WebP support from image editors
    return array('WP_Image_Editor_GD');
});

/**
 * Disable WordPress image compression entirely for uploads
 */
function salesnexus_disable_image_compression($quality, $mime_type) {
    // Return 100% quality for all image types
    return 100;
}
add_filter('wp_editor_set_quality', 'salesnexus_disable_image_compression', 999, 2);

/**
 * Disable image scaling/resizing during upload
 */
function salesnexus_disable_image_scaling($value) {
    return false;
}
add_filter('wp_image_resize_identical_dimensions', 'salesnexus_disable_image_scaling');

/**
 * Prevent WordPress from creating any intermediate image sizes during upload
 */
function salesnexus_disable_intermediate_images($metadata, $attachment_id) {
    // Keep only the original uploaded image
    if (isset($metadata['sizes'])) {
        // Optionally keep only specific sizes we need
        $keep_sizes = array('high-quality-large', 'high-quality-medium', 'hero-image');
        $metadata['sizes'] = array_intersect_key($metadata['sizes'], array_flip($keep_sizes));
    }
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'salesnexus_disable_intermediate_images', 10, 2);

/**
 * Add admin notice to regenerate thumbnails after quality changes
 */
function salesnexus_image_quality_admin_notice() {
    if (current_user_can('manage_options')) {
        $regenerate_url = admin_url('admin.php?page=salesnexus-regenerate-images&action=regenerate');
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>SalesNexus:</strong> Image quality settings have been updated. ';
        echo '<a href="' . esc_url($regenerate_url) . '" class="button button-primary">Regenerate All Images</a> ';
        echo 'to apply high-quality settings to existing images.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'salesnexus_image_quality_admin_notice');

/**
 * Handle image regeneration
 */
function salesnexus_handle_image_regeneration() {
    if (isset($_GET['page']) && $_GET['page'] === 'salesnexus-regenerate-images' && 
        isset($_GET['action']) && $_GET['action'] === 'regenerate' && 
        current_user_can('manage_options')) {
        
        // Get all image attachments
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'numberposts' => -1,
        ));
        
        $regenerated = 0;
        foreach ($attachments as $attachment) {
            $file_path = get_attached_file($attachment->ID);
            if ($file_path && file_exists($file_path)) {
                // Force regeneration of thumbnails with our new quality settings
                wp_update_attachment_metadata($attachment->ID, wp_generate_attachment_metadata($attachment->ID, $file_path));
                $regenerated++;
            }
        }
        
        // Redirect with success message
        $redirect_url = admin_url('upload.php?regenerated=' . $regenerated);
        wp_redirect($redirect_url);
        exit;
    }
}
add_action('admin_init', 'salesnexus_handle_image_regeneration');

/**
 * Increase image upload size limits
 */
function salesnexus_increase_upload_size($size) {
    return '32M'; // 32MB upload limit
}
add_filter('upload_size_limit', 'salesnexus_increase_upload_size');

/**
 * Add high-quality image sizes to Media Library dropdown
 */
function salesnexus_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'high-quality-large' => __('High Quality Large (1920x1080)', 'salesnexus'),
        'high-quality-medium' => __('High Quality Medium (1200x800)', 'salesnexus'),
        'blog-thumbnail' => __('Blog Thumbnail (600x400)', 'salesnexus'),
        'hero-image' => __('Hero Image (1920x1080)', 'salesnexus'),
    ));
}
add_filter('image_size_names_choose', 'salesnexus_custom_image_sizes');

/**
 * Prevent WordPress from creating additional image sizes for uploads
 * (keeps only the sizes we define)
 */
function salesnexus_remove_default_image_sizes($sizes) {
    // Remove default WordPress image sizes to prevent unnecessary compression
    unset($sizes['medium_large']); // 768px
    unset($sizes['1536x1536']);    // 1536px
    unset($sizes['2048x2048']);    // 2048px
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'salesnexus_remove_default_image_sizes');

/**
 * Force higher quality for WebP images if supported
 */
function salesnexus_webp_quality($quality, $mime_type, $context) {
    if ($mime_type === 'image/webp') {
        return 95; // Higher quality for WebP
    }
    return $quality;
}
add_filter('wp_editor_set_quality', 'salesnexus_webp_quality', 10, 3);

/**
 * Disable image compression for PNG files
 */
function salesnexus_png_quality($quality, $mime_type) {
    if ($mime_type === 'image/png') {
        return 100; // No compression for PNG
    }
    return $quality;
}
add_filter('wp_editor_set_quality', 'salesnexus_png_quality', 10, 2);

/**
 * Add image quality settings to Media settings page
 */
function salesnexus_add_image_quality_settings() {
    add_settings_section(
        'salesnexus_image_quality',
        'Image Quality Settings',
        function() {
            echo '<p>These settings control the quality of images uploaded to your site.</p>';
        },
        'media'
    );
    
    add_settings_field(
        'salesnexus_jpeg_quality_setting',
        'JPEG Quality',
        function() {
            echo '<p>JPEG quality is set to <strong>95%</strong> for high-quality images.</p>';
            echo '<p><em>Note: Higher quality means larger file sizes but better image clarity.</em></p>';
        },
        'media',
        'salesnexus_image_quality'
    );
}

/**
 * ===================================
 * CONTACT FORM 7 DEBUGGING & FIXES
 * ===================================
 */

/**
 * Ensure Contact Form 7 scripts and styles are properly loaded
 */
function salesnexus_ensure_cf7_assets() {
    if (function_exists('wpcf7_enqueue_scripts') && function_exists('wpcf7_enqueue_styles')) {
        // Force enqueue CF7 assets on pages with WYSIWYG content
        if (is_page() || is_single()) {
            wpcf7_enqueue_scripts();
            wpcf7_enqueue_styles();
        }
    }
}
add_action('wp_enqueue_scripts', 'salesnexus_ensure_cf7_assets', 20);

/**
 * Add debugging info for Contact Form 7
 */
function salesnexus_cf7_debug() {
    if (current_user_can('administrator') && (is_page() || is_single())) {
        ?>
        <script>
        console.log('CF7 Debug Info:');
        console.log('CF7 Forms found:', document.querySelectorAll('.wpcf7-form').length);
        console.log('CF7 Input fields found:', document.querySelectorAll('.wpcf7-form input[type="text"], .wpcf7-form input[type="email"]').length);
        console.log('All form inputs found:', document.querySelectorAll('input[type="text"], input[type="email"]').length);
        
        // Check if fields are hidden
        document.querySelectorAll('.wpcf7-form input[type="text"], .wpcf7-form input[type="email"]').forEach(function(input, index) {
            const computedStyle = window.getComputedStyle(input);
            console.log('Input ' + index + ':', {
                display: computedStyle.display,
                visibility: computedStyle.visibility,
                opacity: computedStyle.opacity,
                width: computedStyle.width,
                height: computedStyle.height
            });
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'salesnexus_cf7_debug');

/**
 * Force Contact Form 7 field visibility
 */
function salesnexus_cf7_force_visibility() {
    if (is_page() || is_single()) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force show all CF7 input fields
            const cf7Inputs = document.querySelectorAll('.wpcf7-form input[type="text"], .wpcf7-form input[type="email"], .wpcf7-form input[type="tel"], .wpcf7-form textarea');
            
            cf7Inputs.forEach(function(input) {
                input.style.display = 'block';
                input.style.visibility = 'visible';
                input.style.opacity = '1';
                input.style.width = '100%';
                input.style.height = 'auto';
            });
            
            console.log('Forced visibility for', cf7Inputs.length, 'CF7 inputs');
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'salesnexus_cf7_force_visibility');

/**
 * Override Contact Form 7 default styles
 */
function salesnexus_cf7_custom_styles() {
    if (is_page() || is_single()) {
        ?>
        <style>
        /* Force visibility and proper styling for CF7 fields */
        .wpcf7-form input[type="text"],
        .wpcf7-form input[type="email"],
        .wpcf7-form input[type="tel"],
        .wpcf7-form input[type="url"],
        .wpcf7-form textarea {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 12px 16px !important;
            margin: 8px 0 !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            font-size: 16px !important;
            line-height: 1.4 !important;
            background-color: #fff !important;
            color: #333 !important;
            box-sizing: border-box !important;
            height: auto !important;
            min-height: 44px !important;
        }
        
        .wpcf7-form textarea {
            min-height: 120px !important;
        }
        
        .wpcf7-form-control-wrap {
            display: block !important;
            width: 100% !important;
            margin-bottom: 1rem !important;
        }
        
        /* Debug styles to identify hidden elements */
        .wpcf7-form input[style*="display: none"],
        .wpcf7-form input[style*="visibility: hidden"] {
            background-color: red !important;
            display: block !important;
            visibility: visible !important;
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'salesnexus_cf7_custom_styles');

/**
 * Debug what's actually being output in WYSIWYG content
 */
function salesnexus_wysiwyg_content_debug() {
    if (current_user_can('administrator') && (is_page() || is_single())) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wysiwygContent = document.querySelector('.wysiwyg-content');
            if (wysiwygContent) {
                console.log('=== WYSIWYG Content Debug ===');
                console.log('WYSIWYG HTML:', wysiwygContent.innerHTML);
                console.log('WYSIWYG Text:', wysiwygContent.textContent);
                
                // Check for CF7 elements
                const cf7Form = wysiwygContent.querySelector('.wpcf7');
                const cf7FormTag = wysiwygContent.querySelector('form');
                const shortcodeText = wysiwygContent.textContent.includes('[contact-form-7');
                
                console.log('CF7 form element found:', !!cf7Form);
                console.log('Any form element found:', !!cf7FormTag);
                console.log('Contains CF7 shortcode text:', shortcodeText);
                
                if (cf7Form) {
                    console.log('CF7 form HTML:', cf7Form.innerHTML);
                }
                if (cf7FormTag) {
                    console.log('Form HTML:', cf7FormTag.innerHTML);
                }
                
                // Check all input elements in the page
                const allInputs = document.querySelectorAll('input');
                console.log('Total inputs on page:', allInputs.length);
                
                allInputs.forEach((input, index) => {
                    console.log(`Input ${index}:`, {
                        type: input.type,
                        name: input.name,
                        class: input.className,
                        style: input.style.cssText,
                        computedDisplay: window.getComputedStyle(input).display
                    });
                });
            } else {
                console.log('No .wysiwyg-content found');
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'salesnexus_wysiwyg_content_debug');

/**
 * Test if shortcodes are working at all
 */
function salesnexus_test_shortcode() {
    return '<div style="background: yellow; padding: 10px; margin: 10px;">TEST SHORTCODE WORKING!</div>';
}
add_shortcode('test_shortcode', 'salesnexus_test_shortcode');

/**
 * Check if Contact Form 7 is active and working
 */
function salesnexus_cf7_status_check() {
    if (current_user_can('administrator')) {
        ?>
        <script>
        console.log('=== CF7 Status Check ===');
        console.log('CF7 plugin check:', {
            'wpcf7_contact_form_tag_func': typeof window.wpcf7 !== 'undefined',
            'cf7_scripts_loaded': !!document.querySelector('script[src*="contact-form-7"]'),
            'cf7_styles_loaded': !!document.querySelector('link[href*="contact-form-7"]')
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'salesnexus_cf7_status_check');

/**
 * Create a simple test contact form if none exists
 */
function salesnexus_ensure_test_contact_form() {
    // Only run this once and only for admins
    if (!current_user_can('administrator') || get_option('salesnexus_test_form_created')) {
        return;
    }
    
    // Check if CF7 is active
    if (!class_exists('WPCF7_ContactForm')) {
        return;
    }
    
    // Check if we already have any contact forms
    $existing_forms = get_posts(array(
        'post_type' => 'wpcf7_contact_form',
        'numberposts' => 1
    ));
    
    if (!empty($existing_forms)) {
        // We have forms, let's get the first one's ID
        $form_id = $existing_forms[0]->ID;
        update_option('salesnexus_test_form_id', $form_id);
        return;
    }
    
    // Create a simple test form
    $form_title = 'Test Contact Form';
    $form_content = '<label> Your name
    [text* your-name] </label>

<label> Your email
    [email* your-email] </label>

<label> Subject
    [text* your-subject] </label>

<label> Your message (optional)
    [textarea your-message] </label>

[submit "Send"]';
    
    $mail_content = 'From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

-- 
This e-mail was sent from a contact form on ' . get_bloginfo('name') . ' (' . get_bloginfo('url') . ')';

    // Create the form
    $form_id = wp_insert_post(array(
        'post_type' => 'wpcf7_contact_form',
        'post_status' => 'publish',
        'post_title' => $form_title,
        'post_content' => ''
    ));
    
    if ($form_id) {
        // Set form content
        update_post_meta($form_id, '_form', $form_content);
        
        // Set mail content
        update_post_meta($form_id, '_mail', array(
            'subject' => '[your-subject]',
            'sender' => '[your-name] <[your-email]>',
            'body' => $mail_content,
            'recipient' => get_option('admin_email'),
            'additional_headers' => '',
            'attachments' => '',
            'use_html' => 0,
            'exclude_blank' => 0
        ));
        
        update_option('salesnexus_test_form_created', true);
        update_option('salesnexus_test_form_id', $form_id);
    }
}
add_action('init', 'salesnexus_ensure_test_contact_form');

/**
 * Display helpful CF7 info for admins
 */
function salesnexus_cf7_admin_info() {
    if (current_user_can('administrator') && (is_page() || is_single())) {
        $test_form_id = get_option('salesnexus_test_form_id');
        
        if ($test_form_id) {
            echo '<!-- CF7 Test Form ID: ' . $test_form_id . ' -->';
            echo '<!-- Use shortcode: [contact-form-7 id="' . $test_form_id . '"] -->';
        }
        
        // List all available contact forms
        $forms = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'numberposts' => -1
        ));
        
        if (!empty($forms)) {
            echo '<!-- Available CF7 Forms: ';
            foreach ($forms as $form) {
                echo 'ID: ' . $form->ID . ' Title: "' . $form->post_title . '" | ';
            }
            echo '-->';
        } else {
            echo '<!-- No CF7 forms found -->';
        }
    }
}
add_action('wp_head', 'salesnexus_cf7_admin_info');

/**
 * ===================================
 * ACF WYSIWYG SHORTCODE PROCESSING
 * ===================================
 */

/**
 * Ensure ACF WYSIWYG fields process shortcodes
 */
function salesnexus_acf_wysiwyg_shortcodes($content, $post_id, $field) {
    // Only process WYSIWYG fields
    if ($field['type'] == 'wysiwyg') {
        // Apply WordPress content filters and process shortcodes
        $content = apply_filters('the_content', $content);
        $content = do_shortcode($content);
    }
    return $content;
}
add_filter('acf/format_value/type=wysiwyg', 'salesnexus_acf_wysiwyg_shortcodes', 10, 3);

/**
 * Alternative method: Add shortcode processing to ACF WYSIWYG
 */
function salesnexus_acf_wysiwyg_add_shortcode_support() {
    add_filter('acf/format_value', function($value, $post_id, $field) {
        if (is_string($value) && !empty($value) && isset($field['type']) && $field['type'] === 'wysiwyg') {
            // Remove existing content filters to avoid double processing
            remove_filter('the_content', 'wpautop');
            remove_filter('the_content', 'wptexturize');
            
            // Process shortcodes
            $value = do_shortcode($value);
            
            // Re-add content filters
            add_filter('the_content', 'wpautop');
            add_filter('the_content', 'wptexturize');
        }
        return $value;
    }, 20, 3);
}
add_action('init', 'salesnexus_acf_wysiwyg_add_shortcode_support');

/**
 * Force shortcode processing for specific ACF field
 */
function salesnexus_force_wysiwyg_shortcodes($content) {
    // Only process if it looks like it contains shortcodes
    if (strpos($content, '[') !== false && strpos($content, ']') !== false) {
        // Apply WordPress content processing
        $content = apply_filters('the_content', $content);
        // Ensure shortcodes are processed
        $content = do_shortcode($content);
    }
    return $content;
}

/**
 * Debug ACF WYSIWYG processing
 */
function salesnexus_debug_acf_wysiwyg() {
    if (current_user_can('administrator')) {
        ?>
        <script>
        console.log('=== ACF WYSIWYG Debug ===');
        // Check if the content has been processed
        const wysiwygContent = document.querySelector('.wysiwyg-content');
        if (wysiwygContent) {
            const hasShortcodeBrackets = wysiwygContent.innerHTML.includes('[') && wysiwygContent.innerHTML.includes(']');
            const hasFormElements = wysiwygContent.querySelector('form') !== null;
            
            console.log('WYSIWYG Debug:', {
                'has_shortcode_brackets': hasShortcodeBrackets,
                'has_form_elements': hasFormElements,
                'innerHTML_length': wysiwygContent.innerHTML.length,
                'raw_content': wysiwygContent.innerHTML.substring(0, 500) + '...'
            });
        }
        </script>
        <?php
    }
}
add_action('wp_footer', 'salesnexus_debug_acf_wysiwyg'); 