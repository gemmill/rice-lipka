# Design Document: Rice+Lipka WordPress Conversion

## Overview

This design document outlines the technical architecture for converting the Rice+Lipka Architects website to a WordPress-based content management system. The solution leverages Advanced Custom Fields Pro (ACF Pro) to create structured field groups that are conditionally displayed based on post categories, supporting four primary content types: News, Projects, Events, and Awards.

The architecture emphasizes modern WordPress development practices, utilizing the Gutenberg block editor with custom ACF blocks for real-time content preview and intuitive content management. The system uses standard WordPress posts organized by categories rather than custom post types, simplifying the WordPress structure while maintaining full content organization and field customization capabilities. Body copy for all content types utilizes WordPress's native post content field, reducing complexity and ensuring compatibility with WordPress's built-in editing features.

Key design principles include:
- **Category-based content organization** using WordPress taxonomies
- **Block editor integration** with custom ACF blocks for enhanced user experience
- **Native WordPress content fields** for body copy to ensure compatibility and simplicity
- **Real-time preview functionality** for immediate visual feedback
- **Responsive design** optimized for all device types
- **Performance optimization** with caching and image optimization
- **SEO-friendly structure** with proper metadata and structured data

## Architecture

### System Architecture

The WordPress theme follows a modular architecture pattern with clear separation of concerns:

```
WordPress Core
├── Custom Theme (ricelipka-theme)
│   ├── Template Hierarchy
│   ├── Block Templates
│   ├── Category-specific Layouts
│   └── Asset Management
├── ACF Pro Plugin
│   ├── Field Groups (Category-conditional)
│   ├── Custom Blocks
│   ├── Block Templates
│   └── Field Validation
└── Content Organization
    ├── Post Categories (News, Projects, Events, Awards)
    ├── Subcategories
    └── Taxonomies
```

### Theme Structure

```
ricelipka-theme/
├── style.css
├── functions.php
├── index.php
├── templates/
│   ├── category-news.php
│   ├── category-projects.php
│   ├── category-events.php
│   ├── category-awards.php
│   └── single-post.php
├── blocks/
│   ├── news-article/
│   ├── project-portfolio/
│   ├── event-details/
│   └── award-information/
├── inc/
│   ├── acf-blocks.php
│   ├── category-fields.php
│   ├── performance.php
│   └── seo.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── patterns/
    ├── news-layout.php
    ├── project-grid.php
    ├── event-listing.php
    └── award-showcase.php
```

### Block Editor Integration Architecture

The block editor integration follows WordPress best practices with ACF Pro's block functionality:

1. **Custom Block Registration**: Each content type has dedicated ACF blocks
2. **Real-time Preview**: Block previews match frontend appearance exactly
3. **Category-based Block Availability**: Blocks appear based on post category
4. **Field Validation**: Real-time validation within block editor interface
5. **Responsive Preview**: Built-in responsive preview modes

## Components and Interfaces

### ACF Field Groups

#### News Category Field Group
**Conditional Logic**: Display when post category = "News"

Fields:
- `news_title` (Text) - Article headline
- `publication_date` (Date Picker) - Publication date
- `excerpt` (Textarea) - Article summary
- `featured_image` (Image) - Primary article image
- `subcategory` (Select) - News subcategory selection

**Note**: Main article content uses WordPress's default post content field (`post_content`) instead of a custom ACF field.

#### Projects Category Field Group
**Conditional Logic**: Display when post category = "Projects"

Fields:
- `project_name` (Text) - Project title
- `completion_status` (Select) - Completed, In Progress, Planned
- `completion_percentage` (Number) - Progress indicator (0-100)
- `project_type` (Select) - Civic, Cultural, Educational, Public Works
- `client` (Text) - Client organization
- `location` (Text) - Project location
- `image_gallery` (Gallery) - Project photos
- `project_metadata` (Group) - Additional project details

**Note**: Project description uses WordPress's default post content field (`post_content`) instead of a custom ACF field.

#### Events Category Field Group
**Conditional Logic**: Display when post category = "Events"

Fields:
- `event_title` (Text) - Event name
- `event_date` (Date Picker) - Event date
- `event_time` (Time Picker) - Event time
- `location` (Text) - Event location
- `external_links` (Repeater) - Related links
- `registration_link` (URL) - Registration URL
- `recurring_event` (True/False) - Recurring event flag

**Note**: Event description uses WordPress's default post content field (`post_content`) instead of a custom ACF field.

#### Awards Category Field Group
**Conditional Logic**: Display when post category = "Awards"

Fields:
- `award_name` (Text) - Award title
- `awarding_organization` (Text) - Organization name
- `associated_project` (Post Object) - Link to project post
- `date_received` (Date Picker) - Award date
- `recognition_image` (Image) - Award image/certificate

**Note**: Award description uses WordPress's default post content field (`post_content`) instead of a custom ACF field.

### Custom ACF Blocks

#### News Article Block (`news-article`)
**Template**: `blocks/news-article/block.php`
**Style**: `blocks/news-article/style.css`
**Script**: `blocks/news-article/script.js`

Features:
- Real-time preview of article layout
- Automatic excerpt generation
- Featured image optimization
- Publication date formatting
- Social sharing integration

#### Project Portfolio Block (`project-portfolio`)
**Template**: `blocks/project-portfolio/block.php`
**Style**: `blocks/project-portfolio/style.css`
**Script**: `blocks/project-portfolio/script.js`

Features:
- Image gallery with lightbox
- Progress indicator visualization
- Project metadata display
- Responsive grid layout
- Category filtering

#### Event Details Block (`event-details`)
**Template**: `blocks/event-details/block.php`
**Style**: `blocks/event-details/style.css`
**Script**: `blocks/event-details/script.js`

Features:
- Calendar integration
- Location mapping
- Registration button
- Countdown timer
- Event sharing

#### Award Information Block (`award-information`)
**Template**: `blocks/award-information/block.php`
**Style**: `blocks/award-information/style.css`
**Script**: `blocks/award-information/script.js`

Features:
- Award certificate display
- Project association links
- Timeline visualization
- Achievement showcase
- Recognition gallery

### Category-Based Template System

#### Template Hierarchy
WordPress template hierarchy enhanced with category-specific templates:

1. `category-{slug}.php` - Category-specific templates
2. `single-post.php` - Enhanced with category detection
3. `archive.php` - Category-aware archive display
4. `index.php` - Fallback with category support

#### Category Detection Logic
```php
function get_post_primary_category($post_id) {
    $categories = get_the_category($post_id);
    $primary_cats = ['news', 'projects', 'events', 'awards'];
    
    foreach ($categories as $category) {
        if (in_array($category->slug, $primary_cats)) {
            return $category->slug;
        }
    }
    return 'news'; // Default fallback
}
```

## Data Models

### Post Structure
Standard WordPress posts enhanced with category-based field assignment:

```php
Post {
    ID: integer
    post_title: string
    post_content: string
    post_excerpt: string
    post_date: datetime
    post_status: string
    categories: array[Category]
    acf_fields: array[mixed] // Category-dependent fields
}
```

### Category Taxonomy
```php
Category {
    term_id: integer
    name: string
    slug: string
    parent: integer
    description: string
    count: integer
}
```

### ACF Field Data Models

#### News Fields
```php
NewsFields {
    news_title: string
    publication_date: date
    excerpt: text
    featured_image: image_object
    subcategory: string
}
```

**Note**: Main article content is stored in WordPress's default `post_content` field.

#### Project Fields
```php
ProjectFields {
    project_name: string
    completion_status: enum['completed', 'in_progress', 'planned']
    completion_percentage: integer[0-100]
    project_type: enum['civic', 'cultural', 'educational', 'public_works']
    client: string
    location: string
    image_gallery: array[image_object]
    project_metadata: object
}
```

**Note**: Project description is stored in WordPress's default `post_content` field.

#### Event Fields
```php
EventFields {
    event_title: string
    event_date: date
    event_time: time
    location: string
    external_links: array[link_object]
    registration_link: url
    recurring_event: boolean
}
```

**Note**: Event description is stored in WordPress's default `post_content` field.

#### Award Fields
```php
AwardFields {
    award_name: string
    awarding_organization: string
    associated_project: post_object
    date_received: date
    recognition_image: image_object
}
```

**Note**: Award description is stored in WordPress's default `post_content` field.

### Database Schema Considerations

The solution leverages WordPress's existing database structure:
- `wp_posts` - All content stored as standard posts
- `wp_terms` / `wp_term_taxonomy` - Category organization
- `wp_postmeta` - ACF field data storage
- `wp_term_relationships` - Post-category associations

ACF Pro automatically handles field data serialization and storage in `wp_postmeta` with proper indexing for performance.

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Category-based content organization

*For any* content type (News, Projects, Events, Awards), all posts should be stored as standard WordPress posts with the appropriate primary category assignment.

**Validates: Requirements 1.1, 2.1, 3.1, 4.1, 6.1**

### Property 2: Conditional field display based on category

*For any* post with a specific primary category, the ACF Pro system should display only the field groups that are conditionally assigned to that category type.

**Validates: Requirements 1.2, 2.2, 3.2, 4.2, 10.1**

### Property 3: Chronological ordering for time-sensitive content

*For any* collection of posts within News or Events categories, the display order should be chronological with most recent items first.

**Validates: Requirements 1.3, 3.3**

### Property 4: Content truncation with continuation links

*For any* post content that exceeds the defined preview length threshold, the display should include a truncated version with a "more" link to the full content.

**Validates: Requirements 1.4**

### Property 5: Hierarchical category support

*For any* primary category (News, Projects, Events, Awards), the system should support subcategories while ensuring each post belongs to exactly one primary category.

**Validates: Requirements 1.5, 2.4, 3.5, 4.5, 6.2, 6.5**

### Property 6: Category-specific template rendering

*For any* category type, the WordPress theme should render content using the appropriate category-specific template and layout.

**Validates: Requirements 6.3, 8.1, 8.2, 8.3, 8.4**

### Property 7: Block editor integration with real-time preview

*For any* ACF block component, editing field values should immediately update the block preview to match the frontend appearance exactly.

**Validates: Requirements 7.2, 7.4, 9.1**

### Property 8: Category-based block availability

*For any* post category change, the block editor should automatically update the available ACF block options to show only blocks compatible with the selected category.

**Validates: Requirements 7.3, 10.2**

### Property 9: Content validation and field requirements

*For any* ACF block with required fields, the block editor should display real-time validation indicators and prevent publishing when required fields are incomplete.

**Validates: Requirements 9.2**

### Property 10: Responsive preview functionality

*For any* ACF block component, the block editor should provide accurate preview rendering across all responsive breakpoints (desktop, tablet, mobile).

**Validates: Requirements 9.4, 11.1**

### Property 11: Image optimization and display

*For any* image uploaded to ACF fields, the system should automatically optimize the image for different screen sizes and display it correctly in both preview and frontend.

**Validates: Requirements 9.3, 11.2**

### Property 12: Event archiving based on date

*For any* event post, when the event date passes, the system should automatically change the post's display status to archived while preserving the content.

**Validates: Requirements 3.4**

### Property 13: Cross-referencing between content types

*For any* award post that references a project, the system should maintain bidirectional linking between the award and its associated project post.

**Validates: Requirements 4.4**

### Property 14: Content migration data preservation

*For any* existing content during migration, the system should preserve all original data including publication dates, media associations, and content relationships while properly categorizing content.

**Validates: Requirements 5.1, 5.2, 5.3, 5.4**

### Property 15: URL redirection after migration

*For any* URL from the old site structure, the system should provide proper HTTP redirects to the corresponding new category-based WordPress URL.

**Validates: Requirements 5.5**

### Property 16: SEO metadata generation

*For any* post in any category, the system should generate appropriate SEO meta tags, structured data markup, and sitemap entries based on the content type and category.

**Validates: Requirements 12.1, 12.2, 12.3, 12.5**

### Property 17: Admin workflow functionality

*For any* content management operation (creation, editing, bulk operations, preview), the system should provide category-aware admin interfaces and maintain proper content status workflows.

**Validates: Requirements 13.1, 13.2, 13.3, 13.4, 13.5**

### Property 18: Block editor user experience

*For any* ACF block component, the system should provide contextual help, drag-and-drop reordering, and clear visual distinction from standard WordPress blocks.

**Validates: Requirements 14.1, 14.4, 14.5**

### Property 19: WordPress compatibility and standards

*For any* standard WordPress feature or popular plugin, the theme should maintain compatibility and follow WordPress coding standards.

**Validates: Requirements 15.1, 15.2, 15.3, 15.4**

## Error Handling

### Field Validation Errors
- **Missing Required Fields**: Display clear error messages in block editor when required ACF fields are empty
- **Invalid Field Data**: Validate field data types (dates, numbers, URLs) and provide user-friendly error messages
- **Image Upload Errors**: Handle failed image uploads gracefully with retry options and clear error messaging

### Category Assignment Errors
- **Multiple Primary Categories**: Prevent assignment of multiple primary categories and guide users to correct selection
- **Missing Category**: Ensure all posts have at least one primary category assigned, defaulting to "News" if none selected
- **Invalid Subcategory**: Validate that subcategories belong to the correct parent category

### Block Editor Errors
- **Block Rendering Failures**: Provide fallback rendering when ACF blocks fail to load properly
- **Preview Generation Errors**: Handle preview generation failures gracefully while maintaining editing functionality
- **Field Group Loading Errors**: Display appropriate messages when ACF field groups fail to load

### Migration Errors
- **Data Import Failures**: Log and report any content that fails to migrate properly with detailed error information
- **Media Migration Issues**: Handle missing or corrupted media files during migration with appropriate fallbacks
- **URL Redirect Conflicts**: Detect and resolve URL redirect conflicts during migration setup

### Performance Error Handling
- **Image Optimization Failures**: Provide fallback image serving when optimization fails
- **Caching Issues**: Implement cache invalidation strategies and fallback content delivery
- **Database Query Errors**: Handle database connection issues and query failures gracefully

## Testing Strategy

### Dual Testing Approach

The testing strategy employs both unit testing and property-based testing to ensure comprehensive coverage:

**Unit Tests**: Focus on specific examples, edge cases, and integration points
- Category assignment logic with specific test cases
- ACF field group conditional display with known category combinations  
- Block editor integration with specific content scenarios
- Migration scripts with sample data sets
- Template rendering with specific post configurations

**Property-Based Tests**: Verify universal properties across all possible inputs
- Category-based field display across all category combinations
- Content organization and retrieval across all post types
- Block editor functionality across all ACF block types
- Responsive design across all device breakpoints
- SEO metadata generation across all content types

### Property-Based Testing Configuration

**Testing Library**: PHPUnit with Eris (PHP property-based testing library)
**Test Configuration**: Minimum 100 iterations per property test
**Test Tagging**: Each property test references its design document property

Example property test structure:
```php
/**
 * Feature: ricelipka-wordpress-conversion, Property 2: Conditional field display based on category
 */
public function testCategoryBasedFieldDisplay() {
    $this->forAll(
        Generator::elements(['news', 'projects', 'events', 'awards']),
        Generator::associative([
            'post_title' => Generator::string(),
            'post_content' => Generator::string()
        ])
    )->then(function($category, $postData) {
        $post = $this->createPostWithCategory($category, $postData);
        $fieldGroups = $this->getVisibleFieldGroups($post);
        $this->assertFieldGroupsMatchCategory($fieldGroups, $category);
    });
}
```

### Unit Testing Focus Areas

**Category Management**
- Test specific category assignment scenarios
- Validate subcategory hierarchy relationships
- Test category-based template selection

**ACF Integration**
- Test field group conditional logic with known categories
- Validate field data types and validation rules
- Test block registration and availability

**Block Editor Integration**
- Test specific block rendering scenarios
- Validate preview generation for known content
- Test drag-and-drop functionality with sample blocks

**Migration Testing**
- Test migration scripts with sample datasets
- Validate URL redirect mappings
- Test content preservation with known data structures

### Performance Testing

While not part of property-based testing, performance requirements will be validated through:
- Load testing with realistic content volumes
- Image optimization verification
- Caching effectiveness measurement
- Mobile performance testing across devices

### Integration Testing

**WordPress Core Integration**
- Test theme compatibility with WordPress updates
- Validate plugin compatibility with popular extensions
- Test multisite functionality if required

**Third-Party Service Integration**
- Test SEO plugin compatibility (Yoast, RankMath)
- Validate REST API functionality
- Test backup and security plugin compatibility

This comprehensive testing approach ensures both specific functionality works correctly (unit tests) and universal properties hold across all possible inputs (property-based tests), providing confidence in the system's correctness and reliability.