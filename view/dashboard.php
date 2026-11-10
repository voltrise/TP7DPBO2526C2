<?php
/**
 * View Dashboard - Halaman Utama / Ringkasan Sistem
 */

$allEspers = $esperModel->getAllEspers();
$allFactions = $factionModel->getAllFactions();
$allGuides = $guideModel->getAllGuides();

$totalEspers = count($allEspers);
$totalFactions = count($allFactions);
$totalGuides = count($allGuides);

$t0Guides = array_filter($allGuides, function($g) {
    return $g['tier_rating'] === 'T0';
});
?>

<div class="dashboard-wrapper">
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="hero-content">
            <span class="hero-tag">HETHEREAU CITY DATABASE // GUIDE HUB</span>
            <h1 class="hero-title">Neverness to Everness (NTE) Archive</h1>
            <p class="hero-desc">
                Pusat data terpadu karakter Esper, fraksi distrik supranatural, serta panduan racikan build dan tier list meta ala <em>Prydwen & Game8</em>. Kelola data entitas secara aman dan relasional dengan dukungan Prepared Statements.
            </p>
            <div class="hero-actions">
                <a href="index.php?page=espers&action=add" class="btn btn-primary">+ Tambah Esper Baru</a>
                <a href="index.php?page=guides&action=add" class="btn btn-accent">+ Buat Panduan Build</a>
                <a href="index.php?page=factions&action=add" class="btn btn-outline">+ Tambah Fraksi</a>
            </div>
        </div>
        <div class="hero-stats-grid">
            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <div class="stat-info">
                    <span class="stat-value"><?= $totalEspers ?></span>
                    <span class="stat-label">Total Esper Terdaftar</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏢</div>
                <div class="stat-info">
                    <span class="stat-value"><?= $totalFactions ?></span>
                    <span class="stat-label">Fraksi & Organisasi</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">★</div>
                <div class="stat-info">
                    <span class="stat-value"><?= $totalGuides ?></span>
                    <span class="stat-label">Panduan Build Aktif</span>
                </div>
            </div>
            <div class="stat-card stat-card-gold">
                <div class="stat-icon">👑</div>
                <div class="stat-info">
                    <span class="stat-value"><?= count($t0Guides) ?></span>
                    <span class="stat-label">Apex Meta (Tier 0)</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Tier List Highlights -->
    <section class="section-block">
        <div class="section-header">
            <div>
                <h2 class="section-title">◈ Apex Meta Tier Highlights</h2>
                <p class="section-subtitle">Karakter Esper berkekuatan teratas berdasarkan panduan build terkini</p>
            </div>
            <a href="index.php?page=guides" class="link-more">Lihat Seluruh Tier List &rarr;</a>
        </div>

        <div class="guides-grid">
            <?php if (empty($allGuides)): ?>
                <div class="empty-state">
                    <p>Belum ada panduan build yang didaftarkan. <a href="index.php?page=guides&action=add">Tambah panduan pertama!</a></p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($allGuides, 0, 3) as $g): ?>
                    <div class="guide-card">
                        <div class="guide-card-header">
                            <span class="tier-badge tier-<?= strtolower(str_replace('.', '', $g['tier_rating'])) ?>">
                                <?= htmlspecialchars($g['tier_rating']) ?>
                            </span>
                            <span class="attr-badge attr-<?= strtolower($g['attribute']) ?>">
                                <?= htmlspecialchars($g['attribute']) ?>
                            </span>
                        </div>
                        <div class="guide-card-body">
                            <div class="guide-character-info" style="margin-bottom: 12px;">
                                <?php if (!empty($g['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($g['image_url']) ?>" alt="<?= htmlspecialchars($g['esper_name']) ?>" class="char-avatar-mini img-avatar">
                                <?php else: ?>
                                    <div class="char-avatar-mini attr-bg-<?= strtolower($g['attribute']) ?>">
                                        <?= strtoupper(substr($g['esper_name'], 0, 2)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="char-details">
                                    <h3 class="char-name">
                                        <?= htmlspecialchars($g['esper_name']) ?>
                                        <span class="rarity-stars"><?= str_repeat('★', (int)$g['rarity']) ?></span>
                                    </h3>
                                    <span class="char-faction" style="color: <?= htmlspecialchars($g['faction_color']) ?>;">
                                        <?= htmlspecialchars($g['faction_name']) ?> &bull; <?= htmlspecialchars($g['role']) ?>
                                    </span>
                                </div>
                            </div>
                            <h4 class="guide-title-text"><?= htmlspecialchars($g['guide_title']) ?></h4>
                            <div class="guide-details-list">
                                <div class="detail-row">
                                    <span class="detail-label">Senjata Rekomendasi:</span>
                                    <span class="detail-val"><?= htmlspecialchars($g['recommended_weapon']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Set Matrix / Drive:</span>
                                    <span class="detail-val"><?= htmlspecialchars($g['recommended_matrix']) ?></span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Fraksi:</span>
                                    <span class="detail-val" style="color: <?= htmlspecialchars($g['faction_color']) ?>">
                                        <?= htmlspecialchars($g['faction_name']) ?>
                                    </span>
                                </div>
                            </div>
                            <p class="guide-notes"><?= htmlspecialchars($g['playstyle_notes']) ?></p>
                        </div>
                        <div class="guide-card-footer">
                            <a href="index.php?page=guides&action=edit&id=<?= $g['id'] ?>" class="btn btn-sm btn-edit">Edit Build</a>
                            <a href="index.php?page=espers&id=<?= $g['esper_id'] ?>" class="btn btn-sm btn-outline">Profil Esper</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section Factions Overview -->
    <section class="section-block">
        <div class="section-header">
            <div>
                <h2 class="section-title">◈ Fraksi & Distrik Kota Hethereau</h2>
                <p class="section-subtitle">Struktur organisasi pengendali stabilitas dan anomali perkotaan</p>
            </div>
            <a href="index.php?page=factions" class="link-more">Kelola Semua Fraksi &rarr;</a>
        </div>

        <div class="factions-grid">
            <?php foreach ($allFactions as $f): ?>
                <div class="faction-card" style="border-top-color: <?= htmlspecialchars($f['color_tag']) ?>;">
                    <div class="faction-header">
                        <h3 class="faction-name"><?= htmlspecialchars($f['name']) ?></h3>
                        <span class="faction-members-count"><?= (int)$f['total_espers'] ?> Esper</span>
                    </div>
                    <div class="faction-meta">
                        <span class="meta-item">📍 Distrik: <strong><?= htmlspecialchars($f['district']) ?></strong></span>
                        <span class="meta-item">👤 Pemimpin: <strong><?= htmlspecialchars($f['leader']) ?></strong></span>
                    </div>
                    <p class="faction-desc"><?= htmlspecialchars($f['description']) ?></p>
                    <div class="faction-footer">
                        <a href="index.php?page=espers&faction_filter=<?= $f['id'] ?>" class="btn btn-sm btn-outline">Daftar Anggota</a>
                        <a href="index.php?page=factions&action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-edit">Ubah</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>
