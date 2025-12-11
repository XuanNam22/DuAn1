<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">✏️ Cập Nhật Lịch Trình #<?= $lich['id'] ?></h4>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>routes/index.php?action=admin-update-lich&id=<?= $lich['id'] ?>" method="POST">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tour Du Lịch</label>
                                <select name="tour_id" class="form-select" required>
                                    <?php foreach ($tours as $t): ?>
                                        <option value="<?= $t['id'] ?>" <?= $t['id'] == $lich['tour_id'] ? 'selected' : '' ?>>
                                            <?= $t['ten_tour'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Hướng Dẫn Viên</label>
                                <select name="hdv_id" class="form-select">
                                    <option value="">-- Chưa phân công --</option>
                                    <?php foreach ($hdvs as $h): ?>
                                        <option value="<?= $h['id'] ?>" <?= $h['id'] == $lich['hdv_id'] ? 'selected' : '' ?>>
                                            <?= $h['ho_ten'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ngày Khởi Hành</label>
                                    <input type="date" name="ngay_khoi_hanh" class="form-control" 
                                           value="<?= $lich['ngay_khoi_hanh'] ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ngày Kết Thúc</label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control" 
                                           value="<?= $lich['ngay_ket_thuc'] ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Số Chỗ Tối Đa</label>
                                    <input type="number" name="so_cho_toi_da" class="form-control" 
                                           value="<?= $lich['so_cho_toi_da'] ?>" min="1" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng Thái</label>
                                    <select name="trang_thai" class="form-select">
                                        <?php 
                                            $states = ['NhanKhach' => 'Nhận Khách', 'DaDay' => 'Đã Đầy', 'DangDi' => 'Đang Đi', 'HoanThanh' => 'Hoàn Thành', 'Huy' => 'Hủy'];
                                            foreach ($states as $key => $label): 
                                        ?>
                                            <option value="<?= $key ?>" <?= $key == $lich['trang_thai'] ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">Quay Lại</a>
                                <button type="submit" class="btn btn-warning">Cập Nhật</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>