<?php
class GuideManagerController extends BaseController {
    
    private $guideModel;

    public function __construct() {
        $this->guideModel = new GuideModel();
    }

    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        
        // Kiểm tra xem user có muốn xem thùng rác không
        $isTrash = isset($_GET['view']) && $_GET['view'] === 'trash';

        $filters = [
            'keyword'    => $_GET['keyword'] ?? '',
            'phan_loai'  => $_GET['phan_loai'] ?? '',
            'role'       => $_GET['role'] ?? '',
            'trang_thai' => $_GET['trang_thai'] ?? '',
            'view_trash' => $isTrash // Truyền tham số này sang Model
        ];
        
        $guides = $this->guideModel->getAll($filters);
        
        $this->render('pages/admin/guides/index', [
            'guides' => $guides, 
            'filters' => $filters,
            'isTrash' => $isTrash // Truyền biến $isTrash sang View
        ]);
    }

    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        $this->render('pages/admin/guides/form_them');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            
            if ($this->guideModel->checkEmailExists($email)) {
                echo "<script>alert('Lỗi: Email này đã tồn tại!'); window.history.back();</script>";
                return;
            }

            $anh = 'default_avatar.png';
            if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
                $uploaded = $this->uploadImage($_FILES['anh_dai_dien']);
                if ($uploaded) $anh = $uploaded;
            }

            // SỬA: Lưu mật khẩu thô, không dùng password_hash
            $mat_khau_hash = $_POST['mat_khau'];

            $data = [
                'ho_ten' => $_POST['ho_ten'],
                'ngay_sinh' => $_POST['ngay_sinh'],
                'email' => $email,
                'mat_khau' => $mat_khau_hash,
                'sdt' => $_POST['sdt'],
                'anh' => $anh,
                'ngon_ngu' => $_POST['ngon_ngu'],
                'chung_chi' => $_POST['chung_chi'],
                'kinh_nghiem' => $_POST['kinh_nghiem'],
                'suc_khoe' => $_POST['suc_khoe'] ?? 'Tốt',
                'phan_loai' => $_POST['phan_loai'],
                'role' => $_POST['phan_loai_nhan_su'],
                'trang_thai' => 'SanSang'
            ];

            if ($this->guideModel->insert($data)) {
                header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides&msg=created');
            } else {
                echo "Lỗi hệ thống!";
            }
        }
    }

    public function edit() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->getDetail($id);
        if (!$guide) die("Không tìm thấy nhân sự!");
        $this->render('pages/admin/guides/form_sua', ['guide' => $guide]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $email = trim($_POST['email']);

            if ($this->guideModel->checkEmailExists($email, $id)) {
                echo "<script>alert('Lỗi: Email này đã được sử dụng!'); window.history.back();</script>";
                return;
            }

            $oldInfo = $this->guideModel->getDetail($id);
            $anh = $oldInfo['anh_dai_dien'];

            if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
                $newImg = $this->uploadImage($_FILES['anh_dai_dien']);
                if ($newImg) {
                    $anh = $newImg;
                    // Xóa ảnh cũ
                    if ($oldInfo['anh_dai_dien'] != 'default_avatar.png') {
                        $oldPath = 'assets/uploads/hdv/' . $oldInfo['anh_dai_dien'];
                        if (file_exists($oldPath)) unlink($oldPath);
                    }
                }
            }

            // Chỉ đổi mật khẩu nếu nhập mới
            $mat_khau_update = '';
            if (!empty($_POST['mat_khau_moi'])) {
                // SỬA: Lưu mật khẩu thô nếu có thay đổi
                $mat_khau_update = $_POST['mat_khau_moi'];
            }

            // [ĐÃ SỬA] Thêm ?? '' vào các trường không bắt buộc để tránh lỗi Undefined array key
            $data = [
                'ho_ten' => $_POST['ho_ten'],
                'ngay_sinh' => $_POST['ngay_sinh'],
                'email' => $email,
                'sdt' => $_POST['sdt'],
                'anh' => $anh,
                'ngon_ngu' => $_POST['ngon_ngu'] ?? '', 
                'chung_chi' => $_POST['chung_chi'] ?? '',
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? '',
                'suc_khoe' => $_POST['suc_khoe'] ?? '',
                'phan_loai' => $_POST['phan_loai'],
                'role' => $_POST['phan_loai_nhan_su'],
                'trang_thai' => $_POST['trang_thai'],
                'mat_khau' => $mat_khau_update
            ];

            $this->guideModel->update($id, $data);
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides&msg=updated');
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        
        $this->guideModel->delete($id);
        
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides&msg=deleted');
    }

    public function restore() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;

        $id = $_GET['id'] ?? 0;
        $this->guideModel->restore($id);
        
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides&view=trash&msg=restored');
    }

    public function detail() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        $id = $_GET['id'] ?? 0;
        $guide = $this->guideModel->getDetail($id);
        $history = $this->guideModel->getHistory($id);
        $this->render('pages/admin/guides/detail', ['guide' => $guide, 'history' => $history]);
    }

    private function uploadImage($file) {
        $targetDir = "assets/uploads/hdv/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($file["name"]);
        if (move_uploaded_file($file["tmp_name"], $targetDir . $fileName)) return $fileName;
        return false;
    }
}
?>