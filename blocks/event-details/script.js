/**
 * Event Details Block JavaScript
 *
 * @package RiceLipka_Theme
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initEventDetails();
    });

    /**
     * Initialize all event details blocks
     */
    function initEventDetails() {
        const eventBlocks = document.querySelectorAll('.event-details-block');
        
        eventBlocks.forEach(function(block) {
            initCountdownTimer(block);
            initCalendarIntegration(block);
            initEventSharing(block);
            initLocationMapping(block);
            initAccessibility(block);
        });
    }

    /**
     * Initialize countdown timer functionality
     */
    function initCountdownTimer(block) {
        const countdown = block.querySelector('.event-countdown');
        if (!countdown) return;

        const targetDate = countdown.dataset.target;
        if (!targetDate) return;

        const target = new Date(targetDate).getTime();
        const timer = countdown.querySelector('.countdown-timer');
        
        if (!timer) return;

        // Update countdown every second
        const interval = setInterval(function() {
            const now = new Date().getTime();
            const distance = target - now;

            // If event has passed, stop the timer
            if (distance < 0) {
                clearInterval(interval);
                handleEventPassed(block, countdown);
                return;
            }

            // Calculate time units
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Update display
            updateCountdownDisplay(timer, { days, hours, minutes, seconds });

            // Add visual effects for urgency
            if (days === 0 && hours < 1) {
                countdown.classList.add('urgent');
            }

        }, 1000);

        // Store interval for cleanup
        countdown.countdownInterval = interval;
    }

    /**
     * Update countdown display
     */
    function updateCountdownDisplay(timer, time) {
        const daysEl = timer.querySelector('[data-unit="days"]');
        const hoursEl = timer.querySelector('[data-unit="hours"]');
        const minutesEl = timer.querySelector('[data-unit="minutes"]');
        const secondsEl = timer.querySelector('[data-unit="seconds"]');

        if (daysEl) {
            animateNumberChange(daysEl, time.days);
        }
        if (hoursEl) {
            animateNumberChange(hoursEl, time.hours);
        }
        if (minutesEl) {
            animateNumberChange(minutesEl, time.minutes);
        }
        if (secondsEl) {
            animateNumberChange(secondsEl, time.seconds);
        }
    }

    /**
     * Animate number changes in countdown
     */
    function animateNumberChange(element, newValue) {
        const currentValue = parseInt(element.textContent);
        
        if (currentValue !== newValue) {
            element.style.transform = 'scale(1.1)';
            element.textContent = newValue.toString().padStart(2, '0');
            
            setTimeout(function() {
                element.style.transform = 'scale(1)';
            }, 150);
        }
    }

    /**
     * Handle when event has passed
     */
    function handleEventPassed(block, countdown) {
        countdown.innerHTML = `
            <div class="event-ended-message">
                <h3>${getLocalizedString('eventEnded', 'This event has ended')}</h3>
                <p>${getLocalizedString('eventEndedDesc', 'Thank you to everyone who participated!')}</p>
            </div>
        `;
        
        // Update block status
        block.dataset.isPast = 'true';
        
        // Hide registration button
        const registerBtn = block.querySelector('.event-register-btn');
        if (registerBtn) {
            registerBtn.style.display = 'none';
        }
        
        announceToScreenReader('Event countdown has ended');
    }

    /**
     * Initialize calendar integration
     */
    function initCalendarIntegration(block) {
        const calendarBtn = block.querySelector('.add-to-calendar-btn');
        const modal = block.querySelector('.calendar-modal');
        
        if (!calendarBtn || !modal) return;

        const eventData = JSON.parse(calendarBtn.dataset.eventData || '{}');
        
        // Open calendar modal
        calendarBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(modal);
        });

        // Handle calendar option clicks
        const calendarOptions = modal.querySelectorAll('.calendar-option');
        calendarOptions.forEach(function(option) {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const calendarType = this.dataset.calendar;
                generateCalendarLink(calendarType, eventData);
                closeModal(modal);
            });
        });

        // Close modal handlers
        const closeBtn = modal.querySelector('.calendar-modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal(modal);
            });
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal);
            }
        });

        // Keyboard navigation
        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal(modal);
            }
        });
    }

    /**
     * Generate calendar links
     */
    function generateCalendarLink(type, eventData) {
        const { title, start, end, location, description } = eventData;
        
        let url = '';
        
        switch (type) {
            case 'google':
                url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${start}/${end}&location=${encodeURIComponent(location || '')}&details=${encodeURIComponent(description || '')}`;
                window.open(url, '_blank', 'noopener,noreferrer');
                break;
                
            case 'outlook':
                url = `https://outlook.live.com/calendar/0/deeplink/compose?subject=${encodeURIComponent(title)}&startdt=${start}&enddt=${end}&location=${encodeURIComponent(location || '')}&body=${encodeURIComponent(description || '')}`;
                window.open(url, '_blank', 'noopener,noreferrer');
                break;
                
            case 'ics':
                generateICSFile(eventData);
                break;
        }
        
        announceToScreenReader(`Opening ${type} calendar`);
    }

    /**
     * Generate ICS file for download
     */
    function generateICSFile(eventData) {
        const { title, start, end, location, description } = eventData;
        
        const icsContent = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Rice+Lipka Architects//Event Calendar//EN',
            'BEGIN:VEVENT',
            `UID:${Date.now()}@ricelipka.com`,
            `DTSTART:${start}`,
            `DTEND:${end}`,
            `SUMMARY:${title}`,
            `DESCRIPTION:${description || ''}`,
            `LOCATION:${location || ''}`,
            `DTSTAMP:${new Date().toISOString().replace(/[-:]/g, '').split('.')[0]}Z`,
            'END:VEVENT',
            'END:VCALENDAR'
        ].join('\r\n');

        const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `${title.replace(/[^a-z0-9]/gi, '_').toLowerCase()}.ics`;
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        announceToScreenReader('Calendar file downloaded');
    }

    /**
     * Initialize event sharing functionality
     */
    function initEventSharing(block) {
        const shareBtn = block.querySelector('.share-event-btn');
        const modal = block.querySelector('.share-modal');
        
        if (!shareBtn || !modal) return;

        // Open share modal
        shareBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(modal);
        });

        // Handle share option clicks
        const shareOptions = modal.querySelectorAll('.share-option');
        shareOptions.forEach(function(option) {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const shareType = this.dataset.share;
                handleEventShare(shareType, block);
                closeModal(modal);
            });
        });

        // Close modal handlers
        const closeBtn = modal.querySelector('.share-modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal(modal);
            });
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal);
            }
        });

        // Keyboard navigation
        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal(modal);
            }
        });
    }

    /**
     * Handle event sharing
     */
    function handleEventShare(type, block) {
        const eventTitle = block.querySelector('.event-title').textContent;
        const eventDate = block.querySelector('.event-date time');
        const eventLocation = block.querySelector('.event-location address');
        
        const url = window.location.href;
        const title = `${eventTitle} - Rice+Lipka Architects`;
        const text = `Join us for ${eventTitle}${eventDate ? ` on ${eventDate.textContent}` : ''}${eventLocation ? ` at ${eventLocation.textContent}` : ''}`;
        
        switch (type) {
            case 'facebook':
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
                break;
                
            case 'twitter':
                window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
                break;
                
            case 'linkedin':
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank', 'width=600,height=400');
                break;
                
            case 'email':
                const subject = encodeURIComponent(title);
                const body = encodeURIComponent(`${text}\n\nLearn more: ${url}`);
                window.location.href = `mailto:?subject=${subject}&body=${body}`;
                break;
                
            case 'copy':
                copyToClipboard(url);
                showCopyFeedback();
                break;
        }
        
        announceToScreenReader(`Sharing event via ${type}`);
    }

    /**
     * Copy text to clipboard
     */
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                announceToScreenReader('Link copied to clipboard');
            }).catch(function() {
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    }

    /**
     * Fallback copy method
     */
    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            announceToScreenReader('Link copied to clipboard');
        } catch (err) {
            announceToScreenReader('Unable to copy link');
        }
        
        document.body.removeChild(textArea);
    }

    /**
     * Show copy feedback
     */
    function showCopyFeedback() {
        const feedback = document.createElement('div');
        feedback.textContent = 'Link copied!';
        feedback.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            z-index: 10000;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        `;
        
        document.body.appendChild(feedback);
        
        setTimeout(function() {
            feedback.style.opacity = '0';
            feedback.style.transition = 'opacity 0.3s ease';
            setTimeout(function() {
                if (feedback.parentNode) {
                    document.body.removeChild(feedback);
                }
            }, 300);
        }, 2000);
    }

    /**
     * Initialize location mapping
     */
    function initLocationMapping(block) {
        const mapBtn = block.querySelector('.location-map-btn');
        if (!mapBtn) return;

        mapBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const location = this.dataset.location;
            if (location) {
                const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(location)}`;
                window.open(mapsUrl, '_blank', 'noopener,noreferrer');
                announceToScreenReader('Opening location in maps');
            }
        });
    }

    /**
     * Open modal
     */
    function openModal(modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus management
        const firstFocusable = modal.querySelector('button, a, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        // Trap focus within modal
        trapFocus(modal);
    }

    /**
     * Close modal
     */
    function closeModal(modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        
        // Return focus to trigger button
        const triggerBtn = modal.previousElementSibling;
        if (triggerBtn && triggerBtn.focus) {
            triggerBtn.focus();
        }
    }

    /**
     * Trap focus within modal
     */
    function trapFocus(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        lastElement.focus();
                        e.preventDefault();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        firstElement.focus();
                        e.preventDefault();
                    }
                }
            }
        });
    }

    /**
     * Initialize accessibility features
     */
    function initAccessibility(block) {
        // Add ARIA labels to interactive elements
        const registerBtn = block.querySelector('.event-register-btn');
        if (registerBtn) {
            registerBtn.setAttribute('aria-describedby', 'event-registration-info');
        }

        const countdownTimer = block.querySelector('.countdown-timer');
        if (countdownTimer) {
            countdownTimer.setAttribute('aria-live', 'polite');
            countdownTimer.setAttribute('aria-atomic', 'true');
        }

        // Ensure proper heading hierarchy
        const headings = block.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headings.forEach(function(heading) {
            if (!heading.id) {
                heading.id = 'heading-' + Math.random().toString(36).substring(2, 11);
            }
        });

        // Add skip links for screen readers
        const actions = block.querySelector('.event-actions');
        if (actions) {
            actions.setAttribute('role', 'group');
            actions.setAttribute('aria-label', 'Event actions');
        }
    }

    /**
     * Announce to screen readers
     */
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('aria-live', 'polite');
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;
        
        document.body.appendChild(announcement);
        
        setTimeout(function() {
            if (announcement.parentNode) {
                document.body.removeChild(announcement);
            }
        }, 1000);
    }

    /**
     * Get localized string
     */
    function getLocalizedString(key, fallback) {
        if (window.riceLipkaBlocks && window.riceLipkaBlocks.strings && window.riceLipkaBlocks.strings[key]) {
            return window.riceLipkaBlocks.strings[key];
        }
        return fallback;
    }

    /**
     * Handle recurring events
     */
    function handleRecurringEvents() {
        const recurringBlocks = document.querySelectorAll('.event-details-block [data-recurring="true"]');
        
        recurringBlocks.forEach(function(block) {
            // Add special styling or functionality for recurring events
            block.classList.add('recurring-event');
            
            // Could add logic here to calculate next occurrence
            // This would require additional data about recurrence pattern
        });
    }

    /**
     * Handle block editor preview updates
     */
    if (window.wp && window.wp.data) {
        const { subscribe } = window.wp.data;
        
        subscribe(function() {
            // Re-initialize when blocks are updated in editor
            setTimeout(function() {
                const newBlocks = document.querySelectorAll('.event-details-block:not([data-initialized])');
                newBlocks.forEach(function(block) {
                    block.setAttribute('data-initialized', 'true');
                    initCountdownTimer(block);
                    initCalendarIntegration(block);
                    initEventSharing(block);
                    initLocationMapping(block);
                    initAccessibility(block);
                });
            }, 100);
        });
    }

    /**
     * Cleanup function for when blocks are removed
     */
    function cleanupEventBlock(block) {
        const countdown = block.querySelector('.event-countdown');
        if (countdown && countdown.countdownInterval) {
            clearInterval(countdown.countdownInterval);
        }
    }

    // Export cleanup function for potential use
    window.cleanupEventBlock = cleanupEventBlock;

    // Initialize recurring events handling
    handleRecurringEvents();

    // Add CSS for urgent countdown styling
    const urgentStyle = document.createElement('style');
    urgentStyle.textContent = `
        .event-countdown.urgent {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        
        .countdown-unit {
            transition: transform 0.15s ease;
        }
    `;
    document.head.appendChild(urgentStyle);

})();