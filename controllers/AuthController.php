<?php
class AuthController extends BaseController
{

    // Hiển thị form login
    public function showLoginForm()
    {
        if (isset($_SESSION['user'])) {
            $redirect = ($_SESSION['role'] == 'admin') ? 'admin-dashboard' : 'hdv-dashboard';
            header('Location: ' . BASE_URL . "routes/index.php?action=$redirect");
            exit;
        }
        $this->render('auth/login');
    }

    // Xử lý kiểm tra đăng nhập (Đã Validate chi tiết)
    // Xử lý kiểm tra đăng nhập (Đã thêm validate độ dài)
    public function handleLogin()
    {
        // 1. Lấy dữ liệu và loại bỏ khoảng trắng thừa
        $email = trim($_POST['email'] ?? '');
        $pass  = trim($_POST['password'] ?? '');

        $errors = [];
        $old_data = ['email' => $email];

        // 2. VALIDATE CƠ BẢN (Không để trống)
        if (empty($email)) {
            $errors['email'] = "Bạn chưa nhập email";
        } elseif (strlen($email) < 3 || strlen($email) > 30) {
            // Validate độ dài Email (3 - 30 ký tự)
            $errors['email'] = "Email phải từ 3 đến 30 ký tự";
        }

        if (empty($pass)) {
            $errors['password'] = "Bạn chưa nhập mật khẩu";
        } elseif (strlen($pass) < 6 || strlen($pass) > 10) {
            // Validate độ dài Mật khẩu (6 - 10 ký tự)
            $errors['password'] = "Mật khẩu phải từ 6 đến 10 ký tự";
        }

        // 3. Nếu không có lỗi validate form thì mới check DB
        if (empty($errors)) {
            $model = new BaseModel();

            // 3.1 Check Admin
            $sqlAdmin = "SELECT * FROM admin WHERE email = :e AND mat_khau = :p";
            $stmt = $model->conn->prepare($sqlAdmin);
            $stmt->execute(['e' => $email, 'p' => $pass]);
            $admin = $stmt->fetch();

            if ($admin) {
                $_SESSION['user'] = $admin;
                $_SESSION['role'] = 'admin';
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard');
                exit;
            }

            // 3.2 Check Hướng Dẫn Viên
            $sqlHdv = "SELECT * FROM huong_dan_vien WHERE email = :e AND mat_khau = :p";
            $stmt = $model->conn->prepare($sqlHdv);
            $stmt->execute(['e' => $email, 'p' => $pass]);
            $hdv = $stmt->fetch();

            if ($hdv) {
                $_SESSION['user'] = $hdv;
                $_SESSION['role'] = 'hdv';
                header('Location: ' . BASE_URL . 'routes/index.php?action=hdv-dashboard');
                exit;
            }

            // 3.3 Sai tài khoản hoặc mật khẩu
            $errors['login_failed'] = "Email hoặc mật khẩu không đúng!";
        }

        // 4. Render view kèm lỗi
        $this->render('auth/login', [
            'errors'   => $errors,
            'old_data' => $old_data
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
