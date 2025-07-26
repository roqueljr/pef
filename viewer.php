<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/routes/routes.php';
route('photos');
route('session');
route('authentication');

use app\models\photo;
use app\models\session;
use app\models\login;

session::start();
login::loginStatus();

if (isset($_REQUEST['qr']) && isset($_REQUEST['s'])) {
    $qrCode = base64_decode($_REQUEST['qr']);
    $site = base64_decode($_REQUEST['s']);
}

$photos = photo::getImageByQrcodeIdMultiple($qrCode, $site) ?? false;

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/assets/PEF_LOGO.png" type="png">
    <title>Document</title>
    <style>
    body {
        justify-content: flex-start;
        align-items: center;
        display: flex;
        height: auto;
        width: auto;
        box-sizing: border-box;
        flex-wrap: wrap;
    }

    .image-container,
    img {
        width: 320px;
        height: 480px;
        position: relative;
        cursor: pointer;
    }

    .image-container {
        margin: 20px 10px;
    }

    .p-info {
        position: absolute;
        background: rgb(0, 0, 0, 0.7);
        bottom: 0;
        color: white;
        text-align: center;
        width: 300px;
        padding: 10px;
        font-size: 14px;
        font-family: arial, serif;
        line-height: 1.5;
    }

    h1 {
        width: 500px;
        text-align: center;
        margin: 20% auto auto;
        font-family: arial, serif;
        font-weight: 600;
        color: red;
    }

    #back-head {
        position: fixed;
        top: 0;
        left: 20px;
        z-index: 999;
    }
    </style>
</head>

<body>
    <?php if ($photos): ?>
        <button id="back-head" onclick="history.back()">Back</button>
        <?php foreach ($photos as $photo): ?>
            <div class="image-container">
                <img class="fullscreen-image" src="<?php echo $photo[0]; ?>" alt="<?php echo $photo[2]; ?>" />
                <div class="p-info">
                    <span id="sp"><?php echo $photo[2] . ' •<i> ' . $photo[3] . '</i> • ' . $photo[4]; ?></span><br>
                    QRCODE# - <?php echo $qrCode; ?><br>
                    Upload date: <?php echo $photo[1]; ?>
                </div>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <h1>
            No Image Found!<br>
            <button onclick="history.back();">Back</button>
        </h1>

    <?php endif ?>
</body>

</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var fullscreenImages = document.querySelectorAll('.fullscreen-image');

    fullscreenImages.forEach(function(image) {
        image.addEventListener('click', function() {
            toggleFullscreen(image);
        });

        image.addEventListener('dblclick', function() {
            exitFullscreen();
        });
    });

    function toggleFullscreen(element) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            /* Firefox */
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            /* Chrome, Safari and Opera */
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            /* IE/Edge */
            element.msRequestFullscreen();
        }
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            /* Firefox */
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            /* Chrome, Safari and Opera */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE/Edge */
            document.msExitFullscreen();
        }
    }
});
</script>