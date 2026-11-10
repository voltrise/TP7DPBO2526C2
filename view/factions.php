<?php
/**
 * View Factions - Manajemen Fraksi & Organisasi Kota Hethereau
 * Operasi CRUD Lengkap (Create, Read, Update, Delete)
 */

$action = $_GET['action'] ?? 'list';
$editId = $_GET['id'] ?? null;

// Ambil data fraksi jika mode edit
$editData = null;
if ($action === 'edit' && $editId) {
    $editData = $factionModel->getFactionById($editId);
    if (!$editData) {
        echo "<script>window.location.href='index.php?page=factions';</script>";
        exit;
    }
}

// Ambil daftar seluruh fraksi
$factionsList = $factionModel->getAllFactions();
?>

<div class="page-wrapper">
    <!-- Header Halaman -->
    <div class="page-header">
        <div>
            <h1 class="page-title">🏢 Fraksi & Organisasi Kota</h1>
            <p class="page-subtitle">Peta kekuatan faksi penjaga keteraturan, biro penyelidikan, dan sindikat kota</p>
        </div>
        <div class="page-header-actions">
            <?php if ($action !== 'add'): ?>
                <a href="index.php?page=factions&action=add" class="btn btn-primary">+ Tambah Fraksi Baru</a>
            <?php else: ?>
                <a href="index.php?page=factions" class="btn btn-outline">&larr; Kembali ke Daftar</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Tambah / Edit Fraksi -->
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <div class="form-container">
            <div class="form-header">
                <h2><?= $action === 'edit' ? '✎ Ubah Data Fraksi: ' . htmlspecialchars($editData['name']) : '✚ Pendaftaran Fraksi Kota Baru' ?></h2>
                <p>Kelola data markas distrik, pemimpin, dan identitas visual fraksi.</p>
            </div>

            <form method="POST" action="index.php?page=factions" class="modern-form">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="action_type" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">
                <?php else: ?>
                    <input type="hidden" name="action_type" value="create">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama Fraksi / Organisasi <span class="req">*</span></label>
                        <input type="text" id="name" name="name" required placeholder="Contoh: Eibon Antiquities, Kuroshio Syndicate..." 
                               value="<?= htmlspecialchars($editData['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="district">Distrik Markas Besar <span class="req">*</span></label>
                        <input type="text" id="district" name="district" required placeholder="Contoh: Eibon Antique Quarter, Port Sector..." 
                               value="<?= htmlspecialchars($editData['district'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="leader">Pemimpin / Komandan <span class="req">*</span></label>
                        <input type="text" id="leader" name="leader" required placeholder="Contoh: Nanally, Sakiri, Captain Beverly..." 
                               value="<?= htmlspecialchars($editData['leader'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="color_tag">Warna Aksen Identitas Fraksi <span class="req">*</span></label>
                        <div class="color-picker-wrapper">
                            <input type="color" id="color_tag" name="color_tag" 
                                   value="<?= htmlspecialchars($editData['color_tag'] ?? '#00e5ff') ?>">
                            <span class="color-hint">Digunakan sebagai aksen visual badge dan kartu anggota</span>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="description">Deskripsi & Peran di Kota <span class="req">*</span></label>
                        <textarea id="description" name="description" rows="3" required placeholder="Tuliskan tujuan, yurisdiksi, dan sejarah singkat fraksi ini..."><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_faction" class="btn btn-primary">
                        <?= $action === 'edit' ? 'Simpan Perubahan' : 'Daftarkan Fraksi' ?>
                    </button>
                    <a href="index.php?page=factions" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Grid Kartu Fraksi -->
    <div class="factions-cards-grid">
        <?php foreach ($factionsList as $f): ?>
            <div class="faction-card" style="border-top: 4px solid <?= htmlspecialchars($f['color_tag']) ?>;">
                <div class="faction-card-top">
                    <div>
                        <span class="district-badge">📍 <?= htmlspecialchars($f['district']) ?></span>
                        <h3 class="faction-title"><?= htmlspecialchars($f['name']) ?></h3>
                    </div>
                    <span class="members-pill"><?= (int)$f['total_espers'] ?> Esper</span>
                </div>

                <div class="faction-card-meta">
                    <span class="meta-label">Pemimpin:</span>
                    <span class="meta-val">👤 <?= htmlspecialchars($f['leader']) ?></span>
                </div>

                <p class="faction-card-desc"><?= htmlspecialchars($f['description']) ?></p>

                <div class="faction-card-actions">
                    <a href="index.php?page=espers&search=<?= urlencode($f['name']) ?>" class="btn btn-sm btn-outline">Lihat Esper (<?= (int)$f['total_espers'] ?>)</a>
                    <a href="index.php?page=factions&action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                    <a href="index.php?page=factions&action=delete&id=<?= $f['id'] ?>" class="btn btn-sm btn-danger" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus fraksi <?= htmlspecialchars(addslashes($f['name'])) ?>? Seluruh Esper di bawah fraksi ini juga akan terhapus.');">
                       Hapus
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Tabel Data Tabular Fraksi -->
    <div class="table-section">
        <h3 class="table-section-title">◈ Data Tabular Fraksi</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Identitas</th>
                        <th>Nama Fraksi</th>
                        <th>Distrik Markas</th>
                        <th>Pemimpin</th>
                        <th>Jumlah Esper</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($factionsList)): ?>
                        <tr><td colspan="7" class="text-center">Belum ada data fraksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($factionsList as $f): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($f['id']) ?></td>
                                <td>
                                    <span class="color-circle" style="background-color: <?= htmlspecialchars($f['color_tag']) ?>;"></span>
                                </td>
                                <td><strong><?= htmlspecialchars($f['name']) ?></strong></td>
                                <td><?= htmlspecialchars($f['district']) ?></td>
                                <td><?= htmlspecialchars($f['leader']) ?></td>
                                <td><span class="badge-count"><?= (int)$f['total_espers'] ?></span></td>
                                <td>
                                    <div class="table-action-btns">
                                        <a href="index.php?page=factions&action=edit&id=<?= $f['id'] ?>" class="btn btn-xs btn-edit">Edit</a>
                                        <a href="index.php?page=factions&action=delete&id=<?= $f['id'] ?>" class="btn btn-xs btn-danger" 
                                           onclick="return confirm('Hapus fraksi <?= htmlspecialchars(addslashes($f['name'])) ?>?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
