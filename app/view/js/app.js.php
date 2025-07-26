<!--desktop version only-->
<script>
    function toggleNav() {
        var sideNav = document.getElementById("mySidenav");
        var mainContent = document.getElementById("main");
        var titles = document.getElementsByClassName("title");  // Remove the dot before "title"

        // Toggle sideNav and mainContent styles
        if (sideNav.style.width === "250px") {
            sideNav.style.width = "0";
            // Restore the display property for elements with the class "title"
            for (var i = 0; i < titles.length; i++) {
                titles[i].style.display = 'block'; // Replace 'block' with your preferred display value
            }
        } else {
            sideNav.style.width = "250px";
            // Set the display property to 'none' for elements with the class "title"
            for (var i = 0; i < titles.length; i++) {
                titles[i].style.display = 'none';
            }
        }
    }
</script>

<!--mobile version only-->
<script>
let lastScrollPosition = 0; 
const accountContainer = document.querySelector('.account-container'); 
window.addEventListener('scroll', () => { 
    if (window.innerWidth < 600) { 
        const currentScrollPosition = window.scrollY; 
        
        if (currentScrollPosition < lastScrollPosition) {
            
            accountContainer.style.top = '0'; 
            
        } else { 
            accountContainer.style.top = `-${accountContainer.offsetHeight}px`;
        }
        lastScrollPosition = currentScrollPosition;
    }
});
</script>

<!--mobile version only-->
<script>
    let lastTouchTime = 0;
    
    document.addEventListener('touchstart', function (event) {
        const currentTime = new Date().getTime();
        const timeSinceLastTouch = currentTime - lastTouchTime;
    
        // Check if the device has a small screen (adjust as needed)
        if (window.innerWidth <= 600 && timeSinceLastTouch < 300) {
            toggleFullscreen();
            event.preventDefault(); // Prevent default touch behavior
        }
    
        lastTouchTime = currentTime;
    });

    function toggleFullscreen() {
        const docElement = document.documentElement;

        if (!document.fullscreenElement &&
            !document.mozFullScreenElement &&
            !document.webkitFullscreenElement &&
            !document.msFullscreenElement) {
            if (docElement.requestFullscreen) {
                docElement.requestFullscreen();
            } else if (docElement.mozRequestFullScreen) { /* Firefox */
                docElement.mozRequestFullScreen();
            } else if (docElement.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                docElement.webkitRequestFullscreen();
            } else if (docElement.msRequestFullscreen) { /* IE/Edge */
                docElement.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) { /* Firefox */
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE/Edge */
                document.msExitFullscreen();
            }
        }
    }
</script>

<!--desktop version only-->
<script>
// Get all image containers with the 'image-container' class
var imageContainers = document.querySelectorAll('.image-container');

// Loop through each image container
imageContainers.forEach(function (container) {
    // Add a unique event listener for mouseover on each image container
    container.addEventListener('mouseover', function (event) {
        // Get the corresponding info div from the current container
        var infoDiv = event.currentTarget.querySelector('.w-info');

        // Show the specific info div
        infoDiv.style.display = 'block';
    });

    // Add a unique event listener for mouseout on each image container
    container.addEventListener('mouseout', function (event) {
        // Get the corresponding info div from the current container
        var infoDiv = event.currentTarget.querySelector('.w-info');

        // Hide the specific info div when the cursor leaves the image container
        infoDiv.style.display = 'none';
    });
});
</script>


<script>
    // Function to show the scrollbar when hovering
    function showScrollbar(element) {
      var scrollbar = element.querySelector('::-webkit-scrollbar');
      scrollbar.style.opacity = 1; // Show the scrollbar
    }
    
    // Function to hide the scrollbar when not hovering
    function hideScrollbar(element) {
      var scrollbar = element.querySelector('::-webkit-scrollbar');
      scrollbar.style.opacity = 0; // Hide the scrollbar
    }
</script>

<script>
    let lastScrollTop = 0;
    const title = document.querySelector('.title');

    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > lastScrollTop) {
        // Scrolling down
        title.style.transform = 'translateY(-100%)';
      } else {
        // Scrolling up
        title.style.transform = 'translateY(0)';
      }

      lastScrollTop = scrollTop;
    });
 </script>

 
 
