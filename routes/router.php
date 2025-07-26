<?php

define('ROOT', '/home/pefcarbo/');

function route($filename, $type = '')
{

    if ($type === 'css') {

        $dir = ROOT . 'app/view/css';

        $targetExtension = 'css.php'; // Replace with your target extension

        $files = scandir($dir);

        // Iterate through files in the directory
        foreach ($files as $file) {

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $searchFilename = $filename . '.css.' . $extension;

            //echo $searchFilename;
            //echo $file;

            // Check if the file has the target extension
            if ($searchFilename === $file) {

                $filePath = $dir . '/' . $file;


                // Include the file
                require_once $filePath;

                //echo $filePath;

                // If you want to stop searching after the first match, you can break out of the loop here
                break;
            }
        }
    } elseif ($type === 'js') {

        $dir = ROOT . 'app/view/js';

        $targetExtension = 'js.php'; // Replace with your target extension
        $altExtentension = 'js';

        $files = scandir($dir);

        // Iterate through files in the directory
        foreach ($files as $file) {

            $extension = pathinfo($file, PATHINFO_EXTENSION);

            $searchFilename = $filename . '.js.' . $extension;
            $altSearchFilename = $filename . '.' . $extension;

            //echo $searchFilename;
            //echo $file;

            // Check if the file has the target extension
            if ($searchFilename === $file || $altSearchFilename === $file) {

                $filePath = $dir . '/' . $file;


                // Include the file
                require_once $filePath;

                //echo $filePath;

                // If you want to stop searching after the first match, you can break out of the loop here
                break;
            }
        }

    } else {

        $dir1 = ROOT . 'app/view';
        $dir2 = ROOT . 'app/models';
        $dir3 = ROOT . 'app/controllers';
        $dir4 = ROOT . 'config';

        $dir = [$dir1, $dir2, $dir3, $dir4];

        foreach ($dir as $directory) {
            $files = scandir($directory);

            // Iterate through files in the directory
            foreach ($files as $file) {
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $searchFilename = $filename . '.' . $extension;

                // Check if the file has the target filename
                if ($searchFilename === $file) {
                    $filePath = $directory . '/' . $file; // Corrected line

                    // Include the file
                    require_once $filePath;

                    // If you want to stop searching after the first match, you can break out of the loop here
                    break;
                } else {
                    $result = false;
                }
            }
        }

        return $result;
    }
}

function showCssLocation($filename)
{

    $dir = ROOT . 'app/view/css';

    $files = scandir($dir);

    // Iterate through files in the directory
    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $css = 'css.php';

        $css = $filename . '.' . $css;

        // Check if the file has the target filename
        if ($css === $file) {
            $result = $dir . '/' . $file; // Corrected line
            break;
        } else {
            $result = false;
        }
    }
    return $result;
}

function showJsLocation($filename)
{

    $dir = ROOT . 'app/view/js';

    $files = scandir($dir);

    // Iterate through files in the directory
    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $css = 'js.php';

        $css = $filename . '.' . $css;

        // Check if the file has the target filename
        if ($css === $file) {
            $result = $dir . '/' . $file; // Corrected line
            break;
        } else {
            $result = false;
        }
    }
    return $result;
}

function console($message)
{
    echo '<script>console.log("' . $message . '");</script>';
}

function displayNotif($message)
{
    echo '
        <style>
            #noti{
                width: 100vw;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                justify-content: center;
                align-items: center;
                display: flex;
                z-index: 1000;
                background: rgb(0, 0, 0, 0.2);
            }
            
            .noti-container{
                width: 50vw;
                padding: 10px;
                background-color: orange;
                color: white;
                font-family: arial, serif;
                font-weight: bold;
                font-size: 20px;
                text-align: center;
            }
        </style>
        ';

    echo '
            <div id="noti">
                <div class="noti-container">' .
        $message
        . '</div>
            </div>
        ';

    echo "<script>
                setTimeout(function() {
                    document.getElementById('noti').style.display = 'none';
                }, 3000);
            </script>";
}

function alertJs($message)
{
    echo '<script>alert("' . $message . '");</script>';
}

function redirectJs($url)
{
    echo '<script>window.location="' . $url . '";</script>';
}

function alertRedirect($message, $url)
{
    alertJs($message);
    redirectJs($url);
}
?>