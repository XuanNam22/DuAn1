<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Hướng Dẫn Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="card shadow col-md-10 mx-auto">
        <div class="card-header bg-success text-white fw-bold">Thêm Hồ Sơ Nhân Sự Mới</div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>routes/index.php?action=admin-guide-store" method="POST" enctype="multipart/form-data">
                
                <h5 class="text-success mb-3 border-bottom pb-2">1. Thông tin cá nhân</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Ngày Sinh</label>
                        <input type="date" name="ngay_sinh" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Số Điện Thoại <span class="text-danger">*</span></label>
                        <input type="text" name="sdt" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Tình trạng sức khỏe</label>
                        <input type="text" name="suc_khoe" class="form-control" placeholder="Tốt, Say xe, Dị ứng...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email (Tài khoản) <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Mật Khẩu <span class="text-danger">*</span></label>
                        <input type="password" name="mat_khau" class="form-control" value="123456" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Ảnh Thẻ / Avatar</label>
                        <input type="file" name="anh_dai_dien" class="form-control">
                    </div>
                </div>

                <h5 class="text-success mt-4 mb-3 border-bottom pb-2">2. Hồ sơ chuyên môn</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Phân Loại HDV</label>
                        <select name="phan_loai" class="form-select">
                            <option value="NoiDia">Chuyên Tour Nội Địa</option>
                            <option value="QuocTe">Chuyên Tour Quốc Tế (Inbound/Outbound)</option>
                            <option value="CongTacVien">Cộng Tác Viên (CTV)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Ngôn ngữ sử dụng</label>
                        <input type="text" name="ngon_ngu" class="form-control" placeholder="Ví dụ: Anh, Pháp, Trung...">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Chứng chỉ / Bằng cấp</label>
                        <textarea name="chung_chi" class="form-control" rows="2" placeholder="Thẻ HDV số..., Chứng chỉ ngoại ngữ..."></textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Kinh nghiệm & Giới thiệu chi tiết</label>
                        <textarea name="kinh_nghiem" class="form-control" rows="4"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= BASE_URL ?>routes/index.php?action=admin-guides" class="btn btn-secondary">Hủy bỏ</a>
                    <button type="submit" class="btn btn-success px-4">Lưu Hồ Sơ</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>