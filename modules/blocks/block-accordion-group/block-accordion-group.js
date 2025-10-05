document.addEventListener('alpine:init', () => {
  Alpine.data('accordionGroup', () => ({
    activeAccordion: null,
    openFirst: false,

    init() {
      this.openFirst = this.$el.dataset.openFirst === 'true';
    },

    setActive(instance) {
      if (this.activeAccordion && this.activeAccordion !== instance) {
        this.activeAccordion.close();
      }
      this.activeAccordion = instance;
    }
  }));

  Alpine.data('accordion', (el, group = null) => ({
    open: false,
    group,

    init() {
      const panel = el.querySelector('[data-panel]');
      const shouldOpen = el.dataset.open === 'true';

      if (shouldOpen && this.group?.openFirst) {
        this.open = true;
        this.group.setActive(this);
        this.animateOpen(panel, true); // true = immediate show
      } else {
        this.animateClose(panel, true); // true = immediate hide
      }
    },

    toggle() {
      const panel = el.querySelector('[data-panel]');
      if (!this.open) {
        this.group?.setActive(this);
        this.animateOpen(panel);
        this.open = true;
      } else {
        this.animateClose(panel);
        this.open = false;

        if (this.group?.activeAccordion === this) {
          this.group.activeAccordion = null;
        }
      }
    },

    close() {
      const panel = el.querySelector('[data-panel]');
      this.animateClose(panel);
      this.open = false;
    },

    animateOpen(panel, immediate = false) {
      gsap.killTweensOf(panel);
      panel.style.display = 'block';

      if (immediate) {
        panel.style.height = 'auto';
        panel.style.opacity = '1';
        return;
      }

      gsap.fromTo(panel,
        { height: 0, opacity: 0 },
        {
          height: panel.scrollHeight,
          opacity: 1,
          duration: 0.3,
          ease: 'power2.out',
          onComplete: () => {
            panel.style.height = 'auto';
          }
        }
      );
    },

    animateClose(panel, immediate = false) {
      gsap.killTweensOf(panel);

      if (immediate) {
        panel.style.height = '0px';
        panel.style.opacity = '0';
        panel.style.display = 'none';
        return;
      }

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
  }));
});