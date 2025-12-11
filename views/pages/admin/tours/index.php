<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>🏞️ Danh Sách Tours</h3>
        <div>
            <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">Về Dashboard</a>
            <a href="<?= BASE_URL ?>routes/index.php?action=admin-tour-create" class="btn btn-primary">+ Thêm Tour Mới</a>
        </div>
    </div>
    
    <table class="table table-bordered bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên Tour</th>
                <th>Giá (Người lớn)</th>
                <th>Loại</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tours as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td>
                <td>
                    <img src="<?= BASE_URL ?>public/img/tours/<?= $t['anh_tour'] ?>" width="80" height="50" style="object-fit: cover;">
                </td>
                <td><?= $t['ten_tour'] ?></td>
                <td class="text-danger fw-bold"><?= number_format($t['gia_nguoi_lon']) ?>đ</td>
                <td><?= $t['ten_loai'] ?></td>
                <td>
                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-tour-edit&id=<?= $t['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-tour-delete&id=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tour này?')">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>