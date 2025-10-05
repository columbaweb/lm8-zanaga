document.addEventListener('DOMContentLoaded', function () {
    
    // ========================
    // Utility DOM references
    // ========================
    const body = document.body;
    const html = document.documentElement;
    const header = document.querySelector('header');

    // ========================
    // Mobile Menu + Submenu
    // ========================
    (function initMobileMenu() {
        const navExpander = document.getElementById('nav-expander');
        const sitenavLinks = document.querySelectorAll('.sitenav a');
        const subToggles = document.querySelectorAll('.sitenav .menu-item-has-children > .sub-toggle');
        const megaMenuItems = document.querySelectorAll('.has-megamenu > li.menu-item-has-children');

        if (!navExpander) return;

        navExpander.style.cursor = 'pointer';
        navExpander.addEventListener('click', function (e) {
            e.preventDefault();
            body.classList.toggle('nav-expanded');
            html.classList.toggle('has-nav-expanded');
            navExpander.classList.toggle('is-active');
            header.classList.toggle('menu-open');
        });

        sitenavLinks.forEach(link => {
            link.addEventListener('click', () => {
                body.classList.remove('nav-expanded');
                html.classList.remove('has-nav-expanded');
                navExpander.classList.remove('is-active');
                header.classList.remove('menu-open');
            });
        });

        subToggles.forEach(toggle => {
            toggle.addEventListener('click', function () {
                const subMenu = this.nextElementSibling;
                this.classList.toggle('open');
                subMenu.style.maxHeight = subMenu.style.maxHeight ? null : `${subMenu.scrollHeight}px`;
            });
        });

        megaMenuItems.forEach(item => {
            item.addEventListener('mouseenter', () => header.classList.add('megamenu-open'));
            item.addEventListener('mouseleave', () => header.classList.remove('megamenu-open'));
        });
    })();

    // ========================
    // 2-column submenu layout
    // ========================
    (function styleSubmenus() {
        const subMenus = document.querySelectorAll('ul.sub-menu');

        subMenus.forEach(subMenu => {
            const items = subMenu.querySelectorAll('li');
            if (items.length > 4) {
                subMenu.classList.add('col2');
                const midIndex = Math.ceil(items.length / 2);
                if (items[midIndex]) {
                    items[midIndex].classList.add('first-in-col2');
                }
            }
        });
    })();

    // ========================
    // Sticky header
    // ========================
    (function stickyHeader() {
        const header = document.querySelector('.header');
        if (!header) return;

        const headerHeight = header.offsetHeight;
        let lastScroll = window.scrollY || 0;
        let isAtTop = lastScroll <= 0;

        const handleScroll = () => {
            const current = window.scrollY;

            // Add class when scrolled
            if (current > 0) {
                header.classList.add('nav-scrolled');
            } else {
                header.classList.remove('nav-scrolled');
            }

            if (current <= 0 && !isAtTop) {
                setTimeout(() => {
                    header.classList.remove('nav-hidden', 'nav-visible');
                    header.classList.add('nav-default');
                }, 200);
                isAtTop = true;
            } else if (current > lastScroll && current > headerHeight) {
                header.classList.remove('nav-default', 'nav-visible');
                header.classList.add('nav-hidden');
                isAtTop = false;
            } else if (current < lastScroll) {
                header.classList.remove('nav-default', 'nav-hidden');
                header.classList.add('nav-visible');
                isAtTop = false;
            }

            lastScroll = current <= 0 ? 0 : current;
        };

        // Attach scroll listener
        window.addEventListener('scroll', handleScroll);

        // Run once on load to ensure correct state
        handleScroll();
    })();

    // ========================
    // Scroll to Top Button
    // ========================
    (function scrollToTopBtn() {
        const btn = document.querySelector('.b2t');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            btn.classList.toggle('active', window.scrollY > 200);
        });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();

    // ========================
    // Swipe Table Wrapper
    // ========================
    (function wrapSwipeTables() {
        const tables = document.querySelectorAll('.wp-block-table.is-style-swipe');

        tables.forEach(block => {
            const table = block.querySelector('table');
            if (table) {
                const wrapper = document.createElement('div');
                wrapper.className = 'wp-block-table-wrap';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });
    })();

    // ========================
    // Fade-in Animation on Scroll
    // ========================
    (function animateOnScroll() {
        const elements = document.querySelectorAll('.is-style-small-title, .fade, .fade-in-up, .fade-in, .zoom-in, .zoom-in-sm, .fade-in-left, .fade-in-right, .wp-block-cover, .wp-block-columns, .operations-info .inner');
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.015 });

        elements.forEach(el => observer.observe(el));
    })();

    // ========================
    // Anchor Smooth Scroll
    // ========================
    // (function smoothAnchorScroll() {
    //     const hash = window.location.hash;
    //     if (hash) {
    //         window.location.hash = '';
    //         setTimeout(() => {
    //             const target = document.querySelector(hash);
    //             if (target) target.scrollIntoView({ behavior: 'smooth' });
    //         }, 500);
    //     }
    // })();
    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;

        if (hash) {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
            window.scrollTo(0, 0);

            setTimeout(() => {
                const target = document.querySelector(hash);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }, 100);
        }
    });

    // ========================
    // Search Popup Toggle
    // ========================
    (function searchPopup() {
        const toggle = document.querySelector('.search-toggle');
        const popup = document.getElementById('header-search-popup');
        const close = document.querySelector('.search-close');

        if (!toggle || !popup || !close) return;

        toggle.addEventListener('click', () => {
            popup.classList.add('is-active');
            body.classList.add('no-scroll');
        });

        close.addEventListener('click', () => {
            popup.classList.remove('is-active');
            body.classList.remove('no-scroll');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                popup.classList.remove('is-active');
                body.classList.remove('no-scroll');
            }
        });
    })();

    // ========================
    // Wrap small titles in span
    // ========================
    (function wrapSmallTitles() {
        const headings = document.querySelectorAll('.wp-block-heading.is-style-small-title');
        headings.forEach(heading => {
            if (!heading.querySelector('span')) {
                heading.innerHTML = `<strong>${heading.textContent}<span><em></em><em></em><em></em></span></strong>`;
            }
        });
    })();
});

// ========================
// Equal Heights Outside DOM Ready (resizing)
// ========================
(function handleEqualHeights() {
    function equalizeRowHeights(className, breakpoint) {
        const elements = document.getElementsByClassName(className);
        let max = 0;

        if (window.innerWidth < breakpoint) {
            Array.from(elements).forEach(el => el.style.height = 'auto');
            return;
        }

        Array.from(elements).forEach(el => {
            el.style.height = 'auto';
            max = Math.max(max, el.clientHeight);
        });
        Array.from(elements).forEach(el => el.style.height = `${max}px`);
    }

    const configs = [
        { className: "equal", breakpoint: 680 },
        { className: "e1", breakpoint: 782 },
        { className: "e2", breakpoint: 782 },
        { className: "e3", breakpoint: 782 },
        { className: "sh", breakpoint: 420 },  
        //{ className: "team-head", breakpoint: 420 },      
    ];

    function applyEqualHeights() {
        configs.forEach(cfg => equalizeRowHeights(cfg.className, cfg.breakpoint));
    }

    window.addEventListener('load', applyEqualHeights);
    window.addEventListener('resize', applyEqualHeights);
})();


// tools iframe
document.addEventListener("DOMContentLoaded", function () {
	window.addEventListener('message', function(event) {
		var frames = document.getElementsByTagName('iframe');
		for (var i = 0; i < frames.length; i++) {
			if (frames[i].contentWindow === event.source) {
			  var style = frames[i].currentStyle || window.getComputedStyle(frames[i]);
			  var paddingTop = style.paddingTop;
			  var paddingBottom = style.paddingBottom;
			  var padding = parseInt(paddingTop) + parseInt(paddingBottom);
			  frames[i].style.height = event.data+padding+'px';
			  break;
			}
		}
	});
});
