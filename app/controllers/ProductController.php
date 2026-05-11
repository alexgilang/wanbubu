<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Uploader.php';

Session::start();

class ProductController {
    private $productModel;
    private $categoryModel;
    private $uploader;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->uploader = new Uploader();
    }
    
    public function index() {
        Session::requireAdmin();
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $editProduct = isset($_GET['edit']) ? $this->productModel->getById($_GET['edit']) : null;
        $message = Session::flash('message');
        $error = Session::flash('error');
        require_once __DIR__ . '/../views/admin/products.php';
    }
    
    public function store() {
        Session::requireAdmin();
        $gambar = 'default-product.jpg';
        
        if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $result = $this->uploader->upload($_FILES['gambar']);
            if($result['success']) {
                $gambar = $result['filename'];
            } else {
                Session::flash('error', $result['message']);
                header('Location: /wanbubu/admin.php?page=products');
                exit;
            }
        }
        
        $data = [
            'category_id' => $_POST['category_id'],
            'nama' => trim($_POST['nama']),
            'slug' => $this->slugify($_POST['nama']),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            'harga' => (int)$_POST['harga'],
            'stok' => (int)$_POST['stok'],
            'gambar' => $gambar,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0
        ];
        
        $this->productModel->create($data);
        Session::flash('message', 'Produk berhasil ditambahkan!');
        header('Location: /wanbubu/admin.php?page=products');
        exit;
    }
    
    public function update() {
        Session::requireAdmin();
        $id = $_POST['product_id'];
        $produk = $this->productModel->getById($id);
        $gambar = $produk['gambar'] ?? 'default-product.jpg';
        
        if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
            $result = $this->uploader->upload($_FILES['gambar'], $gambar);
            if($result['success']) {
                $gambar = $result['filename'];
            }
        }
        
        $data = [
            'category_id' => $_POST['category_id'],
            'nama' => trim($_POST['nama']),
            'slug' => $this->slugify($_POST['nama']),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
            'harga' => (int)$_POST['harga'],
            'stok' => (int)$_POST['stok'],
            'gambar' => $gambar,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0
        ];
        
        $this->productModel->update($id, $data);
        Session::flash('message', 'Produk berhasil diperbarui!');
        header('Location: /wanbubu/admin.php?page=products');
        exit;
    }
    
    public function delete() {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if($id) {
            $p = $this->productModel->getById($id);
            if($p && !empty($p['gambar']) && $p['gambar'] != 'default-product.jpg') {
                $f = __DIR__ . '/../../uploads/products/' . $p['gambar'];
                if(file_exists($f)) unlink($f);
            }
            $this->productModel->delete($id);
            Session::flash('message', 'Produk berhasil dihapus!');
        }
        header('Location: /wanbubu/admin.php?page=products');
        exit;
    }
    
    public function toggleFeatured() {
        Session::requireAdmin();
        $id = $_GET['id'] ?? null;
        if($id) $this->productModel->toggleFeatured($id);
        header('Location: /wanbubu/admin.php?page=products');
        exit;
    }
    
    private function slugify($text) {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text) ?: 'p-' . time();
    }
}