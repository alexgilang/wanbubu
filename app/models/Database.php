<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'wanbubu_db';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    public $conn;
    
    public function connect() {
        $this->conn = null;
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $this->conn = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch(PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
        return $this->conn;
    }
}