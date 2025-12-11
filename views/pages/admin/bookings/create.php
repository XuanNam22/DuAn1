<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Tạo Booking Mới</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <form action="index.php?action=admin-booking-store" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label">Chọn Tour - Lịch Khởi Hành</label>
                            <select name="lich_khoi_hanh_id" class="form-control border p-2" required>
                                <option value="">-- Chọn chuyến đi --</option>
                                <?php foreach ($schedules as $item): ?>
                                    <option value="<?= $item['id'] ?>">
                                        <?= $item['ten_tour'] ?> | 
                                        KH: <?= date('d/m/Y', strtotime($item['ngay_khoi_hanh'])) ?> | 
                                        Giá: <?= number_format($item['gia_nguoi_lon']) ?> VNĐ
                                        (Còn <?= $item['so_cho_toi_da'] - $item['so_cho_da_dat'] ?> chỗ)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên người đặt</label>
                                <input type="text" name="ten_nguoi_dat" class="form-control border p-2" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="sdt_lien_he" class="form-control border p-2" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email_lien_he" class="form-control border p-2">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số người lớn</label>
                                <input type="number" name="so_nguoi_lon" class="form-control border p-2" value="1" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số trẻ em</label>
                                <input type="number" name="so_tre_em" class="form-control border p-2" value="0" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Yêu cầu đặc biệt (Ghi chú)</label>
                            <textarea name="ghi_chu" class="form-control border p-2" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Xác nhận đặt tour</button>
                        <a href="index.php?action=admin-bookings" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
