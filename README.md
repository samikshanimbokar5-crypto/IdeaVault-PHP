# IdeaVault PHP

IdeaVault is a student project idea repository built with PHP, MySQL, PDO, HTML, and CSS. It lets students capture project concepts, search their collection, and track each idea from a first thought to completion.

## Features

- Dashboard statistics and responsive project cards
- Add, view, edit, and delete project ideas
- Search title, domain, description, and technologies
- Filter by domain, difficulty, and status
- Server-side validation, CSRF protection, safe output escaping, and feedback messages
- MySQL CRUD through PDO prepared statements

## Structure

`index.php` is the dashboard and search/filter view. `add.php`, `details.php`, `edit.php`, `update.php`, and `delete.php` handle CRUD. `config.php` owns the PDO connection; `functions.php` contains validation, escaping, CSRF, flash messages, and redirects. `database.sql` creates the schema and sample data.

## Local setup with XAMPP

1. Start Apache and MySQL in XAMPP.
2. Import `database.sql` in phpMyAdmin.
3. Copy `config.example.php` to `config.local.php`.
4. Set local database values in `config.local.php`; standard XAMPP defaults are already provided.
5. Visit `http://localhost/IdeaVault-Projects/IdeaVault-PHP/`.

For another environment, set `IDEAVAULT_DB_HOST`, `IDEAVAULT_DB_PORT`, `IDEAVAULT_DB_NAME`, `IDEAVAULT_DB_USER`, and `IDEAVAULT_DB_PASSWORD`. Never commit `config.local.php` or production credentials.

## Database and security

The `project_ideas` table uses an auto-incrementing primary key. Required fields use `NOT NULL`; difficulty and status use controlled enums; `created_at` records creation time. The app demonstrates INSERT, SELECT, parameterized search/filter, UPDATE, and DELETE.

Every request value used in SQL is passed to `PDO::prepare()` and `execute()` as a named parameter. User output passes through `htmlspecialchars()` via `e()`, and POST mutations require a session-backed CSRF token. Database errors are not shown to visitors.

### O'Reilly test

Add an idea containing `O'Reilly` in any text field. It must save and display normally because the value is bound as a parameter rather than concatenated into SQL, and HTML output remains escaped.

## Testing

PHP syntax lint passes with the XAMPP PHP binary. Runtime CRUD, MySQL connectivity, search/filter, validation, responsive screenshots, and the O'Reilly test require Apache and MySQL to be started locally. No deployment URL or GitHub URL is claimed until those external services and permissions are actually verified.

## Deployment

The repository includes a `Dockerfile` and `render.yaml` for a Render Docker web service. Render can build and serve the PHP application, but the service still needs a separate reachable MySQL provider because Render's native managed database is PostgreSQL. Import `database.sql` into that MySQL database, then set `IDEAVAULT_DB_HOST`, `IDEAVAULT_DB_PORT`, `IDEAVAULT_DB_NAME`, `IDEAVAULT_DB_USER`, and `IDEAVAULT_DB_PASSWORD` as Render environment variables. Never commit those values.

Deployment is only complete after the Render service is connected to the GitHub repository, the production MySQL schema is imported, the variables are set, and the public dashboard plus a CRUD operation are tested. InfinityFree is intentionally not used.

Future additions could include authentication, ownership, tags, pagination, attachments, and moderation.

## Originality

IdeaVault is an independently implemented DBMS mini-project. Sample records are fictional and contain no sensitive personal data.