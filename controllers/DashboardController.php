<?php
class DashboardController extends BaseController
{

    public function index()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $lichModel = new LichKhoiHanhModel();
        $totalTours = $lichModel->countAll();
        $listTours = $lichModel->getAllToursAdmin();

        $currentTime = time();
        $processedTours = [];

        foreach ($listTours as $tour) {
            $startTime = strtotime($tour['ngay_khoi_hanh']);
            $endTime   = strtotime($tour['ngay_ket_thuc']);

            $oneDayBefore = $startTime - 86400;
            if ($tour['trang_thai'] === 'NhanKhach' && $currentTime >= $oneDayBefore) {
                if ($tour['so_cho_da_dat'] < 10) {
                    $lichModel->updateStatus($tour['id'], 'Huy');
                    $tour['trang_thai'] = 'Huy';
                } else {
                    $lichModel->updateStatus($tour['id'], 'KhongNhanThemKhach');
                    $tour['trang_thai'] = 'KhongNhanThemKhach';
                }
            }
            if ($tour['trang_thai'] === 'KhongNhanThemKhach' && $currentTime >= $startTime && $currentTime <= $endTime) {
                $lichModel->updateStatus($tour['id'], 'DangDi');
                $tour['trang_thai'] = 'DangDi';
            }

            if ($currentTime > $endTime && $tour['trang_thai'] !== 'HoanThanh' && $tour['trang_thai'] !== 'Huy') {
                $lichModel->updateStatus($tour['id'], 'HoanThanh');
                $tour['trang_thai'] = 'HoanThanh';
            }

            $percent = ($tour['so_cho_toi_da'] > 0) ? ($tour['so_cho_da_dat'] / $tour['so_cho_toi_da']) * 100 : 0;
            $tour['view_percent'] = $percent;
            $tour['view_progress_color'] = $percent >= 100 ? 'bg-danger' : 'bg-success';

            if ($tour['trang_thai'] === 'Huy') {
                $tour['view_badge'] = ['bg' => 'secondary', 'label' => 'Đã hủy', 'icon' => ''];
            } elseif ($tour['trang_thai'] === 'KhongNhanThemKhach') {
                $tour['view_badge'] = ['bg' => 'warning text-dark', 'label' => 'Ngưng nhận khách', 'icon' => '<i class="fas fa-ban"></i>'];
            } elseif ($currentTime >= $startTime && $currentTime <= $endTime && $tour['trang_thai'] !== 'Huy') {
                $tour['view_badge'] = ['bg' => 'primary', 'label' => 'Đang đi', 'icon' => '<i class="fas fa-plane"></i>'];
            } elseif ($tour['trang_thai'] === 'HoanThanh' || $currentTime > $endTime) {
                $tour['view_badge'] = ['bg' => 'dark', 'label' => 'Hoàn thành', 'icon' => ''];
            } elseif ($tour['so_cho_da_dat'] >= $tour['so_cho_toi_da']) {
                $tour['view_badge'] = ['bg' => 'danger', 'label' => 'Đã đầy', 'icon' => ''];
            } else {
                $tour['view_badge'] = ['bg' => 'success', 'label' => 'Đang nhận khách', 'icon' => ''];
            }

            $tour['can_edit_cancel'] = ($currentTime < $startTime && $tour['trang_thai'] !== 'Huy');

            $tour['can_delete'] = ($currentTime > $endTime || $tour['trang_thai'] === 'Huy');

            $processedTours[] = $tour;
        }

        $this->render('pages/admin/dashboard', [
            'totalTours' => $totalTours,
            'listTours'  => $processedTours
        ]);
    }
    public function create()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $lichModel = new LichKhoiHanhModel();
        $tours = $lichModel->getAllToursList();
        $guides = $lichModel->getAllHDVList();

        $this->render('pages/admin/form_them_lich', [
            'tours' => $tours,
            'guides' => $guides
        ]);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();

            $tour_id = $_POST['tour_id'];
            $ngay_di = $_POST['ngay_khoi_hanh'];
            $ngay_ve = $_POST['ngay_ket_thuc'];
            $minTimestamp = strtotime(date('Y-m-d') . ' +3 days');
            $inputTimestamp = strtotime($ngay_di);

            if ($inputTimestamp < $minTimestamp) {
                echo "<script>
                    alert('Lỗi: Ngày khởi hành phải cách hiện tại ít nhất 3 ngày để có thời gian chuẩn bị!'); 
                    window.history.back();
                </script>";
                return;
            }

            if (strtotime($ngay_ve) < strtotime($ngay_di)) {
                echo "<script>alert('Lỗi: Ngày kết thúc phải sau hoặc bằng Ngày khởi hành!'); window.history.back();</script>";
                return;
            }

            $data = [
                'tour_id' => $tour_id,
                'ngay_khoi_hanh' => $ngay_di,
                'ngay_ket_thuc' => $ngay_ve,
                'so_cho_toi_da' => $_POST['so_cho_toi_da'],
                'diem_tap_trung' => $_POST['diem_tap_trung']
            ];

            if ($lichModel->insert($data)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=success');
            } else {
                echo "Lỗi khi thêm mới!";
            }
        }
    }
    public function edit()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $id = $_GET['id'] ?? 0;
        $lichModel = new LichKhoiHanhModel();

        $lich = $lichModel->getDetail($id);

        if (!$lich) {
            die("Không tìm thấy lịch trình này!");
        }
        $tours = $lichModel->getAllToursList();
        $allStaff = $lichModel->getAllNhanVienList();
        $assignedStaff = $lichModel->getAssignedStaff($id);

        $this->render('pages/admin/form_sua_lich', [
            'lich'  => $lich,
            'tours' => $tours,
            'allStaff' => $allStaff,
            'assignedStaff' => $assignedStaff
        ]);
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $lichModel = new LichKhoiHanhModel();

            $ngay_di = $_POST['ngay_khoi_hanh'];
            $ngay_ve = $_POST['ngay_ket_thuc'];
            $so_cho_moi = (int)$_POST['so_cho_toi_da'];

            if (strtotime($ngay_ve) < strtotime($ngay_di)) {
                echo "<script>alert('Lỗi: Ngày kết thúc phải sau hoặc bằng Ngày khởi hành!'); window.history.back();</script>";
                return;
            }

            $currentLich = $lichModel->getDetail($id);
            if ($currentLich) {
                $so_da_dat = (int)$currentLich['so_cho_da_dat'];
                if ($so_cho_moi < $so_da_dat) {
                    echo "<script>
                        alert('KHÔNG THỂ CẬP NHẬT!\\n\\nSố chỗ mới ($so_cho_moi) nhỏ hơn số khách đã đặt ($so_da_dat).\\nVui lòng hủy bớt vé trước khi giảm số chỗ.');
                        window.history.back();
                    </script>";
                    return;
                }
            }

            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_khoi_hanh' => $ngay_di,
                'ngay_ket_thuc' => $ngay_ve,
                'so_cho_toi_da' => $so_cho_moi,
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'trang_thai' => $_POST['trang_thai']
            ];

            if ($lichModel->update($id, $data)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=updated');
            } else {
                echo "Lỗi khi cập nhật!";
            }
        }
    }
    public function delete()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if ($id) {
            $lichModel = new LichKhoiHanhModel();
            $lich = $lichModel->getDetail($id);
            if ($lich && $lich['so_cho_da_dat'] > 0) {
                echo "<script>
                    alert('CẢNH BÁO: Không thể xóa lịch trình này!\\n\\nĐang có " . $lich['so_cho_da_dat'] . " khách đã đặt tour. Xóa sẽ làm mất dữ liệu quan trọng.');
                    window.location.href = '" . BASE_URL . "routes/index.php?action=admin-dashboard';
                </script>";
                exit;
            }

            if ($lichModel->delete($id)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=deleted');
            } else {
                echo "Lỗi: Không thể xóa (Có thể tour này đã có khách đặt hoặc dữ liệu liên quan!)";
            }
        } else {
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard');
        }
    }
    public function services()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $id = $_GET['id'] ?? 0;
        $lichModel = new LichKhoiHanhModel();

        $lich = $lichModel->getDetail($id);
        if (!$lich) die('Không tìm thấy lịch khởi hành!');

        $services = $lichModel->getServices($id);
        $suppliers = (new SupplierModel())->getAll();

        $this->render('pages/admin/quan_ly_dich_vu', [
            'lich' => $lich,
            'services' => $services,
            'suppliers' => $suppliers
        ]);
    }
    public function storeService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();

            $lich_id = $_POST['lich_id'];
            $ngay_sd = $_POST['ngay_su_dung'];

            $lich = $lichModel->getDetail($lich_id);
            if ($lich) {
                $start = strtotime($lich['ngay_khoi_hanh']);
                $end = strtotime($lich['ngay_ket_thuc']);
                $current = strtotime($ngay_sd);

                if ($current < $start || $current > $end) {
                    echo "<script>alert('Lỗi: Ngày sử dụng dịch vụ phải nằm trong thời gian tour diễn ra!'); window.history.back();</script>";
                    return;
                }
            }

            $data = [
                'lich_id' => $lich_id,
                'ncc_id' => $_POST['ncc_id'],
                'loai_dv' => $_POST['loai_dich_vu'],
                'ngay_sd' => $ngay_sd,
                'sl' => $_POST['so_luong'],
                'ghi_chu' => $_POST['ghi_chu']
            ];

            $lichModel->addService($data);
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-services&id=' . $lich_id);
        }
    }
    public function deleteService()
    {
        $id = $_GET['id'];
        $lich_id = $_GET['lich_id'];
        (new LichKhoiHanhModel())->deleteService($id);
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-services&id=' . $lich_id);
    }
    public function updateService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();

            $id = $_POST['id'];
            $lich_id = $_POST['lich_id'];
            $ngay_sd = $_POST['ngay_su_dung'];

            $lich = $lichModel->getDetail($lich_id);
            if ($lich) {
                $start = strtotime($lich['ngay_khoi_hanh']);
                $end = strtotime($lich['ngay_ket_thuc']);
                $current = strtotime($ngay_sd);

                if ($current < $start || $current > $end) {
                    echo "<script>alert('Lỗi: Ngày sử dụng dịch vụ phải nằm trong thời gian tour diễn ra!'); window.history.back();</script>";
                    return;
                }
            }

            $data = [
                'ncc_id' => $_POST['ncc_id'],
                'loai_dv' => $_POST['loai_dich_vu'],
                'ngay_sd' => $ngay_sd,
                'sl' => $_POST['so_luong'],
                'ghi_chu' => $_POST['ghi_chu']
            ];

            if ($lichModel->updateService($id, $data)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-services&id=' . $lich_id . '&msg=service_updated');
            } else {
                echo "Lỗi khi cập nhật dịch vụ!";
            }
        }
    }
    public function staffAssignment()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $lichId = $_GET['id'] ?? null;
        if (!$lichId) die("Thiếu ID Lịch trình.");

        $lichModel = new LichKhoiHanhModel();

        $lich = $lichModel->getDetail($lichId);
        if (!$lich) die("Lịch trình không tồn tại.");

        $assignedStaff = $lichModel->getAssignedStaff($lichId);

        $allStaff = $lichModel->getAllNhanVienList();

        $this->render('pages/admin/quan_ly_nhan_su', [
            'lich' => $lich,
            'assignedStaff' => $assignedStaff,
            'allStaff' => $allStaff,
        ]);
    }
    public function storeStaff()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();

            $lichId = $_POST['lich_id'];
            $nhanVienId = $_POST['nhan_vien_id'];
            $vaiTro = $_POST['vai_tro'];

            $lich = $lichModel->getDetail($lichId);
            $allStaff = $lichModel->getAllNhanVienList();

            $staffInfo = null;
            foreach ($allStaff as $s) {
                if ($s['id'] == $nhanVienId) {
                    $staffInfo = $s;
                    break;
                }
            }

            if (!$lich || !$staffInfo) {
                echo "<script>alert('Dữ liệu không hợp lệ!'); window.history.back();</script>";
                return;
            }

            $assigned = $lichModel->getAssignedStaff($lichId);

            foreach ($assigned as $a) {
                if ($a['nhan_vien_id'] == $nhanVienId) {
                    echo "<script>alert('Lỗi: Nhân sự này ĐÃ CÓ TRONG ĐOÀN này rồi!'); window.history.back();</script>";
                    return;
                }
            }

            $busyTour = $lichModel->checkStaffAvailability(
                $nhanVienId,
                $lich['ngay_khoi_hanh'],
                $lich['ngay_ket_thuc']
            );

            if ($busyTour) {
                $tenTour = $busyTour['ten_tour'];
                $start = date('d/m H:i', strtotime($busyTour['ngay_khoi_hanh']));
                $end = date('d/m H:i', strtotime($busyTour['ngay_ket_thuc']));

                echo "<script>
                    alert('KHÔNG THỂ PHÂN CÔNG!\\n\\nNhân sự này đang bận đi tour: \"$tenTour\"\\nThời gian: Từ $start đến $end.\\n\\nVui lòng chọn nhân sự khác.'); 
                    window.history.back();
                </script>";
                return;
            }

            if ($vaiTro === 'HDV_chinh') {
                foreach ($assigned as $a) {
                    if ($a['vai_tro'] === 'HDV_chinh') {
                        echo "<script>alert('Lỗi: Đoàn này đã có HDV Chính rồi! Bạn chỉ có thể thêm HDV Phụ hoặc vai trò khác.'); window.history.back();</script>";
                        return;
                    }
                }
            }
            if ($lichModel->assignStaff($lichId, $nhanVienId, $vaiTro)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-staff&id=' . $lichId . '&msg=assigned');
            } else {
                echo "Lỗi hệ thống khi lưu!";
            }
        }
    }
    public function deleteStaff()
    {
        $id = $_GET['id'];
        $lichId = $_GET['lich_id'];

        $lichModel = new LichKhoiHanhModel();
        $lichModel->unassignStaff($id);

        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-staff&id=' . $lichId . '&msg=deleted');
    }
    public function cancelTour()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $lichModel = new LichKhoiHanhModel();
        $lich = $lichModel->getDetail($id);

        if (!$lich) {
            echo "<script>alert('Tour không tồn tại!'); window.history.back();</script>";
            return;
        }
        $currentTime = time();
        $startTime = strtotime($lich['ngay_khoi_hanh']);

        if ($currentTime >= $startTime) {
            echo "<script>
                alert('LỖI: Không thể hủy tour này vì xe đã lăn bánh!\\n\\nTour đã khởi hành lúc: " . date('H:i d/m/Y', $startTime) . "'); 
                window.history.back();
            </script>";
            return;
        }

        if ($lichModel->updateStatus($id, 'Huy')) {
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=cancel_success');
        } else {
            echo "Lỗi hệ thống, vui lòng thử lại.";
        }
    }
}
