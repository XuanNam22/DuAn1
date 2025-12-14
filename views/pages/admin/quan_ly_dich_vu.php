<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Điều Hành Dịch Vụ Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container mt-4 mb-5">
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
                if($_GET['msg'] == 'service_updated') echo "Cập nhật dịch vụ thành công!";
                else echo "Thao tác thành công!";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-4 border-top border-4 border-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-uppercase fw-bold text-primary mb-1">
                        ✈️ Điều Hành: <?= $lich['ten_tour'] ?>
                    </h4>
                    <p class="mb-0 text-muted">
                        <i class="bi bi-calendar-check"></i> Khởi hành: <?= date('d/m/Y H:i', strtotime($lich['ngay_khoi_hanh'])) ?>
                        | <i class="bi bi-geo-alt"></i> Đón: <?= $lich['diem_tap_trung'] ?>
                    </p>
                </div>
                <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="bi bi-plus-circle"></i> Đặt Dịch Vụ Mới
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>routes/index.php?action=admin-schedule-service-store" method="POST">
                        <input type="hidden" name="lich_id" value="<?= $lich['id'] ?>">
                        
                        <div class="mb-3">
                            <label class="fw-bold">Loại Dịch Vụ</label>
                            <select name="loai_dich_vu" class="form-select" required>
                                <option value="VanChuyen">🚌 Vận chuyển (Xe/Tàu/Bay)</option>
                                <option value="LuuTru">🏨 Lưu trú (Khách sạn)</option>
                                <option value="AnUong">🍽️ Ăn uống (Nhà hàng)</option>
                                <option value="VeThamQuan">🎫 Vé tham quan</option>
                                <option value="Khac">📦 Khác</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Nhà Cung Cấp</label>
                            <select name="ncc_id" class="form-select" required>
                                <?php foreach ($suppliers as $ncc): ?>
                                    <option value="<?= $ncc['id'] ?>"><?= $ncc['ten_ncc'] ?> - <?= $ncc['dich_vu'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text text-end"><a href="<?= BASE_URL ?>routes/index.php?action=admin-supplier-create" target="_blank">Thêm NCC mới</a></div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Thời gian sử dụng</label>
                            <input type="datetime-local" name="ngay_su_dung" class="form-control" required>
                            <small class="text-muted">Nằm trong khoảng: <?= date('d/m', strtotime($lich['ngay_khoi_hanh'])) ?> - <?= date('d/m', strtotime($lich['ngay_ket_thuc'])) ?></small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Số lượng</label>
                            <input type="number" name="so_luong" class="form-control" value="1" min="1">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Ghi chú chi tiết</label>
                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="VD: Biển số 29B-123.45, Tài xế Hùng..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Lưu Booking</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold border-bottom">
                    📋 Danh Sách Dịch Vụ Đã Phân Bổ
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Thời gian</th>
                                <th>Loại & NCC</th>
                                <th>Chi tiết / Ghi chú</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($services)): ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có dịch vụ nào được đặt.</td></tr>
                            <?php else: ?>
                                <?php foreach ($services as $s): ?>
                                <tr>
                                    <td><?= date('d/m H:i', strtotime($s['ngay_su_dung'])) ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark mb-1"><?= $s['loai_dich_vu'] ?></span><br>
                                        <small class="fw-bold"><?= $s['ten_ncc'] ?></small><br>
                                        <small class="text-muted"><?= $s['sdt'] ?></small>
                                    </td>
                                    <td><?= nl2br($s['ghi_chu']) ?></td>
                                    <td class="text-center fw-bold"><?= $s['so_luong'] ?></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-warning me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editServiceModal"
                                            data-id="<?= $s['id'] ?>"
                                            data-loai="<?= $s['loai_dich_vu'] ?>"
                                            data-ncc="<?= $s['ncc_id'] ?>"
                                            data-ngay="<?= date('Y-m-d\TH:i', strtotime($s['ngay_su_dung'])) ?>"
                                            data-sl="<?= $s['so_luong'] ?>"
                                            data-note="<?= $s['ghi_chu'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <a href="<?= BASE_URL ?>routes/index.php?action=admin-schedule-service-delete&id=<?= $s['id'] ?>&lich_id=<?= $lich['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger" onclick="return confirm('Hủy booking này?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
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
</div>
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>routes/index.php?action=admin-schedule-service-update" method="POST">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold">✏️ Cập Nhật Dịch Vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="lich_id" value="<?= $lich['id'] ?>">

                    <div class="mb-3">
                        <label class="fw-bold">Loại Dịch Vụ</label>
                        <select name="loai_dich_vu" id="edit_loai" class="form-select" required>
                            <option value="VanChuyen">🚌 Vận chuyển</option>
                            <option value="LuuTru">🏨 Lưu trú</option>
                            <option value="AnUong">🍽️ Ăn uống</option>
                            <option value="VeThamQuan">🎫 Vé tham quan</option>
                            <option value="Khac">📦 Khác</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Nhà Cung Cấp</label>
                        <select name="ncc_id" id="edit_ncc" class="form-select" required>
                            <?php foreach ($suppliers as $ncc): ?>
                                <option value="<?= $ncc['id'] ?>"><?= $ncc['ten_ncc'] ?> - <?= $ncc['dich_vu'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Thời gian sử dụng</label>
                        <input type="datetime-local" name="ngay_su_dung" id="edit_ngay" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Số lượng</label>
                        <input type="number" name="so_luong" id="edit_sl" class="form-control" min="1">
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Ghi chú</label>
                        <textarea name="ghi_chu" id="edit_note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning fw-bold">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JS xử lý đưa dữ liệu vào Modal khi bấm nút Sửa
    const editModal = document.getElementById('editServiceModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;
        
        // Extract info from data-* attributes
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('edit_loai').value = button.getAttribute('data-loai');
        document.getElementById('edit_ncc').value = button.getAttribute('data-ncc');
        document.getElementById('edit_ngay').value = button.getAttribute('data-ngay');
        document.getElementById('edit_sl').value = button.getAttribute('data-sl');
        document.getElementById('edit_note').value = button.getAttribute('data-note');
    });
</script>
</body>
</html>