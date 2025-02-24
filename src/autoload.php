<?php

spl_autoload_register(function ($class) {
    // Convert namespace separators to directory separators
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    
    // Set the base directory for the project
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR . '..';
    
    // Build the full path to the class file
    $file = $baseDir . DIRECTORY_SEPARATOR . $class . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require_once $file;
    }
});