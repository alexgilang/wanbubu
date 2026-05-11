<?php
require_once __DIR__ . '/app/models/Database.php';

$db = (new Database())->connect();

// Password baru: admin123
$password_baru = 'admin123';
$hash = password_hash($password_baru, PASSWORD_DEFAULT);

// Update password admin
$stmt = $db->prepare("UPDATE users SET password = :pass WHERE username = 'admin'");
$result = $stmt->execute(['pass' => $hash]);

if($result) {
    echo "<h1>✅ Password admin berhasil direset!</h1>";
    echo "<p>Username: <b>admin</b></p>";
    echo "<p>Password: <b>admin123</b></p>";
    echo "<p>Hash baru: <b>" . $hash . "</b></p>";
    echo "<br><a href='/wanbubu/app/views/admin/login.php' style='padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:8px;'>Login Sekarang</a>";
    echo "<br><br><p style='color:red;'><b>⚠️ HAPUS FILE INI SETELAH SELESAI!</b></p>";
} else {
    echo "<h1>❌ Gagal reset password</h1>";
}
?>