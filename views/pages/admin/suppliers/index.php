<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Nhà Cung Cấp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>🤝 Đối Tác & Nhà Cung Cấp</h3>
        <div>
            <a href="index.php?action=admin-dashboard" class="btn btn-secondary">Về Dashboard</a>
            <a href="index.php?action=admin-supplier-create" class="btn btn-primary">+ Thêm NCC Mới</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="25%">Tên Nhà Cung Cấp</th>
                        <th width="20%">Dịch vụ</th>
                        <th width="20%">Liên hệ (SĐT/Email)</th>
                        <th>Địa chỉ</th>
                        <th width="15%">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($suppliers)): ?>
                        <tr><td colspan="6" class="text-center py-3">Chưa có nhà cung cấp nào.</td></tr>
                    <?php else: ?>
                        <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td><?= $s['id'] ?></td>
                            <td class="fw-bold text-primary"><?= $s['ten_ncc'] ?></td>
                            <td><span class="badge bg-info text-dark"><?= $s['dich_vu'] ?></span></td>
                            <td>
                                <div><small>📞 <?= $s['sdt'] ?></small></div>
                                <div><small>✉️ <?= $s['email'] ?></small></div>
                            </td>
                            <td><?= $s['dia_chi'] ?></td>
                            <td>
                                <a href="index.php?action=admin-supplier-edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="index.php?action=admin-supplier-delete&id=<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa đối tác này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>