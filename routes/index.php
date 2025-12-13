<?php
session_start();
require_once '../configs/env.php';
require_once '../configs/autoloader.php';

$action = $_GET['action'] ?? 'login';

match ($action) {
    // Auth routes
    'login'             => (new AuthController)->showLoginForm(),
    'check-login'       => (new AuthController)->handleLogin(),
    'logout'            => (new AuthController)->logout(),
    
    // Route cho Admin Dashboard
    'admin-dashboard'   => (new DashboardController)->index(),

    // Route cho Hướng Dẫn Viên
    'hdv-dashboard'     => (new HdvController)->index(),
    
    // Các route con của HDV 
    'hdv-tour-detail'   => (new HdvController)->detail(),
    'hdv-check-in'      => (new HdvController)->checkIn(),
    'hdv-add-nhat-ky'   => (new HdvController)->addNhatKy(),
    'hdv-edit-nhat-ky'  => (new HdvController)->editNhatKy(),
    'hdv-update-nhat-ky' => (new HdvController)->updateNhatKy(),
    'hdv-delete-nhat-ky' => (new HdvController)->deleteNhatKy(),

    // Quản lý Lịch Trình (Tạo / Sửa / Xóa)
    'admin-create-lich' => (new DashboardController)->create(),
    'admin-store-lich'  => (new DashboardController)->store(),
    'admin-edit-lich'   => (new DashboardController)->edit(),
    
    // [CẬP NHẬT] Đổi sang TourController để xử lý cập nhật lịch
    'admin-update-lich' => (new TourController)->updateSchedule(), 
    
    'admin-delete-lich' => (new DashboardController)->delete(),

    // Quản lý Booking (Đặt Tour)
    'admin-bookings' => (new BookingController)->index(),
    'booking-status' => (new BookingController)->updateStatus(),
    'admin-booking-create' => (new BookingController)->create(),
    'admin-booking-store'  => (new BookingController)->store(),
    'admin-booking-detail' => (new BookingController)->detail(),

    // Routes Quản Lý Tour (Sản Phẩm)
    'admin-tours'       => (new TourController)->index(),
    'admin-tour-create' => (new TourController)->create(),
    'admin-tour-store'  => (new TourController)->store(),
    'admin-tour-edit'   => (new TourController)->edit(),
    'admin-tour-update' => (new TourController)->update(),
    'admin-tour-delete' => (new TourController)->delete(),
    'admin-tour-delete-image' => (new TourController)->deleteGalleryImage(),

    // Routes Quản Lý HDV
    'admin-guides'       => (new GuideManagerController)->index(),
    'admin-guide-create' => (new GuideManagerController)->create(),
    'admin-guide-store'  => (new GuideManagerController)->store(),
    'admin-guide-edit'   => (new GuideManagerController)->edit(),
    'admin-guide-update' => (new GuideManagerController)->update(),
    'admin-guide-delete' => (new GuideManagerController)->delete(),
    'admin-guide-restore'=> (new GuideManagerController)->restore(), 
    'admin-guide-detail' => (new GuideManagerController)->detail(),

    // Routes Quản Lý Nhà Cung Cấp
    'admin-suppliers'       => (new SupplierController)->index(),
    'admin-supplier-create' => (new SupplierController)->create(),
    'admin-supplier-store'  => (new SupplierController)->store(),
    'admin-supplier-edit'   => (new SupplierController)->edit(),
    'admin-supplier-update' => (new SupplierController)->update(),
    'admin-supplier-delete' => (new SupplierController)->delete(),

    // Điều hành Dịch vụ (Giữ nguyên DashboardController nếu chưa chuyển)
    'admin-schedule-services'      => (new DashboardController)->services(),    
    'admin-schedule-service-store' => (new DashboardController)->storeService(),
    'admin-schedule-service-delete'=> (new DashboardController)->deleteService(),
    'admin-schedule-service-update'=> (new DashboardController)->updateService(),

    // [CẬP NHẬT] Điều hành Phân bổ Nhân sự (Chuyển sang TourController)
    'admin-schedule-staff' => (new TourController)->editSchedule(), // Form Sửa Lịch & Phân công
    'admin-add-staff'      => (new TourController)->addStaff(),     // Xử lý thêm nhân sự
    'admin-remove-staff'   => (new TourController)->removeStaff(),  // Xử lý xóa nhân sự

    // Thống kê doanh thu
    'admin-statistics' => (new StatisticalController)->index(),

    // Chức năng Điểm danh (HDV)
    'hdv-create-phien-dd' => (new HdvController)->createPhienDiemDanh(), 
    'hdv-view-diem-danh'  => (new HdvController)->viewDiemDanh(),
    'hdv-save-diem-danh'  => (new HdvController)->saveDiemDanh(),
    'hdv-delete-phien-dd' => (new HdvController)->deletePhienDiemDanh(),

    // Chức năng Cập nhật Ghi chú Đặc biệt (HDV)
    'hdv-update-khach-note' => (new HdvController)->updateYeuCauDacBiet(),

    default             => (new AuthController)->showLoginForm(),
};
?>