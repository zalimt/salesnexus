# SalesNexus SCSS Development Guide

This guide explains how to use SCSS in your SalesNexus child theme.

## 🎯 SCSS Setup

SCSS functionality has been added to your theme with automatic compilation. The setup includes:

- **scssphp library** for server-side SCSS compilation
- **Organized file structure** with partials for better maintainability
- **Automatic compilation** when `WP_DEBUG` is enabled
- **Cache busting** using file modification timestamps

## 📁 SCSS File Structure

```
styles/scss/
├── main.scss           # Main SCSS file (imports all others)
├── _variables.scss     # SCSS variables (colors, fonts, spacing)
├── _mixins.scss        # Reusable mixins
├── _base.scss          # Base styles and resets
├── _header.scss        # Header component styles
├── _footer.scss        # Footer component styles
├── _layout.scss        # Layout and structure
├── _buttons.scss       # Button styles
├── _forms.scss         # Form styles
└── _utilities.scss     # Utility classes
```

## 🚀 How to Use

### 1. Enable SCSS Compilation

Ensure `WP_DEBUG` is enabled in your `wp-config.php`:

```php
define('WP_DEBUG', true);
```

This enables automatic SCSS compilation on every page load during development.

### 2. Edit SCSS Files

- **Main styles**: Edit `styles/scss/main.scss`
- **Variables**: Modify colors, fonts, spacing in `styles/scss/_variables.scss`
- **Component styles**: Edit individual component files like `_header.scss`, `_footer.scss`
- **Custom styles**: Add new partials and import them in `main.scss`

### 3. Compiled CSS

SCSS files are automatically compiled to:
- `styles/compiled/main.css` - The compiled CSS file
- This file is automatically enqueued by WordPress

## 🎨 Available Variables

### Colors
```scss
$primary-color: #007cba;
$secondary-color: #005a8b;
$accent-color: #00a0d2;
$dark-color: #333333;
$light-color: #f9f9f9;
```

### Typography
```scss
$font-family-base: 'Arial', sans-serif;
$font-family-heading: 'Georgia', serif;
$font-size-base: 16px;
$font-size-xl: 24px;
```

### Spacing
```scss
$spacing-xs: 5px;
$spacing-sm: 10px;
$spacing-md: 20px;
$spacing-lg: 40px;
$spacing-xl: 60px;
```

### Breakpoints
```scss
$mobile: 480px;
$tablet: 768px;
$desktop: 1024px;
$large-desktop: 1200px;
```

## 🔧 Useful Mixins

### Responsive Mixins
```scss
@include mobile {
    // Mobile styles (max-width: 480px)
}

@include tablet {
    // Tablet and up (min-width: 768px)
}

@include desktop {
    // Desktop and up (min-width: 1024px)
}
```

### Layout Mixins
```scss
@include flex-center;      // Flexbox center alignment
@include flex-between;     // Flexbox space-between
@include container;        // Max-width container with padding
```

### Button Mixin
```scss
.my-button {
    @include button($primary-color, $white, $secondary-color);
}
```

## 📝 Example Usage

### Adding a New Component

1. Create a new SCSS partial (e.g., `_navigation.scss`)
2. Import it in `main.scss`:
   ```scss
   @import 'navigation';
   ```
3. Use variables and mixins:
   ```scss
   .main-navigation {
       background-color: $primary-color;
       padding: $spacing-md;
       
       @include desktop {
           @include flex-between;
       }
       
       a {
           color: $white;
           transition: color $transition-medium;
           
           &:hover {
               color: $accent-color;
           }
       }
   }
   ```

### Customizing Colors

Edit `_variables.scss`:
```scss
// Change primary color
$primary-color: #your-new-color;

// Add custom colors
$brand-red: #ff0000;
$brand-blue: #0000ff;
```

Then use in your SCSS:
```scss
.custom-element {
    background-color: $brand-red;
    border: 1px solid $brand-blue;
}
```

## 🔄 Development Workflow

1. **Edit SCSS files** in `styles/scss/`
2. **Refresh your browser** - SCSS compiles automatically
3. **Check for errors** in browser console or WordPress debug log
4. **Commit your SCSS files** - the compiled CSS is auto-generated

## 🐛 Troubleshooting

### SCSS Not Compiling?

1. Check that `WP_DEBUG` is enabled
2. Verify scssphp library is installed (run `composer install`)
3. Check WordPress error logs for compilation errors
4. Ensure file permissions allow writing to `styles/compiled/`

### Compilation Errors?

- Check syntax in your SCSS files
- Ensure all imported partials exist
- Verify variable names are spelled correctly
- Check that mixins are defined before use

### Performance Optimization

For production:
1. Disable `WP_DEBUG` 
2. SCSS compilation will stop
3. The last compiled CSS file will continue to be used
4. Consider pre-compiling SCSS files for production

## 📚 SCSS Resources

- [SCSS Documentation](https://sass-lang.com/documentation)
- [SCSS Guidelines](https://sass-guidelin.es/)
- [scssphp Library](https://scssphp.github.io/scssphp/)

## 🎯 Next Steps

1. Customize the variables in `_variables.scss`
2. Add your custom styles to component files
3. Create new partials for additional components
4. Use the mixins to maintain consistent styling
5. Test responsive behavior using the breakpoint mixins 