# Custom HMVC Framework - Core System

This directory contains the core engine of the custom HMVC framework.

## Core Libraries

- **`bootstrap.php`**: The main entry point. It loads helpers and initializes the autoloader and router.
- **`Config.php`**: A singleton class for loading application-specific configuration files from the `applications/*/config/` directory.
- **`Controller.php`**: The base controller that all module controllers must extend. It automatically loads core libraries like Input, Session, Log, Response, and the Loader.
- **`Database.php`**: A singleton class that handles the PDO database connection and all query executions (`get`, `insert`, `update`, `delete`).
- **`Error_Controller.php`**: A system-level controller for displaying themed error and 404 pages.
- **`Input.php`**: A singleton class for securely accessing `$_GET` and `$_POST` data. It also integrates the `Validator`.
- **`Language.php`**: A singleton for loading language files to support internationalization (i18n), currently used by the Validator.
- **`Loader.php`**: A class responsible for loading views, templates, and models within controllers.
- **`Log.php`**: A singleton for writing detailed, leveled log messages to daily files in the `logs/` directory.
- **`Model.php`**: The base model. Its primary role is to provide access to the `Database` library via `$this->db`.
- **`Response.php`**: A class to standardize controller output, providing methods like `redirect()` and `json()`.
- **`Router.php`**: Parses the URL, checks for custom routes in `config/routes.php`, and dispatches the request to the appropriate module controller and method.
- **`Validator.php`**: A powerful class for data validation with a variety of rules, including database checks.
- **`helpers.php`**: A file for global procedural helper functions like `base_url()`, `asset_url()`, `show_error()`, etc.
