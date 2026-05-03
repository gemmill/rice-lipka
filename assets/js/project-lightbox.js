/**
 * Project lightbox — fullscreen slideshow for single project pages.
 * Uses delegated, capture-phase clicks on .project-lightbox-trigger.
 */
(function () {
    'use strict';

    var box = null;
    var imgEl, counter, prevBtn, nextBtn, closeBtn;
    var sources = [];
    var idx = 0;
    var preloadCache = {};

    function collectSources() {
        sources = [];
        var nodes = document.querySelectorAll('.project-lightbox-trigger');
        nodes.forEach(function (a) {
            var src = a.getAttribute('data-lightbox-src') || a.getAttribute('href');
            if (src && sources.indexOf(src) === -1) {
                sources.push(src);
            }
        });
    }

    function buildBox() {
        if (box) {
            return;
        }
        box = document.createElement('div');
        box.id = 'project-lightbox';
        box.innerHTML =
            '<button type="button" class="pl-btn pl-close" aria-label="Close">&times;</button>' +
            '<button type="button" class="pl-btn pl-prev" aria-label="Previous">&#10094;</button>' +
            '<button type="button" class="pl-btn pl-next" aria-label="Next">&#10095;</button>' +
            '<div class="pl-spinner" aria-hidden="true"></div>' +
            '<img alt="" />' +
            '<div class="pl-counter"></div>';
        document.body.appendChild(box);

        imgEl = box.querySelector('img');
        counter = box.querySelector('.pl-counter');
        closeBtn = box.querySelector('.pl-close');
        prevBtn = box.querySelector('.pl-prev');
        nextBtn = box.querySelector('.pl-next');

        closeBtn.addEventListener('click', close);
        prevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            show(idx - 1);
        });
        nextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            show(idx + 1);
        });

        // Touch swipe support
        var touchStartX = 0;
        var touchStartY = 0;
        var touchActive = false;
        var SWIPE_THRESHOLD = 40;

        box.addEventListener('touchstart', function (e) {
            if (e.touches.length !== 1) {
                touchActive = false;
                return;
            }
            touchActive = true;
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        box.addEventListener('touchmove', function (e) {
            if (!touchActive || e.touches.length !== 1) return;
            var dx = e.touches[0].clientX - touchStartX;
            var dy = e.touches[0].clientY - touchStartY;
            // If horizontal intent, prevent vertical scroll/refresh
            if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 10) {
                e.preventDefault();
            }
        }, { passive: false });

        box.addEventListener('touchend', function (e) {
            if (!touchActive) return;
            touchActive = false;
            var t = e.changedTouches[0];
            var dx = t.clientX - touchStartX;
            var dy = t.clientY - touchStartY;
            if (Math.abs(dx) < SWIPE_THRESHOLD || Math.abs(dx) <= Math.abs(dy)) return;
            if (dx < 0) {
                show(idx + 1);
            } else {
                show(idx - 1);
            }
        });
    }

    function preload(src) {
        if (!src || preloadCache[src]) {
            return;
        }
        var p = new Image();
        p.src = src;
        preloadCache[src] = p;
    }

    function show(i) {
        if (!sources.length) {
            return;
        }
        if (i < 0) {
            i = sources.length - 1;
        }
        if (i >= sources.length) {
            i = 0;
        }
        idx = i;

        var src = sources[idx];
        box.classList.remove('is-loaded');
        box.classList.add('is-loading');
        imgEl.onload = function () {
            box.classList.remove('is-loading');
            box.classList.add('is-loaded');
        };
        imgEl.onerror = function () {
            box.classList.remove('is-loading');
        };
        imgEl.src = src;
        if (imgEl.complete && imgEl.naturalWidth > 0) {
            box.classList.remove('is-loading');
            box.classList.add('is-loaded');
        }

        preload(sources[(idx + 1) % sources.length]);
        preload(sources[(idx - 1 + sources.length) % sources.length]);

        counter.textContent = (idx + 1) + ' / ' + sources.length;
        var multi = sources.length > 1;
        prevBtn.style.display = multi ? '' : 'none';
        nextBtn.style.display = multi ? '' : 'none';
        counter.style.display = multi ? '' : 'none';
    }

    function open(i) {
        buildBox();
        collectSources();
        show(i);
        box.classList.add('is-open');
        document.body.classList.add('pl-open');
        document.addEventListener('keydown', onKey);
    }

    function close() {
        if (!box) {
            return;
        }
        box.classList.remove('is-open');
        document.body.classList.remove('pl-open');
        document.removeEventListener('keydown', onKey);
        imgEl.src = '';
    }

    function onKey(e) {
        if (e.key === 'Escape') {
            close();
        } else if (e.key === 'ArrowLeft') {
            show(idx - 1);
        } else if (e.key === 'ArrowRight') {
            show(idx + 1);
        }
    }

    document.addEventListener('click', function (e) {
        if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }
        var link = e.target && e.target.closest
            ? e.target.closest('.project-lightbox-trigger')
            : null;
        if (!link) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
        collectSources();
        var src = link.getAttribute('data-lightbox-src') || link.getAttribute('href');
        var i = sources.indexOf(src);
        open(i < 0 ? 0 : i);
    }, true);
})();
