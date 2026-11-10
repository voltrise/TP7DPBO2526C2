<?php
/**
 * View Espers - Manajemen Katalog Karakter Esper
 * Operasi CRUD Lengkap (Create, Read, Update, Delete) + Filter Atribut & Pencarian
 */

$action = $_GET['action'] ?? 'list';
$editId = $_GET['id'] ?? null;
$attrFilter = $_GET['attribute'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// Ambil data fraksi untuk dropdown relasi
$allFactions = $factionModel->getAllFactions();

// Ambil data esper jika mode edit
$editData = null;
if ($action === 'edit' && $editId) {
    $editData = $esperModel->getEsperById($editId);
    if (!$editData) {
        echo "<script>window.location.href='index.php?page=espers';</script>";
        exit;
    }
}

// Ambil daftar seluruh esper sesuai filter
$espersList = $esperModel->getAllEspers($attrFilter, $searchQuery);
?>

<div class="page-wrapper">
    <!-- Header Halaman -->
    <div class="page-header">
        <div>
            <h1 class="page-title">⚡ Katalog Karakter Esper</h1>
            <p class="page-subtitle">Arsip lengkap pengendali anomali supranatural di Kota Hethereau</p>
        </div>
        <div class="page-header-actions">
            <?php if ($action !== 'add'): ?>
                <a href="index.php?page=espers&action=add" class="btn btn-primary">+ Tambah Esper Baru</a>
            <?php else: ?>
                <a href="index.php?page=espers" class="btn btn-outline">&larr; Kembali ke Daftar</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Tambah / Edit Esper -->
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <div class="form-container">
            <div class="form-header">
                <h2><?= $action === 'edit' ? '✎ Perbarui Data Esper: ' . htmlspecialchars($editData['name']) : '✚ Pendaftaran Esper Baru' ?></h2>
                <p>Isi formulir berikut dengan teliti. Seluruh input diproses menggunakan PDO Prepared Statements.</p>
            </div>

            <form method="POST" action="index.php?page=espers" class="modern-form">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="action_type" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">
                <?php else: ?>
                    <input type="hidden" name="action_type" value="create">
                <?php endif; ?>

                <div class="form-grid">
                    <!-- Nama Esper -->
                    <div class="form-group">
                        <label for="name">Nama Karakter Esper <span class="req">*</span></label>
                        <input type="text" id="name" name="name" required placeholder="Contoh: Nanally, Sakiri, Mint..." 
                               value="<?= htmlspecialchars($editData['name'] ?? '') ?>">
                    </div>

                    <!-- Rarity (Kelangkaan) -->
                    <div class="form-group">
                        <label for="rarity">Tingkat Kelangkaan (Rarity) <span class="req">*</span></label>
                        <select id="rarity" name="rarity" required>
                            <option value="5" <?= (isset($editData['rarity']) && $editData['rarity'] == 5) ? 'selected' : '' ?>>★★★★★ (5-Star / SSR)</option>
                            <option value="4" <?= (isset($editData['rarity']) && $editData['rarity'] == 4) ? 'selected' : '' ?>>★★★★ (4-Star / SR)</option>
                        </select>
                    </div>

                    <!-- Atribut / Elemen -->
                    <div class="form-group">
                        <label for="attribute">Atribut Elemen <span class="req">*</span></label>
                        <select id="attribute" name="attribute" required>
                            <?php 
                            $attributes = ['Electro', 'Glacio', 'Pyro', 'Physical', 'Aero', 'Quantum'];
                            foreach ($attributes as $attr): 
                            ?>
                                <option value="<?= $attr ?>" <?= (isset($editData['attribute']) && $editData['attribute'] === $attr) ? 'selected' : '' ?>>
                                    <?= $attr ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tipe Senjata -->
                    <div class="form-group">
                        <label for="weapon_type">Tipe Senjata Utama <span class="req">*</span></label>
                        <select id="weapon_type" name="weapon_type" required>
                            <?php 
                            $weapons = ['Dual Pistols', 'Katana', 'Tactical Catalyst', 'Heavy Greatsword', 'Sniper Rifle', 'Combat Gauntlets'];
                            foreach ($weapons as $w): 
                            ?>
                                <option value="<?= $w ?>" <?= (isset($editData['weapon_type']) && $editData['weapon_type'] === $w) ? 'selected' : '' ?>>
                                    <?= $w ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Peran Tempur (Role) -->
                    <div class="form-group">
                        <label for="role">Peran Tempur (Role) <span class="req">*</span></label>
                        <select id="role" name="role" required>
                            <?php 
                            $roles = ['Main DPS', 'Burst Sub-DPS', 'Buffer / Healer', 'Tank / Shielder', 'Crowd Controller'];
                            foreach ($roles as $r): 
                            ?>
                                <option value="<?= $r ?>" <?= (isset($editData['role']) && $editData['role'] === $r) ? 'selected' : '' ?>>
                                    <?= $r ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fraksi (Relasi Foreign Key) -->
                    <div class="form-group">
                        <label for="faction_id">Fraksi / Organisasi Kota <span class="req">*</span></label>
                        <select id="faction_id" name="faction_id" required>
                            <option value="">-- Pilih Fraksi --</option>
                            <?php foreach ($allFactions as $f): ?>
                                <option value="<?= $f['id'] ?>" <?= (isset($editData['faction_id']) && $editData['faction_id'] == $f['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f['name']) ?> (<?= htmlspecialchars($f['district']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Path / URL Foto -->
                    <div class="form-group full-width">
                        <label for="image_url">Path / URL Foto Karakter (Opsional)</label>
                        <input type="text" id="image_url" name="image_url" placeholder="Contoh: assets/images/nanally.png atau tautan web" 
                               value="<?= htmlspecialchars($editData['image_url'] ?? '') ?>">
                        <small class="form-hint">Jika dikosongkan, sistem akan otomatis menghasilkan avatar visual berbasis inisial dan atribut elemen.</small>
                    </div>

                    <!-- Biodata & Deskripsi -->
                    <div class="form-group full-width">
                        <label for="bio">Profil & Catatan Kemampuan</label>
                        <textarea id="bio" name="bio" rows="3" placeholder="Deskripsikan latar belakang, kepribadian, atau spesialisasi anomali esper ini..."><?= htmlspecialchars($editData['bio'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_esper" class="btn btn-primary">
                        <?= $action === 'edit' ? 'Simpan Perubahan' : 'Daftarkan Esper' ?>
                    </button>
                    <a href="index.php?page=espers" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Bilah Filter & Pencarian -->
    <div class="filter-bar">
        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="page" value="espers">
            
            <div class="filter-group">
                <label>Filter Elemen:</label>
                <div class="badge-filter-list">
                    <a href="index.php?page=espers<?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?>" 
                       class="filter-pill <?= empty($attrFilter) ? 'active' : '' ?>">Semua</a>
                    <?php 
                    $allAttrs = ['Electro', 'Glacio', 'Pyro', 'Physical', 'Aero', 'Quantum'];
                    foreach ($allAttrs as $attr): 
                    ?>
                        <a href="index.php?page=espers&attribute=<?= $attr ?><?= !empty($searchQuery) ? '&search=' . urlencode($searchQuery) : '' ?>" 
                           class="filter-pill attr-pill-<?= strtolower($attr) ?> <?= $attrFilter === $attr ? 'active' : '' ?>">
                            <?= $attr ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="search-group">
                <input type="text" name="search" placeholder="Cari nama Esper..." value="<?= htmlspecialchars($searchQuery) ?>">
                <?php if (!empty($attrFilter)): ?>
                    <input type="hidden" name="attribute" value="<?= htmlspecialchars($attrFilter) ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-sm btn-outline">Cari</button>
                <?php if (!empty($searchQuery) || !empty($attrFilter)): ?>
                    <a href="index.php?page=espers" class="btn btn-sm btn-ghost">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Grid Kartu Esper (Aesthetic Prydwen) -->
    <div class="espers-cards-grid">
        <?php if (empty($espersList)): ?>
            <div class="empty-state full-width">
                <p>Tidak ada karakter Esper yang cocok dengan filter pencarian.</p>
                <a href="index.php?page=espers&action=add" class="btn btn-sm btn-primary">+ Tambah Esper Baru</a>
            </div>
        <?php else: ?>
            <?php foreach ($espersList as $e): ?>
                <div class="esper-card rarity-border-<?= $e['rarity'] ?>">
                    <!-- Card Top Accent -->
                    <div class="esper-card-top">
                        <span class="attr-badge attr-<?= strtolower($e['attribute']) ?>">
                            <?= htmlspecialchars($e['attribute']) ?>
                        </span>
                        <span class="rarity-stars">
                            <?= str_repeat('★', (int)$e['rarity']) ?>
                        </span>
                    </div>

                    <!-- Avatar / Visual Fallback -->
                    <div class="esper-avatar-area">
                        <?php if (!empty($e['image_url'])): ?>
                            <img src="<?= htmlspecialchars($e['image_url']) ?>" alt="<?= htmlspecialchars($e['name']) ?>" class="esper-img">
                        <?php else: ?>
                            <div class="esper-avatar-fallback attr-bg-<?= strtolower($e['attribute']) ?>">
                                <span><?= strtoupper(substr($e['name'], 0, 2)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Card Body -->
                    <div class="esper-card-content">
                        <h3 class="esper-name"><?= htmlspecialchars($e['name']) ?></h3>
                        <div class="esper-role-tag"><?= htmlspecialchars($e['role']) ?></div>
                        
                        <div class="esper-meta-rows">
                            <div class="meta-row">
                                <span class="label">Senjata:</span>
                                <span class="val"><?= htmlspecialchars($e['weapon_type']) ?></span>
                            </div>
                            <div class="meta-row">
                                <span class="label">Fraksi:</span>
                                <span class="val faction-tag" style="border-left-color: <?= htmlspecialchars($e['faction_color']) ?>">
                                    <?= htmlspecialchars($e['faction_name']) ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($e['bio'])): ?>
                            <p class="esper-bio"><?= htmlspecialchars($e['bio']) ?></p>
                        <?php endif; ?>

                        <!-- Tier List status -->
                        <div class="esper-tier-status">
                            <?php if (!empty($e['tier_rating'])): ?>
                                <span class="tier-pill tier-<?= strtolower(str_replace('.', '', $e['tier_rating'])) ?>">
                                    Tier <?= htmlspecialchars($e['tier_rating']) ?> Build
                                </span>
                            <?php else: ?>
                                <span class="tier-pill tier-none">Belum ada panduan build</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="esper-card-actions">
                        <?php if (!empty($e['guide_id'])): ?>
                            <a href="index.php?page=guides#guide-<?= $e['guide_id'] ?>" class="btn btn-sm btn-accent" title="Lihat Panduan">Build Guide</a>
                        <?php else: ?>
                            <a href="index.php?page=guides&action=add&esper_id=<?= $e['id'] ?>" class="btn btn-sm btn-ghost" title="Buat Panduan">+ Buat Build</a>
                        <?php endif; ?>
                        <a href="index.php?page=espers&action=edit&id=<?= $e['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                        <a href="index.php?page=espers&action=delete&id=<?= $e['id'] ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus Esper <?= htmlspecialchars(addslashes($e['name'])) ?>? Seluruh panduan terkait juga akan terhapus.');">
                           Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tabel Data Tabular Lengkap -->
    <div class="table-section">
        <h3 class="table-section-title">◈ Data Tabular Esper</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Karakter</th>
                        <th>Rarity</th>
                        <th>Elemen</th>
                        <th>Senjata</th>
                        <th>Peran Tempur</th>
                        <th>Fraksi (Foreign Key)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($espersList)): ?>
                        <tr><td colspan="8" class="text-center">Belum ada data Esper.</td></tr>
                    <?php else: ?>
                        <?php foreach ($espersList as $e): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($e['id']) ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if (!empty($e['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($e['image_url']) ?>" alt="<?= htmlspecialchars($e['name']) ?>" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-medium);">
                                        <?php endif; ?>
                                        <strong><?= htmlspecialchars($e['name']) ?></strong>
                                    </div>
                                </td>
                                <td><span class="rarity-badge-num"><?= $e['rarity'] ?>★</span></td>
                                <td>
                                    <span class="attr-pill attr-pill-<?= strtolower($e['attribute']) ?>">
                                        <?= htmlspecialchars($e['attribute']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($e['weapon_type']) ?></td>
                                <td><?= htmlspecialchars($e['role']) ?></td>
                                <td>
                                    <span style="color: <?= htmlspecialchars($e['faction_color']) ?>; font-weight: 600;">
                                        <?= htmlspecialchars($e['faction_name']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="table-action-btns">
                                        <a href="index.php?page=espers&action=edit&id=<?= $e['id'] ?>" class="btn btn-xs btn-edit">Edit</a>
                                        <a href="index.php?page=espers&action=delete&id=<?= $e['id'] ?>" class="btn btn-xs btn-danger" 
                                           onclick="return confirm('Hapus esper <?= htmlspecialchars(addslashes($e['name'])) ?>?');">Hapus</a>
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
