CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `nama` TEXT NOT NULL,
  `email` TEXT NOT NULL UNIQUE,
  `password` TEXT NOT NULL,
  `created_at` TEXT NOT NULL DEFAULT (datetime('now','localtime'))
);
