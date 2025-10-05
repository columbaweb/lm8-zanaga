gsap.registerPlugin(ScrollTrigger);

document.addEventListener("DOMContentLoaded", function () {
  const events = document.querySelectorAll(".event");

  events.forEach((event) => {
    const btnAddToCalendar = event.querySelector(".btn-add-to-calendar");
    const calendarLinks = event.querySelector(".calendar-links");
    let menuVisible = false; // Flag to track menu visibility

    // Function to show calendar links with animation
    function showCalendarLinks() {
      calendarLinks.style.display = "block"; // Ensure the element is displayed before animation
      gsap.fromTo(calendarLinks, 
        { opacity: 0, y: -5 }, 
        { opacity: 1, y: 8, duration: 0.3, ease: "power2.out" }
      );
      calendarLinks.classList.add("show");
      menuVisible = true; // Set the flag to true
    }

    // Function to hide calendar links with animation
    function hideCalendarLinks() {
      gsap.to(calendarLinks, {
        opacity: 0, 
        y: -5, 
        duration: 0.3, 
        ease: "power2.out", 
        onComplete: () => {
          calendarLinks.classList.remove("show");
          calendarLinks.style.display = "none"; // Ensure the display is set to none after animation
          menuVisible = false; // Set the flag to false
        }
      });
    }

    // Toggle calendar links on button click
    btnAddToCalendar.addEventListener("click", (event) => {
      event.stopPropagation(); // Prevent the click from bubbling up to the document
      if (!menuVisible) {
        showCalendarLinks();
      } else {
        hideCalendarLinks();
      }
    });

    // Prevent the click inside calendarLinks from bubbling up to the document
    calendarLinks.addEventListener("click", (event) => {
      event.stopPropagation();
    });

    // Hide calendar links when clicking outside
    document.addEventListener("click", (event) => {
      if (menuVisible && !calendarLinks.contains(event.target) && !btnAddToCalendar.contains(event.target)) {
        hideCalendarLinks();
      }
    });
  });
});