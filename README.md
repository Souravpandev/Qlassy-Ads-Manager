# Qlassy Ads Manager Plugin for Question2Answer

**Developer:** [Sourav Pan](https://github.com/Souravpandev)  
**Version:** 1.4  
**Compatibility:** Question2Answer 1.8+  
**License:** GPLv3  
**Website:** [WP Optimize Lab](https://wpoptimizelab.com/)  
**Repository:** [GitHub](https://github.com/Souravpandev/Qlassy-Ads-Manager)

A comprehensive advertising plugin for Question2Answer that allows you to insert AdSense banner ads or custom HTML code in various locations throughout your Q2A website.

## 🚀 Features

### 📍 **Ad Insertion Locations**
- **Header & Footer Ads** - Top and bottom of every page
- **Sidebar Ads** - Top and bottom of sidebar
- **Homepage Ads** - Top and bottom of homepage
- **Question Page Ads** - Top and bottom of question pages
- **Answer Ads** - Top and bottom of answer sections
- **Category Ads** - Top and bottom of category pages
- **Tag Ads** - Top and bottom of tag pages
- **User Profile Ads** - User profile pages
- **Search Results Ads** - Search results pages

### 🎨 **Theme Compatibility**
- **SnowFlat Theme** - Full compatibility with custom layout and sidepanel
- **Snow Theme** - Proper integration with user profile handling
- **Classic Theme** - Standard Q2A layout compatibility
- **Candy Theme** - Custom navigation structure support
- **Automatic Theme Detection** - Seamless adaptation to any theme
- **Theme-Specific Styling** - CSS adjustments for optimal visual integration

### 🎯 **Dynamic Ad Insertion**
- **Between Questions** - Insert ads after every N questions (customizable frequency)
- **Between Answers** - Insert ads after every N answers (customizable frequency)
- **Custom Frequency Patterns** - Support for patterns like "2,3,5,7"

### 📌 **Sticky Ads**
- **Side Rail Ads** - Fixed position ads on left and right sides
- **Top Sticky Ad** - Fixed position ad at top of screen
- **Bottom Sticky Ad** - Fixed position ad at bottom of screen
- **Close Buttons** - Users can close sticky ads from frontend

### 🔄 **Advanced Features**
- **Ad Rotation** - Rotate multiple ads using `[QLASSY ROTATE]` separator
- **Device Targeting** - Different ads for desktop and mobile using `[DESKTOP]` and `[MOBILE]` tags
- **Guest-Only Targeting** - Show ads only to non-logged-in users
- **Role-Based Targeting** - Hide ads from specific user roles (admin, moderator, etc.)
- **Page Exclude/Include** - Control which pages show ads
- **AdSense Header Integration** - Global AdSense header script
- **Advertisement Labels** - Globally controllable "Advertisement" labels
- **Adblock Detection** - Detect ad blockers and show non-closable popup
- **Dark Mode Support** - Enable/disable dark theme styling for ad containers

### ⚡ **Performance Features**
- **Lazy Loading** - Below-the-fold ads load only when visible
- **Ad Space Reservation** - Prevents layout shifts during ad loading
- **Optimized Detection** - Efficient ad blocker detection with minimal performance impact
- **Production Ready** - Clean, optimized code with no debug artifacts

## 📋 Installation

1. **Download** the plugin files
2. **Upload** the `qlassy-ads-manager` folder to your `qa-plugin/` directory
3. **Go to Admin** → **Plugins** in your Q2A admin panel
4. **Activate** the "Qlassy Ads Manager" plugin
5. **Configure** the plugin settings at **Admin** → **Layout** → **Qlassy Ads Manager Settings**

## ⚙️ Configuration Guide

### 🔧 **Plugin Status**
- **Enable Qlassy Ads Manager Plugin**: Master switch to enable or disable the entire plugin. When disabled, no ads will be displayed.

### 🔧 **Basic Setup**

#### AdSense Header Script
- **Enable AdSense Header**: Check this to insert AdSense header script
- **AdSense Header Code**: Paste your AdSense header script here
  ```
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456" crossorigin="anonymous"></script>
  ```

#### Advertisement Labels
- **Show "Advertisement" Label**: Enable to show "Advertisement" labels on all ad containers

#### Dark Mode Support
- **Enable Dark Mode Support**: Enable this to automatically apply dark theme styling to ad containers when users have dark mode enabled in their browser/system. This provides better visual integration with dark themes.

### 📍 **Static Ad Locations**

#### Header & Footer Ads
- **Enable Header Ad**: Show ad at top of every page
- **Header Ad Code**: Your ad HTML/script code
- **Enable Footer Ad**: Show ad at bottom of every page  
- **Footer Ad Code**: Your ad HTML/script code

#### Sidebar Ads
- **Enable Top Sidebar Ad**: Show ad at top of sidebar
- **Top Sidebar Ad Code**: Your ad HTML/script code
- **Enable Bottom Sidebar Ad**: Show ad at bottom of sidebar
- **Bottom Sidebar Ad Code**: Your ad HTML/script code

#### Page-Specific Ads
- **Homepage Top**: Ads for homepage (all themes)
- **Homepage Bottom**: Ads for homepage (SnowFlat theme only)
- **Question Top/Bottom**: Ads for question pages
- **Answer Top/Bottom**: Ads for answer sections
- **Category Top/Bottom**: Ads for category pages
- **Tag Top/Bottom**: Ads for tag pages
- **User Profile**: Ads for user profile pages
- **Search Results**: Ads for search results pages

### 🎯 **Dynamic Ad Insertion**

#### Between Questions Ads
- **Enable Between Questions**: Insert ads between question posts
- **Show ad after every N questions**: Enter number (e.g., 2, 3, 4)
- **Custom Pattern**: Enter comma-separated values (e.g., "2,3,5,7")
- **Between Questions Ad Code**: Your ad HTML/script code

#### Between Answers Ads
- **Enable Between Answers**: Insert ads between answer posts
- **Show ad after every N answers**: Enter number (e.g., 2, 3, 4)
- **Custom Pattern**: Enter comma-separated values (e.g., "2,3,5,7")
- **Between Answers Ad Code**: Your ad HTML/script code

### 📌 **Sticky Ads**

#### Side Rail Ads
- **Enable Left Rail Ad**: Fixed position ad on left side
- **Left Rail Ad Code**: Your ad HTML/script code
- **Enable Right Rail Ad**: Fixed position ad on right side
- **Right Rail Ad Code**: Your ad HTML/script code

#### Top/Bottom Sticky Ads
- **Enable Top Sticky Ad**: Fixed position ad at top
- **Top Sticky Ad Code**: Your ad HTML/script code
- **Enable Bottom Sticky Ad**: Fixed position ad at bottom
- **Bottom Sticky Ad Code**: Your ad HTML/script code

### 🔄 **Advanced Features**

#### Ad Rotation
- **Enable Ad Rotation**: Rotate multiple ads in same location
- **Usage**: Separate multiple ad codes with `[QLASSY ROTATE]`
  ```
  <img src="ad1.jpg"> [QLASSY ROTATE] <img src="ad2.jpg"> [QLASSY ROTATE] <img src="ad3.jpg">
  ```

#### Device Targeting
- **Enable Device Targeting**: Show different ads for desktop/mobile
- **Usage**: Wrap content with device tags
  ```
  [DESKTOP]<img src="desktop-banner.jpg" style="width:728px;height:90px;">[/DESKTOP] 
  [MOBILE]<img src="mobile-banner.jpg" style="width:320px;height:50px;">[/MOBILE]
  ```

#### Targeting & Rules
- **Show ads only to guests**: Hide ads from logged-in users
- **Hide ads for specific user roles**: Select roles (admin, moderator, editor, expert, user)

#### Page Exclude/Include
- **Mode**: Choose "Exclude" or "Include"
- **Page List**: Enter page names (e.g., admin, user, login, register)
- **Exclude Mode**: Show ads everywhere EXCEPT listed pages
- **Include Mode**: Show ads ONLY on listed pages

#### Adblock Detection
- **Enable Adblock Detection**: Detect ad blockers and show popup
- **Features**:
  - Non-closable popup modal
  - Instructions to disable ad blocker
  - Automatic detection on page load
  - Professional design
  - **Reliable Detection**: Fixed detection logic for consistent results
  - **Optimized Performance**: Minimal impact on page load times

## 💡 Usage Examples

### Basic AdSense Ad
```
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1234567890123456"></script>
<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-1234567890123456" data-ad-slot="1234567890" data-ad-format="auto" data-full-width-responsive="true"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
```

### Ad Rotation Example
```
<img src="banner1.jpg" style="width:300px;height:250px;"> [QLASSY ROTATE] 
<img src="banner2.jpg" style="width:300px;height:250px;"> [QLASSY ROTATE] 
<img src="banner3.jpg" style="width:300px;height:250px;">
```

### Device Targeting Example
```
[DESKTOP]<img src="desktop-banner.jpg" style="width:728px;height:90px;">[/DESKTOP] 
[MOBILE]<img src="mobile-banner.jpg" style="width:320px;height:50px;">[/MOBILE]
```

### Custom HTML Ad
```
<div style="background:#f0f0f0; padding:20px; text-align:center; border:1px solid #ccc;">
  <h3>Sponsored Content</h3>
  <p>Your advertisement content here</p>
  <a href="https://yoursite.com" style="background:#007cba; color:white; padding:10px 20px; text-decoration:none;">Learn More</a>
</div>
```

## 🎨 Customization

### CSS Styling
The plugin includes responsive CSS for all ad containers. You can customize the appearance by modifying the CSS file:
- **File**: `qa-plugin/qlassy-ads-manager/qlassy-ads.min.css`
- **Features**: Responsive design, dark theme support, print styles

### Sticky Ad Positioning
Sticky ads use fixed positioning with high z-index. You can adjust:
- **Position**: Fixed top/bottom/left/right
- **Z-index**: 999999 (highest priority)
- **Close buttons**: Top-right corner with × symbol

## 🔧 Troubleshooting

### Ads Not Showing
1. **Check plugin activation** in Admin → Plugins
2. **Verify ad code** is valid HTML/JavaScript
3. **Check page exclude/include** settings
4. **Test with simple HTML** first (e.g., `<div>Test Ad</div>`)

### Adblock Detection Issues
1. **Ensure adblock detection is enabled**
2. **Test with ad blocker enabled/disabled**
3. **Check browser console** for errors
4. **Verify popup appears** when ad blocker is active
5. **Detection is now reliable** - v1.4 fixes ensure consistent detection

### Sticky Ads Not Positioning Correctly
1. **Check z-index** in CSS file
2. **Verify close buttons** are working
3. **Test on different screen sizes**
4. **Check for CSS conflicts** with theme

## 📊 Performance Tips

1. **Optimize ad images** for web (compress, proper dimensions)
2. **Use lazy loading** for ad images
3. **Limit ad rotation** to 3-5 ads per location
4. **Test on mobile devices** for responsive design
5. **Monitor page load times** with ads enabled
6. **Ad blocker detection is optimized** - minimal performance impact

## 🔒 Security Features

- **HTML sanitization** for ad content
- **XSS protection** through Q2A's built-in security
- **Role-based access** control for admin settings
- **Safe JavaScript execution** in ad codes

## 📊 Performance Analysis

### Database Load
- **Very Low Impact**: ~15-20 database queries per page
- **Efficient Caching**: Uses Q2A's built-in options caching
- **No Custom Tables**: Uses existing Q2A infrastructure

### Server Performance
- **Minimal CPU Usage**: Simple string processing and condition checks
- **Efficient Caching**: Leverages Q2A's caching system
- **Optimized Code**: Clean, production-ready implementation

### Client-Side Performance
- **Lazy Loading**: Below-the-fold ads load only when visible
- **Optimized Detection**: Efficient ad blocker detection
- **Minimal JavaScript**: ~15KB minified JavaScript
- **Responsive Design**: Works perfectly on all devices

### AdSense Compatibility
- **Perfect Integration**: Works seamlessly with real AdSense ads
- **Automatic Initialization**: Handles AdSense setup automatically
- **Lazy Loading Support**: AdSense ads work with lazy loading
- **Performance Optimized**: Minimal impact on Core Web Vitals

## 📝 Changelog

### Version 1.4 (Latest)
- ✅ **Fixed ad blocker detection** - Now works reliably
- ✅ **Optimized detection threshold** - Better accuracy
- ✅ **Resolved detection conflicts** - Unified detection systems
- ✅ **Removed debug code** - Production-ready performance
- ✅ **Enhanced performance** - Minimal impact on page load
- ✅ **Improved reliability** - Consistent detection across browsers
- ✅ **AdSense compatibility** - Perfect integration with real AdSense ads

### Version 1.3
- ✅ LCP (Largest Contentful Paint) optimization
- ✅ Lazy loading for below-the-fold ads
- ✅ Ad space reservation to prevent layout shifts
- ✅ Preconnect to AdSense domains
- ✅ Sticky ads delay for better performance

### Version 1.2
- ✅ Fixed duplicate header/footer ads issue
- ✅ Resolved homepage bottom ad layout conflicts
- ✅ Fixed ad rotation and device targeting features
- ✅ Enhanced SnowFlat theme compatibility
- ✅ Removed dark mode feature for cleaner code

### Version 1.1
- ✅ Plugin enable/disable functionality
- ✅ Configurable dark mode support
- ✅ Fixed plugin registration for proper Q2A integration
- ✅ Enhanced admin interface

### Version 1.0
- ✅ Initial release with all core features
- ✅ 16+ ad insertion locations
- ✅ Dynamic ad insertion (between questions/answers)
- ✅ Sticky ads with close buttons
- ✅ Ad rotation and device targeting
- ✅ Guest-only and role-based targeting
- ✅ Page exclude/include functionality
- ✅ AdSense header integration
- ✅ Advertisement labels
- ✅ Adblock detection and popup
- ✅ Responsive design

## 🤝 Support

For support and questions:
- **Developer**: [Sourav Pan](https://github.com/Souravpandev)
- **Website**: [WP Optimize Lab](https://wpoptimizelab.com/)
- **GitHub**: [@Souravpandev](https://github.com/Souravpandev)
- **Repository**: [Qlassy Ads Manager](https://github.com/Souravpandev/Qlassy-Ads-Manager)
- **Compatibility**: Question2Answer 1.8+
- **License**: GPLv3

## 📄 License

This plugin is licensed under the GNU General Public License v3 (GPLv3).

**Copyright (C) 2025 Sourav Pan**

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

---

**Qlassy Ads Manager Plugin** - Professional advertising solution for Question2Answer websites.
