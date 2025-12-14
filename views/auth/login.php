<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center text-primary mb-4">LOGIN SYSTEM</h3>
        
        <?php if(isset($errors['login_failed'])): ?>
            <div class="alert alert-danger"><?= $errors['login_failed'] ?></div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>routes/index.php?action=check-login" method="POST">
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" placeholder="admin@gmail.com"
                       value="<?= isset($old_data['email']) ? htmlspecialchars($old_data['email']) : '' ?>">
                
                <?php if(isset($errors['email'])): ?>
                    <small class="text-danger"><?= $errors['email'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Mật khẩu:</label>
                <input type="password" name="password" class="form-control" placeholder="123456">
                
                <?php if(isset($errors['password'])): ?>
                    <small class="text-danger"><?= $errors['password'] ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
        </form>
    </div>

</body>
</html>