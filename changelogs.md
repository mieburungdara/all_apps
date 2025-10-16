# Changelog

## [Unreleased]

### Added
- **Migration Runner:**
    - Created `migrate.php` script to automate database schema migrations.
- **Project Scaffolding:**
    - Created initial multi-app structure with a portal and separate application folders.
    - Refactored to a shared `system` core and `applications` structure for code reusability.
- **Core Framework (in `system/`):**
    - `bootstrap.php`: Entry point for the system core with a class autoloader.
    - `core/Router.php`: A router to handle URL parsing and dispatching to module controllers.
    - `core/Controller.php`: A base controller with `load_view()` and `load_model()` methods.
    - `core/Model.php`: A base model with PDO database connection logic and helper methods (`get`, `insert`, `update`, `delete`).
    - `core/Config.php`: A singleton class to handle loading configuration files.
    - `core/Input.php`: A singleton class to securely handle user input.
    - `core/helpers.php`: A file for global helper functions like `base_url()` and `asset_url()`.
- **School Application (`applications/sekolah/`):**
    - `index.php`: Application entry point.
    - `config/database.php`: Application-specific database configuration file.
    - `modules/dashboard/`: A sample module with a Controller, Model, and View.
    - `modules/users/`: A user management module with registration and login functionality.
- **Database:**
    - `database.sql`: SQL schema file for creating the `users` table.
- **URL Handling:**
    - `.htaccess`: Root `.htaccess` for clean URL rewriting (e.g., `/sekolah` instead of `/applications/sekolah`).
- **Documentation:**
    - `changelogs.md`: This file, to track project changes.
    - `todo.md`: A file to track the project roadmap and future tasks.

### Changed
- **Switched to SQLite:** The framework now uses SQLite for easier portability and backup.
    - The base `Model` now connects to a SQLite database.
    - Application database configuration is now simplified for SQLite.
- **Database Structure:**
    - Each application now has a `database/migrations` folder.
    - The root `database.sql` has been removed in favor of migration files.
- The Router now uses `$_SERVER['QUERY_STRING']` for more reliable routing with the new `.htaccess` setup.
- The main portal `index.php` now uses clean URLs.
- Updated `todo.md` with Google Login feature plan.
- Refactored `Users` controller to use the new `Input` library.
- Refactored all views and templates to use the `asset_url()` helper for pathing.
