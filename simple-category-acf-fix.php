<?php
/**
 * Simple Category ACF Fix
 * 
 * This provides a simple solution: when category changes, 
 * save the post as draft to trigger ACF location rules
 */

// Add this to functions.php if the current approach doesn't work
function ricelipka_simple_category_acf_fix() {
    global $pagenow;
    
    if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Convert checkboxes to radio buttons
            $('#categorydiv input[type="checkbox"]').each(function() {
                $(this).attr('type', 'radio');
                $(this).attr('name', 'post_category_single');
            });
            
            // Handle category change
            $('#categorydiv input[type="radio"]').on('change', function() {
                if ($(this).is(':checked')) {
                    var categoryId = $(this).val();
                    var categoryLabel = $(this).closest('li').find('label').text();
                    
                    // Update hidden inputs
                    $('input[name="post_category[]"]').remove();
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'post_category[]',
                        value: categoryId
                    }).appendTo('#post');
                    
                    // Show save message
                    $('#category-save-message').remove();
                    var message = '<div id="category-save-message" style="margin-top: 10px; padding: 10px; background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 4px;">';
                    message += '<p style="margin: 0; font-weight: bold;">Category changed to: ' + categoryLabel + '</p>';
                    message += '<p style="margin: 5px 0 10px 0; font-size: 13px;">Click "Save Draft" to update ACF fields for this category.</p>';
                    message += '<button type="button" id="save-draft-now" class="button button-primary">Save Draft Now</button>';
                    message += '</div>';
                    
                    $('#categorydiv .inside').append(message);
                    
                    // Handle save draft button
                    $('#save-draft-now').on('click', function(e) {
                        e.preventDefault();
                        
                        // Change post status to draft if it's auto-draft
                        if ($('#post_status').val() === 'auto-draft') {
                            $('#post_status').val('draft');
                        }
                        
                        // Trigger save
                        $('#save-post').click();
                    });
                }
            });
            
            // Validation on publish/save
            $('#publish, #save-post').on('click', function(e) {
                var selectedCategories = $('#categorydiv input[type="radio"]:checked');
                if (selectedCategories.length === 0) {
                    e.preventDefault();
                    alert('Please select exactly one category for this post.');
                    return false;
                }
            });
        });
        </script>
        <?php
    }
}

// Uncomment this line to use the simple approach instead:
// add_action('admin_head', 'ricelipka_simple_category_acf_fix');
?>