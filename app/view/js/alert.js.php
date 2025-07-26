<?php
require_once $_SERVER['DOCUMENT_ROOT']. '/routes/routes.php';
route('htmlBuilder','');

use app\view\htmlBuilder as build;

echo build::minify('',
    "<script>
    function showCustomAlert(message, width = '80%', redirect = null) {
        
        var overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        overlay.style.zIndex = '1000';
    
        
        var customAlert = document.createElement('div');
        customAlert.style.position = 'fixed';
        customAlert.style.top = '20px';
        customAlert.style.left = '50%';
        customAlert.style.textAlign = 'center';
        customAlert.style.transform = 'translateX(-50%)';
        customAlert.style.width = width;
        customAlert.style.background = '#f8d7da';
        customAlert.style.color = '#721c24';
        customAlert.style.padding = '20px';
        customAlert.style.border = '1px solid #f5c6cb';
        customAlert.style.borderRadius = '5px';
        customAlert.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.1)';
        customAlert.style.zIndex = '1001';
    
        
        var contentContainer = document.createElement('div');
    
        
        var closeButton = document.createElement('span');
        closeButton.style.position = 'absolute';
        closeButton.style.top = '5px';
        closeButton.style.right = '5px';
        closeButton.style.cursor = 'pointer';
        closeButton.style.fontSize = '20px';
        closeButton.style.fontWeight = 'bold';
        closeButton.style.color = '#721c24';
        closeButton.innerHTML = '&times;';
        closeButton.onclick = function () {
            document.body.removeChild(overlay);
            document.body.removeChild(customAlert);
            if (redirect) {
                window.location.href = redirect;
            }
        };
    
       
        var messageElement = document.createElement('p');
        messageElement.innerHTML = message;
    
        
        contentContainer.appendChild(closeButton);
        contentContainer.appendChild(messageElement);
    
        
        customAlert.appendChild(contentContainer);
    
        
        document.body.appendChild(overlay);
        document.body.appendChild(customAlert);
    
        
        overlay.addEventListener('click', function () {
            document.body.removeChild(overlay);
            document.body.removeChild(customAlert);
            if (redirect) {
                window.location.href = redirect;
            }
        });
    } 
    
    </script>");
?>