<script>
    const zoomImages = document.querySelectorAll(".zoom-image");

    zoomImages.forEach((zoomImage) => {
        zoomImage.addEventListener("click", () => {
            if (!document.fullscreenElement) {
                zoomImage.requestFullscreen().catch((err) => {
                    console.error("Error attempting to enable full-screen:", err);
                });
            } else {
                document.exitFullscreen();
            }
        });
    });
</script>