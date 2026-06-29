<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Database Documentation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php $activeNav = 'dashboard'; require __DIR__ . '/partials/sidebar.view.php'; ?>

    <main class="content-area">
        <div class="app-wrapper">
            <section id="appSection" class="content-section" aria-labelledby="appHeading">
                <div class="section-header">
                    <h2 id="appHeading"><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <button id="logoutBtn" class="btn-secondary" type="button">Logout</button>
                </div>

                <div id="genMessage" class="ui-message ui-error hidden" role="alert" aria-live="assertive"></div>

                <form id="generateForm" novalidate>
                    <div class="form-group">
                        <label for="projTitle">Project Title</label>
                        <input type="text" id="projTitle" placeholder="e.g., University System" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="jsonInput">Schema JSON</label>

                        <div class="import-wrapper">
                            <span class="import-label">Paste code below or:</span>
                            <label for="importJsonBtn" class="import-btn">Upload .json</label>
                            <input type="file" id="importJsonBtn" accept=".json" class="hidden">
                        </div>
                        <textarea id="jsonInput" placeholder='{"tables": []}' aria-describedby="jsonInputHint" required></textarea>
                        <small id="jsonInputHint" class="field-hint">Paste your database schema as a JSON object with a "tables" array.</small>
                    </div>
                    <button id="generateBtn" type="submit" class="btn-primary btn-full">Generate Documentation</button>
                </form>

                <div id="resultArea" class="hidden" aria-labelledby="resultHeading" aria-live="polite">
                    <div class="result-header">
                        <button id="backArrowBtn" class="back-arrow" type="button" aria-label="Go back">&larr; Back</button>
                        <h3 id="resultHeading">Documentation ready to view</h3>
                        <p class="result-subtitle">Choose how you want to explore your generated schema.</p>
                    </div>
                    
                    <div class="result-actions">
                        <div class="main-actions">
                            <a id="resultBtn" href="#" target="_blank" rel="noopener noreferrer" class="btn-success">View Custom Docs</a>
                            <a id="javadocBtn" href="#" target="_blank" rel="noopener noreferrer" class="btn-success hidden">View Javadoc</a>
                        </div>
                        
                        <div class="secondary-actions">
                            <button id="backBtn" class="btn-secondary" type="button">Create Another Documentation</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="js/dashboard.js"></script>
</body>
</html>