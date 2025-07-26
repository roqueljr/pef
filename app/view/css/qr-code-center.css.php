<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }
    
    #camera-container {
        position: fixed;
        width: 100%;
        max-width: 100%;
        height: 250px;
        overflow: hidden;
        margin: 0 auto;
        top: 0;
        padding:0;
        transform: scaleX(-1); /* Flip horizontally for mirror effect */
    }

    #camera-preview {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        border: 2px solid #fff;
    }

    #details-container {
        margin-top: 10px;
        font-size: 18px;
        color: blue;
        text-decoration: underline;
        cursor: pointer;
        display: none;
    }
    
    #result-container {
        margin: 0 auto;
        width: 80%;
        font-size: 16px;
        font-family: Arial, sans-serif;
        overflow-wrap: break-word;
        margin-top: 10px;
    }

    #file-input {
        margin-top: 10px; 
    }
</style>
<script src="/app/view/js/instascan.min.js"></script>