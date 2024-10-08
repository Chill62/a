 window.onbeforeunload = function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        };

        window.onload = function() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition, 10));
                sessionStorage.removeItem('scrollPosition');
            }
        };