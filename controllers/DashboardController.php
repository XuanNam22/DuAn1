<?php
class DashboardController extends BaseController
{
    // Trang Dashboard chính
    public function index()
    {
        // 1. Chặn nếu không phải admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        // 2. Lấy số liệu
        $lichModel = new LichKhoiHanhModel();
        $totalTours = $lichModel->countAll();

        // Lấy danh sách tất cả các tour
        $listTours = $lichModel->getAllToursAdmin();

        // 3. Gọi giao diện
        $this->render('pages/admin/dashboard', [
            'totalTours' => $totalTours,
            'listTours'  => $listTours
        ]);
    }

    // 1. Hiển thị form thêm mới
    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $lichModel = new LichKhoiHanhModel();
        
        // Lấy dữ liệu cho dropdown
        $tours = $lichModel->getAllToursList();
        // $guides vẫn giữ để view cũ không lỗi, nhưng logic xử lý sẽ bỏ qua
        $guides = $lichModel->getAllHDVList(); 

        $this->render('pages/admin/form_them_lich', [
            'tours' => $tours,
            'guides' => $guides 
        ]);
    }

    // 2. Xử lý lưu dữ liệu (ĐÃ CẬP NHẬT: Bỏ logic HDV cũ)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();

            $tour_id = $_POST['tour_id'];
            $ngay_di = $_POST['ngay_khoi_hanh'];
            $ngay_ve = $_POST['ngay_ket_thuc'];

            // Lưu ý: Logic chọn HDV ở đây đã được loại bỏ để chuyển sang module "Phân bổ nhân sự"
            // Ta chỉ tạo khung lịch trình trước.

            $data = [
                'tour_id' => $tour_id,
                'ngay_khoi_hanh' => $ngay_di,
                'ngay_ket_thuc' => $ngay_ve,
                'so_cho_toi_da' => $_POST['so_cho_toi_da'],
                'diem_tap_trung' => $_POST['diem_tap_trung']
            ];

            if ($lichModel->insert($data)) {
                // Sau khi tạo lịch xong, redirect về Dashboard kèm thông báo
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=success');
            } else {
                echo "Lỗi khi thêm mới!";
            }
        }
    }

    // 3. Hiển thị form sửa
    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $id = $_GET['id'] ?? 0;
        $lichModel = new LichKhoiHanhModel();
        
        $lich = $lichModel->getDetail($id);
        
        if (!$lich) {
            die("Không tìm thấy lịch trình này!");
        }

        $tours = $lichModel->getAllToursList();
        $hdvs  = $lichModel->getAllHDVList();

        $this->render('pages/admin/form_sua_lich', [
            'lich'  => $lich,
            'tours' => $tours,
            'hdvs'  => $hdvs
        ]);
    }

    // 4. Xử lý cập nhật (ĐÃ CẬP NHẬT: Bỏ logic HDV cũ)
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            
            $data = [
                'tour_id' => $_POST['tour_id'],
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'so_cho_toi_da' => $_POST['so_cho_toi_da'],
                'diem_tap_trung' => $_POST['diem_tap_trung'],
                'trang_thai' => $_POST['trang_thai']
            ];

            $lichModel = new LichKhoiHanhModel();
            if ($lichModel->update($id, $data)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=updated');
            } else {
                echo "Lỗi khi cập nhật!";
            }
        }
    }

    // 5. Xóa lịch
    public function delete() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if ($id) {
            $lichModel = new LichKhoiHanhModel();
            if ($lichModel->delete($id)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard&msg=deleted');
            } else {
                echo "Lỗi: Không thể xóa (Có thể tour này đã có khách đặt hoặc dữ liệu liên quan!)";
            }
        } else {
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-dashboard');
        }
    }

    // ==========================================================
    // MODULE QUẢN LÝ DỊCH VỤ (ĐIỀU HÀNH) - Giữ nguyên
    // ==========================================================
    
    public function services() {
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

    public function storeService() {
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

    public function deleteService() {
        $id = $_GET['id'];
        $lich_id = $_GET['lich_id'];
        (new LichKhoiHanhModel())->deleteService($id);
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-services&id=' . $lich_id);
    }

    public function updateService() {
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

    // ==========================================================
    // MODULE MỚI: QUẢN LÝ PHÂN BỔ NHÂN SỰ (HDV, TÀI XẾ...)
    // ==========================================================

    // 1. Hiển thị trang phân bổ
    public function staffAssignment() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $lichId = $_GET['id'] ?? null;
        if (!$lichId) die("Thiếu ID Lịch trình.");

        $lichModel = new LichKhoiHanhModel();
        
        // Lấy thông tin lịch
        $lich = $lichModel->getDetail($lichId);
        if (!$lich) die("Lịch trình không tồn tại.");

        // Lấy danh sách nhân sự đã phân bổ cho lịch này
        $assignedStaff = $lichModel->getAssignedStaff($lichId);

        // Lấy danh sách toàn bộ nhân sự để chọn
        $allStaff = $lichModel->getAllNhanVienList();
        
        $this->render('pages/admin/quan_ly_nhan_su', [
            'lich' => $lich,
            'assignedStaff' => $assignedStaff,
            'allStaff' => $allStaff,
        ]);
    }

    // 2. Xử lý lưu phân bổ
    public function storeStaff() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichModel = new LichKhoiHanhModel();
            
            $lichId = $_POST['lich_id'];
            $nhanVienId = $_POST['nhan_vien_id'];
            $vaiTro = $_POST['vai_tro'];

            // A. Lấy thông tin cần thiết
            $lich = $lichModel->getDetail($lichId);
            $allStaff = $lichModel->getAllNhanVienList();
            
            // Tìm thông tin nhân viên đang chọn để biết Phân loại (HDV/TaiXe/HauCan)
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

            // B. Kiểm tra logic nghiệp vụ
            
            // Logic 1: Kiểm tra trùng lịch (Chỉ áp dụng cho HDV và Tài Xế)
            if ($staffInfo['phan_loai_nhan_su'] !== 'HauCan') {
                $isFree = $lichModel->checkStaffAvailability(
                    $nhanVienId, 
                    $lich['ngay_khoi_hanh'], 
                    $lich['ngay_ket_thuc'], 
                    $staffInfo['phan_loai_nhan_su']
                );

                if (!$isFree) {
                    echo "<script>alert('Lỗi: Nhân sự này ĐÃ CÓ LỊCH trong thời gian này!'); window.history.back();</script>";
                    return;
                }
            }

            // Logic 2: Mỗi chuyến chỉ có 1 HDV Chính
            if ($vaiTro === 'HDV_chinh') {
                $assigned = $lichModel->getAssignedStaff($lichId);
                foreach ($assigned as $a) {
                    if ($a['vai_tro'] === 'HDV_chinh') {
                        echo "<script>alert('Lỗi: Chuyến đi này đã có HDV Chính rồi!'); window.history.back();</script>";
                        return;
                    }
                }
            }

            // C. Lưu vào DB
            if ($lichModel->assignStaff($lichId, $nhanVienId, $vaiTro)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-staff&id=' . $lichId . '&msg=assigned');
            } else {
                echo "Lỗi hệ thống khi lưu!";
            }
        }
    }

    // 3. Xóa phân bổ
    public function deleteStaff() {
        $id = $_GET['id'];       // ID bảng phân bổ
        $lichId = $_GET['lich_id']; // Để quay lại trang cũ

        $lichModel = new LichKhoiHanhModel();
        $lichModel->unassignStaff($id);

        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-schedule-staff&id=' . $lichId . '&msg=deleted');
    }
}
?>