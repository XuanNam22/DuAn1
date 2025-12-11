<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch Làm Việc HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">HDV DASHBOARD</a>
            <div class="d-flex">
                <span class="navbar-text me-3 text-white">HDV: <?= $_SESSION['user']['ho_ten'] ?></span>
                <a href="<?= BASE_URL ?>routes/index.php?action=logout" class="btn btn-outline-light btn-sm">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h4 class="mb-4 text-success border-bottom pb-2">📅 LỊCH DẪN TOUR CỦA TÔI</h4>
        
        <?php if (empty($myTours)): ?>
            <div class="alert alert-info text-center">
                Bạn chưa được phân công
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($myTours as $tour): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold"><?= $tour['ten_tour'] ?></h5>
                            <span class="badge bg-secondary mb-2"><?= $tour['so_ngay'] ?> ngày</span>
                            
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><strong>🛫 Khởi hành:</strong> <?= date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) ?></li>
                                <li class="mb-2"><strong>🛬 Kết thúc:</strong> <?= date('d/m/Y', strtotime($tour['ngay_ket_thuc'])) ?></li>
                                <li class="mb-2"><strong>👥 Số khách:</strong> <?= $tour['so_cho_da_dat'] ?>/<?= $tour['so_cho_toi_da'] ?></li>
                            </ul>
                            
                            <div class="d-grid mt-4">
                                <a href="<?= BASE_URL ?>routes/index.php?action=hdv-tour-detail&id=<?= $tour['id'] ?>" class="btn btn-success">
                                    Xem Danh Sách & Điểm Danh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>