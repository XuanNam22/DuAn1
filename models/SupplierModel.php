<?php
class SupplierModel extends BaseModel {
    
    // Lấy danh sách tất cả nhà cung cấp
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM nha_cung_cap ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy chi tiết 1 nhà cung cấp
    public function getDetail($id) {
        $stmt = $this->conn->prepare("SELECT * FROM nha_cung_cap WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Thêm nhà cung cấp mới
    public function insert($data) {
        $sql = "INSERT INTO nha_cung_cap (ten_ncc, dich_vu, sdt, email, dia_chi) 
                VALUES (:ten, :dv, :sdt, :email, :dc)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Cập nhật
    public function update($id, $data) {
        $sql = "UPDATE nha_cung_cap 
                SET ten_ncc=:ten, dich_vu=:dv, sdt=:sdt, email=:email, dia_chi=:dc 
                WHERE id=:id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    // Xóa
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM nha_cung_cap WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>