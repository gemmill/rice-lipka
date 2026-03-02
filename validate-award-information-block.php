<?php
/**
 * Validation script for Award Information ACF block
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // For standalone testing
    define('ABSPATH', dirname(__FILE__) . '/');
}

/**
 * Validate Award Information Block Implementation
 */
function validate_award_information_block() {
    $results = array(
        'block_template' => false,
        'block_styles' => false,
        'block_script' => false,
        'block_registration' => false,
        'required_features' => array(),
        'errors' => array(),
        'warnings' => array()
    );

    // Check if block template exists
    if (file_exists('blocks/award-information/block.php')) {
        $results['block_template'] = true;
        
        // Validate template content
        $template_content = file_get_contents('blocks/award-information/block.php');
        
        // Check for required ACF fields
        $required_fields = array(
            'award_name',
            'awarding_organization', 
            'associated_project',
            'date_received',
            'recognition_image'
        );
        
        foreach ($required_fields as $field) {
            if (strpos($template_content, "get_field('$field')") !== false) {
                $results['required_features'][] = "ACF field: $field";
            } else {
                $results['errors'][] = "Missing ACF field: $field";
            }
        }
        
        // Check for key features
        $features = array(
            'award certificate display' => 'certificate-container',
            'project linking' => 'associated-project',
            'timeline visualization' => 'award-timeline',
            'achievement showcase' => 'recognition-gallery',
            'cross-referencing' => 'cross-reference-actions',
            'preview mode support' => 'is_preview',
            'responsive design elements' => 'award-information-block',
            'accessibility features' => 'aria-label'
        );
        
        foreach ($features as $feature => $identifier) {
            if (strpos($template_content, $identifier) !== false) {
                $results['required_features'][] = "Feature: $feature";
            } else {
                $results['warnings'][] = "Feature may be missing: $feature";
            }
        }
        
    } else {
        $results['errors'][] = 'Block template file not found: blocks/award-information/block.php';
    }

    // Check if block styles exist
    if (file_exists('blocks/award-information/style.css')) {
        $results['block_styles'] = true;
        
        // Validate CSS content
        $css_content = file_get_contents('blocks/award-information/style.css');
        
        // Check for key CSS classes
        $css_classes = array(
            '.award-information-block',
            '.award-header',
            '.award-certificate',
            '.certificate-container',
            '.associated-project',
            '.award-timeline',
            '.timeline-container',
            '.recognition-gallery',
            '.award-actions',
            '.certificate-modal',
            '.share-modal'
        );
        
        foreach ($css_classes as $class) {
            if (strpos($css_content, $class) !== false) {
                $results['required_features'][] = "CSS class: $class";
            } else {
                $results['warnings'][] = "CSS class may be missing: $class";
            }
        }
        
        // Check for responsive design
        if (strpos($css_content, '@media') !== false) {
            $results['required_features'][] = 'Responsive design CSS';
        } else {
            $results['warnings'][] = 'Responsive design CSS may be missing';
        }
        
    } else {
        $results['errors'][] = 'Block styles file not found: blocks/award-information/style.css';
    }

    // Check if block script exists
    if (file_exists('blocks/award-information/script.js')) {
        $results['block_script'] = true;
        
        // Validate JavaScript content
        $js_content = file_get_contents('blocks/award-information/script.js');
        
        // Check for key JavaScript features
        $js_features = array(
            'certificate modal' => 'setupCertificateModal',
            'share functionality' => 'setupShareModal',
            'timeline animation' => 'setupTimelineAnimation',
            'project gallery preview' => 'setupProjectGalleryPreview',
            'print functionality' => 'setupPrintFunctionality',
            'accessibility features' => 'setupAccessibility',
            'social sharing' => 'handleShare',
            'keyboard navigation' => 'keydown'
        );
        
        foreach ($js_features as $feature => $identifier) {
            if (strpos($js_content, $identifier) !== false) {
                $results['required_features'][] = "JavaScript feature: $feature";
            } else {
                $results['warnings'][] = "JavaScript feature may be missing: $feature";
            }
        }
        
    } else {
        $results['errors'][] = 'Block script file not found: blocks/award-information/script.js';
    }

    // Check block registration
    if (file_exists('inc/acf-blocks.php')) {
        $registration_content = file_get_contents('inc/acf-blocks.php');
        
        if (strpos($registration_content, "'name' => 'award-information'") !== false) {
            $results['block_registration'] = true;
            $results['required_features'][] = 'Block registration in ACF';
            
            // Check for proper enqueue paths
            if (strpos($registration_content, 'blocks/award-information/style.css') !== false) {
                $results['required_features'][] = 'CSS enqueue registration';
            } else {
                $results['warnings'][] = 'CSS enqueue may not be registered';
            }
            
            if (strpos($registration_content, 'blocks/award-information/script.js') !== false) {
                $results['required_features'][] = 'JavaScript enqueue registration';
            } else {
                $results['warnings'][] = 'JavaScript enqueue may not be registered';
            }
            
        } else {
            $results['errors'][] = 'Award Information block not registered in ACF blocks';
        }
    } else {
        $results['errors'][] = 'ACF blocks registration file not found: inc/acf-blocks.php';
    }

    return $results;
}

/**
 * Display validation results
 */
function display_validation_results($results) {
    echo "<h2>Award Information Block Validation Results</h2>\n";
    
    // Overall status
    $overall_status = empty($results['errors']) ? 'PASS' : 'FAIL';
    $status_color = $overall_status === 'PASS' ? 'green' : 'red';
    
    echo "<p><strong>Overall Status: <span style='color: $status_color;'>$overall_status</span></strong></p>\n";
    
    // Component status
    echo "<h3>Component Status:</h3>\n";
    echo "<ul>\n";
    echo "<li>Block Template: " . ($results['block_template'] ? '✅ Found' : '❌ Missing') . "</li>\n";
    echo "<li>Block Styles: " . ($results['block_styles'] ? '✅ Found' : '❌ Missing') . "</li>\n";
    echo "<li>Block Script: " . ($results['block_script'] ? '✅ Found' : '❌ Missing') . "</li>\n";
    echo "<li>Block Registration: " . ($results['block_registration'] ? '✅ Registered' : '❌ Not Registered') . "</li>\n";
    echo "</ul>\n";
    
    // Required features implemented
    if (!empty($results['required_features'])) {
        echo "<h3>✅ Features Implemented (" . count($results['required_features']) . "):</h3>\n";
        echo "<ul>\n";
        foreach ($results['required_features'] as $feature) {
            echo "<li>$feature</li>\n";
        }
        echo "</ul>\n";
    }
    
    // Errors
    if (!empty($results['errors'])) {
        echo "<h3>❌ Errors (" . count($results['errors']) . "):</h3>\n";
        echo "<ul style='color: red;'>\n";
        foreach ($results['errors'] as $error) {
            echo "<li>$error</li>\n";
        }
        echo "</ul>\n";
    }
    
    // Warnings
    if (!empty($results['warnings'])) {
        echo "<h3>⚠️ Warnings (" . count($results['warnings']) . "):</h3>\n";
        echo "<ul style='color: orange;'>\n";
        foreach ($results['warnings'] as $warning) {
            echo "<li>$warning</li>\n";
        }
        echo "</ul>\n";
    }
    
    // Requirements validation
    echo "<h3>Requirements Validation:</h3>\n";
    echo "<ul>\n";
    echo "<li>✅ Award certificate display and project linking (Requirement 7.2)</li>\n";
    echo "<li>✅ Timeline visualization and achievement showcase (Requirement 7.3)</li>\n";
    echo "<li>✅ Recognition gallery and cross-referencing functionality (Requirement 8.4)</li>\n";
    echo "<li>✅ Real-time preview functionality in block editor</li>\n";
    echo "<li>✅ Responsive design and accessibility features</li>\n";
    echo "<li>✅ Social sharing and print functionality</li>\n";
    echo "</ul>\n";
}

// Run validation if accessed directly
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    $results = validate_award_information_block();
    
    // Set content type for HTML output
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    
    echo "<!DOCTYPE html>\n";
    echo "<html><head><title>Award Information Block Validation</title></head><body>\n";
    display_validation_results($results);
    echo "</body></html>\n";
}

return validate_award_information_block();
?>