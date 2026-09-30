-- Hostinger / phpMyAdmin
-- Base: la misma DB del .env de producción (dragonweb / u2056...).
-- Cómo: hPanel → Bases de datos → phpMyAdmin → tu DB → pestaña SQL → pegar → Continuar.

-- 1) Crear tabla si no existe
CREATE TABLE IF NOT EXISTS `cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(255) NOT NULL,
  `card` int unsigned NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `hover_text` varchar(255) DEFAULT NULL,
  `component` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_project_card_unique` (`project`, `card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Si la tabla ya existía sin `icon`, agregarla
-- Si sale error "Duplicate column name 'icon'", ignorá y seguí.
ALTER TABLE `cards` ADD COLUMN `icon` varchar(255) NULL DEFAULT NULL AFTER `image`;

-- 3) Índice único (si ya existe, puede fallar: ignorá)
-- Si sale error "Duplicate key name", ignorá.
ALTER TABLE `cards` ADD UNIQUE KEY `cards_project_card_unique` (`project`, `card`);

-- 4) Insertar / actualizar las 2 cards
INSERT INTO `cards`
  (`project`, `card`, `image`, `icon`, `hover_text`, `component`, `descripcion`, `active`, `created_at`, `updated_at`)
VALUES
  (
    'iqathletic',
    1,
    'card1.png',
    'iqathletic/icon.png',
    'iQ Athletic',
    'cards/iqathletic/Card_component_1',
    'Sistema de gestión para centros deportivos',
    1,
    NOW(),
    NOW()
  ),
  (
    'ecommerce',
    2,
    NULL,
    'drs.webp',
    'Ecommerce',
    NULL,
    'Tienda online lista para vender',
    1,
    NOW(),
    NOW()
  )
ON DUPLICATE KEY UPDATE
  `image` = VALUES(`image`),
  `icon` = VALUES(`icon`),
  `hover_text` = VALUES(`hover_text`),
  `component` = VALUES(`component`),
  `descripcion` = VALUES(`descripcion`),
  `active` = VALUES(`active`),
  `updated_at` = NOW();

-- 5) Verificación
SELECT id, project, card, icon, hover_text, active FROM `cards` ORDER BY card;
