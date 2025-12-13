<?php
class AuthController extends BaseController
{
    public function showLoginForm()
    {
        if (isset($_SESSION['user'])) {
            $redirect = ($_SESSION['role'] == 'admin') ? 'admin-dashboard' : 'hdv-dashboard';
            header('Location: ' . BASE_URL . "routes/index.php?action=$redirect");
            exit;
        }
        $this->render('auth/login');
    }

    public function handleLogin()
    {
        $email = trim($_POST['email'] ?? '');
        $pass  = trim($_POST['password'] ?? '');

        $errors = [];
        
        if (empty($email)) $errors['email'] = "Chưa nhập email";
        if (empty($pass))  $errors['password'] = "Chưa nhập mật khẩu";

        if (empty($errors)) {
            $model = new BaseModel();

            // 1. Kiểm tra trong bảng ADMIN
            $sqlAdmin = "SELECT * FROM admin WHERE email = :e";
            $stmt = $model->conn->prepare($sqlAdmin);
            $stmt->execute(['e' => $email]);
            $admin = $stmt->fetch();

            if ($admin) {
                // SỬA: So sánh trực tiếp mật khẩu thô
                if ($admin['mat_khau'] == $pass) {
                    $_SESSION['user'] = $admin;
                    $_SESSION['role'] = 'admin';
                    header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard');
                    exit;
                }
            }

            // 2. Kiểm tra trong bảng HƯỚNG DẪN VIÊN
            $sqlHdv = "SELECT * FROM huong_dan_vien WHERE email = :e AND trang_thai != 'NghiPhep'";
            $stmt = $model->conn->prepare($sqlHdv);
            $stmt->execute(['e' => $email]);
            $hdv = $stmt->fetch();

            // SỬA: So sánh trực tiếp mật khẩu thô
            if ($hdv && $hdv['mat_khau'] == $pass) {
                $_SESSION['user'] = $hdv;
                $_SESSION['role'] = 'hdv';
                header('Location: ' . BASE_URL . 'routes/index.php?action=hdv-dashboard');
                exit;
            }

            $errors['login_failed'] = "Email hoặc mật khẩu không đúng!";
        }

        $this->render('auth/login', [
            'errors'   => $errors,
            'old_data' => ['email' => $email]
        ]);
    }

    public function logout()
    {
        if (session_id() === '') session_start();
        session_destroy();
        $_SESSION = [];
        header('Location: ' . BASE_URL . 'routes/index.php?action=login');
        exit;
    }
}