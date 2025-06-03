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
    $fonts_url = 'https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;500;600;700&family=Caladea:wght@700&display=swap';
    
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
}
add_action('after_setup_theme', 'salesnexus_theme_setup');

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