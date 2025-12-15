<?php
class StatisticalModel extends BaseModel {
    
    // Thống kê Doanh thu, Chi phí, Lợi nhuận theo Tour trong khoảng thời gian
    public function getRevenueByTour($fromDate, $toDate) {
        $sql = "
            SELECT 
                t.id as tour_id,
                t.ten_tour,
                COUNT(DISTINCT lkh.id) as so_chuyen_di,
                
                -- Tính tổng doanh thu từ các Booking ĐÃ THANH TOÁN
                COALESCE(SUM(b_stats.total_revenue), 0) as doanh_thu,
                
                -- Tính tổng chi phí từ các Dịch vụ KHÔNG BỊ HỦY
                COALESCE(SUM(dv_stats.total_cost), 0) as chi_phi,
                
                -- Lợi nhuận = Doanh thu - Chi phí
                (COALESCE(SUM(b_stats.total_revenue), 0) - COALESCE(SUM(dv_stats.total_cost), 0)) as loi_nhuan

            FROM tours t
            
            -- Join với Lịch khởi hành để lọc theo ngày
            JOIN lich_khoi_hanh lkh ON t.id = lkh.tour_id
            
            -- Subquery tính Doanh thu theo từng Lịch khởi hành
            LEFT JOIN (
                SELECT lich_khoi_hanh_id, SUM(tong_tien) as total_revenue
                FROM bookings 
                WHERE trang_thai = 'DaThanhToan' -- Chỉ tính đơn đã trả tiền
                GROUP BY lich_khoi_hanh_id
            ) b_stats ON lkh.id = b_stats.lich_khoi_hanh_id
             
            LEFT JOIN (
                SELECT lich_khoi_hanh_id, SUM(thanh_tien) as total_cost
                FROM lich_dich_vu
                WHERE trang_thai != 'Huy' -- Không tính dịch vụ đã hủy
                GROUP BY lich_khoi_hanh_id
            ) dv_stats ON lkh.id = dv_stats.lich_khoi_hanh_id

            WHERE lkh.ngay_khoi_hanh BETWEEN :fromDate AND :toDate
            GROUP BY t.id, t.ten_tour
            ORDER BY loi_nhuan DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fromDate' => $fromDate,
            'toDate' => $toDate
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Thống kê tổng quát (dùng cho các thẻ bài trên cùng báo cáo)
    public function getOverallStats($fromDate, $toDate) {
        $sql = "
            SELECT 
                SUM(b.tong_tien) as tong_doanh_thu,
                (SELECT SUM(thanh_tien) 
                 FROM lich_dich_vu ldv 
                 JOIN lich_khoi_hanh lkh ON ldv.lich_khoi_hanh_id = lkh.id
                 WHERE ldv.trang_thai != 'Huy' 
                 AND lkh.ngay_khoi_hanh BETWEEN :fromDate1 AND :toDate1
                ) as tong_chi_phi
            FROM bookings b
            JOIN lich_khoi_hanh lkh ON b.lich_khoi_hanh_id = lkh.id
            WHERE b.trang_thai = 'DaThanhToan'
            AND lkh.ngay_khoi_hanh BETWEEN :fromDate2 AND :toDate2
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fromDate1' => $fromDate, 'toDate1' => $toDate,
            'fromDate2' => $fromDate, 'toDate2' => $toDate
        ]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $doanhThu = $result['tong_doanh_thu'] ?? 0;
        $chiPhi = $result['tong_chi_phi'] ?? 0;
        
        return [
            'doanh_thu' => $doanhThu,
            'chi_phi' => $chiPhi,
            'loi_nhuan' => $doanhThu - $chiPhi
        ];
    }
}
?>