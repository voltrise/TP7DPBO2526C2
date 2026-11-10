<?php
/**
 * Kelas EsperGuide - Model Entitas Panduan Build & Tier List Karakter
 * Berelasi many-to-one terhadap entitas Esper melalui foreign key esper_id
 * Terhubung secara relasional bertingkat ke Factions via Espers
 */

require_once __DIR__ . '/../config/db.php';

class EsperGuide {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    /**
     * Mengambil seluruh data panduan build beserta detail karakter dan fraksinya
     */
    public function getAllGuides($tierFilter = '') {
        $sql = "SELECT g.*, e.name AS esper_name, e.rarity, e.attribute, e.weapon_type, e.role, e.image_url, 
                       f.name AS faction_name, f.color_tag AS faction_color 
                FROM esper_guides g 
                JOIN espers e ON g.esper_id = e.id 
                JOIN factions f ON e.faction_id = f.id 
                WHERE 1=1";
        $params = [];

        if (!empty($tierFilter)) {
            $sql .= " AND g.tier_rating = ?";
            $params[] = $tierFilter;
        }

        $sql .= " ORDER BY 
                    CASE g.tier_rating 
                        WHEN 'T0' THEN 1 
                        WHEN 'T0.5' THEN 2 
                        WHEN 'T1' THEN 3 
                        WHEN 'T2' THEN 4 
                        ELSE 5 
                    END, g.updated_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil satu data panduan berdasarkan ID panduan
     */
    public function getGuideById($id) {
        $sql = "SELECT g.*, e.name AS esper_name, e.rarity, e.attribute, e.weapon_type, e.role, 
                       f.name AS faction_name, f.color_tag AS faction_color 
                FROM esper_guides g 
                JOIN espers e ON g.esper_id = e.id 
                JOIN factions f ON e.faction_id = f.id 
                WHERE g.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mengambil panduan berdasarkan ID Esper
     */
    public function getGuideByEsperId($esper_id) {
        $sql = "SELECT * FROM esper_guides WHERE esper_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$esper_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Menambahkan panduan build baru
     */
    public function createGuide($esper_id, $guide_title, $tier_rating, $recommended_weapon, $recommended_matrix, $team_synergy, $playstyle_notes) {
        $sql = "INSERT INTO esper_guides (esper_id, guide_title, tier_rating, recommended_weapon, recommended_matrix, team_synergy, playstyle_notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$esper_id, $guide_title, $tier_rating, $recommended_weapon, $recommended_matrix, $team_synergy, $playstyle_notes]);
    }

    /**
     * Memperbarui panduan build yang sudah ada
     */
    public function updateGuide($id, $esper_id, $guide_title, $tier_rating, $recommended_weapon, $recommended_matrix, $team_synergy, $playstyle_notes) {
        $sql = "UPDATE esper_guides 
                SET esper_id = ?, guide_title = ?, tier_rating = ?, recommended_weapon = ?, recommended_matrix = ?, team_synergy = ?, playstyle_notes = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$esper_id, $guide_title, $tier_rating, $recommended_weapon, $recommended_matrix, $team_synergy, $playstyle_notes, $id]);
    }

    /**
     * Menghapus panduan build berdasarkan ID
     */
    public function deleteGuide($id) {
        $sql = "DELETE FROM esper_guides WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
