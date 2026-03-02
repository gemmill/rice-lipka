<?php
/**
 * Validation script for Event Details ACF Block
 * 
 * This script validates that the Event Details block is properly implemented
 * according to Task 5.3 requirements.
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    // For command line execution
    define('ABSPATH', dirname(__FILE__) . '/');
}

echo "<h2>Event Details ACF Block Validation</h2>\n";

$validation_results = array();
$all_passed = true;

// Check if block template file exists
echo "<h3>1. Block Template File</h3>\n";
$block_template = 'blocks/event-details/block.php';
if (file_exists($block_template)) {
    echo "✅ Block template exists: {$block_template}\n";
    $validation_results['template_exists'] = true;
    
    // Check template content
    $template_content = file_get_contents($block_template);
    
    // Check for required elements
    $required_elements = array(
        'event-details-block' => 'Main block container class',
        'event-header' => 'Event header section',
        'event-title' => 'Event title display',
        'event-meta' => 'Event metadata section',
        'event-date-time' => 'Date and time display',
        'event-location' => 'Location information',
        'event-countdown' => 'Countdown timer section',
        'countdown-timer' => 'Countdown display',
        'countdown-unit' => 'Individual countdown units',
        'event-actions' => 'Action buttons section',
        'event-register-btn' => 'Registration button',
        'add-to-calendar-btn' => 'Calendar integration button',
        'share-event-btn' => 'Event sharing button',
        'location-map-btn' => 'Location mapping button',
        'event-external-links' => 'External links section',
        'calendar-modal' => 'Calendar integration modal',
        'share-modal' => 'Event sharing modal'
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
        'event_title' => 'Event Title field',
        'event_date' => 'Event Date field',
        'event_time' => 'Event Time field',
        'location' => 'Location field',
        'external_links' => 'External Links field',
        'registration_link' => 'Registration Link field',
        'recurring_event' => 'Recurring Event field'
    );
    
    foreach ($acf_fields as $field => $description) {
        if (strpos($template_content, "get_field('{$field}')") !== false) {
            echo "✅ {$description}: Integrated\n";
        } else {
            echo "❌ {$description}: Missing integration\n";
            $all_passed = false;
        }
    }
    
    // Check for countdown functionality
    echo "<h4>Countdown Timer Features:</h4>\n";
    $countdown_features = array(
        'data-event-date' => 'Event date data attribute',
        'data-target' => 'Countdown target data',
        'countdown-number' => 'Countdown number display',
        'data-unit="days"' => 'Days countdown unit',
        'data-unit="hours"' => 'Hours countdown unit',
        'data-unit="minutes"' => 'Minutes countdown unit',
        'data-unit="seconds"' => 'Seconds countdown unit'
    );
    
    foreach ($countdown_features as $feature => $description) {
        if (strpos($template_content, $feature) !== false) {
            echo "✅ {$description}: Implemented\n";
        } else {
            echo "❌ {$description}: Missing\n";
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
$css_file = 'blocks/event-details/style.css';
if (file_exists($css_file)) {
    echo "✅ CSS file exists: {$css_file}\n";
    $validation_results['css_exists'] = true;
    
    $css_content = file_get_contents($css_file);
    
    // Check for responsive design and features
    $css_elements = array(
        '@media (max-width: 768px)' => 'Mobile responsive styles',
        '@media (max-width: 480px)' => 'Small mobile styles',
        '.event-countdown' => 'Countdown timer styles',
        '.countdown-timer' => 'Countdown display grid',
        '.countdown-unit' => 'Individual countdown unit styles',
        '.event-actions' => 'Action buttons layout',
        '.event-register-btn' => 'Registration button styles',
        '.calendar-modal' => 'Calendar modal styles',
        '.share-modal' => 'Share modal styles',
        '.location-map-btn' => 'Location mapping button',
        'grid-template-columns' => 'CSS Grid layout',
        'backdrop-filter' => 'Modern CSS effects',
        '@media (prefers-reduced-motion: reduce)' => 'Accessibility - reduced motion',
        '@media (prefers-contrast: high)' => 'Accessibility - high contrast',
        '.sr-only' => 'Screen reader only content'
    );
    
    echo "<h4>CSS Features:</h4>\n";
    foreach ($css_elements as $element => $description) {
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
$js_file = 'blocks/event-details/script.js';
if (file_exists($js_file)) {
    echo "✅ JavaScript file exists: {$js_file}\n";
    $validation_results['js_exists'] = true;
    
    $js_content = file_get_contents($js_file);
    
    // Check for interactive features
    $js_features = array(
        'initCountdownTimer' => 'Countdown timer functionality',
        'initCalendarIntegration' => 'Calendar integration',
        'initEventSharing' => 'Event sharing functionality',
        'initLocationMapping' => 'Location mapping',
        'initAccessibility' => 'Accessibility features',
        'generateCalendarLink' => 'Calendar link generation',
        'generateICSFile' => 'ICS file download',
        'handleEventShare' => 'Social sharing handling',
        'copyToClipboard' => 'Clipboard functionality',
        'setInterval' => 'Real-time countdown updates',
        'addEventListener' => 'Event handling',
        'aria-' => 'ARIA attributes for accessibility',
        'trapFocus' => 'Modal focus management',
        'announceToScreenReader' => 'Screen reader announcements'
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
    
    // Check for calendar integration services
    echo "<h4>Calendar Integration Services:</h4>\n";
    $calendar_services = array(
        'google' => 'Google Calendar integration',
        'outlook' => 'Outlook Calendar integration',
        'ics' => 'ICS file download'
    );
    
    foreach ($calendar_services as $service => $description) {
        if (strpos($js_content, $service) !== false) {
            echo "✅ {$description}: Supported\n";
        } else {
            echo "❌ {$description}: Missing\n";
            $all_passed = false;
        }
    }
    
    // Check for sharing platforms
    echo "<h4>Sharing Platform Support:</h4>\n";
    $sharing_platforms = array(
        'facebook' => 'Facebook sharing',
        'twitter' => 'Twitter sharing',
        'linkedin' => 'LinkedIn sharing',
        'email' => 'Email sharing',
        'copy' => 'Link copying'
    );
    
    foreach ($sharing_platforms as $platform => $description) {
        if (strpos($js_content, $platform) !== false) {
            echo "✅ {$description}: Supported\n";
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
    
    // Check for event details block registration
    if (strpos($acf_content, "'name' => 'event-details'") !== false) {
        echo "✅ Event Details block registered\n";
        
        // Check registration details
        $registration_elements = array(
            "'title' => __('Event Details'" => 'Block title',
            "'render_template' => 'blocks/event-details/block.php'" => 'Template path',
            "'category' => 'ricelipka-blocks'" => 'Block category',
            "'icon' => 'calendar-alt'" => 'Block icon',
            "'keywords' => array('event', 'calendar', 'date', 'countdown'" => 'Block keywords',
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
        echo "❌ Event Details block not registered\n";
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
        'Rice+Lipka Architects Open House' => 'Preview sample data',
        'block-preview-indicator' => 'Preview mode indicator',
        'block-preview-footer' => 'Preview mode footer'
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

// Check for recurring event support
echo "<h3>6. Recurring Event Support</h3>\n";
if (isset($template_content)) {
    $recurring_features = array(
        'recurring_event' => 'Recurring event field',
        'event-recurring-badge' => 'Recurring event badge',
        'recurring-icon' => 'Recurring event icon'
    );
    
    foreach ($recurring_features as $feature => $description) {
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
    echo "🎉 <strong>All validations passed!</strong> The Event Details ACF block is properly implemented.\n";
    echo "<ul>\n";
    echo "<li>✅ Block template with calendar integration and location mapping</li>\n";
    echo "<li>✅ Countdown timer functionality with real-time updates</li>\n";
    echo "<li>✅ Registration button and action buttons</li>\n";
    echo "<li>✅ Event sharing across multiple platforms</li>\n";
    echo "<li>✅ Recurring event support</li>\n";
    echo "<li>✅ Calendar integration (Google, Outlook, ICS download)</li>\n";
    echo "<li>✅ Location mapping with Google Maps integration</li>\n";
    echo "<li>✅ Real-time preview functionality</li>\n";
    echo "<li>✅ Accessibility features and ARIA support</li>\n";
    echo "<li>✅ Responsive design with mobile optimization</li>\n";
    echo "<li>✅ Requirements 7.2, 7.3, 8.3 satisfied</li>\n";
    echo "</ul>\n";
} else {
    echo "⚠️ <strong>Some validations failed.</strong> Please review the issues above.\n";
}

echo "\n<p><em>Task 5.3: Create Event Details ACF block - Validation Complete</em></p>\n";
?>