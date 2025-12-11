<?php
class BookingModel extends BaseModel {
    
    // 1. Lấy danh sách tất cả booking
    public function getAllBookings() {
        $sql = "SELECT b.*, t.ten_tour, lkh.ngay_khoi_hanh 
                FROM bookings b
                JOIN lich_khoi_hanh lkh ON b.lich_khoi_hanh_id = lkh.id
                JOIN tours t ON lkh.tour_id = t.id
                ORDER BY b.ngay_dat DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Cập nhật trạng thái (Dùng để duyệt đơn hoặc hủy đơn)
    public function updateStatus($id, $status) {
        $sql = "UPDATE bookings SET trang_thai = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    // 3. Lấy chi tiết đơn hàng (Dùng cho xem chi tiết sau này)
    public function getDetail($id) {
        $sql = "SELECT b.*, t.ten_tour, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc 
                FROM bookings b
                JOIN lich_khoi_hanh lkh ON b.lich_khoi_hanh_id = lkh.id
                JOIN tours t ON lkh.tour_id = t.id
                WHERE b.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO bookings (lich_khoi_hanh_id, ten_nguoi_dat, sdt_lien_he, email_lien_he, so_nguoi_lon, so_tre_em, tong_tien, ghi_chu, trang_thai) 
                VALUES (:lich_id, :ten, :sdt, :email, :sl_lon, :sl_tre, :tong_tien, :ghi_chu, 'ChoXacNhan')";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }
    // Hàm lấy trạng thái cũ
    public function getStatus($id) {
        $stmt = $this->conn->prepare("SELECT trang_thai FROM bookings WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn(); 
    }

    // Cập nhật trạng thái và ghi lịch sử
    public function updateStatusAndLog($id, $newStatus, $adminName, $note = '') {
        try {
            $this->conn->beginTransaction();

            // 1. Lấy trạng thái cũ
            $oldStatus = $this->getStatus($id);
            if ($oldStatus === $newStatus) return true; // Không đổi thì thôi

            // 2. Update bảng bookings
            $sql = "UPDATE bookings SET trang_thai = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['status' => $newStatus, 'id' => $id]);

            // 3. Ghi log vào booking_history
            $sqlHistory = "INSERT INTO booking_history (booking_id, nguoi_thay_doi, trang_thai_cu, trang_thai_moi, ghi_chu_thay_doi) 
                           VALUES (:id, :admin, :old, :new, :note)";
            $stmtHistory = $this->conn->prepare($sqlHistory);
            $stmtHistory->execute([
                'id' => $id,
                'admin' => $adminName, // Tên Admin thực hiện
                'old' => $oldStatus,
                'new' => $newStatus,
                'note' => $note
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    // Lấy lịch sử thay đổi của một booking
    public function getHistory($bookingId) {
        $sql = "SELECT * FROM booking_history WHERE booking_id = :id ORDER BY thoi_gian DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $bookingId]);
        return $stmt->fetchAll();
    }
}
?>