<?php
// Kiểm tra và định nghĩa hàm currency_format nếu chưa có trong helper.php
if (!function_exists('currency_format')) {
    function currency_format($number)
    {
        if ($number === null) return '0 VNĐ';
        return number_format($number, 0, ',', '.') . ' VNĐ';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Điều Hành Dịch Vụ Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                            <h6 class="text-white text-capitalize ps-3">Báo Cáo Doanh Thu, Chi Phí, Lợi Nhuận</h6>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-2">
                        <div class="p-3">
                            <form action="<?= BASE_URL ?>/admin-statistics" method="GET" class="row align-items-end">
                                <div class="col-md-3 mb-3">
                                    <label for="from_date" class="form-label">Từ ngày:</label>
                                    <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $fromDate ?>">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="to_date" class="form-label">Đến ngày:</label>
                                    <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $toDate ?>">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                </div>
                            </form>
                        </div>

                        <div class="row px-3 mt-4">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card p-3 shadow-sm border-start border-3 border-success">
                                    <p class="text-sm mb-0 text-capitalize">Tổng Doanh Thu (Tour đã khởi hành/Thanh toán)</p>
                                    <h4 class="font-weight-bolder text-success mb-0">
                                        <?= currency_format($overallStats['doanh_thu']) ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card p-3 shadow-sm border-start border-3 border-danger">
                                    <p class="text-sm mb-0 text-capitalize">Tổng Chi Phí (Dịch vụ đã dùng)</p>
                                    <h4 class="font-weight-bolder text-danger mb-0">
                                        <?= currency_format($overallStats['chi_phi']) ?>
                                    </h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 mb-4">
                                <div class="card p-3 shadow-sm border-start border-3 border-info">
                                    <p class="text-sm mb-0 text-capitalize">Lợi Nhuận Ròng (Doanh thu - Chi phí)</p>
                                    <h4 class="font-weight-bolder text-info mb-0">
                                        <?= currency_format($overallStats['loi_nhuan']) ?>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive p-0 mt-4">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tên Tour</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Số chuyến đi</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Doanh thu</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Chi phí</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lợi Nhuận</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hiệu suất</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tourStats)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Không có dữ liệu tour trong giai đoạn này.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($tourStats as $stat): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"><?= $stat['ten_tour'] ?></h6>
                                                            <p class="text-xs text-secondary mb-0">ID: <?= $stat['tour_id'] ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0"><?= $stat['so_chuyen_di'] ?></p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-success text-xs font-weight-bold"><?= currency_format($stat['doanh_thu']) ?></span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span class="text-danger text-xs font-weight-bold"><?= currency_format($stat['chi_phi']) ?></span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php
                                                    $loiNhuan = $stat['loi_nhuan'];
                                                    $color = $loiNhuan >= 0 ? 'text-info' : 'text-danger';
                                                    ?>
                                                    <span class="<?= $color ?> text-sm font-weight-bold"><?= currency_format($loiNhuan) ?></span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <?php
                                                    $doanhThu = $stat['doanh_thu'];
                                                    $hieuSuat = ($doanhThu > 0) ? round(($loiNhuan / $doanhThu) * 100, 2) : 0;
                                                    $hieuSuatColor = $hieuSuat >= 10 ? 'bg-gradient-success' : ($hieuSuat > 0 ? 'bg-gradient-warning' : 'bg-gradient-danger');
                                                    ?>
                                                    <span class="badge badge-sm <?= $hieuSuatColor ?>"><?= $hieuSuat ?>%</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>