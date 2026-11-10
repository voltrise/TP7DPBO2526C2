<?php
/**
 * View Guides - Manajemen Tier List & Rekomendasi Build Esper
 * Operasi CRUD Lengkap (Create, Read, Update, Delete) + Filter Tingkat Tier
 */

$action = $_GET['action'] ?? 'list';
$editId = $_GET['id'] ?? null;
$tierFilter = $_GET['tier'] ?? '';
$preselectedEsperId = $_GET['esper_id'] ?? null;

// Ambil seluruh data esper untuk dropdown relasi foreign key
$allEspers = $esperModel->getAllEspers();

// Ambil data panduan jika mode edit
$editData = null;
if ($action === 'edit' && $editId) {
    $editData = $guideModel->getGuideById($editId);
    if (!$editData) {
        echo "<script>window.location.href='index.php?page=guides';</script>";
        exit;
    }
}

// Ambil daftar seluruh panduan sesuai filter tier
$guidesList = $guideModel->getAllGuides($tierFilter);
?>

<div class="page-wrapper">
    <!-- Header Halaman -->
    <div class="page-header">
        <div>
            <h1 class="page-title">★ Tier List & Panduan Build Esper</h1>
            <p class="page-subtitle">Rekomendasi senjata, set matrix, sinergi tim, dan panduan rotasi tempur ala <em>Prydwen & Game8</em></p>
        </div>
        <div class="page-header-actions">
            <?php if ($action !== 'add'): ?>
                <a href="index.php?page=guides&action=add" class="btn btn-accent">+ Buat Panduan Baru</a>
            <?php else: ?>
                <a href="index.php?page=guides" class="btn btn-outline">&larr; Kembali ke Tier List</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Tambah / Edit Panduan -->
    <?php if ($action === 'add' || $action === 'edit'): ?>
        <div class="form-container">
            <div class="form-header">
                <h2><?= $action === 'edit' ? '✎ Ubah Panduan Build: ' . htmlspecialchars($editData['esper_name']) : '✚ Perumusan Panduan Build Baru' ?></h2>
                <p>Tautkan panduan dengan karakter Esper pilihan dan tentukan rating tier serta perlengkapan optimal.</p>
            </div>

            <form method="POST" action="index.php?page=guides" class="modern-form">
                <?php if ($action === 'edit'): ?>
                    <input type="hidden" name="action_type" value="update">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($editData['id']) ?>">
                <?php else: ?>
                    <input type="hidden" name="action_type" value="create">
                <?php endif; ?>

                <div class="form-grid">
                    <!-- Esper Pilihan (Foreign Key) -->
                    <div class="form-group">
                        <label for="esper_id">Karakter Esper Sasaran (Foreign Key) <span class="req">*</span></label>
                        <select id="esper_id" name="esper_id" required>
                            <option value="">-- Pilih Karakter Esper --</option>
                            <?php foreach ($allEspers as $esp): ?>
                                <?php 
                                $selected = '';
                                if (isset($editData['esper_id']) && $editData['esper_id'] == $esp['id']) {
                                    $selected = 'selected';
                                } elseif ($action === 'add' && $preselectedEsperId == $esp['id']) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?= $esp['id'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($esp['name']) ?> (<?= $esp['rarity'] ?>★ - <?= htmlspecialchars($esp['attribute']) ?> - <?= htmlspecialchars($esp['role']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tier Rating -->
                    <div class="form-group">
                        <label for="tier_rating">Peringkat Tier (Tier Rating) <span class="req">*</span></label>
                        <select id="tier_rating" name="tier_rating" required>
                            <?php 
                            $tiers = [
                                'T0'   => 'T0 - Apex Meta (Kekuatan Paling Mutlak)',
                                'T0.5' => 'T0.5 - High Priority (Sangat Kuat & Fleksibel)',
                                'T1'   => 'T1 - Competitive (Kuat pada Kondisi Spesifik)',
                                'T2'   => 'T2 - Situational / Niche Specialist'
                            ];
                            foreach ($tiers as $val => $label): 
                            ?>
                                <option value="<?= $val ?>" <?= (isset($editData['tier_rating']) && $editData['tier_rating'] === $val) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Judul Panduan -->
                    <div class="form-group full-width">
                        <label for="guide_title">Judul Racikan Build <span class="req">*</span></label>
                        <input type="text" id="guide_title" name="guide_title" required 
                               placeholder="Contoh: Nanally Hyper-Electro Rapid Trigger, Sakiri Crimson Blade Burst..." 
                               value="<?= htmlspecialchars($editData['guide_title'] ?? '') ?>">
                    </div>

                    <!-- Senjata Rekomendasi -->
                    <div class="form-group">
                        <label for="recommended_weapon">Senjata Rekomendasi (Best-in-Slot) <span class="req">*</span></label>
                        <input type="text" id="recommended_weapon" name="recommended_weapon" required 
                               placeholder="Contoh: Dual Thunderbolt X-9, Muramasa Spark..." 
                               value="<?= htmlspecialchars($editData['recommended_weapon'] ?? '') ?>">
                    </div>

                    <!-- Set Matrix / Drive Disk -->
                    <div class="form-group">
                        <label for="recommended_matrix">Set Matrix / Perlengkapan Optimal <span class="req">*</span></label>
                        <input type="text" id="recommended_matrix" name="recommended_matrix" required 
                               placeholder="Contoh: Overcharge Surge 4-Pc, Blazing Velocity 4-Pc..." 
                               value="<?= htmlspecialchars($editData['recommended_matrix'] ?? '') ?>">
                    </div>

                    <!-- Sinergi Tim -->
                    <div class="form-group full-width">
                        <label for="team_synergy">Komposisi Tim Rekomendasi (Team Synergy) <span class="req">*</span></label>
                        <input type="text" id="team_synergy" name="team_synergy" required 
                               placeholder="Contoh: Nanally + Mint + Sakiri (Discharge Electro-Pyro-Glacio)" 
                               value="<?= htmlspecialchars($editData['team_synergy'] ?? '') ?>">
                    </div>

                    <!-- Catatan Gaya Main / Rotasi -->
                    <div class="form-group full-width">
                        <label for="playstyle_notes">Catatan Gaya Main & Rotasi Skill <span class="req">*</span></label>
                        <textarea id="playstyle_notes" name="playstyle_notes" rows="4" required 
                                  placeholder="Jelaskan urutan skill, trik rotasi evasif, dan sinergi elemen saat bertarung melawan anomali..."><?= htmlspecialchars($editData['playstyle_notes'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="submit_guide" class="btn btn-accent">
                        <?= $action === 'edit' ? 'Perbarui Panduan' : 'Publikasikan Panduan' ?>
                    </button>
                    <a href="index.php?page=guides" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Filter Tier Tabs ala Prydwen -->
    <div class="tier-filter-tabs">
        <span class="filter-label">Filter Tier Rating:</span>
        <div class="tabs-list">
            <a href="index.php?page=guides" class="tier-tab <?= empty($tierFilter) ? 'active' : '' ?>">Semua Tier</a>
            <a href="index.php?page=guides&tier=T0" class="tier-tab tab-t0 <?= $tierFilter === 'T0' ? 'active' : '' ?>">T0 (Apex Meta)</a>
            <a href="index.php?page=guides&tier=T0.5" class="tier-tab tab-t05 <?= $tierFilter === 'T0.5' ? 'active' : '' ?>">T0.5 (High Priority)</a>
            <a href="index.php?page=guides&tier=T1" class="tier-tab tab-t1 <?= $tierFilter === 'T1' ? 'active' : '' ?>">T1 (Competitive)</a>
            <a href="index.php?page=guides&tier=T2" class="tier-tab tab-t2 <?= $tierFilter === 'T2' ? 'active' : '' ?>">T2 (Situational)</a>
        </div>
    </div>

    <!-- Grid Panduan Build (Aesthetic Gaming Card) -->
    <div class="guides-cards-grid">
        <?php if (empty($guidesList)): ?>
            <div class="empty-state full-width">
                <p>Belum ada panduan build untuk filter tier ini.</p>
                <a href="index.php?page=guides&action=add" class="btn btn-sm btn-accent">+ Tambah Panduan Baru</a>
            </div>
        <?php else: ?>
            <?php foreach ($guidesList as $g): ?>
                <div class="guide-item-card" id="guide-<?= $g['id'] ?>">
                    <!-- Header Kartu -->
                    <div class="guide-card-head">
                        <div class="head-left">
                            <span class="tier-badge tier-<?= strtolower(str_replace('.', '', $g['tier_rating'])) ?>">
                                Tier <?= htmlspecialchars($g['tier_rating']) ?>
                            </span>
                            <span class="attr-badge attr-<?= strtolower($g['attribute']) ?>">
                                <?= htmlspecialchars($g['attribute']) ?>
                            </span>
                        </div>
                        <div class="head-right">
                            <span class="rarity-stars"><?= str_repeat('★', (int)$g['rarity']) ?></span>
                        </div>
                    </div>

                    <!-- Esper Info Row -->
                    <div class="guide-character-info">
                        <?php if (!empty($g['image_url'])): ?>
                            <img src="<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['esper_name']) ?>" class="char-avatar-mini img-avatar">
                        <?php else: ?>
                            <div class="char-avatar-mini attr-bg-<?= strtolower($g['attribute']) ?>">
                                <?= strtoupper(substr($g['esper_name'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                        <div class="char-details">
                            <h3 class="char-name"><?= htmlspecialchars($g['esper_name']) ?></h3>
                            <span class="char-faction" style="color: <?= htmlspecialchars($g['faction_color']) ?>;">
                                <?= htmlspecialchars($g['faction_name']) ?> &bull; <?= htmlspecialchars($g['role']) ?>
                            </span>
                        </div>
                    </div>

                    <h4 class="guide-headline"><?= htmlspecialchars($g['guide_title']) ?></h4>

                    <!-- Build Spec Boxes -->
                    <div class="build-spec-boxes">
                        <div class="spec-box">
                            <span class="spec-label">Senjata Utama:</span>
                            <span class="spec-value">⚔ <?= htmlspecialchars($g['recommended_weapon']) ?></span>
                        </div>
                        <div class="spec-box">
                            <span class="spec-label">Matrix / Perlengkapan:</span>
                            <span class="spec-value">🛡 <?= htmlspecialchars($g['recommended_matrix']) ?></span>
                        </div>
                        <div class="spec-box full-box">
                            <span class="spec-label">Sinergi Tim Ideal:</span>
                            <span class="spec-value synergy-val">👥 <?= htmlspecialchars($g['team_synergy']) ?></span>
                        </div>
                    </div>

                    <!-- Playstyle Accordion / Box -->
                    <div class="guide-playstyle-box">
                        <div class="box-title">Panduan Rotasi & Gaya Bermain:</div>
                        <p class="box-text"><?= nl2br(htmlspecialchars($g['playstyle_notes'])) ?></p>
                    </div>

                    <!-- Footer Kartu -->
                    <div class="guide-card-foot">
                        <span class="foot-time">Diperbarui: <?= date('d M Y, H:i', strtotime($g['updated_at'])) ?></span>
                        <div class="foot-actions">
                            <a href="index.php?page=guides&action=edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-edit">Edit Build</a>
                            <a href="index.php?page=guides&action=delete&id=<?= $g['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus panduan build untuk <?= htmlspecialchars(addslashes($g['esper_name'])) ?>?');">
                               Hapus
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Tabel Tabular Panduan Build -->
    <div class="table-section">
        <h3 class="table-section-title">◈ Data Tabular Panduan Build</h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Karakter (FK)</th>
                        <th>Tier</th>
                        <th>Judul Panduan</th>
                        <th>Senjata</th>
                        <th>Set Matrix</th>
                        <th>Terakhir Diperbarui</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($guidesList)): ?>
                        <tr><td colspan="8" class="text-center">Belum ada data panduan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($guidesList as $g): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($g['id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($g['esper_name']) ?></strong>
                                    <small>(<?= htmlspecialchars($g['attribute']) ?>)</small>
                                </td>
                                <td>
                                    <span class="tier-pill tier-<?= strtolower(str_replace('.', '', $g['tier_rating'])) ?>">
                                        <?= htmlspecialchars($g['tier_rating']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($g['guide_title']) ?></td>
                                <td><?= htmlspecialchars($g['recommended_weapon']) ?></td>
                                <td><?= htmlspecialchars($g['recommended_matrix']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($g['updated_at'])) ?></td>
                                <td>
                                    <div class="table-action-btns">
                                        <a href="index.php?page=guides&action=edit&id=<?= $g['id'] ?>" class="btn btn-xs btn-edit">Edit</a>
                                        <a href="index.php?page=guides&action=delete&id=<?= $g['id'] ?>" class="btn btn-xs btn-danger" 
                                           onclick="return confirm('Hapus panduan ini?');">Hapus</a>
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
