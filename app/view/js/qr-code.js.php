<script>
    let scanner;
    const cameraContainer = document.getElementById('camera-container');
    const cameraPreview = document.getElementById('camera-preview');
    const detailsContainer = document.getElementById('details-container');
    const resultContainer = document.getElementById('result-container');
    const fileInput = document.getElementById('file-input');

    function startScanner() {
        scanner = new Instascan.Scanner({ video: cameraPreview });
        scanner.addListener('scan', function (content) {
            console.log('QR Code detected:', content);
            const extractedLink = extractLink(content);
            if (extractedLink) {
                detailsContainer.style.display = 'block';
                detailsContainer.dataset.link = extractedLink;
            } else {
                detailsContainer.style.display = 'none';
            }
            resultContainer.textContent = 'QR Code detected: ' + content;
            resultContainer.style.display = 'block';
        });

        Instascan.Camera.getCameras()
            .then(function (cameras) {
                const rearCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                if (rearCamera) {
                    scanner.start(rearCamera);
                } else {
                    console.error('No rear camera found.');
                }
            })
            .catch(function (err) {
                console.error(err);
            });
    }

    function extractLink(text) {
        // Simple check to extract the part that looks like a link
        const linkRegex = /(https?:\/\/[^\s]+)/i;
        const match = text.match(linkRegex);
        return match ? match[0] : null;
    }

    function captureImage() {
        // You can use the captured image or perform additional actions here
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const aspectRatio = cameraPreview.videoWidth / cameraPreview.videoHeight;
        const targetWidth = window.innerWidth; // Adjust as needed
        const targetHeight = targetWidth / aspectRatio;
        canvas.width = targetWidth;
        canvas.height = targetHeight;

        context.drawImage(cameraPreview, 0, 0, canvas.width, canvas.height);
        const capturedImage = canvas.toDataURL('image/png');
        console.log('Captured Image:', capturedImage);
    }

    function openLink() {
        const detectedLink = detailsContainer.dataset.link;
        window.open(detectedLink, '_self');
    }

    function readQRCodeFromImage(input) {
        const file = input.files[0];
        if (file) {
            const img = new Image();
            img.onload = function () {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                context.drawImage(img, 0, 0, img.width, img.height);
    
                // Get the data URL of the image and pass it to the scanner
                const imageDataURL = canvas.toDataURL();
                
                // Directly use the scanner on the image data
                scanner.scan(imageDataURL);
            };
            img.src = URL.createObjectURL(file);
        }
    }

    // Start the scanner when the page loads
    window.addEventListener('load', startScanner);
</script>