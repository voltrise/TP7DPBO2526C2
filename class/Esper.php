<?php
/**
 * Kelas Esper - Model Entitas Karakter Esper (Pengendali Supranatural)
 * Berelasi many-to-one terhadap entitas Faction melalui foreign key faction_id
 */

require_once __DIR__ . '/../config/db.php';

class Esper {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    /**
     * Mengambil seluruh data esper dengan relasi nama fraksi
     * Mendukung filter atribut opsional dan pencarian nama
     */
    public function getAllEspers($attributeFilter = '', $search = '') {
        $sql = "SELECT e.*, f.name AS faction_name, f.color_tag AS faction_color, g.id AS guide_id, g.tier_rating 
                FROM espers e 
                JOIN factions f ON e.faction_id = f.id 
                LEFT JOIN esper_guides g ON e.id = g.esper_id 
                WHERE 1=1";
        $params = [];

        if (!empty($attributeFilter)) {
            $sql .= " AND e.attribute = ?";
            $params[] = $attributeFilter;
        }

        if (!empty($search)) {
            $sql .= " AND e.name LIKE ?";
            $params[] = '%' . $search . '%';
        }

        $sql .= " ORDER BY e.rarity DESC, e.name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil detail esper berdasarkan ID
     */
    public function getEsperById($id) {
        $sql = "SELECT e.*, f.name AS faction_name, f.color_tag AS faction_color 
                FROM espers e 
                JOIN factions f ON e.faction_id = f.id 
                WHERE e.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan data esper baru
     */
    public function createEsper($name, $rarity, $attribute, $weapon_type, $role, $faction_id, $image_url = '', $bio = '') {
        $sql = "INSERT INTO espers (name, rarity, attribute, weapon_type, role, faction_id, image_url, bio) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $rarity, $attribute, $weapon_type, $role, $faction_id, $image_url, $bio]);
    }

    /**
     * Memperbarui data esper yang sudah ada
     */
    public function updateEsper($id, $name, $rarity, $attribute, $weapon_type, $role, $faction_id, $image_url, $bio) {
        $sql = "UPDATE espers 
                SET name = ?, rarity = ?, attribute = ?, weapon_type = ?, role = ?, faction_id = ?, image_url = ?, bio = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $rarity, $attribute, $weapon_type, $role, $faction_id, $image_url, $bio, $id]);
    }

    /**
     * Menghapus data esper berdasarkan ID
     */
    public function deleteEsper($id) {
        $sql = "DELETE FROM espers WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
