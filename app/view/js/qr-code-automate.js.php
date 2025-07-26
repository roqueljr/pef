<script>
    let scanner;
    const cameraPreview = document.getElementById('camera-preview');
    const detailsContainer = document.getElementById('details-container');
    const resultContainer = document.getElementById('result-container');

    function startScanner() {
        scanner = new Instascan.Scanner({ video: cameraPreview });
        scanner.addListener('scan', function (content) {
            console.log('QR Code detected:', content);
            const extractedLink = extractLink(content);
            
            if (extractedLink) {
                window.open(extractedLink, '_self');
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
        
        const linkRegex = /(https?:\/\/[^\s]+)/i;
        const match = text.match(linkRegex);
        return match ? match[0] : null;
    }

    window.addEventListener('load', startScanner);
</script>