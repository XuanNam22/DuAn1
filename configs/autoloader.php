<?php
spl_autoload_register(function ($class) {
    // Mảng các thư mục chứa file class cần nạp
    $directories = [
        PATH_CONTROLLER, // Đường dẫn đến folder controllers (đã define trong env.php)
        PATH_MODEL,      // Đường dẫn đến folder models
        PATH_ROOT . 'configs/' // Đường dẫn đến folder configs
    ];

    // Duyệt qua từng thư mục để tìm file
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return; // Tìm thấy thì nạp và thoát luôn
        }
    }
});
?>