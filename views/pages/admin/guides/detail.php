<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Hồ Sơ Nhân Sự: <?= htmlspecialchars($guide['ho_ten']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-person-lines-fill"></i> Hồ Sơ Nhân Sự</h3>
        <a href="<?= BASE_URL ?>routes/index.php?action=admin-guides" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center bg-white">
                    <img src="<?= BASE_URL ?>public/img/hdv/<?= $guide['anh_dai_dien'] ?>" 
                         class="rounded-circle img-thumbnail mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 class="mb-0"><?= $guide['ho_ten'] ?></h4>
                    <p class="text-muted"><?= $guide['email'] ?></p>
                    
                    <?php 
                        $badgeColor = match($guide['phan_loai']) {
                            'NoiDia' => 'info',
                            'QuocTe' => 'warning',
                            default => 'secondary'
                        };
                        $phanLoaiText = match($guide['phan_loai']) {
                            'NoiDia' => 'HDV Nội Địa',
                            'QuocTe' => 'HDV Quốc Tế',
                            default => 'Cộng Tác Viên'
                        };
                    ?>
                    <span class="badge bg-<?= $badgeColor ?> fs-6"><?= $phanLoaiText ?></span>
                    
                    <div class="mt-3 text-start">
                        <hr>
                        <p><strong><i class="bi bi-telephone"></i> SĐT:</strong> <?= $guide['sdt'] ?></p>
                        <p><strong><i class="bi bi-calendar-event"></i> Ngày sinh:</strong> <?= $guide['ngay_sinh'] ?? 'Chưa cập nhật' ?></p>
                        <p><strong><i class="bi bi-heart-pulse"></i> Sức khỏe:</strong> <?= $guide['suc_khoe'] ?></p>
                        <p><strong><i class="bi bi-translate"></i> Ngôn ngữ:</strong> <?= $guide['ngon_ngu'] ?></p>
                        <p><strong><i class="bi bi-star-fill text-warning"></i> Đánh giá:</strong> <?= $guide['diem_danh_gia'] ?>/5.0</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Chứng chỉ & Bằng cấp</div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($guide['chung_chi'] ?? 'Chưa có thông tin')) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">Giới thiệu & Kinh nghiệm</div>
                <div class="card-body">
                    <?= nl2br(htmlspecialchars($guide['kinh_nghiem'])) ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history"></i> Lịch Sử Dẫn Tour</span>
                    <span class="badge bg-light text-dark"><?= count($history) ?> chuyến</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Tour</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($history)): ?>
                                <tr><td colspan="3" class="text-center py-3">Chưa có lịch sử dẫn tour nào.</td></tr>
                            <?php else: ?>
                                <?php foreach ($history as $h): ?>
                                <tr>
                                    <td class="fw-bold"><?= $h['ten_tour'] ?></td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($h['ngay_khoi_hanh'])) ?> 
                                        <i class="bi bi-arrow-right-short"></i> 
                                        <?= date('d/m/Y', strtotime($h['ngay_ket_thuc'])) ?>
                                    </td>
                                    <td>
                                        <?php if($h['trang_thai'] == 'HoanThanh'): ?>
                                            <span class="badge bg-success">Đã hoàn thành</span>
                                        <?php elseif($h['trang_thai'] == 'DangDi'): ?>
                                            <span class="badge bg-warning text-dark">Đang đi</span>
                                        <?php elseif($h['trang_thai'] == 'Huy'): ?>
                                            <span class="badge bg-danger">Đã hủy</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Sắp tới</span>
                                        <?php endif; ?>
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
</body>
</html>