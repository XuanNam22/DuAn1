<?php
class HdvController extends BaseController {
    
    // Trang danh sách tour (Dashboard)
    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $hdvId = $_SESSION['user']['id'];
        $lichModel = new LichKhoiHanhModel();
        $myTours = $lichModel->getToursByHdv($hdvId);

        $this->render('pages/hdv/dashboard', ['myTours' => $myTours]);
    }

    // Xem chi tiết tour và danh sách khách
    public function detail() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'hdv') {
            header('Location: ' . BASE_URL . 'routes/index.php?action=login');
            exit;
        }

        $lichId = $_GET['id'] ?? 0;
        $lichModel = new LichKhoiHanhModel();
        $khachModel = new KhachTourModel();

        // 1. Lấy thông tin chi tiết chuyến đi (Tên tour, ngày đi,...)
        $tourInfo = $lichModel->getDetailForHdv($lichId);

        if (!$tourInfo) {
            die("Không tìm thấy thông tin chuyến đi!");
        }

        // 2. Lấy danh sách khách hàng
        $passengers = $khachModel->getPassengersByTour($lichId);

        // 3. [MỚI] Lấy lịch trình chi tiết (Ngày 1:..., Ngày 2:...)
        $itineraries = $lichModel->getTourItinerary($tourInfo['tour_id']);

        // Truyền tất cả sang view
        $this->render('pages/hdv/tour_detail', [
            'tourInfo'   => $tourInfo,   // Thông tin chung
            'passengers' => $passengers, // Danh sách khách
            'itineraries'=> $itineraries,// Lịch trình chi tiết
            'lichId'     => $lichId
        ]);
    }

    // Xử lý lưu điểm danh
    public function checkIn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichId = $_POST['lich_id'];
            $attendanceData = $_POST['attendance'] ?? []; // Mảng chứa ID các khách có mặt

            $khachModel = new KhachTourModel();
            
            // Bước 1: Reset toàn bộ khách của tour này về "Vắng" (0)
            $khachModel->resetStatus($lichId);

            // Bước 2: Cập nhật những người được tick thành "Có mặt" (1)
            foreach ($attendanceData as $idKhach => $value) {
                $khachModel->updateStatus($idKhach, 1);
            }

            // Lưu xong thì load lại trang và báo thành công
            header('Location: ' . BASE_URL . 'routes/index.php?action=hdv-tour-detail&id=' . $lichId . '&status=success');
        }
    }

    


}
?>