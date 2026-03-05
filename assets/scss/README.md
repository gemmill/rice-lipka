# SCSS Workflow

This theme uses SCSS for better CSS organization and maintainability with a minimal, modular approach.

## Structure

- `style.scss` - Main stylesheet that imports only essential components
- `_reset.scss` - CSS reset and base styles
- `_typography.scss` - Site-wide typography system
- `_layout.scss` - Basic layout utilities and structure
- `_menu.scss` - Menu and navigation styles
- `_project-list.scss` - Project listing and grid styles
- `_news.scss` - News item component styles
- `_project-component.scss` - Isolated project component styles

## Philosophy

The theme follows a **minimal CSS approach** where only essential styles are included:
- **Reset styles**: Modern CSS reset with sensible defaults
- **Typography system**: Two font styles - heading (2rem bold) and body (1rem)
- **Layout utilities**: Basic layout classes for structure
- **Menu styles**: Navigation and menu component styles
- **Project list styles**: Grid layouts for project listings
- **News styles**: News item component styles
- **Project component**: Completely isolated component styles
- **No theme styles**: All complex layout, typography, and design styles have been removed to focus purely on functionality

## Building CSS

To compile SCSS to CSS, run:

```bash
sass assets/scss/style.scss style.css --style=compressed
```

Or use the build script:

```bash
./build-css.sh
```

## Architecture

### Reset Styles (`_reset.scss`)
Contains modern CSS reset with sensible defaults:
- Box-sizing reset
- Margin reset
- Base typography and font smoothing
- Media element defaults
- Form element inheritance

### Typography System (`_typography.scss`)
Simple two-font system:
- **$heading**: 2rem bold sans-serif (for h1-h6)
- **$body**: 1rem sans-serif (for body text, paragraphs, links, etc.)
- Consistent typography across all elements

### Layout Styles (`_layout.scss`)
Basic layout utilities and structure:
- `.layout` class with flexbox row layout and padding
- `.column` class with flexbox column layout

### Menu Styles (`_menu.scss`)
Navigation and menu component styles:
- `.menu` class for menu containers
- `.menu ul` with responsive flexbox layout:
  - Column layout on mobile (default)
  - Row layout on medium screens and up (769px+)
  - Consistent list styling (no bullets, margins, or padding)
- `.menu a` with consistent link styling:
  - Block display with padding
  - Consistent color, font-size, and typography
  - Hover effects
- Submenu visibility controls:
  - Hidden by default
  - Shown only when parent menu item is active or ancestor (no hover)

### Project List Styles (`_project-list.scss`)
Grid layouts for project listings:
- `.project-list` class for project list containers
- `.project-grid` class with 3-column flex grid:
  - `flex: 3` for container sizing
  - `gap: 1rem` between items
  - `flex-wrap: wrap` for responsive wrapping
  - Project items sized to fit 3 per row

### News Styles (`_news.scss`)
News item component styles:
- `.news-grid` class with 3-column flex grid (same structure as projects):
  - `flex: 3` for container sizing
  - `gap: 1rem` between items
  - `flex-wrap: wrap` for responsive wrapping
  - News items sized to fit 3 per row
- `.news-item` class for news item containers
- `.news-image-wrapper` with 3:2 aspect ratio for images
- `.news-placeholder` for items without images
- `.news-content` with date, title, and excerpt styling

### Project Component (`_project-component.scss`)
The project component styles are completely isolated to ensure consistent display across all pages:
- SCSS variables for maintainability
- Nested selectors for organization
- `!important` declarations to override any conflicting styles
- Extend/placeholder selectors for DRY code
- Comprehensive hover effects (image fade out, title fade in)
- 3:2 aspect ratio with top-left text alignment

## Development

When making changes to SCSS files, always recompile to update the main `style.css` file that WordPress uses.

The modular structure allows for:
- **Minimal footprint**: Only essential styles are included
- **Component isolation**: Project component cannot be affected by other styles
- **Easy maintenance**: Clear separation of concerns
- **Consistent behavior**: Project component displays identically across all pages