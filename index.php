<?php
/**
 * Entry Point Utama (Front Controller & Router)
 * Aplikasi Web Neverness to Everness (NTE) Esper Database & Guide
 * Mata Kuliah: Desain dan Pemrograman Berorientasi Objek (DPBO)
 * Pengembang: Devindra Azra Dwi Putra (NIM 2509003) - Kelas C2
 */

session_start();

require_once __DIR__ . '/class/Faction.php';
require_once __DIR__ . '/class/Esper.php';
require_once __DIR__ . '/class/EsperGuide.php';

$factionModel = new Faction();
$esperModel = new Esper();
$guideModel = new EsperGuide();

// =====================================================================
// Penanganan Aksi HTTP POST (Create & Update)
// Mengadopsi Pola Post/Redirect/Get (PRG) & Prepared Statements
// =====================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Aksi Entitas Factions
    if (isset($_POST['submit_faction'])) {
        $actionType = $_POST['action_type'] ?? 'create';
        $name = trim($_POST['name'] ?? '');
        $district = trim($_POST['district'] ?? '');
        $leader = trim($_POST['leader'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $colorTag = trim($_POST['color_tag'] ?? '#00e5ff');

        try {
            if ($actionType === 'create') {
                $factionModel->createFaction($name, $district, $leader, $description, $colorTag);
                $_SESSION['flash_success'] = "Fraksi <strong>" . htmlspecialchars($name) . "</strong> berhasil didaftarkan!";
            } elseif ($actionType === 'update') {
                $id = (int)$_POST['id'];
                $factionModel->updateFaction($id, $name, $district, $leader, $description, $colorTag);
                $_SESSION['flash_success'] = "Data fraksi <strong>" . htmlspecialchars($name) . "</strong> berhasil diperbarui!";
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Gagal memproses data fraksi: " . $e->getMessage();
        }
        header("Location: index.php?page=factions");
        exit;
    }

    // 2. Aksi Entitas Espers
    if (isset($_POST['submit_esper'])) {
        $actionType = $_POST['action_type'] ?? 'create';
        $name = trim($_POST['name'] ?? '');
        $rarity = (int)($_POST['rarity'] ?? 5);
        $attribute = trim($_POST['attribute'] ?? 'Electro');
        $weaponType = trim($_POST['weapon_type'] ?? 'Dual Pistols');
        $role = trim($_POST['role'] ?? 'Main DPS');
        $factionId = (int)($_POST['faction_id'] ?? 0);
        $imageUrl = trim($_POST['image_url'] ?? '');
        $bio = trim($_POST['bio'] ?? '');

        try {
            if ($actionType === 'create') {
                $esperModel->createEsper($name, $rarity, $attribute, $weaponType, $role, $factionId, $imageUrl, $bio);
                $_SESSION['flash_success'] = "Karakter Esper <strong>" . htmlspecialchars($name) . "</strong> berhasil ditambahkan ke arsip!";
            } elseif ($actionType === 'update') {
                $id = (int)$_POST['id'];
                $esperModel->updateEsper($id, $name, $rarity, $attribute, $weaponType, $role, $factionId, $imageUrl, $bio);
                $_SESSION['flash_success'] = "Data karakter <strong>" . htmlspecialchars($name) . "</strong> berhasil diperbarui!";
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Gagal memproses data esper: " . $e->getMessage();
        }
        header("Location: index.php?page=espers");
        exit;
    }

    // 3. Aksi Entitas Esper Guides
    if (isset($_POST['submit_guide'])) {
        $actionType = $_POST['action_type'] ?? 'create';
        $esperId = (int)($_POST['esper_id'] ?? 0);
        $guideTitle = trim($_POST['guide_title'] ?? '');
        $tierRating = trim($_POST['tier_rating'] ?? 'T0');
        $recommendedWeapon = trim($_POST['recommended_weapon'] ?? '');
        $recommendedMatrix = trim($_POST['recommended_matrix'] ?? '');
        $teamSynergy = trim($_POST['team_synergy'] ?? '');
        $playstyleNotes = trim($_POST['playstyle_notes'] ?? '');

        try {
            if ($actionType === 'create') {
                $guideModel->createGuide($esperId, $guideTitle, $tierRating, $recommendedWeapon, $recommendedMatrix, $teamSynergy, $playstyleNotes);
                $_SESSION['flash_success'] = "Panduan build <strong>" . htmlspecialchars($guideTitle) . "</strong> berhasil dipublikasikan!";
            } elseif ($actionType === 'update') {
                $id = (int)$_POST['id'];
                $guideModel->updateGuide($id, $esperId, $guideTitle, $tierRating, $recommendedWeapon, $recommendedMatrix, $teamSynergy, $playstyleNotes);
                $_SESSION['flash_success'] = "Panduan build <strong>" . htmlspecialchars($guideTitle) . "</strong> berhasil diperbarui!";
            }
        } catch (Exception $e) {
            $_SESSION['flash_error'] = "Gagal memproses panduan build: " . $e->getMessage();
        }
        header("Location: index.php?page=guides");
        exit;
    }
}

// =====================================================================
// Penanganan Aksi HTTP GET (Delete)
// =====================================================================
$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($action === 'delete' && $id) {
    try {
        if ($page === 'factions') {
            $factionModel->deleteFaction($id);
            $_SESSION['flash_success'] = "Data fraksi berhasil dihapus dari sistem.";
            header("Location: index.php?page=factions");
            exit;
        } elseif ($page === 'espers') {
            $esperModel->deleteEsper($id);
            $_SESSION['flash_success'] = "Karakter Esper berhasil dihapus dari arsip.";
            header("Location: index.php?page=espers");
            exit;
        } elseif ($page === 'guides') {
            $guideModel->deleteGuide($id);
            $_SESSION['flash_success'] = "Panduan build berhasil dihapus dari sistem.";
            header("Location: index.php?page=guides");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['flash_error'] = "Gagal menghapus data: " . $e->getMessage();
        header("Location: index.php?page=" . urlencode($page));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neverness to Everness (NTE) // Esper Database & Guide Hub</title>
    <!-- Favicon & Stylesheet Vanilla CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="site-container">
        <!-- Template Header Modular -->
        <?php include __DIR__ . '/view/header.php'; ?>

        <!-- Notifikasi Pesan Flash -->
        <div class="notification-container">
            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span class="alert-msg"><?= $_SESSION['flash_success'] ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger">
                    <span class="alert-icon">⚠</span>
                    <span class="alert-msg"><?= $_SESSION['flash_error'] ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
            <?php endif; ?>
        </div>

        <!-- Konten Halaman Dinamis -->
        <main class="site-main">
            <?php
            switch ($page) {
                case 'espers':
                    include __DIR__ . '/view/espers.php';
                    break;
                case 'factions':
                    include __DIR__ . '/view/factions.php';
                    break;
                case 'guides':
                    include __DIR__ . '/view/guides.php';
                    break;
                case 'dashboard':
                default:
                    include __DIR__ . '/view/dashboard.php';
                    break;
            }
            ?>
        </main>

        <!-- Template Footer Modular -->
        <?php include __DIR__ . '/view/footer.php'; ?>
    </div>
</body>
</html>
