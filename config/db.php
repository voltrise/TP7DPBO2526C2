<?php
/**
 * Kelas Database - Helper Koneksi Basis Data Terpusat
 * Menggunakan PDO (PHP Data Objects) dengan Prepared Statement Enforcement
 * Mata Kuliah: Desain dan Pemrograman Berorientasi Objek (DPBO)
 */

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "db_nte_guide";
    public $conn;

    public function __construct() {
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Koneksi basis data gagal: " . $e->getMessage() . "<br>Pastikan server MySQL sudah berjalan dan file <code>database/db_nte_guide.sql</code> telah diimpor.");
        }
    }
}
?>
