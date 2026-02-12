# Laravel Smart Seeder
Created by Arturo Deutsch — [LinkedIn](https://linkedin.com/in/arturo-deutsch) 

## 🚀 Laravel Smart Seeder

A lightweight tracking system for Laravel seeders. This repository provides a minimal, manual solution to track which seeders have been executed so you don't accidentally run the same seeder twice in the same environment.

---

## ✅ Overview

### What problem this solves
By default, Laravel's `php artisan db:seed` command is not tracked. Running it multiple times can create duplicate records or trigger unique-constraint errors. Laravel Smart Seeder records executed seeders in a `laravel_seeder_logs` table so each seeder only runs once per environment.

### Key benefits
- Prevent duplicate data insertion.
- Keep a history of executed seeders with timestamps and batch grouping.
- Make seeding deterministic for CI/CD and production.

- Batch grouping of runs for easy history and rollback reasoning.

---

## ✨ Features

### Main features
- Tracking table (`laravel_seeder_logs`) for executed seeders.
- A command to run only seeders that are not yet recorded.
- Batch numbers for grouping runs and easier auditing.

---

## ⚙️ Manual Installation (copy into a Laravel app)

> This guide shows how to install the smart seeder manually by generating scaffolds with Artisan and replacing their contents with the implementation provided in this repo (`code/`).

### 1. Create the migration
Generate a migration scaffold:

```bash
php artisan make:migration create_seeder_logs_table
```

This creates a migration file with a timestamped filename similar to:
- `database/migrations/202x_xx_xx_xxxxxxxx_create_seeder_logs_table.php`

Open the generated file and replace the generated `up()`/`down()` methods (or the whole file) with the migration implementation from this repository:
- `code/create_seeder_logs_table.php`

After replacing the contents, run the migration:

```bash
php artisan migrate
```

### 2. Create the artisan command
Generate a command scaffold:

```bash
php artisan make:command SmartSeederCommand
```

This creates `app/Console/Commands/SmartSeederCommand.php` with a scaffolded class. Replace the generated file's contents with the implementation from:
- `code/SmartSeederCommand.php`

What the SmartSeederCommand does:
- Scans `database/seeders` for seeder class files.
- Filters out helper seeders (for example, `DatabaseSeeder`).
- Compares seeder class names with entries in `laravel_seeder_logs`.
- Runs only the seeders that are not recorded and inserts a log entry with seeder name, batch number and timestamp.

Run the command:

```bash
php artisan db:smart-seed
```

---

## 🛠 Initializing an existing project

If you have existing data and you don't want those seeders to run again, mark them as executed in the logs table. Example SQL (run for each seeder you want to mark):

```sql
INSERT INTO laravel_seeder_logs (seeder, batch, ran_at)
VALUES ('YourSeederName', 1, NOW());
```

Or insert multiple rows as needed. Use a consistent `batch` value for the same run group.

---



## 📁 Files in this repository to copy from

- Migration source: `code/create_seeder_logs_table.php`  
- Command source: `code/SmartSeederCommand.php`


---

## 🤝 Contributing

- Open issues or PRs to suggest features or fixes.
- If you add features (for example, configurable ignored seeders or support for alternate namespaces), include tests and update this README with usage examples.

---

## 📜 License

Add a LICENSE file (e.g., MIT) in the repo root and indicate the license here.

---

Created by Arturo Deutsch — [LinkedIn](https://linkedin.com/in/arturo-deutsch)
