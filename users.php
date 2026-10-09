<?php
include 'auth.php';
include 'config.php';

require_admin();

$message = "";

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username'] ?? "");
    $password = $_POST['password'] ?? "";
    $role = $_POST['role'] ?? "";

    if ($username === "") {
        $message = "Username wajib diisi.";
    } elseif (strlen($password) < 6) {
        $message = "Password minimal 6 karakter.";
    } elseif (!in_array($role, ['admin', 'staff'], true)) {
        $message = "Role tidak valid.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO users (username, password, role) VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "sss", $username, $password_hash, $role);

        try {
            mysqli_stmt_execute($stmt);
            $message = "User berhasil ditambahkan.";
        } catch (mysqli_sql_exception $e) {
            $message = "Username sudah digunakan.";
        }
    }
}

$users = mysqli_query(
    $conn,
    "SELECT id, username, role FROM users ORDER BY id"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'navbar.php'; ?>

<div class="container mt-4">

    <?php if ($message !== ""): ?>
        <div class="alert alert-info">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add User</h5>
                </div>

                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username"
                                   name="username" maxlength="50" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password"
                                   name="password" minlength="6" required>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="add_user" class="btn btn-success">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">User List</h5>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Role</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $no = 1; ?>

                            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if ((int) $user['id'] === (int) $_SESSION['user_id']): ?>
                                            <span class="badge bg-info">you</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>