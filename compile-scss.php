<?php
/**
 * SCSS Compilation Script for Development
 * 
 * Run this file directly to force SCSS compilation:
 * php compile-scss.php
 */

// Check if scssphp library exists
$scss_lib_path = __DIR__ . '/vendor/scssphp/scssphp/scss.inc.php';

if (!file_exists($scss_lib_path)) {
    die("❌ Error: scssphp library not found. Run 'composer require scssphp/scssphp' first.\n");
}

require_once $scss_lib_path;

use ScssPhp\ScssPhp\Compiler;

echo "🎨 Starting SCSS compilation...\n";

try {
    $scss = new Compiler();
    
    // Set import paths
    $scss->addImportPath(__DIR__ . '/styles/scss/');
    
    // SCSS file path
    $scss_file = __DIR__ . '/styles/scss/main.scss';
    $css_file = __DIR__ . '/styles/compiled/main.css';
    
    // Create compiled directory if it doesn't exist
    $compiled_dir = __DIR__ . '/styles/compiled/';
    if (!file_exists($compiled_dir)) {
        mkdir($compiled_dir, 0755, true);
        echo "📁 Created compiled directory: $compiled_dir\n";
    }
    
    if (!file_exists($scss_file)) {
        die("❌ Error: Main SCSS file not found: $scss_file\n");
    }
    
    echo "📄 Reading SCSS file: $scss_file\n";
    $scss_content = file_get_contents($scss_file);
    
    echo "⚙️  Compiling SCSS...\n";
    $compiled_css = $scss->compile($scss_content);
    
    echo "💾 Writing compiled CSS: $css_file\n";
    file_put_contents($css_file, $compiled_css);
    
    echo "✅ SCSS compilation completed successfully!\n";
    echo "📊 File size: " . number_format(strlen($compiled_css)) . " bytes\n";
    echo "🕒 Compiled at: " . date('Y-m-d H:i:s') . "\n";
    
} catch (Exception $e) {
    echo "❌ SCSS Compilation Error: " . $e->getMessage() . "\n";
    exit(1);
} 