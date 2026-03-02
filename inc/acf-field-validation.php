<?php
/**
 * ACF Field Validation and Guidance
 * 
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class RiceLipka_ACF_Field_Validation {
    
    /**
     * Initialize validation system
     */
    public static function init() {
        add_filter('acf/validate_value/name=news_title', array(__CLASS__, 'validate_news_title'), 10, 4);
        add_filter('acf/validate_value/name=project_name', array(__CLASS__, 'validate_project_name'), 10, 4);
        add_filter('acf/validate_value/name=event_title', array(__CLASS__, 'validate_event_title'), 10, 4);
        add_filter('acf/validate_value/name=award_name', array(__CLASS__, 'validate_award_name'), 10, 4);
        
        add_filter('acf/validate_value/name=excerpt', array(__CLASS__, 'validate_excerpt'), 10, 4);
        add_filter('acf/validate_value/name=event_date', array(__CLASS__, 'validate_event_date'), 10, 4);
        add_filter('acf/validate_value/name=completion_percentage', array(__CLASS__, 'validate_completion_percentage'), 10, 4);
        add_filter('acf/validate_value/name=registration_link', array(__CLASS__, 'validate_url'), 10, 4);
        
        add_action('acf/input/admin_footer', array(__CLASS__, 'add_validation_scripts'));
    }
    
    /**
     * Validate news title
     */
    public static function validate_news_title($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (empty($value)) {
            return __('News title is required. Use a compelling headline that summarizes your story.', 'ricelipka-theme');
        }
        
        if (strlen($value) > 100) {
            return __('News title should be under 100 characters for better SEO and readability.', 'ricelipka-theme');
        }
        
        if (strlen($value) < 10) {
            return __('News title seems too short. Try to be more descriptive about your story.', 'ricelipka-theme');
        }
        
        return $valid;
    }
    
    /**
     * Validate project name
     */
    public static function validate_project_name($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (empty($value)) {
            return __('Project name is required. Use the official project or building name.', 'ricelipka-theme');
        }
        
        if (strlen($value) > 80) {
            return __('Project name should be concise. Consider using the primary building name.', 'ricelipka-theme');
        }
        
        return $valid;
    }
    
    /**
     * Validate event title
     */
    public static function validate_event_title($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (empty($value)) {
            return __('Event title is required. Use the official event name as it appears on invitations.', 'ricelipka-theme');
        }
        
        if (strlen($value) > 120) {
            return __('Event title is quite long. Consider shortening for better display in listings.', 'ricelipka-theme');
        }
        
        return $valid;
    }
    
    /**
     * Validate award name
     */
    public static function validate_award_name($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (empty($value)) {
            return __('Award name is required. Use the exact name as it appears on the certificate.', 'ricelipka-theme');
        }
        
        if (strlen($value) > 150) {
            return __('Award name seems very long. Verify this matches the official award title.', 'ricelipka-theme');
        }
        
        return $valid;
    }
    
    /**
     * Validate excerpt
     */
    public static function validate_excerpt($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (!empty($value)) {
            $word_count = str_word_count(strip_tags($value));
            
            if ($word_count > 50) {
                return __('Excerpt should be brief (under 50 words). This text appears in listings and social media.', 'ricelipka-theme');
            }
            
            if ($word_count < 10 && strlen($value) > 0) {
                return __('Excerpt seems too short. Provide a meaningful summary of your content.', 'ricelipka-theme');
            }
        }
        
        return $valid;
    }
    
    /**
     * Validate event date
     */
    public static function validate_event_date($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (empty($value)) {
            return __('Event date is required. Select the date when the event will take place.', 'ricelipka-theme');
        }
        
        $event_timestamp = strtotime($value);
        $now = time();
        $one_year_ago = strtotime('-1 year');
        $five_years_future = strtotime('+5 years');
        
        if ($event_timestamp < $one_year_ago) {
            return __('Event date seems too far in the past. Verify this is the correct date.', 'ricelipka-theme');
        }
        
        if ($event_timestamp > $five_years_future) {
            return __('Event date is very far in the future. Please verify the date is correct.', 'ricelipka-theme');
        }
        
        return $valid;
    }
    
    /**
     * Validate completion percentage
     */
    public static function validate_completion_percentage($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (!empty($value)) {
            $percentage = intval($value);
            
            if ($percentage < 0 || $percentage > 100) {
                return __('Completion percentage must be between 0 and 100.', 'ricelipka-theme');
            }
            
            // Check if completion status matches percentage
            if (isset($_POST['acf']['field_completion_status'])) {
                $status = $_POST['acf']['field_completion_status'];
                
                if ($status === 'completed' && $percentage < 100) {
                    return __('Completed projects should have 100% completion. Update status or percentage.', 'ricelipka-theme');
                }
                
                if ($status === 'planned' && $percentage > 0) {
                    return __('Planned projects should have 0% completion until construction begins.', 'ricelipka-theme');
                }
            }
        }
        
        return $valid;
    }
    
    /**
     * Validate URL fields
     */
    public static function validate_url($valid, $value, $field, $input) {
        if (!$valid) return $valid;
        
        if (!empty($value)) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                return __('Please enter a valid URL starting with http:// or https://', 'ricelipka-theme');
            }
            
            // Check if URL is accessible (optional warning)
            $headers = @get_headers($value);
            if (!$headers || strpos($headers[0], '200') === false) {
                // Don't fail validation, just provide guidance
                // This would be handled by JavaScript for better UX
            }
        }
        
        return $valid;
    }
    
    /**
     * Add client-side validation scripts
     */
    public static function add_validation_scripts() {
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Real-time character count for title fields
            $('[data-name="news_title"] input, [data-name="project_name"] input, [data-name="event_title"] input, [data-name="award_name"] input').each(function() {
                var $input = $(this);
                var $field = $input.closest('.acf-field');
                var maxLength = 100; // Default max length
                
                // Adjust max length based on field
                if ($field.data('name') === 'project_name') maxLength = 80;
                if ($field.data('name') === 'event_title') maxLength = 120;
                if ($field.data('name') === 'award_name') maxLength = 150;
                
                // Add character counter
                var $counter = $('<div class="acf-character-counter" style="font-size: 12px; color: #666; margin-top: 5px;"></div>');
                $input.after($counter);
                
                function updateCounter() {
                    var length = $input.val().length;
                    var remaining = maxLength - length;
                    var color = remaining < 20 ? '#d63638' : (remaining < 40 ? '#dba617' : '#666');
                    
                    $counter.html(length + ' / ' + maxLength + ' characters').css('color', color);
                    
                    if (remaining < 0) {
                        $counter.append(' <strong>(too long)</strong>');
                    }
                }
                
                $input.on('input keyup', updateCounter);
                updateCounter();
            });
            
            // Word count for excerpt field
            $('[data-name="excerpt"] textarea').each(function() {
                var $textarea = $(this);
                var $counter = $('<div class="acf-word-counter" style="font-size: 12px; color: #666; margin-top: 5px;"></div>');
                $textarea.after($counter);
                
                function updateWordCount() {
                    var text = $textarea.val().trim();
                    var words = text ? text.split(/\s+/).length : 0;
                    var color = words > 50 ? '#d63638' : (words > 40 ? '#dba617' : '#666');
                    
                    $counter.html(words + ' words (recommended: under 50)').css('color', color);
                }
                
                $textarea.on('input keyup', updateWordCount);
                updateWordCount();
            });
            
            // URL validation feedback
            $('[data-name="registration_link"] input, [data-name*="link"] input[type="url"]').each(function() {
                var $input = $(this);
                var $feedback = $('<div class="acf-url-feedback" style="font-size: 12px; margin-top: 5px;"></div>');
                $input.after($feedback);
                
                $input.on('blur', function() {
                    var url = $input.val().trim();
                    if (url && !url.match(/^https?:\/\//)) {
                        $feedback.html('<span style="color: #dba617;">⚠ URL should start with http:// or https://</span>');
                    } else if (url) {
                        $feedback.html('<span style="color: #00a32a;">✓ URL format looks good</span>');
                    } else {
                        $feedback.html('');
                    }
                });
            });
            
            // Date validation for events
            $('[data-name="event_date"] input').on('change', function() {
                var $input = $(this);
                var $feedback = $input.siblings('.acf-date-feedback');
                
                if (!$feedback.length) {
                    $feedback = $('<div class="acf-date-feedback" style="font-size: 12px; margin-top: 5px;"></div>');
                    $input.after($feedback);
                }
                
                var selectedDate = new Date($input.val());
                var now = new Date();
                var daysDiff = Math.ceil((selectedDate - now) / (1000 * 60 * 60 * 24));
                
                if (daysDiff < 0) {
                    $feedback.html('<span style="color: #dba617;">⚠ This event is in the past</span>');
                } else if (daysDiff > 365) {
                    $feedback.html('<span style="color: #dba617;">⚠ This event is more than a year away</span>');
                } else if (daysDiff < 7) {
                    $feedback.html('<span style="color: #00a32a;">✓ Upcoming event (less than a week)</span>');
                } else {
                    $feedback.html('<span style="color: #00a32a;">✓ Future event</span>');
                }
            });
            
            // Completion percentage vs status validation
            $('[data-name="completion_status"] select, [data-name="completion_percentage"] input').on('change', function() {
                var status = $('[data-name="completion_status"] select').val();
                var percentage = parseInt($('[data-name="completion_percentage"] input').val()) || 0;
                var $percentageField = $('[data-name="completion_percentage"]');
                var $feedback = $percentageField.find('.acf-status-feedback');
                
                if (!$feedback.length) {
                    $feedback = $('<div class="acf-status-feedback" style="font-size: 12px; margin-top: 5px;"></div>');
                    $percentageField.find('input').after($feedback);
                }
                
                if (status === 'completed' && percentage < 100) {
                    $feedback.html('<span style="color: #dba617;">⚠ Completed projects should be 100%</span>');
                } else if (status === 'planned' && percentage > 0) {
                    $feedback.html('<span style="color: #dba617;">⚠ Planned projects should be 0%</span>');
                } else if (status === 'in_progress' && (percentage === 0 || percentage === 100)) {
                    $feedback.html('<span style="color: #dba617;">⚠ In-progress projects should be between 1-99%</span>');
                } else {
                    $feedback.html('<span style="color: #00a32a;">✓ Status and percentage match</span>');
                }
            });
        });
        </script>
        <?php
    }
}

// Initialize validation system
RiceLipka_ACF_Field_Validation::init();