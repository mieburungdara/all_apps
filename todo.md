# Project TODO List

This file tracks the development roadmap for our suite of applications.

## Core Framework (`system/`)

- [x] **Migration Runner:** A simple CLI script (`migrate.php`) to run SQL migrations.
- [ ] **Session Management:** Create a `Session` library in `system/core` to handle user login state.
- [ ] **Authentication Layer:** Add a method in the base `Controller` to check for active sessions and protect pages.
- [x] **Configuration Loader:** Create a `Config` class to easily load configuration files (like `database.php`).
- [ ] **Input Library:** Create an `Input` class to securely handle `$_GET`, `$_POST`, and other user input.

## Features & Modules

- [x] **User Module:** Basic registration and login.
    - [ ] Implement Google OAuth for social login.
- [ ] **Attendance Module:**
    - [ ] Design database table for attendance.
    - [ ] Create module for recording and viewing attendance.
- [ ] **Finance Module:**
    - [ ] Design database tables for `kas_kecil` and `kas_besar`.
    - [ ] Create module for managing finances.

## UI/UX

- [ ] **Integrate a CSS Framework:** Choose and implement a framework like Bootstrap or Tailwind CSS to improve the visual design.
- [ ] **Create Layout/Template:** Build a main template (header, footer, sidebar) so that views are consistent.

## Application-Specific

- [ ] **Rumah Sakit App:** Scaffold the `dashboard` module for the hospital application to demonstrate reusability.
