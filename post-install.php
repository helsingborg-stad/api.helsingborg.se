<?php
    $muPluginsDir = __DIR__ . '/wp-content/mu-plugins/';
    $moveTo = $muPluginsDir . 'must_use_loader.php';
    $muLoaderPath = $muPluginsDir.'must-use-loader/must_use_loader.php';
    
    if(file_exists($muLoaderPath)) {
        rename($muLoaderPath, $moveTo);
    }
