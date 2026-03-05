# SCSS Workflow

This theme uses SCSS for better CSS organization and maintainability with a minimal, modular approach.

## Structure

- `style.scss` - Main stylesheet that imports only essential components
- `_reset.scss` - CSS reset and base styles
- `_layout.scss` - Basic layout utilities and structure
- `_menu.scss` - Menu and navigation styles
- `_project-component.scss` - Isolated project component styles

## Philosophy

The theme follows a **minimal CSS approach** where only essential styles are included:
- **Reset styles**: Modern CSS reset with sensible defaults
- **Layout utilities**: Basic layout classes for structure
- **Menu styles**: Navigation and menu component styles
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

### Layout Styles (`_layout.scss`)
Basic layout utilities and structure:
- `.layout` class with flexbox row layout and padding
- `.column` class with flexbox column layout

### Menu Styles (`_menu.scss`)
Navigation and menu component styles:
- `.menu` class for menu containers
- `.menu ul` with responsive flexbox layout:
  - Column layout on mobile (default)
  - Row layout on medium screens and up (768px+)

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