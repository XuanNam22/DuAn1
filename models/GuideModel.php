<?php
class GuideModel extends BaseModel
{
    public function getAll($filters = [])
    {
        $sql = "SELECT * FROM huong_dan_vien WHERE 1=1";
        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND (ho_ten LIKE :kw OR email LIKE :kw OR sdt LIKE :kw)";
            $params['kw'] = '%' . $filters['keyword'] . '%';
        }

        // 2. Lọc theo phân loại (Nội địa / Quốc tế)
        if (!empty($filters['phan_loai'])) {
            $sql .= " AND phan_loai = :phan_loai";
            $params['phan_loai'] = $filters['phan_loai'];
        }

        // 3. Lọc theo vai trò (HDV / Tài xế...)
        if (!empty($filters['role'])) {
            $sql .= " AND phan_loai_nhan_su = :role";
            $params['role'] = $filters['role'];
        }

        // 4. Lọc theo trạng thái (Sẵn sàng / Đang bận...)
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND trang_thai = :trang_thai";
            $params['trang_thai'] = $filters['trang_thai'];
        }

        // Sắp xếp mới nhất lên đầu
        $sql .= " ORDER BY id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function getDetail($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM huong_dan_vien WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    public function checkEmailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM huong_dan_vien WHERE email = :email";
        $params = ['email' => $email];

        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
    public function insert($data)
    {
        $sql = "INSERT INTO huong_dan_vien (ho_ten, ngay_sinh, email, mat_khau, sdt, anh_dai_dien, chung_chi, kinh_nghiem, suc_khoe, phan_loai_nhan_su) 
        VALUES (:ho_ten, :ngay_sinh, :email, :mat_khau, :sdt, :anh, :chung_chi, :kinh_nghiem, :suc_khoe, :role)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function update($id, $data)
    {
        $sql = "UPDATE huong_dan_vien 
                SET ho_ten=:ho_ten, 
                    ngay_sinh=:ngay_sinh, 
                    email=:email, 
                    sdt=:sdt, 
                    anh_dai_dien=:anh";
        if (!empty($data['mat_khau'])) {
            $sql .= ", mat_khau=:mat_khau";
        } else {
            unset($data['mat_khau']);
        }

        $sql .= " WHERE id=:id";

        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM huong_dan_vien WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getHistory($hdv_id)
    {
        $sql = "SELECT lkh.*, t.ten_tour 
                FROM lich_khoi_hanh lkh
                JOIN tours t ON lkh.tour_id = t.id
                JOIN lich_nhan_vien lnv ON lkh.id = lnv.lich_khoi_hanh_id
                WHERE lnv.nhan_vien_id = :id
                ORDER BY lkh.ngay_khoi_hanh DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $hdv_id]);
        return $stmt->fetchAll();
    }
}
