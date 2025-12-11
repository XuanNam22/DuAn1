<?php
class SupplierController extends BaseController {
    private $model;

    public function __construct() {
        $this->model = new SupplierModel();
    }

    public function index() {
        $suppliers = $this->model->getAll();
        $this->render('pages/admin/suppliers/index', ['suppliers' => $suppliers]);
    }

    public function create() {
        $this->render('pages/admin/suppliers/form_them');
    }

    public function store() {
        $data = [
            'ten' => $_POST['ten_ncc'],
            'dv' => $_POST['dich_vu'],
            'sdt' => $_POST['sdt'],
            'email' => $_POST['email'],
            'dc' => $_POST['dia_chi']
        ];
        $this->model->insert($data);
        header('Location: index.php?action=admin-suppliers');
    }

    public function edit() {
        $id = $_GET['id'];
        $supplier = $this->model->getDetail($id);
        $this->render('pages/admin/suppliers/form_sua', ['supplier' => $supplier]);
    }

    public function update() {
        $id = $_POST['id'];
        $data = [
            'ten' => $_POST['ten_ncc'],
            'dv' => $_POST['dich_vu'],
            'sdt' => $_POST['sdt'],
            'email' => $_POST['email'],
            'dc' => $_POST['dia_chi']
        ];
        $this->model->update($id, $data);
        header('Location: index.php?action=admin-suppliers');
    }

    public function delete() {
        $id = $_GET['id'];
        $this->model->delete($id);
        header('Location: index.php?action=admin-suppliers');
    }
}
?>