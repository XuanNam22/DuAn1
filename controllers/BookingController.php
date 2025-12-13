<?php
class BookingController extends BaseController {

    // Danh sách booking (ĐÃ NÂNG CẤP: Thêm bộ lọc)
    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        // 1. Nhận dữ liệu bộ lọc từ URL
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'tour_id' => $_GET['tour_id'] ?? ''
        ];

        // 2. Lấy danh sách Booking theo bộ lọc
        $bookingModel = new BookingModel();
        $bookings = $bookingModel->getAllBookings($filters);

        // 3. Lấy danh sách Tour để hiển thị trong ô chọn (Select Box)
        // Chúng ta tái sử dụng LichKhoiHanhModel vì nó đã có hàm getAllToursList
        $lkhModel = new LichKhoiHanhModel();
        $tourList = $lkhModel->getAllToursList();

        // 4. Truyền dữ liệu sang View
        $this->render('pages/admin/bookings', [
            'bookings' => $bookings,
            'tourList' => $tourList, // Biến này dùng cho <select name="tour_id">
            'filters'  => $filters   // Để giữ lại giá trị đã chọn sau khi reload
        ]);
    }

    // --- Xử lý đổi trạng thái & Ghi lịch sử ---
    public function updateStatus() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $status = $_GET['status'] ?? '';
        $adminName = $_SESSION['user']['ho_ten'] ?? 'Administrator'; 
        $validStatus = ['ChoXacNhan', 'DaXacNhan', 'DaThanhToan', 'Huy'];

        if ($id && in_array($status, $validStatus)) {
            $bookingModel = new BookingModel();
            $note = "Cập nhật nhanh từ trang danh sách";
            
            $result = $bookingModel->updateStatusAndLog($id, $status, $adminName, $note);

            if ($result) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-bookings');
                exit;
            } else {
                echo "<script>
                        alert('KHÔNG THỂ CẬP NHẬT TRẠNG THÁI!\\n\\nNguyên nhân có thể:\\n1. Tour đã hết chỗ trống (khi bạn khôi phục vé Hủy).\\n2. Lỗi hệ thống.');
                        window.location.href = '" . BASE_URL . "routes/index.php?action=admin-bookings';
                      </script>";
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-bookings');
    }

    // Hiển thị form tạo booking
    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $lkhModel = new LichKhoiHanhModel();
        $schedules = $lkhModel->getOpenSchedules();

        $this->render('pages/admin/bookings/create', ['schedules' => $schedules]);
    }

    // Xử lý lưu booking với Transaction (Đã có logic chống Overbooking)
    public function store() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $lichId = $_POST['lich_khoi_hanh_id'];
        $soLon = (int)$_POST['so_nguoi_lon'];
        $soTre = (int)($_POST['so_tre_em'] ?? 0);
        $tongKhach = $soLon + $soTre;

        $lkhModel = new LichKhoiHanhModel();
        $bookingModel = new BookingModel();
        $tourModel = new TourModel();

        try {
            $bookingModel->conn->beginTransaction();

            // Khóa dòng dữ liệu để xử lý độc quyền
            $lkh = $lkhModel->getDetailForUpdate($lichId);

            if (!$lkh) throw new Exception("Lịch khởi hành không tồn tại!");

            // Kiểm tra chỗ trống chính xác
            if (($lkh['so_cho_da_dat'] + $tongKhach) > $lkh['so_cho_toi_da']) {
                throw new Exception("Rất tiếc, vừa có người đặt và hiện tại tour không đủ chỗ trống!");
            }

            $tour = $tourModel->getDetail($lkh['tour_id']);
            $tongTien = ($soLon * $tour['gia_nguoi_lon']) + ($soTre * $tour['gia_tre_em']);

            $data = [
                'lich_id' => $lichId,
                'ten' => $_POST['ten_nguoi_dat'],
                'sdt' => $_POST['sdt_lien_he'],
                'email' => $_POST['email_lien_he'],
                'sl_lon' => $soLon,
                'sl_tre' => $soTre,
                'tong_tien' => $tongTien,
                'ghi_chu' => $_POST['ghi_chu']
            ];

            $newId = $bookingModel->create($data); 

            if (!$newId) throw new Exception("Lỗi hệ thống khi lưu đơn hàng.");

            // Cập nhật số chỗ
            $lkhModel->updateSoCho($lichId, $tongKhach);

            $bookingModel->conn->commit();
            header('Location: index.php?action=admin-bookings');

        } catch (Exception $e) {
            $bookingModel->conn->rollBack();
            echo "<script>
                    alert('" . $e->getMessage() . "'); 
                    window.history.back();
                  </script>";
        }
    }

    public function detail() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $id = $_GET['id'] ?? 0;
        $bookingModel = new BookingModel();
        
        $booking = $bookingModel->getDetail($id); 
        $history = $bookingModel->getHistory($id);

        if (!$booking) {
            echo "Không tìm thấy đơn hàng!";
            exit;
        }

        $this->render('pages/admin/bookings/detail', [
            'booking' => $booking,
            'history' => $history
        ]);
    }
}
?>