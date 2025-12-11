<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Tour Mới</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>➕ Thêm Tour Mới</h3>
        <a href="index.php?action=admin-tours" class="btn btn-secondary">Quay lại danh sách</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="index.php?action=admin-tour-store" method="POST" enctype="multipart/form-data">
                
                <ul class="nav nav-tabs" id="tourTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">Thông tin chung</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button" role="tab">Lịch trình</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy" type="button" role="tab">Chính sách</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="supplier-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">Đối tác & NCC</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab">Thư viện ảnh</button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="tourTabContent">
                    
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tên Tour</label>
                                <input type="text" name="ten_tour" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loại Tour</label>
                                <select name="loai_tour_id" class="form-select">
                                    <?php foreach ($categories as $cate): ?>
                                        <option value="<?= $cate['id'] ?>"><?= $cate['ten_loai'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá Người Lớn</label>
                                <div class="input-group">
                                    <input type="number" name="gia_nguoi_lon" class="form-control" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Giá Trẻ Em</label>
                                <div class="input-group">
                                    <input type="number" name="gia_tre_em" class="form-control" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số ngày</label>
                                <input type="number" name="so_ngay" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Giới thiệu ngắn</label>
                                <textarea name="gioi_thieu" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Lịch trình tóm tắt</label>
                                <input type="text" name="lich_trinh_tom_tat" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" name="anh_tour" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="itinerary" role="tabpanel">
                        <div id="itinerary-wrapper"></div>
                        <button type="button" class="btn btn-success mt-3" onclick="addDay()">+ Thêm Ngày</button>
                    </div>

                    <div class="tab-pane fade" id="policy" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá bao gồm</label>
                                <textarea name="bao_gom" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giá KHÔNG bao gồm</label>
                                <textarea name="khong_bao_gom" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Chính sách Hoàn/Hủy</label>
                                <textarea name="chinh_sach_huy" class="form-control" rows="5"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lưu ý</label>
                                <textarea name="luu_y" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="suppliers" role="tabpanel">
                        <div class="alert alert-info">
                            Tick chọn các nhà cung cấp sẽ phục vụ cho tour này và ghi chú (VD: Loại phòng, Loại xe...)
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">Chọn</th>
                                        <th>Tên Nhà Cung Cấp</th>
                                        <th>Dịch vụ</th>
                                        <th>Ghi chú sử dụng (Cho tour này)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($suppliers)): ?>
                                        <?php foreach($suppliers as $s): ?>
                                        <tr>
                                            <td class="text-center">
                                                <input class="form-check-input" type="checkbox" name="suppliers[]" value="<?= $s['id'] ?>">
                                            </td>
                                            <td class="fw-bold"><?= $s['ten_ncc'] ?></td>
                                            <td><span class="badge bg-secondary"><?= $s['dich_vu'] ?></span></td>
                                            <td>
                                                <input type="text" name="suppliers_note[<?= $s['id'] ?>]" class="form-control form-control-sm" placeholder="VD: 02 phòng Twin...">
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4">Chưa có nhà cung cấp nào. Hãy thêm ở menu Đối tác.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="gallery" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn nhiều ảnh</label>
                            <input type="file" name="gallery[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end border-top pt-3">
                    <a href="index.php?action=admin-tours" class="btn btn-secondary me-2">Hủy bỏ</a>
                    <button type="submit" class="btn btn-primary px-4">Lưu Tour Mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let dayCount = 0;
    function addDay() {
        dayCount++;
        const html = `
            <div class="card mb-3 border bg-light">
                <div class="card-header py-2"><strong>Ngày ${dayCount}</strong></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="itinerary_title[]" class="form-control bg-white" required>
                    </div>
                    <div class="mb-0">
                        <textarea name="itinerary_content[]" class="form-control bg-white" rows="3"></textarea>
                    </div>
                </div>
            </div>`;
        document.getElementById('itinerary-wrapper').insertAdjacentHTML('beforeend', html);
    }
    addDay();
</script>
</body>
</html>