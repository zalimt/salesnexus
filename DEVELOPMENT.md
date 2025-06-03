# SalesNexus Theme - Development Guide

## 🚀 Instant SCSS Compilation

Your theme now has **automatic SCSS compilation** that applies styles instantly when you make changes!

## How It Works

### ✅ **Automatic Compilation**
- SCSS files are automatically checked for changes on every page load
- When any `.scss` file is modified, it triggers automatic recompilation
- Compiled CSS includes automatic cache-busting for instant updates
- No need to manually compile - just edit and refresh!

### 📁 **File Structure**
```
styles/scss/
├── main.scss          (imports all partials)
├── _variables.scss    (your Google Fonts & color variables)
├── _base.scss         (base styles)
├── _header.scss       (header styles - currently empty)
├── _footer.scss       (footer styles - currently empty)
├── _layout.scss       (layout & structure)
├── _buttons.scss      (button styles - currently empty)
├── _forms.scss        (form styles - currently empty)
└── _utilities.scss    (utility classes)

styles/compiled/
└── main.css          (auto-generated - don't edit!)
```

## 🎨 **Google Fonts Available**

### **Lexend Deca** (Sans-serif)
- Light (300): `$font-weight-light`
- Regular (400): `$font-weight-regular`
- Medium (500): `$font-weight-medium`
- SemiBold (600): `$font-weight-semibold`
- Bold (700): `$font-weight-bold`

### **Caladea** (Serif)
- Bold (700): `$font-weight-bold`

### **Usage Examples**
```scss
// In SCSS
.my-heading {
    font-family: $font-caladea;
    font-weight: $font-weight-bold;
}

.my-text {
    font-family: $font-lexend-deca;
    font-weight: $font-weight-medium;
}
```

```html
<!-- In HTML with utility classes -->
<h1 class="font-caladea font-bold">Elegant Heading</h1>
<p class="font-lexend font-regular">Readable body text</p>
```

## 🎨 **Color Palette**

### **Available Colors**
- Orange: `$orange` (#E85124) - Primary brand color
- Black: `$black` (#000000)
- Light Grey: `$light-grey` (#B4B4B4)
- Grey: `$grey` (#626262)
- Dark Grey: `$dark-grey` (#717171)
- Darkest Grey: `$darkest-grey` (#363636)

### **Color Usage Examples**
```scss
// In SCSS
.my-button {
    background-color: $orange;
    color: $black;
    border: 1px solid $light-grey;
}
```

```html
<!-- In HTML with utility classes -->
<h1 class="text-orange bg-darkest-grey">Orange text on dark background</h1>
<button class="bg-orange text-white">Orange button</button>
```

## 🛠️ **Development Workflow**

### **Option 1: Automatic (Recommended)**
1. Edit any `.scss` file in `styles/scss/`
2. Save the file
3. Refresh your browser - styles apply instantly!

### **Option 2: Manual Compilation**
If you need to force recompilation:

```bash
# In your terminal (theme directory)
php compile-scss.php
```

### **Option 3: WordPress Admin**
Force recompile via URL (admin users only):
```
https://salesnexus.local/wp-admin/?force_scss_recompile=1
```

## 🎯 **Quick Start**

1. **Edit Variables**: Add your custom variables to `_variables.scss`
2. **Style Header**: Add your header styles to `_header.scss` (currently empty)
3. **Style Footer**: Add your footer styles to `_footer.scss` (currently empty)
4. **Style Buttons**: Add button styles to `_buttons.scss` (currently empty)
5. **Style Forms**: Add form styles to `_forms.scss` (currently empty)
6. **Base Styles**: Modify `_base.scss` for global styling
7. **Utilities**: Add utility classes to `_utilities.scss`

## 💡 **Pro Tips**

### **Cache Busting**
- CSS files automatically get new version numbers when SCSS changes
- No browser cache issues during development

### **Development Mode**
- When `WP_DEBUG` is enabled, additional cache-prevention headers are added
- Styles update immediately without hard refresh

### **Error Handling**
- SCSS compilation errors are logged to WordPress error log
- Admin notices show compilation status

### **File Watching**
The system checks these files for modifications:
- All `.scss` files in `/styles/scss/`
- Compares modification time with compiled CSS
- Automatically recompiles when changes detected

## 🚨 **Important Notes**

- **Never edit** `styles/compiled/main.css` directly (it gets overwritten)
- **Always work** in the `.scss` files
- Keep the Google Fonts and color variables in `_variables.scss`
- The system works in both development and production environments
- **No mixins** - write standard CSS/SCSS syntax

## 📊 **File Status**

✅ **Ready to use:**
- `_variables.scss` - Google Fonts & color variables
- `_base.scss` - Basic font setup
- `_layout.scss` - Basic container styles
- `_utilities.scss` - Font & color utility classes

📝 **Empty & ready for your styles:**
- `_header.scss` - Add your header styles here
- `_footer.scss` - Add your footer styles here
- `_buttons.scss` - Add your button styles here
- `_forms.scss` - Add your form styles here

Happy coding! 🎉 