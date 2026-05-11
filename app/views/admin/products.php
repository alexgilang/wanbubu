<?php

$products = $products ?? [];
$categories = $categories ?? [];
$editProduct = $editProduct ?? null;
$message = $message ?? null;
$error = $error ?? null;
$pageTitle = $editProduct ? 'Edit Produk' : 'Kelola Produk';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Admin Wanbubu</title>
    <link rel="stylesheet" href="/wanbubu/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php require_once __DIR__ . '/../layouts/admin-sidebar.php'; ?>
        
        <main class="main-content">
            <header class="content-header">
                <div>
                    <h1>📦 <?php echo $pageTitle; ?></h1>
                    <p class="text-muted">Total: <?php echo count($products); ?> produk</p>
                </div>
                <div class="btn-group">
                    <?php if($editProduct): ?>
                        <a href="/wanbubu/admin.php?page=products" class="btn btn-warning"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <?php else: ?>
                        <button class="btn btn-primary" onclick="showForm()"><i class="fas fa-plus"></i> Tambah Produk</button>
                    <?php endif; ?>
                </div>
            </header>
            
            <?php if($message): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <?php if($error): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Form Produk -->
            <div class="card" id="form-produk" style="display:<?php echo $editProduct ? 'block' : 'none'; ?>;">
                <div class="card-header"><h3><?php echo $editProduct ? '✏️ Edit Produk' : '➕ Tambah Produk Baru'; ?></h3></div>
                
                <form method="POST" action="/wanbubu/admin.php?page=products&action=<?php echo $editProduct ? 'update' : 'store'; ?>" enctype="multipart/form-data">
                    <?php if($editProduct): ?>
                    <input type="hidden" name="product_id" value="<?php echo $editProduct['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Nama Produk *</label>
                        <input type="text" name="nama" required value="<?php echo $editProduct ? htmlspecialchars($editProduct['nama']) : ''; ?>" placeholder="Masukkan nama produk">
                    </div>
                    
                    <div class="form-group">
                        <label>Kategori *</label>
                        <select name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['nama']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga (Rp) *</label>
                        <input type="number" name="harga" required value="<?php echo $editProduct ? $editProduct['harga'] : ''; ?>" placeholder="15000" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Stok *</label>
                        <input type="number" name="stok" required value="<?php echo $editProduct ? $editProduct['stok'] : ''; ?>" placeholder="100" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" rows="4" placeholder="Deskripsikan produk..."><?php echo $editProduct ? htmlspecialchars($editProduct['deskripsi'] ?? '') : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Gambar Produk (600x600px)</label>
                        <div class="upload-area" id="dropZone" onclick="document.getElementById('input-gambar').click()">
                            <input type="file" name="gambar" id="input-gambar" accept="image/*" style="display:none;" onchange="previewImage(event)">
                            <div id="preview-container" style="margin-bottom:10px;">
                                <?php if($editProduct && !empty($editProduct['gambar']) && $editProduct['gambar'] != 'default-product.jpg'): ?>
                                <img src="/wanbubu/uploads/products/<?php echo $editProduct['gambar']; ?>" id="preview" style="max-width:250px;max-height:250px;border-radius:10px;object-fit:cover;">
                                <?php else: ?>
                                <img src="" id="preview" style="max-width:250px;max-height:250px;border-radius:10px;object-fit:cover;display:none;">
                                <?php endif; ?>
                            </div>
                            <i class="fas fa-cloud-upload-alt" style="font-size:40px;color:#4CAF50;"></i>
                            <p><strong>Klik atau Seret Gambar</strong></p>
                            <p style="color:#999;font-size:12px;">JPG, PNG, WebP | Max 5MB | 600x600px</p>
                            <p id="file-info" style="color:#4CAF50;font-size:13px;margin-top:10px;"></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><input type="checkbox" name="is_featured" value="1" <?php echo ($editProduct && $editProduct['is_featured']) ? 'checked' : ''; ?>> Produk Unggulan</label>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> <?php echo $editProduct ? 'Perbarui' : 'Simpan'; ?></button>
                        <?php if($editProduct): ?>
                        <a href="/wanbubu/admin.php?page=products" class="btn btn-warning">Batal</a>
                        <?php else: ?>
                        <button type="button" class="btn btn-warning" onclick="hideForm()">Batal</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <!-- Tabel Produk -->
            <div class="card">
                <div class="card-header">
                    <h3>📋 Daftar Produk</h3>
                    <div style="width:250px;">
                        <input type="text" id="search" placeholder="Cari produk..." onkeyup="filterTable()" style="width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:8px;">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="admin-table" id="produk-table">
                        <thead>
                            <tr>
                                <th width="70">Gambar</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Rating</th>
                                <th>Unggulan</th>
                                <th width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($products) > 0): ?>
                                <?php foreach($products as $p): ?>
                                <tr>
                                    <td>
                                        <?php if(!empty($p['gambar']) && $p['gambar'] != 'default-product.jpg'): ?>
                                        <img src="/wanbubu/uploads/products/<?php echo $p['gambar']; ?>" style="width:50px;height:50px;border-radius:8px;object-fit:cover;" alt="">
                                        <?php else: ?>
                                        <div style="width:50px;height:50px;background:#e8f5e9;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-image" style="color:#4CAF50;"></i>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($p['nama']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($p['kategori_nama'] ?? '-'); ?></td>
                                    <td><strong>Rp <?php echo number_format($p['harga'], 0, ',', '.'); ?></strong></td>
                                    <td>
                                        <?php if($p['stok'] <= 0): ?><span class="badge badge-danger">Habis</span>
                                        <?php elseif($p['stok'] <= 5): ?><span class="badge badge-warning"><?php echo $p['stok']; ?> ⚠️</span>
                                        <?php else: ?><span class="badge badge-success"><?php echo $p['stok']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $p['rating'] > 0 ? "⭐ {$p['rating']} ({$p['total_rating']})" : '<span class="text-muted">-</span>'; ?></td>
                                    <td style="text-align:center;">
                                        <a href="/wanbubu/admin.php?page=products&action=toggleFeatured&id=<?php echo $p['id']; ?>" style="font-size:20px;text-decoration:none;">
                                            <?php echo $p['is_featured'] ? '⭐' : '☆'; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/wanbubu/admin.php?page=products&edit=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="/wanbubu/admin.php?page=products&action=delete&id=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk?')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8"><div class="empty-state"><i class="fas fa-box-open"></i><h4>Belum ada produk</h4></div></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script>
    function showForm() { var f = document.getElementById('form-produk'); f.style.display = 'block'; f.scrollIntoView({behavior:'smooth'}); }
    function hideForm() { document.getElementById('form-produk').style.display = 'none'; }
    function previewImage(e) {
        var file = e.target.files[0];
        if(file) {
            var reader = new FileReader();
            reader.onload = function(r) { var img = document.getElementById('preview'); img.src = r.target.result; img.style.display = 'block'; };
            reader.readAsDataURL(file);
            document.getElementById('file-info').textContent = '📎 ' + file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
        }
    }
    var dz = document.getElementById('dropZone');
    if(dz) {
        dz.addEventListener('dragover', function(e){ e.preventDefault(); this.style.borderColor='#4CAF50'; this.style.background='#f0f9f0'; });
        dz.addEventListener('dragleave', function(){ this.style.borderColor='#ddd'; this.style.background=''; });
        dz.addEventListener('drop', function(e){
            e.preventDefault(); this.style.borderColor='#ddd'; this.style.background='';
            var file = e.dataTransfer.files[0], input = document.getElementById('input-gambar');
            if(file) { var dt = new DataTransfer(); dt.items.add(file); input.files = dt.files; input.dispatchEvent(new Event('change', {bubbles:true})); }
        });
    }
    function filterTable() {
        var f = document.getElementById('search').value.toUpperCase();
        document.querySelectorAll('#produk-table tbody tr').forEach(function(r){ r.style.display = r.textContent.toUpperCase().includes(f) ? '' : 'none'; });
    }
    </script>
</body>
</html>