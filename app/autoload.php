<?php

spl_autoload_register(function ($class) {
    $baseNamespace = 'App\\';
    $baseDir = dirname(__DIR__, 1) . '/app/';

    if (strpos($class, $baseNamespace) === 0) {
        $relativeClass = substr($class, strlen($baseNamespace));
        $relativePath = str_replace('\\', '/', $relativeClass);
        $file = $baseDir . $relativePath . '.php';

        if (!class_exists($class, false)) {
            if (file_exists($file)) {
                //echo $file . "<br>";
                require_once $file;
            }
        }
    }
});