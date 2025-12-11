<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Chuyến Đi & Điểm Danh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .timeline-item {
            border-left: 3px solid #ced4da;
            padding-left: 1rem;
            margin-left: 0.5rem;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #28a745; /* Màu xanh lá cây */
            border: 2px solid #fff;
        }
        .timeline-day {
            margin-left: -20px;
            margin-top: -8px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>routes/index.php?action=hdv-dashboard">
                <i class="bi bi-arrow-left"></i> Quay lại Danh sách Tour
            </a>
            <span class="navbar-text text-white fw-bold fs-5">
                Chuyến: <?= $tourInfo['ten_tour'] ?> (<?= date('d/m/Y', strtotime($tourInfo['ngay_khoi_hanh'])) ?>)
            </span>
        </div>
    </nav>

    <div class="container">
        
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> Đã lưu trạng thái điểm danh thành công!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane" type="button" role="tab" aria-controls="info-tab-pane" aria-selected="true">
                            <i class="bi bi-info-circle-fill"></i> Thông tin chung
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary-tab-pane" type="button" role="tab" aria-controls="itinerary-tab-pane" aria-selected="false">
                            <i class="bi bi-calendar-range-fill"></i> Lịch trình tour
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="passenger-tab" data-bs-toggle="tab" data-bs-target="#passenger-tab-pane" type="button" role="tab" aria-controls="passenger-tab-pane" aria-selected="false">
                            <i class="bi bi-people-fill"></i> Danh sách Khách & Điểm danh
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    
                    <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab" tabindex="0">
                        <h5 class="text-primary mb-3">Chi tiết chuyến đi</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Mã chuyến:</strong> <?= $tourInfo['id'] ?></li>
                                    <li class="list-group-item"><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($tourInfo['ngay_khoi_hanh'])) ?></li>
                                    <li class="list-group-item"><strong>Ngày kết thúc:</strong> <?= date('d/m/Y', strtotime($tourInfo['ngay_ket_thuc'])) ?></li>
                                    <li class="list-group-item"><strong>Địa điểm tập trung:</strong> <?= $tourInfo['diem_tap_trung'] ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Tổng số chỗ:</strong> <?= $tourInfo['so_cho_toi_da'] ?></li>
                                    <li class="list-group-item"><strong>Số chỗ đã đặt:</strong> <span class="badge bg-success fs-6"><?= $tourInfo['so_cho_da_dat'] ?></span></li>
                                    <li class="list-group-item"><strong>Trạng thái:</strong> <span class="badge bg-warning text-dark"><?= $tourInfo['trang_thai'] ?></span></li>
                                    <li class="list-group-item"><strong>Lưu ý chung:</strong> <?= $tourInfo['luu_y'] ?: 'Không có.' ?></li>
                                </ul>
                            </div>
                        </div>

                        <h5 class="text-primary mt-4 mb-3 border-top pt-3">Nhiệm vụ của Hướng dẫn viên</h5>
                        <p class="alert alert-info"></p>
                    </div>
                    
                    <div class="tab-pane fade" id="itinerary-tab-pane" role="tabpanel" aria-labelledby="itinerary-tab" tabindex="0">
                        <?php if (empty($itineraries)): ?>
                            <div class="alert alert-warning text-center">
                                Tour này chưa có lịch trình chi tiết theo ngày.
                            </div>
                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($itineraries as $item): ?>
                                <div class="timeline-item pb-4">
                                    <h6 class="timeline-day">Ngày <?= $item['ngay_thu'] ?></h6>
                                    <h5 class="mt-2 text-dark fw-bold"><?= $item['tieu_de'] ?></h5>
                                    <p class="text-muted small mb-1"><i class="bi bi-clock"></i> <?= $item['thoi_gian'] ?: 'Cả ngày' ?></p>
                                    <div class="text-break"><?= nl2br(htmlspecialchars($item['noi_dung_chi_tiet'])) ?></div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="passenger-tab-pane" role="tabpanel" aria-labelledby="passenger-tab" tabindex="0">
                        <form action="<?= BASE_URL ?>routes/index.php?action=hdv-check-in" method="POST"> 
                            <input type="hidden" name="lich_id" value="<?= $lichId ?>">
                            
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Họ Tên Khách</th>
                                            <th>Thông Tin (Giới tính/Loại)</th>
                                            <th>Trưởng Đoàn (Liên hệ)</th>
                                            <th class="text-center">Điểm Danh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(empty($passengers)): ?>
                                            <tr><td colspan="4" class="text-center py-4">Chưa có khách nào đặt tour này.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($passengers as $p): ?>
                                            <tr class="<?= $p['trang_thai_diem_danh'] ? 'table-success' : '' ?>">
                                                <td>
                                                    <strong><?= $p['ho_ten_khach'] ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= $p['gioi_tinh'] ?></span>
                                                    <span class="badge bg-info text-dark"><?= $p['loai_khach'] ?></span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">Người đặt:</small> <?= $p['ten_nguoi_dat'] ?><br>
                                                    <small class="text-muted">SĐT:</small> <strong><?= $p['sdt_lien_he'] ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="attendance[<?= $p['id_khach'] ?>]" 
                                                               value="1" 
                                                               style="transform: scale(1.5);"
                                                               <?= $p['trang_thai_diem_danh'] == 1 ? 'checked' : '' ?>>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= BASE_URL ?>routes/index.php?action=hdv-dashboard" class="btn btn-outline-secondary me-2">Hủy bỏ</a>
                                <button type="submit" class="btn btn-success px-4 fw-bold">LƯU ĐIỂM DANH</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Khởi tạo tab khi load trang
        document.addEventListener('DOMContentLoaded', function () {
            var triggerEl = document.querySelector('#myTab button[data-bs-target="#info-tab-pane"]')
            bootstrap.Tab.getInstance(triggerEl).show()
        });
    </script>
</body>
</html>