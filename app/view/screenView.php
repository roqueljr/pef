<script>
    // Function to check if the screen width corresponds to a mobile device
    function isMobile() {
        return window.matchMedia('(max-width: 768px)').matches;
    }
    
    // Function to store or update the device type in a cookie 
    function storeOrUpdateDeviceTypeInCookie() {
        try {
            var deviceType = isMobile() ? 'Mobile' : 'Desktop';
    
            // Store or update the device type in a cookie (valid for 1 day)
            document.cookie = 'deviceType=' + deviceType + '; max-age=' + 60 * 60 * 24;
    
            console.log('Device type stored or updated in cookie:', deviceType);
        } catch (error) {
            console.error('Error storing or updating device type:', error);
        }
    }
    
    // Attach the function to the window resize event
    window.addEventListener('resize', storeOrUpdateDeviceTypeInCookie);
    
    // Call the function when the DOM content is loaded
    document.addEventListener('DOMContentLoaded', function () {
        try {
            // Call the function on page load to set the initial device type
            storeOrUpdateDeviceTypeInCookie();
    
            // Additional logic or functions that need to run on page load
            // ...
        } catch (error) {
            console.error('Error during DOMContentLoaded:', error);
        }
    });
</script>