<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Schemas - Database Documentation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php $activeNav = 'projects'; require __DIR__ . '/partials/sidebar.view.php'; ?>

    <main class="content-area">
        <div class="app-wrapper">
            <section id="appSection" class="content-section" aria-labelledby="appHeading">
                <div class="section-header">
                    <h2 id="appHeading"><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <button id="logoutBtn" class="btn-secondary" type="button">Logout</button>
                </div>

                <div id="projectsMessage" class="ui-message hidden" role="alert" aria-live="assertive"></div>

                <table class="data-table" id="projectsTable">
                    <thead>
                        <tr>
                            <th style="width: 40%">Title</th>
                            <th style="width: 25%">Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="projectsTableBody">
                        <tr><td colspan="3">Loading schemas...</td></tr>
                    </tbody>
                </table>

                <p id="emptyState" class="hidden field-hint" style="margin-top: 1.5rem;">
                    You haven't generated any schemas yet. Head to <a href="dashboard.php">Generate Docs</a> to create your first one.
                </p>
            </section>
        </div>
    </main>

    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this schema? This action cannot be undone.</p>
            <div class="modal-actions">
                <button id="cancelDelete" class="btn-secondary">Cancel</button>
                <button id="confirmDelete" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
    
    <script src="js/projects.js"></script>
</body>
</html>
