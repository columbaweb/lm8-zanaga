document.addEventListener('DOMContentLoaded', function () {
    var teamHeads = document.querySelectorAll('.team-head');

    for (var i = 0; i < teamHeads.length; i++) {
        var teamHead = teamHeads[i];
        teamHead.addEventListener('click', handleClickEvent);
        teamHead.addEventListener('touchstart', handleTouchStart);
        teamHead.addEventListener('touchend', handleTouchEnd);
    }

    var touchStartY = 0;
    var touchEndY = 0;

    function handleTouchStart(event) {
        touchStartY = event.touches[0].clientY;
    }

    function handleTouchEnd(event) {
        touchEndY = event.changedTouches[0].clientY;
        var touchDistance = touchEndY - touchStartY;
        
        if (Math.abs(touchDistance) < 10) {
            handleClickEvent.call(this, event);
        }
    }

    function handleClickEvent(event) {
        var openElements = document.querySelectorAll('.open');
        for (var i = 0; i < openElements.length; i++) {
            openElements[i].classList.remove('open');
        }

        var teamFadeElements = document.querySelectorAll('.team-fade');
        for (var i = 0; i < teamFadeElements.length; i++) {
            teamFadeElements[i].classList.remove('team-fade');
        }

        var nextElement = this.nextElementSibling;
        if (nextElement && !nextElement.classList.contains('active-tab')) {
            var teamBioElements = document.querySelectorAll('.team-bio');
            for (var i = 0; i < teamBioElements.length; i++) {
                teamBioElements[i].classList.remove('active-tab');
            }

            this.classList.add('open');
            this.parentElement.classList.toggle('team-fade');
        }

        if (nextElement) {
            nextElement.classList.toggle('active-tab');
        }

        event.preventDefault();
    }

    // Add event listener for the ESC key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAllTabs();
        }
    });

    function closeAllTabs() {
        var openElements = document.querySelectorAll('.open');
        for (var i = 0; i < openElements.length; i++) {
            openElements[i].classList.remove('open');
        }

        var teamFadeElements = document.querySelectorAll('.team-fade');
        for (var i = 0; i < teamFadeElements.length; i++) {
            teamFadeElements[i].classList.remove('team-fade');
        }

        var activeTabs = document.querySelectorAll('.active-tab');
        for (var i = 0; i < activeTabs.length; i++) {
            activeTabs[i].classList.remove('active-tab');
        }
    }
});