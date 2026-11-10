-- =====================================================================
-- Basis Data: db_nte_guide
-- Tema: Neverness to Everness (NTE) Supernatural Urban Esper Database & Guide
-- Tugas Praktikum 7 DPBO (C2) - Universitas Pendidikan Indonesia
-- Pengembang: Devindra Azra Dwi Putra (NIM 2509003)
-- Sumber Aset Asli: https://ntebuild.com/ (Official Neverness to Everness Web Assets)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS db_nte_guide;
USE db_nte_guide;

-- -----------------------------------------------------
-- 1. Tabel Factions (Organisasi / Fraksi Kota Hethereau)
-- -----------------------------------------------------
DROP TABLE IF EXISTS esper_guides;
DROP TABLE IF EXISTS espers;
DROP TABLE IF EXISTS factions;

CREATE TABLE factions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    district VARCHAR(100) NOT NULL,
    leader VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    color_tag VARCHAR(20) DEFAULT '#00e5ff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 2. Tabel Espers (Karakter Esper / Pengendali Supranatural)
-- -----------------------------------------------------
CREATE TABLE espers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    rarity INT NOT NULL DEFAULT 5,
    attribute VARCHAR(50) NOT NULL,
    weapon_type VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL,
    faction_id INT NOT NULL,
    image_url VARCHAR(255) DEFAULT '',
    bio TEXT DEFAULT '',
    FOREIGN KEY (faction_id) REFERENCES factions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- 3. Tabel Esper Guides (Panduan Build & Tier List Karakter)
-- -----------------------------------------------------
CREATE TABLE esper_guides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    esper_id INT NOT NULL,
    guide_title VARCHAR(200) NOT NULL,
    tier_rating VARCHAR(10) NOT NULL,
    recommended_weapon VARCHAR(150) NOT NULL,
    recommended_matrix VARCHAR(150) NOT NULL,
    team_synergy VARCHAR(255) NOT NULL,
    playstyle_notes TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (esper_id) REFERENCES espers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================================
-- DML: Data Karakter & Panduan Resmi Neverness to Everness
-- Aset Gambar Bersumber dari ntebuild.com
-- =====================================================================

INSERT INTO factions (name, district, leader, description, color_tag) VALUES
('Eibon Antiquities', 'Eibon Antique Quarter', 'Nanally', 'Toko barang antik merangkap biro penyelidikan anomali supranatural perkotaan dan perantara kasus ganjil Hethereau.', '#00e5ff'),
('Kuroshio Syndicate', 'Port Kuroshio District', 'Sakiri', 'Sindikat penjaga pelabuhan kargo bawah tanah dengan armada kendaraan balap berkecepatan tinggi dan pendekar pedang.', '#ff007f'),
('Hethereau Public Security', 'Central Core Sector', 'Commander Zankou', 'Divisi kepolisian khusus penegakan hukum anomalus, karantina supranatural, dan stabilitas publik kota.', '#3b82f6'),
('Silver Dawn Research', 'Aurora Heights Lab', 'Chief Researcher Linko', 'Konsorsium riset teknologi kuantum partikel esper dan rekayasa modul matrix mutakhir perkotaan.', '#a855f7');

INSERT INTO espers (name, rarity, attribute, weapon_type, role, faction_id, image_url, bio) VALUES
('Nanally', 5, 'Electro', 'Dual Pistols', 'Main DPS', 1, 'assets/images/characters/nanally_icon.webp', 'Gadis energik pemburu anomali di Eibon Antiquities, spesialis tembakan ganda berkecepatan tinggi bertegangan kilat voltase tinggi.'),
('Mint', 5, 'Glacio', 'Tactical Catalyst', 'Buffer / Healer', 1, 'assets/images/characters/mint_icon.webp', 'Asisten setia toko Eibon berkemampuan memanipulasi kristal es pendingin guna membekukan musuh dan memulihkan vitalitas skuad.'),
('Sakiri', 5, 'Pyro', 'Katana', 'Burst Sub-DPS', 2, 'assets/images/characters/sakiri_icon.webp', 'Pendekar katana dari Kuroshio Syndicate yang membakar aspal malam dengan sabetan pedang berapi ekstrem dan akselerasi tinggi.'),
('Akane Rin', 5, 'Electro', 'Dual Pistols', 'Main DPS', 2, 'assets/images/characters/akane_rin_icon.webp', 'Esper legendaris pengendali petir berkecepatan sonik dengan tembakan akurat pemusnah anomali berskala distrik.'),
('Linko', 5, 'Aero', 'Combat Gauntlets', 'Main DPS', 4, 'assets/images/characters/linko_icon.webp', 'Peneliti muda berdaya tempur tinggi yang memanfaatkan sarung tinju angin kinetik untuk melempar musuh ke udara.'),
('Zankou', 5, 'Pyro', 'Heavy Greatsword', 'Tank / Shielder', 3, 'assets/images/characters/zankou_icon.webp', 'Komandan garda depan Public Security bertubuh perkasa dengan pedang raksasa pemantik api pelindung perimeter.'),
('Blackbird', 4, 'Physical', 'Sniper Rifle', 'Burst Sub-DPS', 3, 'assets/images/characters/blackbird_icon.webp', 'Penembak jitu misterius dari divisi intelijen kota yang melumpuhkan inti anomali dari jarak ribuan meter.'),
('Iroi', 4, 'Quantum', 'Katana', 'Crowd Controller', 4, 'assets/images/characters/iroi_icon.webp', 'Ahli manipulasi ruang kuantum yang mampu mendistorsi gravitasi lokal musuh di medan pertempuran.');

INSERT INTO esper_guides (esper_id, guide_title, tier_rating, recommended_weapon, recommended_matrix, team_synergy, playstyle_notes) VALUES
(4, 'Akane Rin Sonic Thunder Discharge Core', 'T0', 'Dual Thunderbolt Requiem', 'Voltage Overdrive 4-Pc', 'Akane Rin + Mint + Sakiri (Discharge Electro-Pyro-Glacio)', 'Manfaatkan kecepatan manuver dash untuk mengumpulkan muatan listrik statis, lalu picu Elemental Resonance saat Mint membekukan perisai lawan.'),
(1, 'Nanally Hyper-Electro Rapid Trigger', 'T0', 'Dual Thunderbolt X-9', 'Overcharge Surge 4-Pc', 'Nanally + Mint + Linko (Overclocked Windburst)', 'Fokuskan kombinasi skill evasion untuk memicu Overclocked Reload, lepaskan burst barrage saat musuh dalam kondisi crowd control.'),
(3, 'Sakiri Crimson Blade Ignition Core', 'T0.5', 'Muramasa Spark Ignition', 'Blazing Velocity 4-Pc', 'Sakiri + Akane Rin (Overload Vapor Burst)', 'Manfaatkan burst damage putaran katana berapi untuk menghancurkan perisai musuh berskala besar dalam hitungan detik.'),
(2, 'Mint Cryo Support & Freeze Lockdown', 'T0.5', 'Frostbite Prism Orb', 'Glacial Symphony 4-Pc', 'Sangat fleksibel di seluruh tim berorientasi Elemental Discharge', 'Gunakan skill pendingin area untuk mengontrol gerak musuh anomalus, sediakan regenerasi vitalitas berkesinambungan bagi Main DPS.'),
(5, 'Linko Aero Kinetic Air-Combo Juggle', 'T1', 'Tempest Gale Claws', 'Aero Vortex 4-Pc', 'Linko + Nanally + Zankou', 'Lemparkan musuh ke udara dengan upper cut kinetik dan tuntaskan dengan hentakan gravitasi area sebelum musuh menyentuh tanah.'),
(6, 'Zankou Molten Fortress Counter-Guard', 'T1', 'Infernal Bastion Blade', 'Molten Aegis 4-Pc', 'Zankou + Sakiri + Mint', 'Aktifkan perisai gelombang magma saat musuh melancarkan serangan berat, balas dengan tebasan balik berdaya hancur area.'),
(7, 'Blackbird Long-Range Armor Piercing', 'T2', 'Hawk-Eye Longstrike .50', 'Piercing Shadow 4-Pc', 'Blackbird + Zankou + Iroi', 'Bidik titik lemah inti anomali dari posisi aman di belakang perisai Zankou untuk menghasilkan critical strike instan.');
