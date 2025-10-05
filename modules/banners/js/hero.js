// document.addEventListener('DOMContentLoaded', function () {
//     if (document.body.classList.contains('home')) {
//         const heroElements = document.querySelectorAll('.home .hero-static, .home .hero-video');

//         heroElements.forEach(heroElement => {
//             const heroStaticImage = heroElement.querySelector('.hero-static__image, .hero-video__image');
//             const iframe = heroElement.querySelector('.hero-video__image .video-player, .hero-static__image .video-player');

//             if (heroStaticImage && iframe) {
//                 const updateIframeSize = () => {
//                     const containerWidth = heroStaticImage.offsetWidth;
//                     const containerHeight = heroStaticImage.offsetHeight;
//                     const videoAspectRatio = 16 / 9;
//                     const containerAspectRatio = containerWidth / containerHeight;

//                     if (containerAspectRatio > videoAspectRatio) {
//                         iframe.style.width = `${containerWidth}px`;
//                         iframe.style.height = `${containerWidth / videoAspectRatio}px`;
//                     } else {
//                         iframe.style.height = `${containerHeight}px`;
//                         iframe.style.width = `${containerHeight * videoAspectRatio}px`;
//                     }

//                     iframe.style.top = '50%';
//                     iframe.style.left = '50%';
//                     iframe.style.transform = 'translate(-50%, -50%)';
//                 };

//                 // Initial size set
//                 updateIframeSize();

//                 // Adjust the size on window resize
//                 window.addEventListener('resize', updateIframeSize);
//             }
//         });
//     }
// });



document.addEventListener('DOMContentLoaded', function () {
  // === 1) HOME: keep video/iframe sizing in hero ===
  if (document.body.classList.contains('home')) {
    const heroElements = document.querySelectorAll('.home .hero-static, .home .hero-video');

    heroElements.forEach(heroElement => {
      const heroStaticImage = heroElement.querySelector('.hero-static__image, .hero-video__image');
      const iframe = heroElement.querySelector('.hero-video__image .video-player, .hero-static__image .video-player');

      if (heroStaticImage && iframe) {
        const updateIframeSize = () => {
          const containerWidth = heroStaticImage.offsetWidth;
          const containerHeight = heroStaticImage.offsetHeight;
          const videoAspectRatio = 16 / 9;
          const containerAspectRatio = containerWidth / containerHeight;

          if (containerAspectRatio > videoAspectRatio) {
            iframe.style.width = `${containerWidth}px`;
            iframe.style.height = `${containerWidth / videoAspectRatio}px`;
          } else {
            iframe.style.height = `${containerHeight}px`;
            iframe.style.width = `${containerHeight * videoAspectRatio}px`;
          }

          iframe.style.top = '50%';
          iframe.style.left = '50%';
          iframe.style.transform = 'translate(-50%, -50%)';
          iframe.style.position = 'absolute';
        };

        updateIframeSize();
        window.addEventListener('resize', updateIframeSize);
      }
    });
  }

  // === 2) ALL PAGES: banner image scaling + overlay fading ===
  const banners = document.querySelectorAll('.hero-static__image');
  if (!banners.length) return;

  // Cache offsetTop once (layout doesn’t change for hero)
  const bannerData = Array.from(banners).map(el => ({
    el,
    img: el.querySelector('.banner-image'),
    top: el.getBoundingClientRect().top + window.scrollY,
    height: el.offsetHeight
  })).filter(b => b.img);

  let ticking = false;

  const update = () => {
    const y = window.scrollY;

    bannerData.forEach(({ el, img, top, height }) => {
      // Start effect once page has scrolled past the banner's top
      const scrolled = Math.max(0, y - top);

      // Tune these to taste
      const maxScale = 1.15;          // final scale
      const maxOverlay = 0.5;         // final overlay opacity
      const distance = Math.max(600, height); // distance over which the effect completes

      const t = Math.min(scrolled / distance, 1); // 0..1
      const scale = 1 + (maxScale - 1) * t;
      const overlay = maxOverlay * t;

      // Write CSS variables
      img.style.setProperty('--image-scale', String(scale));
      el.style.setProperty('--overlay-opacity', String(overlay));
    });

    ticking = false;
  };

  const onScrollOrResize = () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(update);
    }
  };

  // Initial paint
  update();

  // Listen
  window.addEventListener('scroll', onScrollOrResize, { passive: true });
  window.addEventListener('resize', onScrollOrResize);
});