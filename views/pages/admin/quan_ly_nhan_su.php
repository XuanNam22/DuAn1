<?php 
// 1. Nhận dữ liệu từ Controller
$lich = $data['lich']; 
$assignedStaff = $data['assignedStaff'];
$allStaff = $data['allStaff'];

// 2. Định nghĩa hiển thị vai trò
$roles = [
    'HDV_chinh' => 'Hướng Dẫn Viên Chính',
    'HDV_phu'   => 'Hướng Dẫn Viên Phụ',
    'TaiXe'     => 'Tài Xế',
    'HauCan'    => 'Hậu Cần'
];

$staffRoles = [
    'HDV_chinh' => 'HDV Chính',
    'HDV_phu'   => 'HDV Phụ',
    'TaiXe'     => 'Tài Xế',
    'HauCan'    => 'Hậu Cần',
];
// 3. Lọc danh sách nhân viên chưa phân bổ
$assignedIds = array_column($assignedStaff, 'nhan_vien_id');
$availableStaff = array_filter($allStaff, function($staff) use ($assignedIds) {
    return !in_array($staff['id'], $assignedIds); 
});
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Điều Hành Nhân Sự - <?= $lich['ten_tour'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard"> ADMIN PANEL</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    Xin chào, <strong><?= $_SESSION['user']['ho_ten'] ?? 'Admin' ?></strong>
                </span>
                <a href="<?= BASE_URL ?>routes/index.php?action=logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <div>
                <h4 class="mb-0 text-primary fw-bold">
                    <i class="fas fa-users-cog"></i> Phân Bổ Nhân Sự
                </h4>
                <p class="text-muted mb-0">
                    Tour: <strong><?= $lich['ten_tour'] ?></strong> 
                    (<?= date('d/m', strtotime($lich['ngay_khoi_hanh'])) ?> - <?= date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) ?>)
                </p>
            </div>
            <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-<?= ($_GET['msg']=='error') ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?php 
                    $msg = $_GET['msg'];
                    if($msg == 'assigned') echo "Phân bổ nhân sự thành công!";
                    elseif($msg == 'deleted') echo "Đã hủy phân bổ nhân sự!";
                    elseif($msg == 'error') echo "Có lỗi xảy ra hoặc dữ liệu không hợp lệ!";
                    else echo "Thao tác thành công!";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white fw-bold">
                        <i class="fas fa-plus-circle"></i> Thêm Nhân Sự Mới
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>routes/index.php?action=admin-schedule-staff-store" method="POST">
                            <input type="hidden" name="lich_id" value="<?= $lich['id'] ?>">

                            <div class="mb-3">
                                <label for="nhan_vien_id" class="form-label fw-bold">Chọn Nhân sự:</label>
                                <select name="nhan_vien_id" id="nhan_vien_id" class="form-select" required>
                                    <option value="">-- Chọn nhân viên --</option>
                                    <?php foreach ($availableStaff as $staff): ?>
                                        <option value="<?= $staff['id'] ?>">
                                            [<?= $staff['phan_loai_nhan_su'] ?>] <?= $staff['ho_ten'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(empty($availableStaff)): ?>
                                    <div class="form-text text-danger">Tất cả nhân viên đã được phân bổ hoặc không khả dụng.</div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="vai_tro" class="form-label fw-bold">Vai trò:</label>
                                <select name="vai_tro" id="vai_tro" class="form-select" required>
                                    <option value="">-- Chọn vai trò --</option>
                                    <?php foreach ($staffRoles as $key => $value): ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text text-danger small">
                                    <i class="fas fa-exclamation-circle"></i> Lưu ý: Mỗi chuyến đi chỉ được có <strong>01 HDV Chính</strong>.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold">
                                <i class="fas fa-check"></i> Xác nhận Phân bổ
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-dark fw-bold border-bottom">
                        <i class="fas fa-list"></i> Danh Sách Nhân Sự Đã Phân Bổ (<?= count($assignedStaff) ?>)
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vai trò</th>
                                        <th>Họ tên</th>
                                        <th>Liên hệ</th>
                                        <th class="text-end">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($assignedStaff)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Chưa có nhân sự nào được phân bổ cho chuyến này.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($assignedStaff as $staff): ?>
                                            <tr>
                                                <td>
                                                    <?php 
                                                        $badgeClass = 'bg-secondary';
                                                        if($staff['vai_tro'] == 'HDV_chinh') $badgeClass = 'bg-success';
                                                        elseif($staff['vai_tro'] == 'TaiXe') $badgeClass = 'bg-warning text-dark';
                                                        elseif($staff['vai_tro'] == 'HDV_phu') $badgeClass = 'bg-info text-dark';
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= $roles[$staff['vai_tro']] ?? $staff['vai_tro'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong><?= $staff['ho_ten'] ?></strong><br>
                                                    <small class="text-muted"><?= $staff['phan_loai_nhan_su'] ?></small>
                                                </td>
                                                <td><?= $staff['sdt'] ?></td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-schedule-staff-delete&id=<?= $staff['id'] ?>&lich_id=<?= $lich['id'] ?>" 
                                                       onclick="return confirm('Bạn chắc chắn muốn HỦY phân bổ nhân sự này?')"
                                                       class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>