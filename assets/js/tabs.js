document.querySelectorAll(".tab-group").forEach((tableWrap) => {
    const tabs = tableWrap.querySelector("ul.tabs");

    if (tabs) {
        let active, content, links = tabs.querySelectorAll("a");

        for (const link of links) {
            if (link.href === location.hash) {
                active = link;
                break;
            }
        }
        active = active || links[0];
        active.classList.add("active");
        active.parentElement.classList.add("has-active");

        content = tableWrap.querySelector(active.hash);

        links.forEach((link) => {
            if (link !== active) {
                tableWrap.querySelector(link.hash).style.display = "none";
            }
        });

        content.classList.add("active");

        tabs.addEventListener("click", function (event) {
            if (event.target.tagName === "A") {
                active.classList.remove("active");
                active.parentElement.classList.remove("has-active");
                content.style.display = "none";
                content.classList.remove("active");

                active = event.target;
                content = tableWrap.querySelector(active.hash);

                active.classList.add("active");
                active.parentElement.classList.add("has-active");
                content.style.display = "";
                content.classList.add("active");

                //equalizeRowHeights("equal");  is it used?

                event.preventDefault();
            }
        });
    }

    const url = document.URL;
    const hash = url.substring(url.indexOf("#"));

    tableWrap.querySelectorAll("ul.tabs a").forEach(function (link) {
        if (hash === link.getAttribute("href")) {
            link.click();
        }

        link.addEventListener("click", function (event) {
            event.preventDefault();
            const scrollTop = window.scrollY;
            location.hash = link.getAttribute("href");
            window.scrollTo(0, scrollTop);
        });
    });
});


// first accordion open
document.addEventListener('DOMContentLoaded', function() {
	const tabGroups = document.querySelectorAll('.tab-group');
  
	tabGroups.forEach(tabGroup => {
		if (tabGroup.classList.contains('first-active')) {
			const firstPanelCheckbox = tabGroup.querySelector('input[type="checkbox"]');
			firstPanelCheckbox.checked = true;
		}
	});
});
