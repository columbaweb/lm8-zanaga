document.addEventListener("DOMContentLoaded", () => {
	const initCounters = () => {
		const numberContainers = document.querySelectorAll(".statistics__number");

		numberContainers.forEach((container) => {
			// Get clean text and replace multiple spaces with a single space
			const text = container.textContent.trim().replace(/\s+/g, ' ');
			container.innerHTML = "";

			text.split("").forEach((char) => {
				const span = document.createElement("span");

				if (char === " ") {
					span.classList.add("space");
					span.innerHTML = "&nbsp;";
				} else {
					span.textContent = char;
				}

				container.appendChild(span);
			});

			const spans = container.querySelectorAll("span");

			gsap.fromTo(
				spans,
				{ autoAlpha: 0, x: "-20px" },
				{
					autoAlpha: 1,
					x: "0px",
					stagger: 0.05,
					duration: 0.5,
					scrollTrigger: {
						trigger: container,
						start: "top 90%",
						end: "bottom 60%",
						once: true,
					},
				}
			);
		});
	};

	initCounters();
});