/**
 * Chronological Ordering JavaScript
 * Handles filtering, pagination, and AJAX for time-sensitive content
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Initialize chronological ordering functionality
    $(document).ready(function() {
        initChronologicalOrdering();
    });

    function initChronologicalOrdering() {
        // Initialize filtering
        initContentFiltering();
        
        // Initialize AJAX pagination
        initAjaxPagination();
        
        // Initialize archive navigation
        initArchiveNavigation();
        
        // Initialize date range filtering
        initDateRangeFiltering();
    }

    /**
     * Initialize content filtering functionality
     */
    function initContentFiltering() {
        // Handle filter button clicks
        $('.filter-btn').on('click', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $container = $button.closest('.category-nav');
            var filterType = $container.hasClass('subcategory-filters') ? 'subcategory' : 
                           $container.hasClass('event-filters') ? 'event_status' :
                           $container.hasClass('event-type-filters') ? 'event_type' : 'general';
            var filterValue = $button.data('filter') || $button.data('subcategory');
            
            // Update active state
            $container.find('.filter-btn').removeClass('active');
            $button.addClass('active');
            
            // Apply filter
            applyContentFilter(filterType, filterValue);
        });
        
        // Handle date range selector
        $('#date-range-selector').on('change', function() {
            var dateRange = $(this).val();
            applyContentFilter('date_range', dateRange);
        });
    }

    /**
     * Apply content filter via AJAX
     */
    function applyContentFilter(filterType, filterValue) {
        var $postsContainer = $('.posts-container');
        var category = $('body').hasClass('category-news') ? 'news' : 
                      $('body').hasClass('category-events') ? 'events' : '';
        
        if (!category) return;
        
        // Show loading state
        $postsContainer.addClass('loading');
        showLoadingSpinner($postsContainer);
        
        // Prepare AJAX data
        var ajaxData = {
            action: 'ricelipka_filter_content',
            category: category,
            filter_type: filterType,
            filter_value: filterValue,
            paged: 1,
            nonce: ricelipka_ajax.nonce
        };
        
        // Make AJAX request
        $.ajax({
            url: ricelipka_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    $postsContainer.html(response.data.content);
                    updateResultsCount(response.data.found_posts);
                    
                    // Update URL without page reload
                    updateFilterURL(filterType, filterValue);
                    
                    // Reinitialize lazy loading for new content
                    if (window.ricelipkaPerformance && window.ricelipkaPerformance.initLazyLoading) {
                        window.ricelipkaPerformance.initLazyLoading();
                    }
                } else {
                    showErrorMessage('Failed to load filtered content.');
                }
            },
            error: function() {
                showErrorMessage('An error occurred while filtering content.');
            },
            complete: function() {
                $postsContainer.removeClass('loading');
                hideLoadingSpinner();
            }
        });
    }

    /**
     * Initialize AJAX pagination
     */
    function initAjaxPagination() {
        $(document).on('click', '.load-more-btn', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var page = parseInt($button.data('page'));
            var maxPages = parseInt($button.closest('.ajax-pagination').data('max-pages'));
            
            if (page > maxPages) return;
            
            loadMoreContent(page, $button);
        });
        
        // Handle traditional pagination links
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            
            var href = $(this).attr('href');
            var page = getPageFromURL(href);
            
            if (page) {
                loadPageContent(page);
            }
        });
    }

    /**
     * Load more content via AJAX
     */
    function loadMoreContent(page, $button) {
        var category = $('body').hasClass('category-news') ? 'news' : 
                      $('body').hasClass('category-events') ? 'events' : '';
        
        if (!category) return;
        
        // Get current filter state
        var activeFilters = getCurrentFilters();
        
        // Show loading state
        $button.text('Loading...').prop('disabled', true);
        
        // Prepare AJAX data
        var ajaxData = {
            action: 'ricelipka_filter_content',
            category: category,
            filter_type: activeFilters.type,
            filter_value: activeFilters.value,
            paged: page,
            nonce: ricelipka_ajax.nonce
        };
        
        // Make AJAX request
        $.ajax({
            url: ricelipka_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    var $newContent = $(response.data.content);
                    var $postsContainer = $('.posts-container');
                    
                    // Remove old pagination
                    $('.ajax-pagination').remove();
                    
                    // Append new content
                    $postsContainer.append($newContent.not('.ajax-pagination'));
                    
                    // Add new pagination if exists
                    var $newPagination = $newContent.filter('.ajax-pagination');
                    if ($newPagination.length) {
                        $postsContainer.after($newPagination);
                    }
                    
                    // Reinitialize lazy loading for new content
                    if (window.ricelipkaPerformance && window.ricelipkaPerformance.initLazyLoading) {
                        window.ricelipkaPerformance.initLazyLoading();
                    }
                    
                    // Update URL
                    updatePageURL(page);
                } else {
                    showErrorMessage('Failed to load more content.');
                }
            },
            error: function() {
                showErrorMessage('An error occurred while loading content.');
            },
            complete: function() {
                $button.text('Load More').prop('disabled', false);
            }
        });
    }

    /**
     * Initialize archive navigation
     */
    function initArchiveNavigation() {
        // Handle year selection
        $('.archive-year-selector').on('change', function() {
            var year = $(this).val();
            var category = $(this).data('category');
            
            if (year && category) {
                window.location.href = '/' + category + '/' + year + '/';
            }
        });
        
        // Handle month selection
        $('.archive-month-selector').on('change', function() {
            var month = $(this).val();
            var year = $('.archive-year-selector').val();
            var category = $(this).data('category');
            
            if (month && year && category) {
                var monthPadded = month.toString().padStart(2, '0');
                window.location.href = '/' + category + '/' + year + '/' + monthPadded + '/';
            }
        });
        
        // Handle archive view toggle
        $('.archive-toggle').on('click', function(e) {
            e.preventDefault();
            
            var $toggle = $(this);
            var $archiveControls = $('.archive-controls');
            
            $archiveControls.slideToggle();
            $toggle.toggleClass('active');
        });
    }

    /**
     * Initialize date range filtering
     */
    function initDateRangeFiltering() {
        // Custom date range picker
        if ($('.custom-date-range').length) {
            $('.custom-date-range input[type="date"]').on('change', function() {
                var startDate = $('#start-date').val();
                var endDate = $('#end-date').val();
                
                if (startDate && endDate) {
                    applyCustomDateRange(startDate, endDate);
                }
            });
        }
        
        // Quick date range buttons
        $('.quick-date-btn').on('click', function(e) {
            e.preventDefault();
            
            var range = $(this).data('range');
            var dates = getQuickDateRange(range);
            
            if (dates) {
                $('#start-date').val(dates.start);
                $('#end-date').val(dates.end);
                applyCustomDateRange(dates.start, dates.end);
            }
        });
    }

    /**
     * Get current active filters
     */
    function getCurrentFilters() {
        var $activeFilter = $('.filter-btn.active');
        var type = 'all';
        var value = 'all';
        
        if ($activeFilter.length) {
            var $container = $activeFilter.closest('.category-nav');
            
            if ($container.hasClass('subcategory-filters')) {
                type = 'subcategory';
                value = $activeFilter.data('subcategory') || 'all';
            } else if ($container.hasClass('event-filters')) {
                type = 'event_status';
                value = $activeFilter.data('filter') || 'all';
            } else if ($container.hasClass('event-type-filters')) {
                type = 'event_type';
                value = $activeFilter.data('subcategory') || 'all';
            }
        }
        
        return { type: type, value: value };
    }

    /**
     * Get quick date range values
     */
    function getQuickDateRange(range) {
        var today = new Date();
        var start, end;
        
        switch (range) {
            case 'this_week':
                start = new Date(today.setDate(today.getDate() - today.getDay()));
                end = new Date(today.setDate(today.getDate() - today.getDay() + 6));
                break;
            case 'this_month':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'last_month':
                start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                end = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            case 'this_year':
                start = new Date(today.getFullYear(), 0, 1);
                end = new Date(today.getFullYear(), 11, 31);
                break;
            default:
                return null;
        }
        
        return {
            start: formatDate(start),
            end: formatDate(end)
        };
    }

    /**
     * Format date for input fields
     */
    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    /**
     * Apply custom date range filter
     */
    function applyCustomDateRange(startDate, endDate) {
        // Implementation for custom date range filtering
        var filterValue = startDate + '|' + endDate;
        applyContentFilter('custom_date_range', filterValue);
    }

    /**
     * Update filter URL without page reload
     */
    function updateFilterURL(filterType, filterValue) {
        if (history.pushState) {
            var url = new URL(window.location);
            
            if (filterValue === 'all') {
                url.searchParams.delete(filterType);
            } else {
                url.searchParams.set(filterType, filterValue);
            }
            
            history.pushState(null, '', url);
        }
    }

    /**
     * Update page URL for pagination
     */
    function updatePageURL(page) {
        if (history.pushState && page > 1) {
            var url = new URL(window.location);
            url.searchParams.set('paged', page);
            history.pushState(null, '', url);
        }
    }

    /**
     * Get page number from URL
     */
    function getPageFromURL(url) {
        var matches = url.match(/\/page\/(\d+)\//);
        return matches ? parseInt(matches[1]) : null;
    }

    /**
     * Update results count display
     */
    function updateResultsCount(count) {
        var $countElement = $('.category-count, .results-count');
        if ($countElement.length) {
            var category = $('body').hasClass('category-news') ? 'article' : 'event';
            var text = count === 1 ? 
                count + ' ' + category : 
                count + ' ' + category + 's';
            $countElement.text(text);
        }
    }

    /**
     * Show loading spinner
     */
    function showLoadingSpinner($container) {
        if (!$container.find('.loading-spinner').length) {
            $container.append('<div class="loading-spinner"><div class="spinner"></div></div>');
        }
    }

    /**
     * Hide loading spinner
     */
    function hideLoadingSpinner() {
        $('.loading-spinner').remove();
    }

    /**
     * Show error message
     */
    function showErrorMessage(message) {
        var $errorDiv = $('<div class="error-message">' + message + '</div>');
        $('.posts-container').prepend($errorDiv);
        
        setTimeout(function() {
            $errorDiv.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Load specific page content
     */
    function loadPageContent(page) {
        var category = $('body').hasClass('category-news') ? 'news' : 
                      $('body').hasClass('category-events') ? 'events' : '';
        
        if (!category) return;
        
        var activeFilters = getCurrentFilters();
        var $postsContainer = $('.posts-container');
        
        // Show loading state
        $postsContainer.addClass('loading');
        showLoadingSpinner($postsContainer);
        
        // Prepare AJAX data
        var ajaxData = {
            action: 'ricelipka_filter_content',
            category: category,
            filter_type: activeFilters.type,
            filter_value: activeFilters.value,
            paged: page,
            nonce: ricelipka_ajax.nonce
        };
        
        // Make AJAX request
        $.ajax({
            url: ricelipka_ajax.ajax_url,
            type: 'POST',
            data: ajaxData,
            success: function(response) {
                if (response.success) {
                    $postsContainer.html(response.data.content);
                    updateResultsCount(response.data.found_posts);
                    updatePageURL(page);
                    
                    // Scroll to top of content
                    $('html, body').animate({
                        scrollTop: $postsContainer.offset().top - 100
                    }, 500);
                    
                    // Reinitialize lazy loading
                    if (window.ricelipkaPerformance && window.ricelipkaPerformance.initLazyLoading) {
                        window.ricelipkaPerformance.initLazyLoading();
                    }
                } else {
                    showErrorMessage('Failed to load page content.');
                }
            },
            error: function() {
                showErrorMessage('An error occurred while loading content.');
            },
            complete: function() {
                $postsContainer.removeClass('loading');
                hideLoadingSpinner();
            }
        });
    }

})(jQuery);
    /**
     * Initialize archive controls visibility
     */
    function initArchiveControlsVisibility() {
        // Show archive controls on category pages
        if ($('body').hasClass('category-news') || $('body').hasClass('category-events')) {
            $('.archive-controls').show();
            
            // Handle archive toggle
            $('.archive-toggle').on('click', function(e) {
                e.preventDefault();
                
                var $toggle = $(this);
                var $content = $toggle.siblings('.archive-content');
                
                $content.slideToggle(300);
                $toggle.toggleClass('active');
            });
        }
        
        // Always show archive controls on archive pages
        if ($('body').hasClass('archive-page')) {
            $('.archive-controls').show();
            $('.archive-content').show();
        }
    }

    // Add to initialization
    $(document).ready(function() {
        initChronologicalOrdering();
        initArchiveControlsVisibility();
    });