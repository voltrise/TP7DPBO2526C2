<?php
/**
 * Template Header - Antarmuka Bagian Atas & Navigasi
 * Desain Dark Cyber-Urban Gaming Dashboard khas Prydwen / Game8
 */
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<header class="site-header">
    <div class="header-container">
        <div class="brand-area">
            <a href="index.php?page=dashboard" class="brand-link">
                <div class="brand-identity">
                    <img src="assets/images/ui/nte_logo.webp" alt="NTE Official Logo" class="brand-logo-img">
                    <div class="brand-text-block">
                        <span class="brand-badge">NTE // ANOMALY ARCHIVE</span>
                        <span class="brand-title">Neverness to Everness</span>
                        <span class="brand-sub">Hethereau Esper Database & Guide Hub</span>
                    </div>
                </div>
            </a>
        </div>

        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php?page=dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <span class="nav-icon">◈</span> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=espers" class="nav-link <?= $currentPage === 'espers' ? 'active' : '' ?>">
                        <span class="nav-icon">⚡</span> Katalog Esper
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=factions" class="nav-link <?= $currentPage === 'factions' ? 'active' : '' ?>">
                        <span class="nav-icon">🏢</span> Fraksi Kota
                    </a>
                </li>
                <li class="nav-item">
                    <a href="index.php?page=guides" class="nav-link <?= $currentPage === 'guides' ? 'active' : '' ?>">
                        <span class="nav-icon">★</span> Tier List & Builds
                    </a>
                </li>
            </ul>
        </nav>

        <div class="header-status">
            <span class="status-indicator"></span>
            <span class="status-text">Server Online (Hethereau Core)</span>
        </div>
    </div>
</header>
