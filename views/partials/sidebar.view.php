<aside class="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <img src="images/db-symbol.png" alt="" role="presentation" class="db-symbol">
        DB Doc Generator
    </a>
    <div class="nav-group">MENU</div>
    <nav class="nav-links">
        <a href="dashboard.php" class="nav-link<?php echo ($activeNav ?? '') === 'dashboard' ? ' active' : ''; ?>">Generate Docs</a>
        <a href="projects.php" class="nav-link<?php echo ($activeNav ?? '') === 'projects' ? ' active' : ''; ?>">My Schemas</a>
        <a href="profile.php" class="nav-link<?php echo ($activeNav ?? '') === 'profile' ? ' active' : ''; ?>">My Profile</a>
    </nav>
</aside>
