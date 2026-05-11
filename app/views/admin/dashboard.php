<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../helpers/Session.php';

Session::start();
Session::requireAdmin();

$productModel = new Product();
$orderModel = new Order();
$userModel = new User();

$totalProducts = $productModel->countAll();
$totalOrders = $orderModel->countAll();
$totalRevenue = $orderModel->totalRevenue();
$totalUsers = $userModel->countUsers();
$recentOrders = $orderModel->getRecent(5);
$salesData = $orderModel->getSalesData(30);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <?php 
$sidebarPath = __DIR__ . '/../layouts/admin-sidebar.php';
if(file_exists($sidebarPath)) {
    require_once $sidebarPath;
} else {
    echo '<div style="background:red;color:white;padding:20px;">';
    echo 'File sidebar tidak ditemukan di: ' . $sidebarPath;
    echo '<br>Silakan buat file: app/views/layouts/admin-sidebar.php';
    echo '</div>';
}
?>
        <main class="main-content">
            <header class="content-header">
                <div>
                    <h1>📊 Dashboard</h1>
                    <p class="text-muted">Selamat datang, <?php echo htmlspecialchars(Session::get('nama_lengkap')); ?></p>
                </div>
                <div>
                    <span class="text-muted"><?php echo date('d M Y'); ?></span>
                </div>
            </header>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $totalOrders; ?></h3>
                        <p>Total Pesanan</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-info">
                        <h3>Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></h3>
                        <p>Total Pendapatan</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-box"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $totalProducts; ?></h3>
                        <p>Total Produk</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $totalUsers; ?></h3>
                        <p>Pelanggan</p>
                    </div>
                </div>
            </div>
            
            <!-- Charts -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3>📈 Penjualan (30 Hari Terakhir)</h3>
                    <canvas id="salesChart" height="250"></canvas>
                </div>
                <div class="chart-card">
                    <h3>📋 Status Pesanan</h3>
                    <canvas id="orderChart" height="250"></canvas>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h3>🛒 Pesanan Terbaru</h3>
                    <a href="/wanbubu/admin.php?page=orders" class="btn btn-primary btn-sm">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($recentOrders) > 0): ?>
                                <?php foreach($recentOrders as $o): 
                                    $statusLabels = [
                                        'menunggu' => ['Menunggu', 'badge-warning'],
                                        'diproses' => ['Diproses', 'badge-info'],
                                        'dikirim' => ['Dikirim', 'badge-primary'],
                                        'selesai' => ['Selesai', 'badge-success'],
                                        'dibatalkan' => ['Dibatalkan', 'badge-danger']
                                    ];
                                    $s = $statusLabels[$o['status']] ?? ['-', ''];
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($o['order_number']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($o['nama_lengkap'] ?? 'Tamu'); ?></td>
                                    <td>Rp <?php echo number_format($o['total_harga'], 0, ',', '.'); ?></td>
                                    <td><span class="badge <?php echo $s[1]; ?>"><?php echo $s[0]; ?></span></td>
                                    <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                                    <td>
                                        <a href="/wanbubu/admin.php?page=orders&view=<?php echo $o['id']; ?>" class="btn btn-info btn-sm">Lihat</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h4>Belum ada pesanan</h4>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script>
    // Sales Chart
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: [<?php 
                $labels = []; $totals = [];
                if(count($salesData) > 0) {
                    foreach($salesData as $d) {
                        $labels[] = "'" . date('d M', strtotime($d['tgl'])) . "'";
                        $totals[] = $d['total'];
                    }
                } else { 
                    $labels = ["'Belum ada data'"]; $totals = [0]; 
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [<?php echo implode(',', $totals); ?>],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4, fill: true, borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    
    // Order Status Chart
    new Chart(document.getElementById('orderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: [10, 15, 20, 35, 5],
                backgroundColor: ['#ffc107', '#17a2b8', '#4CAF50', '#28a745', '#dc3545'],
                borderWidth: 2, borderColor: '#fff'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
    </script>
</body>
</html>