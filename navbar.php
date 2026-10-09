<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            Student Management
        </a>

        <div class="d-flex align-items-center gap-2">
            <?php if (is_admin()): ?>
                <a href="users.php" class="btn btn-outline-light btn-sm">
                    Users
                </a>
            <?php endif; ?>

            <span class="text-white small">
                <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>
                <span class="badge bg-secondary">
                    <?= htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </span>

            <a href="logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>
        </div>
    </div>
</nav>