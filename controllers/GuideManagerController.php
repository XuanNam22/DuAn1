<?php
class GuideManagerController extends BaseController {
    
    // Danh sách HDV
    public function index() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        
        // Hứng dữ liệu từ thanh tìm kiếm (URL params)
        $filters = [
            'keyword'    => $_GET['keyword'] ?? '',
            'phan_loai'  => $_GET['phan_loai'] ?? '',
            'trang_thai' => $_GET['trang_thai'] ?? ''
        ];
        
        $model = new GuideModel();
        $guides = $model->getAll($filters); // Truyền bộ lọc vào model
        
        // Truyền cả biến $filters ra view để giữ lại lựa chọn trên form sau khi reload
        $this->render('pages/admin/guides/index', [
            'guides' => $guides, 
            'filters' => $filters
        ]);
    }

    // Form thêm
    public function create() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        $this->render('pages/admin/guides/form_them');
    }

    // Xử lý thêm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Upload ảnh đại diện
            $anh = 'default_avatar.png';
            if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
                $targetDir = "public/img/hdv/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES["anh_dai_dien"]["name"]);
                if (move_uploaded_file($_FILES["anh_dai_dien"]["tmp_name"], $targetDir . $fileName)) {
                    $anh = $fileName;
                }
            }

            // Thu thập dữ liệu từ form
            $data = [
                'ho_ten' => $_POST['ho_ten'],
                'ngay_sinh' => !empty($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : null,
                'email' => $_POST['email'],
                'mat_khau' => $_POST['mat_khau'], 
                'sdt' => $_POST['sdt'],
                'anh_dai_dien' => $anh,
                'ngon_ngu' => $_POST['ngon_ngu'],
                'chung_chi' => $_POST['chung_chi'],
                'kinh_nghiem' => $_POST['kinh_nghiem'],
                'suc_khoe' => $_POST['suc_khoe'],
                'phan_loai' => $_POST['phan_loai'],
                'trang_thai' => 'SanSang' // Mặc định khi tạo mới
            ];

            (new GuideModel())->insert($data);
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides');
        }
    }

    // Form sửa
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $guide = (new GuideModel())->getDetail($id);
        if ($guide) {
            $this->render('pages/admin/guides/form_sua', ['guide' => $guide]);
        }
    }

    // Xử lý sửa
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'];
            $model = new GuideModel();
            $oldGuide = $model->getDetail($id);
            
            // Xử lý ảnh
            $anh = $oldGuide['anh_dai_dien'];
            if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
                $targetDir = "public/img/hdv/";
                $fileName = time() . "_" . basename($_FILES["anh_dai_dien"]["name"]);
                if (move_uploaded_file($_FILES["anh_dai_dien"]["tmp_name"], $targetDir . $fileName)) {
                    $anh = $fileName;
                }
            }

            $data = [
                'ho_ten' => $_POST['ho_ten'],
                'ngay_sinh' => !empty($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : null,
                'email' => $_POST['email'],
                'sdt' => $_POST['sdt'],
                'anh_dai_dien' => $anh,
                'ngon_ngu' => $_POST['ngon_ngu'],
                'chung_chi' => $_POST['chung_chi'],
                'kinh_nghiem' => $_POST['kinh_nghiem'],
                'suc_khoe' => $_POST['suc_khoe'],
                'phan_loai' => $_POST['phan_loai'],
                'trang_thai' => $_POST['trang_thai']
            ];

            $model->update($id, $data);
            header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides');
        }
    }

    // Xóa
    public function delete() {
        $id = $_GET['id'] ?? 0;
        (new GuideModel())->delete($id);
        header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides');
    }

    // Xem chi tiết hồ sơ nhân sự
    public function detail() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') exit;
        
        $id = $_GET['id'] ?? 0;
        $model = new GuideModel();
        
        // Lấy thông tin cá nhân
        $guide = $model->getDetail($id);
        
        // Lấy lịch sử dẫn tour
        $history = $model->getHistory($id);

        if ($guide) {
            $this->render('pages/admin/guides/detail', [
                'guide' => $guide,
                'history' => $history
            ]);
        } else {
             // Nếu không tìm thấy, quay về danh sách
             header('Location: ' . BASE_URL . 'routes/index.php?action=admin-guides');
        }
    }
}
?>