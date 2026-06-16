# dbdocs_0MI0600282_4MI0600336
Project for Web Technologies course in FMI 2025-2026 on the topic: Database documentation generator.

## PROJECT: DB Documentation Generator
STUDENT: Irena Stancheva, Radina Kazakova
FN: 4MI0600336 ,0MI0600282
STATUS: Draft version of project
With possible extention of user functionalities (maybe add history of user projects to their profile)
Maybe it would be worth to add more descriptions, so that the user can have a better idea of the purpose of the website.
Style changes are also possible - first draft version has a simple UI


## DESCRIPTION:
A web application designed to create visual Data Dictionaries from JSON database structures using Javadoc engine manipulation.

Structure of the project:
-api (folder)
    --auth.php
    --config.php
    --db-style.css
    --db.php
    --generate.php
    --JavadocBuilder.php
-css (folder)
    --style.css
-images (folder)
    --db_symbol.png
-js (folder)
    --auth.js
    --dashboard.js
-storage (folder)
-database.sql
-index.php
-dashboard.php
-docker-compose.yml
-Dockerfile
-0MI0600282_4MI0600336_README.txt
-.gitignore
-.env


## HOW TO RUN VIA DOCKER:
1.Make sure Docker Desktop is running.
2. Ensure you have a .env file in the project root.
3. Ensure there is an empty folder named storage in the project root (create it if it doesn't exist).
4. Open a terminal in the project root folder.
5. Run command: docker-compose up -d --build
6. Open your browser and navigate to: http://localhost:8080

## HOW TO RUN VIA XAMPP:
1. Extract the project inside the XAMPP htdocs folder.
2. Open your preferred database administration tool (e.g., phpMyAdmin) and import the provided "database.sql" file to automatically create the database and tables.
3. Check config.php to ensure the fallback database credentials match your local MySQL configuration (defaults to root with no password).
4. Ensure the storage folder exists and has write permissions.
5. Open your browser and navigate to the project folder (e.g., http://localhost/your-folder-name/index.php).

Note: The Javadoc generation feature will automatically disable itself if Java is not installed or not in the system's PATH. The Custom HTML Documentation will work regardless!
