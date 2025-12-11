<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="text-white mb-0">Chi tiết đơn hàng #<?= $booking['id'] ?></h5>
                </div>
                <div class="card-body">
                    <p><strong>Khách hàng:</strong> <?= $booking['ten_nguoi_dat'] ?></p>
                    <p><strong>SĐT:</strong> <?= $booking['sdt_lien_he'] ?></p>
                    <p><strong>Email:</strong> <?= $booking['email_lien_he'] ?></p>
                    <p><strong>Tour:</strong> <?= $booking['ten_tour'] ?></p>
                    <p><strong>Ngày khởi hành:</strong> <?= date('d/m/Y H:i', strtotime($booking['ngay_khoi_hanh'])) ?></p>
                    <p><strong>Số khách:</strong> <?= $booking['so_nguoi_lon'] ?> Lớn - <?= $booking['so_tre_em'] ?> Trẻ em</p>
                    <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?= number_format($booking['tong_tien']) ?> VNĐ</span></p>
                    <p><strong>Ghi chú (Yêu cầu đặc biệt):</strong> <?= $booking['ghi_chu'] ?></p>
                    
                    <hr>
                    <p><strong>Trạng thái hiện tại:</strong> <span class="badge bg-warning text-dark"><?= $booking['trang_thai'] ?></span></p>
                    <div>
                        <a href="index.php?action=booking-status&id=<?= $booking['id'] ?>&status=DaXacNhan" class="btn btn-sm btn-info">Xác nhận cọc</a>
                        <a href="index.php?action=booking-status&id=<?= $booking['id'] ?>&status=DaThanhToan" class="btn btn-sm btn-success">Hoàn tất</a>
                        <a href="index.php?action=booking-status&id=<?= $booking['id'] ?>&status=Huy" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn hủy?')">Hủy đơn</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="text-white mb-0">Lịch sử xử lý</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($history)): ?>
                            <li class="list-group-item text-center">Chưa có lịch sử thay đổi</li>
                        <?php else: ?>
                            <?php foreach ($history as $log): ?>
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-muted"><?= date('H:i d/m/Y', strtotime($log['thoi_gian'])) ?></small>
                                    <small class="fw-bold"><?= $log['nguoi_thay_doi'] ?></small>
                                </div>
                                <p class="mb-1" style="font-size: 0.9rem;">
                                    <?= $log['trang_thai_cu'] ?> <i class="fa fa-arrow-right"></i> <strong><?= $log['trang_thai_moi'] ?></strong>
                                </p>
                                <small class="text-muted fst-italic"><?= $log['ghi_chu_thay_doi'] ?></small>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="index.php?action=admin-bookings" class="btn btn-secondary">Quay lại danh sách</a>
    </div>
</div>
</body>
</html>
