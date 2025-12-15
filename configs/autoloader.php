<?php
spl_autoload_register(function ($class) {
    $directories = [
        PATH_CONTROLLER, 
        PATH_MODEL,     
        PATH_ROOT . 'configs/' 
    ];


    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return; 
        }
    }
});
?>