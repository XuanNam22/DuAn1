<?php
class BaseController {
    public function render($view, $data = []) {
        extract($data);
        $viewPath = PATH_VIEW . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "Lỗi: Không tìm thấy file view tại $viewPath";
        }
    }
}
?>