document.addEventListener('alpine:init', () => {
    Alpine.data('accordion', () => ({
        open: false,
        toggle(el) {
            this.open = !this.open;
            const panel = el.nextElementSibling;
            gsap.killTweensOf(panel);
            if (this.open) {
                panel.style.display = 'block';
                gsap.fromTo(panel, { height: 0, opacity: 0 }, {
                    height: panel.scrollHeight,
                    opacity: 1,
                    duration: 0.3,
                    ease: 'power2.out',
                    onComplete: () => {
                        panel.style.height = 'auto';
                    }
                });
            } else {
                gsap.to(panel, {
                    height: 0,
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        panel.style.display = 'none';
                    }
                });
            }
        },
        init() {
            if (this.$el.dataset.open === "true") {
                this.open = true;
                const panel = this.$el.querySelector('[data-panel]');
                panel.style.display = 'block';
                panel.style.height = 'auto';
                panel.style.opacity = '1';
            }
        }
    }));
});