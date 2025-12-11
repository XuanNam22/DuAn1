<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Nhà Cung Cấp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thêm Đối Tác Mới</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php?action=admin-supplier-store" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Nhà Cung Cấp</label>
                            <input type="text" name="ten_ncc" class="form-control" required placeholder="Ví dụ: Khách sạn Mường Thanh, Xe du lịch ABC...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Loại Dịch Vụ</label>
                            <input type="text" name="dich_vu" class="form-control" required placeholder="Ví dụ: Lưu trú, Vận chuyển, Nhà hàng...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số Điện Thoại</label>
                                <input type="text" name="sdt" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <textarea name="dia_chi" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="text-end border-top pt-3">
                            <a href="index.php?action=admin-suppliers" class="btn btn-secondary">Hủy</a>
                            <button type="submit" class="btn btn-primary px-4">Lưu Thông Tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>