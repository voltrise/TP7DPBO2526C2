<?php
/**
 * Kelas Faction - Model Entitas Organisasi/Fraksi Kota Hethereau
 * Mengimplementasikan prinsip enkapsulasi OOP & prepared statements PDO
 */

require_once __DIR__ . '/../config/db.php';

class Faction {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    /**
     * Mengambil seluruh data fraksi
     */
    public function getAllFactions() {
        $sql = "SELECT f.*, COUNT(e.id) AS total_espers 
                FROM factions f 
                LEFT JOIN espers e ON f.id = e.faction_id 
                GROUP BY f.id 
                ORDER BY f.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail fraksi berdasarkan ID
     */
    public function getFactionById($id) {
        $sql = "SELECT * FROM factions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan fraksi baru ke basis data
     */
    public function createFaction($name, $district, $leader, $description, $color_tag = '#00e5ff') {
        $sql = "INSERT INTO factions (name, district, leader, description, color_tag) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $district, $leader, $description, $color_tag]);
    }

    /**
     * Memperbarui data fraksi yang sudah ada
     */
    public function updateFaction($id, $name, $district, $leader, $description, $color_tag) {
        $sql = "UPDATE factions SET name = ?, district = ?, leader = ?, description = ?, color_tag = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $district, $leader, $description, $color_tag, $id]);
    }

    /**
     * Menghapus fraksi berdasarkan ID
     */
    public function deleteFaction($id) {
        $sql = "DELETE FROM factions WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
