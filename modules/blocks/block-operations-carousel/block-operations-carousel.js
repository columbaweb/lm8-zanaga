document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper('.operations-carousel', {
        loop: true,
        autoHeight: false,
        slidesPerView: 1,
        spaceBetween: 0,
        effect: "fade",
        fadeEffect: { crossFade: true },

        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },

        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });
});