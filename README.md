# Rice+Lipka Architects WordPress Theme

A custom WordPress theme designed for Rice+Lipka Architects with Advanced Custom Fields Pro integration and category-based content organization.

## Features

- **Category-based Content Organization**: Uses WordPress categories (News, Projects, Events, Awards) instead of custom post types
- **ACF Pro Integration**: Custom field groups that display conditionally based on post categories
- **Block Editor Support**: Custom ACF blocks for enhanced content creation with real-time preview
- **Responsive Design**: Mobile-first approach with optimized layouts for all devices
- **Performance Optimized**: Image optimization, caching, and asset optimization built-in
- **SEO Ready**: Structured data, meta tags, and sitemap generation
- **Accessibility Compliant**: WCAG guidelines followed throughout

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- Advanced Custom Fields Pro plugin

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Install and activate Advanced Custom Fields Pro
3. Activate the Rice+Lipka theme
4. The theme will automatically create the required categories

## Content Types

### News
- Fields: title, publication date, excerpt, featured image, subcategory
- Subcategories: Project Updates, Event Announcements, Award Notifications, Firm News

### Projects
- Fields: project name, completion status, project type, client, location, image gallery, metadata
- Types: Civic Architecture, Cultural Projects, Educational Buildings, Public Works

### Events
- Fields: event title, date, time, location, external links, registration link
- Features: Countdown timer, automatic archiving, recurring event support

### Awards
- Fields: award name, organization, associated project, date received, recognition image
- Features: Project cross-referencing, achievement display

## Block Editor

The theme includes custom ACF blocks for each content type:

- **News Article Block**: Structured news content with real-time preview
- **Project Portfolio Block**: Project showcase with galleries and progress indicators
- **Event Details Block**: Event information with countdown and registration
- **Award Information Block**: Award display with project associations

## Customization

### Theme Options
- Custom logo support
- Navigation menus (Primary, Footer)
- Widget areas (Sidebar, Footer)
- Color scheme customization

### Performance Features
- Image lazy loading
- CSS/JS optimization
- HTML compression
- Caching headers
- WebP support detection

### SEO Features
- Structured data for all content types
- Open Graph meta tags
- XML sitemap generation
- Breadcrumb navigation
- Optimized permalinks

## Development

### File Structure
```
ricelipka-theme/
├── style.css
├── functions.php
├── index.php
├── single.php
├── category.php
├── header.php
├── footer.php
├── sidebar.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── blocks/
│   ├── news-article/
│   ├── project-portfolio/
│   ├── event-details/
│   └── award-information/
└── inc/
    ├── acf-blocks.php
    ├── category-fields.php
    ├── performance.php
    └── seo.php
```

### Hooks and Filters
The theme provides various hooks for customization:
- `ricelipka_after_header`
- `ricelipka_before_footer`
- `ricelipka_category_fields`
- `ricelipka_block_attributes`

## Support

For theme support and customization, please refer to the WordPress documentation and ACF Pro documentation.

## License

This theme is licensed under the GPL v2 or later.

## Changelog

### Version 1.0.0
- Initial release
- Category-based content organization
- ACF Pro integration
- Custom blocks for block editor
- Responsive design
- Performance optimization
- SEO features