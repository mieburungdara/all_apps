# Project TODO List

This file tracks the development roadmap for our suite of applications.

## Core Framework (`system/`)

- [x] **Migration Runner:** A simple CLI script (`migrate.php`) to run SQL migrations.
- [x] **Session Management:** Create a `Session` library in `system/core` to complete the login/logout flow.
- [x] **Authentication Layer:** Add a helper method in the base `Controller` to check for active sessions and protect pages from unauthenticated access.
- [x] **Configuration Loader:** Create a `Config` class to easily load configuration files.
- [x] **Input Library:** Create an `Input` class to securely handle `$_GET`, `$_POST`, and other user input.
- [ ] **CLI Tools:**
    - [ ] Build a CLI tool `make:module` to auto-generate module boilerplate.
    - [ ] Build a CLI tool `make:migration` to auto-generate timestamped migration files.
- [ ] **Code Refactoring:**
    - [ ] Refactor `Users` controller to reduce code duplication in `register` and `login` methods.

## Features & Modules

- [x] **User Module:** Basic registration and login.
    - [x] Implement Google OAuth for social login.
    - [ ] Create a "User Profile" page where users can view and update their information (name, email).
    - [ ] Implement a "Change Password" feature on the profile page.
- [ ] **Attendance Module:**
    - [ ] Create a new migration file for the `attendance` table (`user_id`, `check_in_time`, `check_out_time`, `date`).
    - [ ] Create the `attendance` module (Controller, Model, Views) for recording and viewing attendance.
- [ ] **Finance Module:**
    - [ ] Create migration files for `kas_kecil` and `kas_besar` tables.
    - [ ] Create the `finance` module for managing finances.

## UI/UX

- [x] **Integrate a CSS Framework:** Choose and implement a framework like Bootstrap or Tailwind CSS to improve the visual design.
- [x] **Create a Master Layout:** Build a main template (header, footer, sidebar) to be reused by all views for a consistent look and feel.

## Application-Specific

- [ ] **Rumah Sakit App:** Scaffold the `dashboard` module for the hospital application to demonstrate framework reusability.