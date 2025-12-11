<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Cập Nhật Hồ Sơ HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4 mb-5">
        <div class="card shadow col-md-10 mx-auto">
            <div class="card-header bg-warning text-dark fw-bold">
                ✏️ Cập Nhật Hồ Sơ: <?= htmlspecialchars($guide['ho_ten']) ?>
            </div>
            
            <div class="card-body">
                <form action="<?= BASE_URL ?>routes/index.php?action=admin-guide-update&id=<?= $guide['id'] ?>" method="POST" enctype="multipart/form-data">

                    <h5 class="text-warning text-dark mb-3 border-bottom pb-2">1. Thông tin cá nhân</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Họ và Tên</label>
                            <input type="text" name="ho_ten" class="form-control" required value="<?= htmlspecialchars($guide['ho_ten']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" value="<?= $guide['ngay_sinh'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Số điện thoại</label>
                            <input type="text" name="sdt" class="form-control" required value="<?= htmlspecialchars($guide['sdt']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Sức khỏe</label>
                            <input type="text" name="suc_khoe" class="form-control" value="<?= htmlspecialchars($guide['suc_khoe']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email (Tài khoản)</label>
                            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($guide['email']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Ảnh Đại Diện</label>
                            <input type="file" name="anh_dai_dien" class="form-control">
                            <div class="mt-2">
                                <img src="<?= BASE_URL ?>public/img/hdv/<?= $guide['anh_dai_dien'] ?>" width="80" class="img-thumbnail rounded-circle">
                            </div>
                        </div>
                    </div>

                    <h5 class="text-warning text-dark mt-4 mb-3 border-bottom pb-2">2. Hồ sơ chuyên môn & Trạng thái</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Phân Loại</label>
                            <select name="phan_loai" class="form-select">
                                <option value="NoiDia" <?= $guide['phan_loai'] == 'NoiDia' ? 'selected' : '' ?>>Chuyên Tour Nội Địa</option>
                                <option value="QuocTe" <?= $guide['phan_loai'] == 'QuocTe' ? 'selected' : '' ?>>Chuyên Tour Quốc Tế</option>
                                <option value="CongTacVien" <?= $guide['phan_loai'] == 'CongTacVien' ? 'selected' : '' ?>>Cộng Tác Viên</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Trạng thái công việc</label>
                            <select name="trang_thai" class="form-select">
                                <option value="SanSang" <?= $guide['trang_thai'] == 'SanSang' ? 'selected' : '' ?>>Sẵn sàng</option>
                                <option value="DangBan" <?= $guide['trang_thai'] == 'DangBan' ? 'selected' : '' ?>>Đang bận / Có lịch</option>
                                <option value="NghiPhep" <?= $guide['trang_thai'] == 'NghiPhep' ? 'selected' : '' ?>>Nghỉ phép / Khóa</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Ngôn ngữ</label>
                            <input type="text" name="ngon_ngu" class="form-control" value="<?= htmlspecialchars($guide['ngon_ngu']) ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Chứng chỉ</label>
                            <textarea name="chung_chi" class="form-control" rows="2"><?= htmlspecialchars($guide['chung_chi']) ?></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Kinh nghiệm & Giới thiệu</label>
                            <textarea name="kinh_nghiem" class="form-control" rows="4"><?= htmlspecialchars($guide['kinh_nghiem']) ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= BASE_URL ?>routes/index.php?action=admin-guides" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-warning fw-bold">Cập Nhật Hồ Sơ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>