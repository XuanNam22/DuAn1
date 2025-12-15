<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
</head>

<body class="bg-light">
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-2"></i>
                <div>
                    <strong>Rất tiếc!</strong> <?= htmlspecialchars($_GET['error']) ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">➕ Thêm Lịch Khởi Hành</h4>
                        <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-sm btn-light">Quay lại</a>
                    </div>
                    <div class="card-body">
                        <form action="<?= BASE_URL ?>routes/index.php?action=admin-store-lich" method="POST">

                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h5 class="text-primary border-bottom pb-2">1. Thông tin Lịch trình</h5>

                                    <div class="mb-3">
                                        <label class="fw-bold">Chọn Tour <span class="text-danger">*</span></label>
                                        <select name="tour_id" id="tour_select" class="form-select" required>
                                            <option value="" data-days="0">-- Chọn tour --</option>
                                            <?php foreach ($tours as $t): ?>
                                                <option value="<?= $t['id'] ?>" data-days="<?= $t['so_ngay'] ?>">
                                                    <?= $t['ten_tour'] ?> (<?= $t['so_ngay'] ?> ngày)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Ngày Giờ Khởi Hành</label>
                                            <small class="text-muted d-block mb-1">(Phải cách hôm nay ít nhất 3 ngày)</small>
                                            <input type="text" id="ngay_khoi_hanh" name="ngay_khoi_hanh"
                                                class="form-control datetimepicker" placeholder="Chọn ngày đi..." required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="fw-bold">Ngày Giờ Kết Thúc</label>
                                            <small class="text-muted d-block mb-1">(Tự động tính toán)</small>
                                            <input type="text" id="ngay_ket_thuc" name="ngay_ket_thuc"
                                                class="form-control datetimepicker"
                                                placeholder="Chọn tour và ngày đi..."
                                                style="background-color: #e9ecef; cursor: not-allowed;"
                                                readonly required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Điểm Tập Trung / Đón Khách</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <input type="text" name="diem_tap_trung" class="form-control" placeholder="VD: Nhà Hát Lớn, 05:00 AM" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Số chỗ tối đa</label>
                                        <input type="number" name="so_cho_toi_da" class="form-control" value="40" min="1" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="text-primary border-bottom pb-2">2. Phân Bổ Nhân Sự</h5>

                                    <div class="mb-3">
                                        <label class="fw-bold text-success">Hướng Dẫn Viên (Chính)</label>
                                        <select name="hdv_id" id="hdv_select" class="form-select" required disabled>
                                            <option value="">-- Vui lòng chọn Tour & Ngày đi trước --</option>
                                        </select>
                                        <div id="hdv_loading" class="text-muted small mt-1" style="display:none">
                                            <span class="spinner-border spinner-border-sm"></span> Đang kiểm tra lịch...
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold text-secondary">Tài Xế</label>
                                        <select name="taixe_id" id="taixe_select" class="form-select" required disabled>
                                            <option value="">-- Vui lòng chọn Tour & Ngày đi trước --</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Ghi chú Nhân sự (Biển số/Phụ xe...)</label>
                                        <textarea name="ghi_chu_nhan_su" class="form-control" rows="5"
                                            placeholder="- Biển số: 29B-12345&#10;- Phụ xe: Trần Văn B..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL ?>routes/index.php?action=admin-dashboard" class="btn btn-secondary">Hủy bỏ</a>
                                <button type="submit" class="btn btn-success px-4 fw-bold">💾 Tạo Lịch Trình</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Cấu hình Flatpickr (Giữ nguyên) ---
            const baseConfig = {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "d/m/Y H:i",
                time_24hr: true,
                locale: "vn"
            };
            const startConfig = {
                ...baseConfig,
                minDate: "<?= date('Y-m-d', strtotime('+4 days')) ?>"
            };
            const endConfig = {
                ...baseConfig,
                clickOpens: false,
                allowInput: false
            };

            const fp_start = flatpickr("#ngay_khoi_hanh", startConfig);
            const fp_end = flatpickr("#ngay_ket_thuc", endConfig);

            const tourSelect = document.getElementById('tour_select');
            const hdvSelect = document.getElementById('hdv_select');
            const taixeSelect = document.getElementById('taixe_select');
            const hdvLoading = document.getElementById('hdv_loading');

            // Hàm tính ngày về và gọi API kiểm tra nhân sự
            function handleDateChange() {
                const startDateStr = document.getElementById('ngay_khoi_hanh').value;
                const selectedOption = tourSelect.options[tourSelect.selectedIndex];
                const days = parseInt(selectedOption.getAttribute('data-days')) || 0;

                if (!startDateStr || days === 0) {
                    resetStaffSelects();
                    return;
                }

                // 1. Tính toán ngày về
                const startDate = new Date(startDateStr);
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + (days - 1));
                endDate.setHours(17, 0, 0, 0); // Giả sử kết thúc lúc 17h
                fp_end.setDate(endDate);

                // Lấy chuỗi ngày về format Y-m-d H:i để gửi lên server
                const endDateStr = flatpickr.formatDate(endDate, "Y-m-d H:i");

                // 2. Gọi AJAX kiểm tra nhân sự
                checkStaffAvailability(startDateStr, endDateStr);
            }

            function resetStaffSelects() {
                hdvSelect.innerHTML = '<option value="">-- Vui lòng chọn Tour & Ngày đi trước --</option>';
                taixeSelect.innerHTML = '<option value="">-- Vui lòng chọn Tour & Ngày đi trước --</option>';
                hdvSelect.disabled = true;
                taixeSelect.disabled = true;
            }

            function checkStaffAvailability(start, end) {
                // Hiệu ứng loading
                hdvSelect.disabled = true;
                taixeSelect.disabled = true;
                hdvLoading.style.display = 'block';

                // URL này trỏ đến Controller xử lý (Xem Bước 2)
                const url = `<?= BASE_URL ?>routes/index.php?action=api-check-availability`;

                const formData = new FormData();
                formData.append('ngay_di', start);
                formData.append('ngay_ve', end);

                fetch(url, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        hdvLoading.style.display = 'none';

                        // Mở khóa select
                        hdvSelect.disabled = false;
                        taixeSelect.disabled = false;

                        // Cập nhật Select HDV
                        updateSelect(hdvSelect, data.hdv, "Hướng Dẫn Viên");

                        // Cập nhật Select Tài xế
                        updateSelect(taixeSelect, data.taixe, "Tài Xế");
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        hdvLoading.style.display = 'none';
                        alert('Có lỗi khi kiểm tra lịch nhân sự.');
                    });
            }

            function updateSelect(selectElement, listData, roleName) {
                selectElement.innerHTML = `<option value="">-- Chọn ${roleName} --</option>`;

                listData.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;

                    if (item.is_busy) {
                        option.text = `${item.ho_ten} (Đang bận lịch khác)`;
                        option.disabled = true; // Không cho chọn
                        option.style.color = '#dc3545'; // Màu đỏ
                    } else {
                        option.text = `${item.ho_ten} (${item.sdt})`;
                        option.style.color = '#198754'; // Màu xanh
                    }
                    selectElement.appendChild(option);
                });
            }

            document.getElementById('ngay_khoi_hanh').addEventListener('change', handleDateChange);
            tourSelect.addEventListener('change', handleDateChange);
        });
    </script>
</body>

</html>