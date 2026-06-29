# dbdocs_0MI0600282_4MI0600336

*Project for Web Technologies course in FMI 2025-2026 on the topic: Database documentation generator.*

## Project Information
* **Project:** DB Documentation Generator
* **Students:** Irena Stancheva, Radina Kazakova
* **FN:** 4MI0600336, 0MI0600282
* **Status:** Final version

---

## Description
A web application designed to create visual Data Dictionaries from JSON database structures using Javadoc engine manipulation and custom visualisation.

---

## Structure of the Project

* **api/**
  * `auth.php`
  * `config.php`
  * `db.php`
  * `generate.php`
  * `JavadocBuilder.php`
  * `profile.php`
  * `projects.php`
* **css/**
  * `style.css`
  * `db-style.css`
* **images/**
  * `db_symbol.png`
* **js/**
  * `auth.js`
  * `dashboard.js`
  * `profile.js`
  * `projects.js`
* **views/**
  * **partials/**
    * `sidebar.view.php`
  * `auth.view.php`
  * `dashboard.view.php`
  * `profile.view.php`
  * `projects.view.php`
* **storage/**
* `database.sql`
* `index.php`
* `dashboard.php`
* `profile.php`
* `projects.php`
* `docker-compose.yml`
* `Dockerfile`
* `.gitignore`

---

## How to Run via Docker

1. Make sure Docker Desktop is running.
2. Ensure you have a `.env` file in the project root.
3. Ensure there is an empty folder named `storage` in the project root (create it if it doesn't exist).
4. Open a terminal in the project root folder.
5. Run the following command: 
   ```bash
   docker-compose up -d --build
   ```

## How to Run via XAMPP:
1. Extract the project inside the XAMPP htdocs folder.
2. Open your preferred database administration tool (e.g., phpMyAdmin) and import the provided "database.sql" file to automatically create the database and tables.
3. Check config.php to ensure the fallback database credentials match your local MySQL configuration (defaults to root with no password).
4. Ensure the storage folder exists and has write permissions.
5. Open your browser and navigate to the project folder (e.g., http://localhost/your-folder-name/index.php).

Note: The Javadoc generation feature will automatically disable itself if Java is not installed or not in the system's PATH. The Custom HTML Documentation will work regardless.
