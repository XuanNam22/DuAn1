<?php
class TourModel extends BaseModel {
    
    // 1. Lấy danh sách tour (kèm tên loại tour)
    public function getAll() {
        $sql = "SELECT t.*, lt.ten_loai 
                FROM tours t
                JOIN loai_tour lt ON t.loai_tour_id = lt.id
                ORDER BY t.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy danh sách loại tour (cho dropdown)
    public function getCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM loai_tour");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 3. Lấy chi tiết tour (bao gồm cả cột chính sách mới thêm)
    public function getDetail($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tours WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // 4. Lấy thư viện ảnh của tour
    public function getGallery($tourId) {
        $stmt = $this->conn->prepare("SELECT * FROM tour_images WHERE tour_id = :id");
        $stmt->execute(['id' => $tourId]);
        return $stmt->fetchAll();
    }

    // 5. Lấy lịch trình chi tiết
    public function getItinerary($tourId) {
        $stmt = $this->conn->prepare("SELECT * FROM tour_itineraries WHERE tour_id = :id ORDER BY ngay_thu ASC");
        $stmt->execute(['id' => $tourId]);
        return $stmt->fetchAll();
    }

    // 6. Thêm tour mới (trả về ID để dùng lưu ảnh/lịch trình)
    public function insertAndGetId($data) {
        $sql = "INSERT INTO tours (ten_tour, anh_tour, gioi_thieu, lich_trinh, gia_nguoi_lon, gia_tre_em, so_ngay, loai_tour_id, bao_gom, khong_bao_gom, chinh_sach_huy, luu_y) 
                VALUES (:ten, :anh, :gt, :lt, :gia_lon, :gia_tre, :ngay, :loai, :bao_gom, :khong_bao_gom, :chinh_sach_huy, :luu_y)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId(); // Trả về ID tour vừa tạo
    }

    // 7. Cập nhật thông tin tour
    public function update($id, $data) {
        $sql = "UPDATE tours 
                SET ten_tour=:ten, anh_tour=:anh, gioi_thieu=:gt, lich_trinh=:lt, 
                    gia_nguoi_lon=:gia_lon, gia_tre_em=:gia_tre, so_ngay=:ngay, loai_tour_id=:loai,
                    bao_gom=:bao_gom, khong_bao_gom=:khong_bao_gom, chinh_sach_huy=:chinh_sach_huy, luu_y=:luu_y
                WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // 8. Các hàm hỗ trợ thêm dữ liệu phụ (Ảnh, Lịch trình)
    public function insertImage($tourId, $url) {
        $stmt = $this->conn->prepare("INSERT INTO tour_images (tour_id, image_url) VALUES (:id, :url)");
        return $stmt->execute(['id' => $tourId, 'url' => $url]);
    }

    public function insertItinerary($tourId, $day, $title, $content) {
        $stmt = $this->conn->prepare("INSERT INTO tour_itineraries (tour_id, ngay_thu, tieu_de, noi_dung) VALUES (:id, :day, :title, :content)");
        return $stmt->execute(['id' => $tourId, 'day' => $day, 'title' => $title, 'content' => $content]);
    }

    // Xóa dữ liệu phụ cũ (dùng khi cập nhật tour)
    public function deleteOldItinerary($tourId) {
        $stmt = $this->conn->prepare("DELETE FROM tour_itineraries WHERE tour_id = :id");
        return $stmt->execute(['id' => $tourId]);
    }
    
    public function deleteOldImages($tourId) {
        $stmt = $this->conn->prepare("DELETE FROM tour_images WHERE tour_id = :id");
        return $stmt->execute(['id' => $tourId]);
    }

    // 9. Xóa tour (Database đã có ON DELETE CASCADE nên tự xóa bảng phụ)
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM tours WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    // 10. Lấy danh sách NCC đã gắn với tour này
    public function getTourSuppliers($tourId) {
        $sql = "SELECT ts.*, ncc.ten_ncc, ncc.dich_vu 
                FROM tour_suppliers ts 
                JOIN nha_cung_cap ncc ON ts.ncc_id = ncc.id 
                WHERE ts.tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $tourId]);
        return $stmt->fetchAll();
    }

    // 11. Gắn NCC vào tour
    public function insertTourSupplier($tourId, $nccId, $note) {
        $sql = "INSERT INTO tour_suppliers (tour_id, ncc_id, ghi_chu) VALUES (:tid, :nid, :note)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['tid' => $tourId, 'nid' => $nccId, 'note' => $note]);
    }

    // 12. Xóa danh sách NCC cũ của tour (dùng khi update)
    public function deleteOldSuppliers($tourId) {
        $stmt = $this->conn->prepare("DELETE FROM tour_suppliers WHERE tour_id = :id");
        return $stmt->execute(['id' => $tourId]);
    }
}
?>