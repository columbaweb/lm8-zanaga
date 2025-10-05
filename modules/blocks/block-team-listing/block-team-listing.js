document.addEventListener("DOMContentLoaded", () => {
  let overlay = document.querySelector(".lightbox-overlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "lightbox-overlay";
    document.body.appendChild(overlay);
  }

  const html = document.documentElement;

  document.querySelectorAll(".team-head[data-has-bio='true']").forEach(teamHead => {
    teamHead.style.cursor = "pointer";

    teamHead.addEventListener("click", () => {
      const wrapper = teamHead.closest(".team-item-wrapper");
      const bio = wrapper?.querySelector(".team-bio");
      if (!bio) return;

      // Clone the bio content
      const modal = bio.cloneNode(true);
      modal.classList.add("lightbox-content");

      // Create close button ABOVE modal
      const closeBtn = document.createElement("button");
      closeBtn.className = "lightbox-close";
      closeBtn.setAttribute("aria-label", "Close modal");
      closeBtn.innerHTML = `
        <svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none">
          <circle cx="18" cy="18" r="17.5" stroke="#CD136A" transform="rotate(-90 18 18)"/>
          <path fill="#0A2342" d="M18 9.724a.75.75 0 0 1 .75.75v6.776h6.776a.75.75 0 0 1 0 1.5H18.75v6.776a.75.75 0 0 1-1.5 0V18.75h-6.776a.75.75 0 0 1 0-1.5h6.776v-6.776a.75.75 0 0 1 .75-.75Z"/>
        </svg>
      `;

      // Clear previous overlay content and append in order
      overlay.innerHTML = '';
      overlay.appendChild(closeBtn); // Append first
      overlay.appendChild(modal);    // Append second
      overlay.classList.add("active");
      html.classList.add("lightbox-open");

      // Animate modal in
      gsap.fromTo(overlay, { autoAlpha: 0 }, { autoAlpha: 1, duration: 0.3 });
      gsap.fromTo(modal, { y: 50, scale: 0.95, autoAlpha: 0 }, {
        y: 0,
        scale: 1,
        autoAlpha: 1,
        duration: 0.4,
        ease: "power2.out"
      });

      // Close logic
      closeBtn.addEventListener("click", e => {
        e.stopPropagation();
        closeModal();
      });
    });
  });

  // Click outside to close
  overlay.addEventListener("click", e => {
    if (e.target === overlay) closeModal();
  });

  // Esc key to close
  document.addEventListener("keydown", e => {
    if (e.key === "Escape") closeModal();
  });

  // Close modal function
  function closeModal() {
    gsap.to(".lightbox-content", { scale: 0.95, autoAlpha: 0, duration: 0.3 });
    gsap.to(".lightbox-overlay", {
      autoAlpha: 0,
      duration: 0.3,
      onComplete: () => {
        overlay.classList.remove("active");
        html.classList.remove("lightbox-open");
      }
    });
  }
});