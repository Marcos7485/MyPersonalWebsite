-- Solo Zankou (si ya corriste el SQL anterior de iq + ecommerce)
INSERT INTO `cards`
  (`project`, `card`, `image`, `icon`, `hover_text`, `component`, `descripcion`, `active`, `created_at`, `updated_at`)
VALUES
  (
    'zankou',
    3,
    NULL,
    'seccion-4-2/logo.png',
    'Zankou',
    NULL,
    'Asistente de IA de escritorio con memoria, vision y control del sistema',
    1,
    NOW(),
    NOW()
  )
ON DUPLICATE KEY UPDATE
  `icon` = VALUES(`icon`),
  `hover_text` = VALUES(`hover_text`),
  `descripcion` = VALUES(`descripcion`),
  `active` = VALUES(`active`),
  `updated_at` = NOW();

SELECT id, project, card, icon, hover_text, active FROM `cards` ORDER BY card;
