<script>
function selectAll() {
    const checkboxes = document.querySelectorAll('.checkbox');
    
    for (let i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = true;
    }
}

function deselectAll() {
        const checkboxes = document.querySelectorAll('.checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
        
function batchUpdate() {
    const approvalStatus = document.getElementById('approvalStatus').value;
    const checkboxes = document.querySelectorAll('.checkbox:checked');
    
    // Check if any checkboxes are selected
    if (checkboxes.length === 0) {
        alert('Please select at least one item.');
        return; // Stop further processing if no checkboxes are selected
    }
    
    // Create a form element dynamically
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href; // _self

    checkboxes.forEach(checkbox => {
        const checkboxId = checkbox.dataset.id;

        // Create an input element for each data you want to send
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'id[]'; // Use an array for multiple selected checkboxes
        inputId.value = checkboxId;

        const inputStatus = document.createElement('input');
        inputStatus.type = 'hidden';
        inputStatus.name = 'status[]'; // Use an array for multiple selected checkboxes
        inputStatus.value = approvalStatus;

        // Append the input elements to the form
        form.appendChild(inputId);
        form.appendChild(inputStatus);
    });

    // Append the form to the document and submit it
    document.body.appendChild(form);
    form.submit();

    // Remove the form from the document
    document.body.removeChild(form);
}
</script>

<script>
    function sendPostRequest(postData, customCallback = null, targetUrl = 'handle-post.php') {
        // If targetUrl is 'self', set it to the current URL
        const url = (targetUrl === 'self') ? window.location.href : targetUrl;

        // Default callback function
        const defaultCallback = function(error, response) {
            if (error) {
                console.error(error);
            } else {
                console.log('POST request successful');
                console.log('Server Response:', response);

                // Log the received data in the console
                console.log('Received Data:', response);
                
                // Update the DOM or perform other actions based on the response
            }
        };

        // Use the provided customCallback or the defaultCallback
        const callback = customCallback || defaultCallback;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: postData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Parse the response as plain text
        })
        .then(responseText => callback(null, responseText))
        .catch(error => callback(error, null));
    }
</script>