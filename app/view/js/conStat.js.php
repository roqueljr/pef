<?php require $_SERVER['DOCUMENT_ROOT'] . '/app/view/css/conStat.css.php';?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      const bubbleMessage = document.getElementById('notificationMessage');
    
      // Check if the popup has already been shown
      const popupShown = localStorage.getItem('popupShown');
    
      if (!popupShown) {
        // Show the popup
        bubbleMessage.style.display = 'block';
    
        // Set the flag in localStorage to indicate that the popup has been shown
        localStorage.setItem('popupShown', 'true');
      }
    });

    function showNotification(message) {
      const notification = document.getElementById('customNotification');
      const notificationMessage = document.getElementById('notificationMessage');
    
      notificationMessage.textContent = message; 
      notification.classList.add('show');
    
      setTimeout(() => {
        notification.classList.remove('show');
      }, 3000); // Display notification for 3 seconds
    }
    
    function closeNotification() {
      const notification = document.getElementById('customNotification');
      notification.classList.remove('show');
    }
    
    // Check online status and display appropriate notification
    function updateOnlineStatus() {
      if (navigator.onLine) {
        showNotification('You are now online.');
      } else {
        showNotification('You are now offline.');
      }
    }
    
    // Initial check for online status
    updateOnlineStatus();
    
    // Listen for online/offline events
    window.addEventListener('online', updateOnlineStatus);
    window.addEventListener('offline', updateOnlineStatus);
</script>