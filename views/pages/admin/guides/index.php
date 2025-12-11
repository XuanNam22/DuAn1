<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Quản Lý Nhân Sự Hướng Dẫn Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-people-fill"></i> Danh Sách Nhân Sự</h3>
        <div>
            <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="<?= BASE_URL ?>routes/index.php?action=admin-guide-create" class="btn btn-success">
                <i class="bi bi-person-plus-fill"></i> Thêm Hồ Sơ
            </a>
        </div>
    </div>
    
    <div class="card shadow-sm mb-4 border-primary border-opacity-25">
        <div class="card-body bg-white py-3">
            <form action="<?= BASE_URL ?>routes/index.php" method="GET" class="row g-3">
                <input type="hidden" name="action" value="admin-guides">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold small text-muted">Từ khóa</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" class="form-control" 
                               placeholder="Tên, SĐT, Email, Ngôn ngữ..." 
                               value="<?= htmlspecialchars($filters['keyword']) ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Phân loại</label>
                    <select name="phan_loai" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="NoiDia" <?= $filters['phan_loai'] == 'NoiDia' ? 'selected' : '' ?>>Chuyên Tour Nội Địa</option>
                        <option value="QuocTe" <?= $filters['phan_loai'] == 'QuocTe' ? 'selected' : '' ?>>Chuyên Tour Quốc Tế</option>
                        <option value="CongTacVien" <?= $filters['phan_loai'] == 'CongTacVien' ? 'selected' : '' ?>>Cộng Tác Viên</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="SanSang" <?= $filters['trang_thai'] == 'SanSang' ? 'selected' : '' ?>>🟢 Sẵn sàng</option>
                        <option value="DangBan" <?= $filters['trang_thai'] == 'DangBan' ? 'selected' : '' ?>>🟠 Đang bận</option>
                        <option value="NghiPhep" <?= $filters['trang_thai'] == 'NghiPhep' ? 'selected' : '' ?>>🔴 Nghỉ phép/Dừng</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-funnel-fill"></i> Lọc Dữ Liệu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Thông tin cá nhân</th>
                        <th>Chuyên môn & Kỹ năng</th>
                        <th class="text-center">Đánh giá</th>
                        <th class="text-center">Trạng Thái</th>
                        <th class="text-center" width="200">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($guides)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Không tìm thấy nhân sự nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($guides as $g): ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $g['id'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= BASE_URL ?>public/img/hdv/<?= $g['anh_dai_dien'] ?>" 
                                         width="50" height="50" class="rounded-circle border me-3 shadow-sm" style="object-fit: cover;">
                                    <div>
                                        <strong class="text-primary fs-6"><?= $g['ho_ten'] ?></strong><br>
                                        <small class="text-muted"><i class="bi bi-telephone"></i> <?= $g['sdt'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    $badgeColor = match($g['phan_loai']) {
                                        'NoiDia' => 'info',
                                        'QuocTe' => 'warning',
                                        default => 'secondary'
                                    };
                                    $phanLoaiText = match($g['phan_loai']) {
                                        'NoiDia' => 'Nội Địa',
                                        'QuocTe' => 'Quốc Tế',
                                        default => 'CTV'
                                    };
                                ?>
                                <div class="mb-1">
                                    <span class="badge bg-<?= $badgeColor ?>"><?= $phanLoaiText ?></span>
                                </div>
                                <small class="text-dark d-block text-truncate" style="max-width: 250px;">
                                    <i class="bi bi-translate text-muted"></i> <?= $g['ngon_ngu'] ?? '---' ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-warning"><?= $g['diem_danh_gia'] ?> <i class="bi bi-star-fill"></i></span>
                            </td>
                            <td class="text-center">
                                <?php if($g['trang_thai'] == 'SanSang'): ?>
                                    <span class="badge bg-success bg-opacity-75 text-white border border-success">Sẵn sàng</span>
                                <?php elseif($g['trang_thai'] == 'DangBan'): ?>
                                    <span class="badge bg-warning bg-opacity-25 text-dark border border-warning">Đang bận</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-75 text-white">Nghỉ phép</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-guide-detail&id=<?= $g['id'] ?>" 
                                       class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-guide-edit&id=<?= $g['id'] ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Sửa thông tin">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-guide-delete&id=<?= $g['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Xóa hồ sơ này? Hành động không thể hoàn tác!')" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>