<script>
   // Reusable function to show/hide scroll buttons based on scroll direction and position
    function toggleScrollButtons() {
        var scrollUpButton = document.getElementById('scrollUpButton');
        var scrollDownButton = document.getElementById('scrollDownButton');
        var lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
        function hideButtonsAfterDelay() {
            setTimeout(function () {
                scrollUpButton.style.display = 'none';
                scrollDownButton.style.display = 'none';
            }, 5000); // 3000 milliseconds (3 seconds)
        }
    
        window.addEventListener('scroll', function () {
            var currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
            if (currentScrollTop > lastScrollTop) {
                // Scrolling down
                scrollUpButton.style.display = 'none';
                scrollDownButton.style.display = currentScrollTop < document.body.scrollHeight - window.innerHeight ? 'block' : 'none';
            } else {
                // Scrolling up
                scrollUpButton.style.display = currentScrollTop > 0 ? 'block' : 'none';
                scrollDownButton.style.display = 'none';
            }
    
            lastScrollTop = currentScrollTop;
    
            // Reset the timer when scrolling occurs
            clearTimeout(hideButtonsAfterDelay);
            hideButtonsAfterDelay();
        });
    
        // Initial call to hide buttons after 3 seconds
        hideButtonsAfterDelay();
    }
    
    // Reusable function to scroll to the top
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    // Reusable function to scroll to the bottom
    function scrollToBottom() {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }
    
    // Call the toggleScrollButtons function to initialize the behavior
    toggleScrollButtons();
</script>