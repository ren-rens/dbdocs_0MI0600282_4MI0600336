<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Database Documentation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php $activeNav = 'profile'; require __DIR__ . '/partials/sidebar.view.php'; ?>

    <main class="content-area">
        <div class="app-wrapper">
            <section id="appSection" class="content-section" aria-labelledby="appHeading">
                <div class="section-header">
                    <h2 id="appHeading"><?php echo htmlspecialchars($pageTitle); ?></h2>
                    <button id="logoutBtn" class="btn-secondary" type="button">Logout</button>
                </div>

                <div id="profileMessage" class="ui-message hidden" role="alert" aria-live="assertive"></div>

                <div class="form-group">
                    <label>Email Address</label>
                    <p id="profileEmail" class="profile-static-value">Loading...</p>
                </div>
                <div class="form-group">
                    <label>Member Since</label>
                    <p id="profileCreatedAt" class="profile-static-value">Loading...</p>
                </div>

                <h2 style="margin-top: 2.5rem;">Change Password</h2>
                <div id="passwordMessage" class="ui-message hidden" role="alert" aria-live="assertive"></div>

                <form id="changePasswordForm" novalidate>
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" placeholder="********" autocomplete="current-password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" placeholder="********" autocomplete="new-password" required>
                        <small class="field-hint">Must be at least 6 characters long.</small>
                    </div>
                    <button id="changePasswordBtn" type="submit" class="btn-primary btn-full">Update Password</button>
                </form>
            </section>
        </div>
    </main>
    <script src="js/profile.js"></script>
</body>
</html>
