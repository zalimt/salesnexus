# SalesNexus Child Theme

A WordPress child theme based on Twenty Twenty-Five, customized for SalesNexus with SCSS support.

## Features

- Child theme of Twenty Twenty-Five
- **SCSS compilation** with automatic processing
- Custom styles folder for organized CSS
- Custom scripts folder for organized JavaScript
- Block editor support with custom colors and typography
- Mobile-responsive enhancements
- Proper enqueueing of parent and child theme assets

## File Structure

```
salesnexus/
├── style.css              # Main child theme stylesheet
├── functions.php          # Theme functions and enqueue scripts
├── theme.json             # Block editor configuration
├── composer.json          # PHP dependencies (SCSS compiler)
├── README.md              # This file
├── SCSS_README.md         # SCSS development guide
├── templates/
│   ├── index.html         # Main template
│   ├── page.html          # Page template
│   └── single.html        # Single post template
├── parts/
│   ├── header.html        # Header template part
│   └── footer.html        # Footer template part
├── styles/
│   ├── scss/              # SCSS source files
│   │   ├── main.scss      # Main SCSS file
│   │   ├── _variables.scss # SCSS variables
│   │   ├── _mixins.scss   # SCSS mixins
│   │   └── ...            # Other SCSS partials
│   ├── compiled/          # Auto-compiled CSS files
│   │   └── main.css       # Compiled from SCSS
│   └── custom.css         # Additional custom styles
└── scripts/
    └── custom.js          # Custom JavaScript functionality
```

## SCSS Development

This theme includes full SCSS support with automatic compilation. See [SCSS_README.md](SCSS_README.md) for complete documentation.

### Quick Start with SCSS

1. Enable WordPress debug mode in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   ```

2. Edit SCSS files in `/styles/scss/`
3. Refresh your site - CSS compiles automatically
4. Customize variables in `_variables.scss`

## Customization

### Adding Custom Styles
- **SCSS (Recommended)**: Edit files in `/styles/scss/`
- **CSS**: Add to `/styles/custom.css`
- Both are automatically enqueued by the theme

### Adding Custom Scripts
- Add your JavaScript to `/scripts/custom.js`
- The file is automatically enqueued with jQuery dependency

### Block Editor Customization
- Modify `theme.json` to customize:
  - Color palette
  - Typography settings
  - Spacing options
  - Layout settings

## Colors

The theme includes a custom color palette:
- Primary: #007cba
- Secondary: #005a8b
- Accent: #00a0d2
- Dark: #333333
- Light: #f9f9f9

## Development

### SCSS Workflow
1. Edit SCSS files in `/styles/scss/`
2. SCSS compiles automatically when `WP_DEBUG` is enabled
3. For production, disable `WP_DEBUG` to stop compilation
4. The last compiled CSS will continue to be used

### Regular CSS Workflow
1. Edit files in the appropriate folders
2. Clear any caching plugins
3. Test changes in the WordPress admin and frontend

## Installation

1. Upload the `salesnexus` folder to `/wp-content/themes/`
2. Run `composer install` in the theme directory (for SCSS support)
3. Activate the theme in WordPress Admin > Appearance > Themes
4. Customize as needed through the WordPress Customizer or by editing theme files

## Requirements

- WordPress 5.0+
- PHP 7.4+ (for SCSS compilation)
- Composer (for SCSS dependencies)

## Support

This child theme inherits all functionality from the Twenty Twenty-Five parent theme while allowing for safe customizations that won't be lost during theme updates.

For SCSS development help, see [SCSS_README.md](SCSS_README.md). 