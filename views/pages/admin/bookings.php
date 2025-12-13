<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Đặt Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .badge { font-weight: 500; padding: 6px 10px; }
        .table-hover tbody tr:hover { background-color: #f8f9fa; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard">
                <i class="fas fa-user-shield me-2"></i>ADMIN PANEL
            </a>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white small">
                    Xin chào, <strong><?= $_SESSION['user']['ho_ten'] ?? 'Quản trị viên' ?></strong>
                </span>
                <a href="<?= BASE_URL ?>routes/index.php?action=logout" class="btn btn-outline-light btn-sm rounded-pill">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-primary fw-bold m-0"><i class="fas fa-box-open me-2"></i>Quản Lý Đơn Đặt Tour</h4>
            <div>
                <a href="<?= BASE_URL ?>routes/index.php?action=admin-booking-create" class="btn btn-success btn-sm shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Tạo Booking Mới
                </a>
                <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary btn-sm shadow-sm ms-1">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body bg-white rounded">
                <form action="<?= BASE_URL ?>routes/index.php" method="GET" class="row g-2 align-items-end">
                    <input type="hidden" name="action" value="admin-bookings">
                    
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Từ khóa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="keyword" class="form-control" 
                                   placeholder="Tên khách, SĐT, Mã đơn..." 
                                   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Tour</label>
                        <select name="tour_id" class="form-select">
                            <option value="">-- Tất cả Tour --</option>
                            <?php if(isset($tourList)): ?>
                                <?php foreach($tourList as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $t['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['ten_tour']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="ChoXacNhan" <?= (isset($_GET['status']) && $_GET['status'] == 'ChoXacNhan') ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="DaXacNhan" <?= (isset($_GET['status']) && $_GET['status'] == 'DaXacNhan') ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="DaThanhToan" <?= (isset($_GET['status']) && $_GET['status'] == 'DaThanhToan') ? 'selected' : '' ?>>Đã thanh toán</option>
                            <option value="Huy" <?= (isset($_GET['status']) && $_GET['status'] == 'Huy') ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-3">Mã #</th>
                                <th>Khách Hàng</th>
                                <th>Thông Tin Tour</th>
                                <th>Tổng Tiền</th>
                                <th>Ngày Đặt</th>
                                <th>Trạng Thái</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($bookings)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>
                                        Không tìm thấy đơn hàng nào phù hợp.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $b): ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">#<?= $b['id'] ?></td>
                                    
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($b['ten_nguoi_dat']) ?></div>
                                        <div class="small text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 0.8em;"></i><?= htmlspecialchars($b['sdt_lien_he']) ?></div>
                                    </td>

                                    <td style="max-width: 280px;">
                                        <div class="text-truncate fw-bold text-dark" title="<?= htmlspecialchars($b['ten_tour']) ?>">
                                            <?= htmlspecialchars($b['ten_tour']) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>KH: <?= date('d/m/Y', strtotime($b['ngay_khoi_hanh'])) ?>
                                        </div>
                                    </td>

                                    <td class="fw-bold text-danger">
                                        <?= number_format($b['tong_tien'], 0, ',', '.') ?> <span class="small text-muted">đ</span>
                                    </td>

                                    <td class="small text-secondary">
                                        <?= date('d/m/Y', strtotime($b['ngay_dat'])) ?><br>
                                        <?= date('H:i', strtotime($b['ngay_dat'])) ?>
                                    </td>

                                    <td>
                                        <?php 
                                            $badges = [
                                                'ChoXacNhan'  => ['bg-warning text-dark', 'Chờ xử lý'],
                                                'DaXacNhan'   => ['bg-info text-white', 'Đã xác nhận'],
                                                'DaThanhToan' => ['bg-success', 'Đã thanh toán'],
                                                'Huy'         => ['bg-secondary', 'Đã hủy']
                                            ];
                                            $stt = $badges[$b['trang_thai']] ?? ['bg-dark', $b['trang_thai']];
                                        ?>
                                        <span class="badge rounded-pill <?= $stt[0] ?>"><?= $stt[1] ?></span>
                                    </td>

                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm border" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li>
                                                    <a class="dropdown-item" href="<?= BASE_URL ?>routes/index.php?action=admin-booking-detail&id=<?= $b['id'] ?>">
                                                        <i class="fas fa-eye text-info me-2"></i> Xem chi tiết
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                
                                                <?php if ($b['trang_thai'] === 'ChoXacNhan'): ?>
                                                    <li>
                                                        <a class="dropdown-item text-success" href="<?= BASE_URL ?>routes/index.php?action=booking-status&id=<?= $b['id'] ?>&status=DaXacNhan">
                                                            <i class="fas fa-check me-2"></i> Xác nhận đơn
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= BASE_URL ?>routes/index.php?action=booking-status&id=<?= $b['id'] ?>&status=Huy" onclick="return confirm('Hủy đơn này?')">
                                                            <i class="fas fa-times me-2"></i> Hủy đơn
                                                        </a>
                                                    </li>
                                                <?php elseif ($b['trang_thai'] === 'DaXacNhan'): ?>
                                                    <li>
                                                        <a class="dropdown-item text-primary" href="<?= BASE_URL ?>routes/index.php?action=booking-status&id=<?= $b['id'] ?>&status=DaThanhToan">
                                                            <i class="fas fa-dollar-sign me-2"></i> Đã thu tiền
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" href="<?= BASE_URL ?>routes/index.php?action=booking-status&id=<?= $b['id'] ?>&status=Huy" onclick="return confirm('Khách hủy? Trả lại chỗ trống.')">
                                                            <i class="fas fa-times me-2"></i> Khách hủy
                                                        </a>
                                                    </li>
                                                <?php elseif ($b['trang_thai'] === 'Huy'): ?>
                                                    <li>
                                                        <a class="dropdown-item text-warning" href="<?= BASE_URL ?>routes/index.php?action=booking-status&id=<?= $b['id'] ?>&status=ChoXacNhan" onclick="return confirm('Khôi phục đơn hàng này?')">
                                                            <i class="fas fa-undo me-2"></i> Khôi phục
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
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
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>