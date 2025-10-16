# Changelog

## [Unreleased]

### Added
- **Project Scaffolding:**
    - Created initial multi-app structure with a portal and separate application folders.
    - Refactored to a shared `system` core and `applications` structure for code reusability.
- **Core Framework (in `system/`):**
    - `bootstrap.php`: Entry point for the system core with a class autoloader.
    - `core/Router.php`: A router to handle URL parsing and dispatching to module controllers.
    - `core/Controller.php`: A base controller with `load_view()` and `load_model()` methods.
    - `core/Model.php`: A base model with PDO database connection logic.
- **School Application (`applications/sekolah/`):**
    - `index.php`: Application entry point.
    - `config/database.php`: Application-specific database configuration file.
    - `modules/dashboard/`: A sample module with a Controller, Model, and View.
- **URL Handling:**
    - `.htaccess`: Root `.htaccess` for clean URL rewriting (e.g., `/sekolah` instead of `/applications/sekolah`).
- **Documentation:**
    - `changelogs.md`: This file, to track project changes.

### Changed
- The Router now uses `$_SERVER['QUERY_STRING']` for more reliable routing with the new `.htaccess` setup.
- The main portal `index.php` now uses clean URLs.
