-- ============================================
-- Reservasi Tables Setup Script
-- Run this SQL in your database to set up
-- the cinema-style table reservation system
-- 12 tables, 4 seats each (all regular)
-- ============================================

-- Create meja (tables) table
CREATE TABLE IF NOT EXISTS `meja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_meja` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `kapasitas` int NOT NULL DEFAULT '4',
  `posisi_row` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi_col` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create kursi_reservasi (seat reservations) table
CREATE TABLE IF NOT EXISTS `kursi_reservasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meja_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_sesi` time NOT NULL,
  `tersedia` tinyint(1) NOT NULL DEFAULT '1',
  `reservasi_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kursi_reservasi_meja_id_tanggal_waktu_sesi_unique` (`meja_id`,`tanggal`,`waktu_sesi`),
  KEY `kursi_reservasi_reservasi_id_foreign` (`reservasi_id`),
  CONSTRAINT `kursi_reservasi_meja_id_foreign` FOREIGN KEY (`meja_id`) REFERENCES `meja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kursi_reservasi_reservasi_id_foreign` FOREIGN KEY (`reservasi_id`) REFERENCES `reservasis` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add meja_ids column to reservasis table if not exists
ALTER TABLE `reservasis` ADD COLUMN `meja_ids` json DEFAULT NULL AFTER `status`;

-- Insert default tables (12 tables, 4 seats each, all regular)
INSERT INTO `meja` (`nama_meja`, `kategori`, `kapasitas`, `posisi_row`, `posisi_col`, `is_active`, `created_at`, `updated_at`) VALUES
-- Row A
('Meja A1', 'regular', 4, 'A', 1, 1, NOW(), NOW()),
('Meja A2', 'regular', 4, 'A', 2, 1, NOW(), NOW()),
('Meja A3', 'regular', 4, 'A', 3, 1, NOW(), NOW()),
('Meja A4', 'regular', 4, 'A', 4, 1, NOW(), NOW()),
-- Row B
('Meja B1', 'regular', 4, 'B', 1, 1, NOW(), NOW()),
('Meja B2', 'regular', 4, 'B', 2, 1, NOW(), NOW()),
('Meja B3', 'regular', 4, 'B', 3, 1, NOW(), NOW()),
('Meja B4', 'regular', 4, 'B', 4, 1, NOW(), NOW()),
-- Row C
('Meja C1', 'regular', 4, 'C', 1, 1, NOW(), NOW()),
('Meja C2', 'regular', 4, 'C', 2, 1, NOW(), NOW()),
('Meja C3', 'regular', 4, 'C', 3, 1, NOW(), NOW()),
('Meja C4', 'regular', 4, 'C', 4, 1, NOW(), NOW());
