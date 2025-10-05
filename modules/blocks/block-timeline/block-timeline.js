document.addEventListener("DOMContentLoaded", function() {
    // Initialize GSAP and ScrollTrigger
    gsap.registerPlugin(ScrollTrigger);

    // Select all event blocks
    const eventBlocks = document.querySelectorAll('.timeline__event-block');

    // Loop through each event block
    eventBlocks.forEach((block) => {
        const divider = block.querySelector('.divider');

        // Create GSAP animation
        gsap.fromTo(divider, 
            { height: '0%' }, 
            { 
                height: '100%', 
                scrollTrigger: {
                    trigger: block,
                    start: 'top center', // Adjust these values based on your needs
                    end: 'bottom center',
                    scrub: true, // Allows the animation to be tied to scroll position
                    toggleActions: 'play reverse play reverse',
                    onEnter: () => block.classList.add('active'),
                    onLeave: () => block.classList.remove('active'),
                    onEnterBack: () => block.classList.add('active'),
                    onLeaveBack: () => block.classList.remove('active'),
                }
            }
        );
    });
});
