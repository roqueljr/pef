<?php

function autoloader($dir = __DIR__ . '/models/')
{
    // Open the directory
    $files = scandir($dir);

    // Loop through the files and directories
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            // If it's a directory, recursively call the function
            if (is_dir($dir . '/' . $file)) {
                autoloader($dir . '/' . $file);
            } else {
                // If it's a PHP file, require it
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    //echo $dir.$file.'<br>';
                    require_once ($dir . '/' . $file);
                }
            }
        }
    }
}
autoloader();
?>

