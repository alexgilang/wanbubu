<?php
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../helpers/Session.php';
Session::start(); Session::requireAdmin();

$orderModel = new Order();

// Update status
if(isset($_POST['update_status'])) {
    $orderModel->updateStatus($_POST['order_id'], $_POST['status']);
    header('Location: /wanbubu/admin.php?page=orders');
    exit;
}

$viewOrder = isset($_GET['view']) ? $orderModel->getById($_GET['view']) : null;
$orderItems = $viewOrder ? $orderModel->getItems($viewOrder['id']) : [];
$orders = $orderModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        <main class="main-content">
            <header class="content-header">
                <h1>🛒 <?php echo $viewOrder ? 'Detail Pesanan' : 'Kelola Pesanan'; ?></h1>
                <?php if($viewOrder): ?><a href="/wanbubu/admin.php?page=orders" class="btn btn-warning"><i class="fas fa-arrow-left"></i> Kembali</a><?php endif; ?>
            </header>
            
            <?php if($viewOrder): ?>
            <div class="card">
                <h3>Pesanan #<?php echo htmlspecialchars($viewOrder['order_number']); ?></h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin:20px 0;">
                    <div><strong>Pelanggan:</strong> <?php echo htmlspecialchars($viewOrder['nama_lengkap'] ?? $viewOrder['username'] ?? 'Tamu'); ?></div>
                    <div><strong>Total:</strong> Rp <?php echo number_format($viewOrder['total_harga'], 0, ',', '.'); ?></div>
                    <div><strong>Pembayaran:</strong> <?php echo htmlspecialchars($viewOrder['metode_pembayaran']); ?></div>
                    <div><strong>Tanggal:</strong> <?php echo date('d M Y H:i', strtotime($viewOrder['created_at'])); ?></div>
                </div>
                <p><strong>Alamat:</strong> <?php echo nl2br(htmlspecialchars($viewOrder['alamat_pengiriman'])); ?></p>
                
                <h4 style="margin-top:20px;">Item Pesanan</h4>
                <table class="admin-table">
                    <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        <?php $total = 0; foreach($orderItems as $item): $sub = $item['quantity'] * $item['harga_satuan']; $total += $sub; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($sub, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr><td colspan="3"><strong>Total</strong></td><td><strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></td></tr>
                    </tbody>
                </table>
                
                <form method="POST" style="margin-top:20px;">
                    <input type="hidden" name="order_id" value="<?php echo $viewOrder['id']; ?>">
                    <label>Update Status:</label>
                    <select name="status" style="padding:8px;border-radius:8px;border:1px solid #ddd;margin:0 10px;">
                        <?php foreach(['menunggu','diproses','dikirim','selesai','dibatalkan'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $viewOrder['status'] == $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                </form>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead><tr><th>No. Pesanan</th><th>Pelanggan</th><th>Total</th><th>Pembayaran</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php foreach($orders as $o): 
                                $sl = ['menunggu'=>['Menunggu','badge-warning'],'diproses'=>['Diproses','badge-info'],'dikirim'=>['Dikirim','badge-primary'],'selesai'=>['Selesai','badge-success'],'dibatalkan'=>['Dibatalkan','badge-danger']];
                                $s = $sl[$o['status']] ?? ['-',''];
                            ?>
                            <tr>
                                <td><strong><?php echo $o['order_number']; ?></strong></td>
                                <td><?php echo htmlspecialchars($o['nama_lengkap'] ?? 'Tamu'); ?></td>
                                <td>Rp <?php echo number_format($o['total_harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $o['metode_pembayaran']; ?></td>
                                <td><span class="badge <?php echo $s[1]; ?>"><?php echo $s[0]; ?></span></td>
                                <td><?php echo date('d M Y', strtotime($o['created_at'])); ?></td>
                                <td><a href="/wanbubu/admin.php?page=orders&view=<?php echo $o['id']; ?>" class="btn btn-info btn-sm">Lihat</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>