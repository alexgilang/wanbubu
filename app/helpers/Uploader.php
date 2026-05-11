<?php
class Uploader {
    private $uploadDir;
    private $targetWidth = 600;
    private $targetHeight = 600;
    private $quality = 85;
    private $maxSize = 5242880; // 5MB
    private $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    
    public function __construct() {
        $this->uploadDir = __DIR__ . '/../../uploads/products/';
        if(!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    public function upload($file, $oldFile = null) {
        // Validasi error upload
        if(!isset($file) || $file['error'] !== 0) {
            return ['success' => false, 'message' => 'File tidak ditemukan atau error upload'];
        }
        
        // Validasi ukuran
        if($file['size'] > $this->maxSize) {
            return ['success' => false, 'message' => 'Ukuran file terlalu besar (maks 5MB)'];
        }
        
        // Validasi tipe file
        if(!in_array($file['type'], $this->allowedTypes)) {
            return ['success' => false, 'message' => 'Format file tidak didukung (gunakan JPG, PNG, atau WebP)'];
        }
        
        // Generate nama file unik
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'product-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $this->uploadDir . $filename;
        
        // Hapus file lama jika ada
        if($oldFile && $oldFile != 'default-product.jpg' && file_exists($this->uploadDir . $oldFile)) {
            unlink($this->uploadDir . $oldFile);
        }
        
        // Resize dan simpan gambar
        if($this->resizeImage($file['tmp_name'], $destination, $file['type'])) {
            return ['success' => true, 'filename' => $filename];
        }
        
        return ['success' => false, 'message' => 'Gagal menyimpan gambar'];
    }
    
    private function resizeImage($source, $destination, $type) {
        // Buat resource dari source
        switch($type) {
            case 'image/jpeg':
                $srcImage = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($source);
                break;
            case 'image/webp':
                $srcImage = imagecreatefromwebp($source);
                break;
            default:
                return false;
        }
        
        if(!$srcImage) return false;
        
        // Dapatkan ukuran asli
        $srcWidth = imagesx($srcImage);
        $srcHeight = imagesy($srcImage);
        
        // Buat canvas ukuran target
        $dstImage = imagecreatetruecolor($this->targetWidth, $this->targetHeight);
        
        // Background putih
        $white = imagecolorallocate($dstImage, 255, 255, 255);
        imagefill($dstImage, 0, 0, $white);
        
        // Hitung proporsi resize
        $ratio = min($this->targetWidth / $srcWidth, $this->targetHeight / $srcHeight);
        $newWidth = round($srcWidth * $ratio);
        $newHeight = round($srcHeight * $ratio);
        $x = round(($this->targetWidth - $newWidth) / 2);
        $y = round(($this->targetHeight - $newHeight) / 2);
        
        // Resize gambar
        imagecopyresampled($dstImage, $srcImage, $x, $y, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);
        
        // Simpan gambar
        $result = false;
        switch($type) {
            case 'image/jpeg':
                $result = imagejpeg($dstImage, $destination, $this->quality);
                break;
            case 'image/png':
                $result = imagepng($dstImage, $destination, 6);
                break;
            case 'image/webp':
                $result = imagewebp($dstImage, $destination, $this->quality);
                break;
        }
        
        // Bersihkan memory
        imagedestroy($srcImage);
        imagedestroy($dstImage);
        
        return $result;
    }
}