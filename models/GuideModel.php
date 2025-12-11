<?php
class GuideModel extends BaseModel
{
    // Lấy danh sách HDV
    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM huong_dan_vien WHERE 1=1";
        $params = [];

        // Tìm theo từ khóa (Tên, Email, SĐT hoặc Kỹ năng/Kinh nghiệm)
        if (!empty($filters['keyword'])) {
            $sql .= " AND (ho_ten LIKE :kw OR email LIKE :kw OR sdt LIKE :kw OR kinh_nghiem LIKE :kw OR ngon_ngu LIKE :kw)";
            $params['kw'] = '%' . $filters['keyword'] . '%';
        }

        // Lọc theo phân loại
        if (!empty($filters['phan_loai'])) {
            $sql .= " AND phan_loai = :phan_loai";
            $params['phan_loai'] = $filters['phan_loai'];
        }

        // Lọc theo trạng thái
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND trang_thai = :trang_thai";
            $params['trang_thai'] = $filters['trang_thai'];
        }

        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Lấy chi tiết 1 HDV
    public function getDetail($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM huong_dan_vien WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Thêm mới (Đã cập nhật đầy đủ các trường)
    public function insert($data)
    {
        $sql = "INSERT INTO huong_dan_vien 
                (ho_ten, ngay_sinh, email, mat_khau, sdt, anh_dai_dien, ngon_ngu, chung_chi, kinh_nghiem, suc_khoe, phan_loai, trang_thai) 
                VALUES 
                (:ho_ten, :ngay_sinh, :email, :mat_khau, :sdt, :anh_dai_dien, :ngon_ngu, :chung_chi, :kinh_nghiem, :suc_khoe, :phan_loai, :trang_thai)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Cập nhật (Đã cập nhật đầy đủ các trường)
    public function update($id, $data)
    {
        $sql = "UPDATE huong_dan_vien 
                SET ho_ten=:ho_ten, ngay_sinh=:ngay_sinh, email=:email, sdt=:sdt, anh_dai_dien=:anh_dai_dien, 
                    ngon_ngu=:ngon_ngu, chung_chi=:chung_chi, kinh_nghiem=:kinh_nghiem, suc_khoe=:suc_khoe, 
                    phan_loai=:phan_loai, trang_thai=:trang_thai 
                WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Xóa
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM huong_dan_vien WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Lấy lịch sử dẫn tour của một HDV
    public function getHistory($hdv_id)
    {
        $sql = "SELECT l.*, t.ten_tour 
                FROM lich_khoi_hanh l
                JOIN tours t ON l.tour_id = t.id
                WHERE l.hdv_id = :id
                ORDER BY l.ngay_khoi_hanh DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $hdv_id]);
        return $stmt->fetchAll();
    }

    /**
     * Kiểm tra xem HDV có rảnh trong khoảng thời gian này không
     * @param int $hdv_id ID của HDV
     * @param string $start_date Ngày bắt đầu tour mới (Y-m-d H:i:s)
     * @param string $end_date Ngày kết thúc tour mới (Y-m-d H:i:s)
     * @param int|null $exclude_tour_id (Tùy chọn) ID tour hiện tại để trừ ra khi đang Sửa tour
     * @return bool True nếu rảnh, False nếu bận
     */
    public function checkAvailability($hdv_id, $start_date, $end_date, $exclude_tour_id = null) {
        $sql = "SELECT COUNT(*) as ban 
                FROM lich_khoi_hanh 
                WHERE hdv_id = :hdv_id 
                AND trang_thai != 'Huy' 
                AND (
                    (ngay_khoi_hanh <= :end_date AND ngay_ket_thuc >= :start_date)
                )";
        
        $params = [
            'hdv_id' => $hdv_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        // Nếu đang sửa tour, cần loại trừ chính tour đó ra khỏi phép kiểm tra
        if ($exclude_tour_id) {
            $sql .= " AND id != :tour_id";
            $params['tour_id'] = $exclude_tour_id;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['ban'] == 0; // Nếu số lượng tour trùng = 0 thì là Rảnh (True)
    }
}

