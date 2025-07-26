<script>
    function toggleUpArrow() {
        var scrollUpArrow = document.getElementById('scrollUpArrowDesktop');
        var timeoutId;

        function updateArrowVisibility() {
            if (window.scrollY > 0) {
                // Scrolled down
                scrollUpArrow.style.display = 'block';

                // Cancel the previous timeout (if any)
                clearTimeout(timeoutId);

                // Set a timeout to hide the arrow after 5 seconds
                timeoutId = setTimeout(function () {
                    scrollUpArrow.style.display = 'none';
                }, 5000);
            } else {
                // At the top
                scrollUpArrow.style.display = 'none';
            }
        }

        // Event listener for scrolling
        window.addEventListener('scroll', function () {
            requestAnimationFrame(updateArrowVisibility);
        });

        // Initial check
        updateArrowVisibility();

        // Additional click event for scrolling to the top
        scrollUpArrow.addEventListener('click', function () {
            scrollToTopDesktop();
        });
    }
    
    function scrollToTopDesktop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    toggleUpArrow();
</script>