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
    - `core/Error_Controller.php`: A system controller for displaying error pages.
    - `core/Log.php`: A library for writing leveled log messages to files.
    - `core/Validator.php`: A class to handle data validation rules.
    - `core/Language.php`: A library for loading language files.
    - `core/security_helper.php`: A helper file containing security-related functions.
    - `sekolah/config/google_auth.php`: Configuration for Google OAuth.
    - `sekolah/modules/users/controllers/Auth.php`: Controller for handling social logins.
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

### Added
- **Core Framework (in `system/`):**
    - `core/Database.php`: A new singleton library to handle all database operations, separating it from the base Model.
    - `core/Response.php`: A new library to standardize controller outputs like redirects and JSON.
- **Documentation:**
    - `system/README.md`: Documentation for the core framework libraries.

### Changed
- **Architecture Refactoring:**
    - The base `Model` is now a thin layer that provides access to the `Database` library.
    - The `Router` now supports custom routes from a `config/routes.php` file.
    - Controllers now use `$this->response->redirect()` instead of `header()`.
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
- Replaced `die()` calls with the new `show_error()` helper for better error handling.
- Integrated the Log library into the base Controller and Error_Controller.
- Enhanced `Log` library to automatically include file, line, and function details in messages.
- Refactored `Error_Controller` to use a themed view, providing a consistent UI for errors.
- Made error helpers (`show_error`, `show_404`) dynamically resolve the application path, removing the hardcoded path.
- Refactored `base_url()` helper to auto-detect the URL, removing the need for a config file.
- Refactored `asset_url()` to be based on a new root-level `base_url()` helper, making it robust for any subdirectory.
- Integrated a new `Validator` class into the `Input` library.
- Refactored the user registration process to use the new validation system.
- Enhanced `Validator` with `matches` and `unique` (database) rules.
- Implemented 'old input' functionality to repopulate forms on validation failure.
- Implemented a language library and internationalized validation messages.
- Added `numeric`, `alpha`, `alpha_space`, and `alpha_numeric` rules to the `Validator`.
- Added `valid_date` and `is_natural` rules to the `Validator`.
- Enhanced the `is_unique` validation rule to support exceptions for record updates.
- Enhanced `Model::get()` to support flexible where clauses with different operators.
- Refactored `Model::update()` to use the new flexible where clause format.
- Updated `todo.md` with more detailed future tasks.
- The `Router` now logs a detailed error message before triggering a 404 page.
- Refactored `Users` controller to reduce code duplication for POST request handling.
- Implemented lazy loading for core libraries in the `Loader` for better performance.
- Enhanced `Config` library with dynamic item management (`item()`, `set_item()`) and caching.
- Enhanced `Response` library with methods for setting status codes and custom headers.
- Enhanced `Session` library with ID regeneration and improved flash message setting.
- Implemented failsafe error and shutdown handlers in `bootstrap.php` to log early-stage fatal errors.
- Integrated an `xss_clean` function into the `Input` library to automatically sanitize all GET and POST data.
- Implemented Google Social Login using `league/oauth2-google`.
- Added a web-based installer module.
- Improved installer to check for existing tables before running migrations.
- Added a role-based access control (RBAC) system with a new `Auth_model`.
- Added a dedicated 403 Access Denied error page.
- Integrated Dashmix theme as the main application layout.
- Refactored auth and installer pages to match the Dashmix theme.
- Added a user management CRUD interface to the admin area.
- Added a dedicated 404 Not Found error page.
- Implemented server-side form validation with persistent old input.
- Added API key authentication via Bearer token.
- Added admin-only API endpoints for listing users (`/api/users`) and fetching a single user (`/api/user/{id}`).

### Changed
- Improved flash message system to be more robust and consistently styled.
- The `/api/user/{id}` endpoint now allows non-admin users to fetch their own data.
- Added `api_docs.md` to document all available API endpoints.
- Added pagination to the `/api/users` endpoint.
- Added a user profile page for updating personal data and managing API keys.
- Added the initial version of the Attendance module with check-in/check-out functionality.
- Added an admin-only page to manage and view all user attendance records.
- Admins can now edit and delete attendance records.
- Added a `superadmin` role with unrestricted access.
- Added a UI for superadmins to manage roles and permissions.
- Added filtering (by user and date) to the attendance management page.
- Added a monthly attendance summary report page.

### Changed
- The user attendance page now displays a history of the user's past attendance.
- Refactored the authorization system from Role-Based to a more granular Permission-Based Access Control.
- The attendance feature is now controlled by the `attendance.perform` permission.
