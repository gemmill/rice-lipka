<?php
/**
 * Validation script for Project Portfolio ACF Block
 * 
 * This script validates that the Project Portfolio block is properly implemented
 * according to Task 5.2 requirements.
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // For command line execution
    define('ABSPATH', dirname(__FILE__) . '/');
}

echo "<h2>Project Portfolio ACF Block Validation</h2>\n";

$validation_results = array();
$all_passed = true;

// Check if block template file exists
echo "<h3>1. Block Template File</h3>\n";
$block_template = 'blocks/project-portfolio/block.php';
if (file_exists($block_template)) {
    echo "✅ Block template exists: {$block_template}\n";
    $validation_results['template_exists'] = true;
    
    // Check template content
    $template_content = file_get_contents($block_template);
    
    // Check for required elements
    $required_elements = array(
        'project-portfolio-block' => 'Main block container class',
        'project-header' => 'Project header section',
        'project-name' => 'Project name display',
        'project-meta' => 'Project metadata section',
        'completion-progress' => 'Progress indicator',
        'progress-bar' => 'Progress bar element',
        'project-gallery' => 'Image gallery section',
        'gallery-grid' => 'Responsive grid layout',
        'gallery-item' => 'Individual gallery items',
        'project-metadata' => 'Project metadata display',
        'lightbox functionality' => 'data-full attribute for lightbox'
    );
    
    echo "<h4>Template Elements:</h4>\n";
    foreach ($required_elements as $element => $description) {
        if (strpos($template_content, $element) !== false) {
            echo "✅ {$description}: Found\n";
        } else {
            echo "❌ {$description}: Missing\n";
            $all_passed = false;
        }
    }
    
    // Check for ACF field integration
    echo "<h4>ACF Field Integration:</h4>\n";
    $acf_fields = array(
        'project_name' => 'Project Name field',
        'completion_status' => 'Completion Status field',
        'completion_percentage' => 'Completion Percentage field',
        'project_type' => 'Project Type field',
        'client' => 'Client field',
        'location' => 'Location field',
        'image_gallery' => 'Image Gallery field',
        'project_metadata' => 'Project Metadata group'
    );
    
    foreach ($acf_fields as $field => $description) {
        if (strpos($template_content, "get_field('{$field}')") !== false) {
            echo "✅ {$description}: Integrated\n";
        } else {
            echo "❌ {$description}: Missing integration\n";
            $all_passed = false;
        }
    }
    
} else {
    echo "❌ Block template missing: {$block_template}\n";
    $validation_results['template_exists'] = false;
    $all_passed = false;
}

// Check CSS file
echo "<h3>2. Block Styles</h3>\n";
$css_file = 'blocks/project-portfolio/style.css';
if (file_exists($css_file)) {
    echo "✅ CSS file exists: {$css_file}\n";
    $validation_results['css_exists'] = true;
    
    $css_content = file_get_contents($css_file);
    
    // Check for responsive design
    $responsive_elements = array(
        '@media (max-width: 768px)' => 'Mobile responsive styles',
        '@media (max-width: 480px)' => 'Small mobile styles',
        'grid-template-columns' => 'CSS Grid layout',
        'lightbox' => 'Lightbox styles',
        'progress-bar' => 'Progress bar styles',
        'gallery-filter' => 'Gallery filtering styles'
    );
    
    echo "<h4>CSS Features:</h4>\n";
    foreach ($responsive_elements as $element => $description) {
        if (strpos($css_content, $element) !== false) {
            echo "✅ {$description}: Found\n";
        } else {
            echo "❌ {$description}: Missing\n";
            $all_passed = false;
        }
    }
    
} else {
    echo "❌ CSS file missing: {$css_file}\n";
    $validation_results['css_exists'] = false;
    $all_passed = false;
}

// Check JavaScript file
echo "<h3>3. Block JavaScript</h3>\n";
$js_file = 'blocks/project-portfolio/script.js';
if (file_exists($js_file)) {
    echo "✅ JavaScript file exists: {$js_file}\n";
    $validation_results['js_exists'] = true;
    
    $js_content = file_get_contents($js_file);
    
    // Check for interactive features
    $js_features = array(
        'initGalleryLightbox' => 'Lightbox functionality',
        'initGalleryFiltering' => 'Gallery filtering',
        'initProgressAnimations' => 'Progress animations',
        'initAccessibility' => 'Accessibility features',
        'addEventListener' => 'Event handling',
        'aria-' => 'ARIA attributes for accessibility',
        'IntersectionObserver' => 'Performance optimization'
    );
    
    echo "<h4>JavaScript Features:</h4>\n";
    foreach ($js_features as $feature => $description) {
        if (strpos($js_content, $feature) !== false) {
            echo "✅ {$description}: Implemented\n";
        } else {
            echo "❌ {$description}: Missing\n";
            $all_passed = false;
        }
    }
    
} else {
    echo "❌ JavaScript file missing: {$js_file}\n";
    $validation_results['js_exists'] = false;
    $all_passed = false;
}

// Check ACF block registration
echo "<h3>4. Block Registration</h3>\n";
$acf_blocks_file = 'inc/acf-blocks.php';
if (file_exists($acf_blocks_file)) {
    echo "✅ ACF blocks file exists: {$acf_blocks_file}\n";
    
    $acf_content = file_get_contents($acf_blocks_file);
    
    // Check for project portfolio block registration
    if (strpos($acf_content, "'name' => 'project-portfolio'") !== false) {
        echo "✅ Project Portfolio block registered\n";
        
        // Check registration details
        $registration_elements = array(
            "'title' => __('Project Portfolio'" => 'Block title',
            "'render_template' => 'blocks/project-portfolio/block.php'" => 'Template path',
            "'category' => 'ricelipka-blocks'" => 'Block category',
            "'icon' => 'portfolio'" => 'Block icon',
            "'keywords' => array('project', 'portfolio'" => 'Block keywords',
            'enqueue_style' => 'CSS enqueuing',
            'enqueue_script' => 'JavaScript enqueuing'
        );
        
        echo "<h4>Registration Details:</h4>\n";
        foreach ($registration_elements as $element => $description) {
            if (strpos($acf_content, $element) !== false) {
                echo "✅ {$description}: Configured\n";
            } else {
                echo "❌ {$description}: Missing\n";
                $all_passed = false;
            }
        }
        
    } else {
        echo "❌ Project Portfolio block not registered\n";
        $all_passed = false;
    }
    
} else {
    echo "❌ ACF blocks file missing: {$acf_blocks_file}\n";
    $all_passed = false;
}

// Check for preview functionality
echo "<h3>5. Preview Functionality</h3>\n";
if (isset($template_content)) {
    $preview_features = array(
        'is_preview' => 'Preview mode detection',
        'block[\'data\'][\'is_preview\']' => 'Preview data handling',
        'Sample Architectural Project' => 'Preview sample data',
        'placeholder' => 'Placeholder images for preview'
    );
    
    foreach ($preview_features as $feature => $description) {
        if (strpos($template_content, $feature) !== false) {
            echo "✅ {$description}: Implemented\n";
        } else {
            echo "❌ {$description}: Missing\n";
            $all_passed = false;
        }
    }
}

// Summary
echo "<h3>Validation Summary</h3>\n";
if ($all_passed) {
    echo "🎉 <strong>All validations passed!</strong> The Project Portfolio ACF block is properly implemented.\n";
    echo "<ul>\n";
    echo "<li>✅ Block template with image gallery and progress indicators</li>\n";
    echo "<li>✅ Responsive grid layout implementation</li>\n";
    echo "<li>✅ Interactive features (lightbox and filtering)</li>\n";
    echo "<li>✅ Project metadata display</li>\n";
    echo "<li>✅ Real-time preview functionality</li>\n";
    echo "<li>✅ Accessibility features and ARIA support</li>\n";
    echo "<li>✅ Requirements 7.2, 7.3, 8.2 satisfied</li>\n";
    echo "</ul>\n";
} else {
    echo "⚠️ <strong>Some validations failed.</strong> Please review the issues above.\n";
}

echo "\n<p><em>Task 5.2: Create Project Portfolio ACF block - Validation Complete</em></p>\n";
?>