<?php
if (!function_exists('debug')) {
    function debug($var) {
        echo "<div style='background: #f8f9fa; border: 1px solid #ddd; padding: 15px; margin: 10px; border-radius: 5px;'>";
        echo "<pre style='color: #d63384; font-weight: bold;'>";
        print_r($var);
        echo "</pre>";
        echo "</div>";
        die(); // Dừng chương trình ngay lập tức để xem lỗi
    }
}
?>