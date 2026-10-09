<?php
include 'auth.php';
include 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    die("Invalid student ID.");
}

$stmt = mysqli_prepare($conn, "SELECT id, nim, name, major_id FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die("Student not found.");
}

$error = "";

if (isset($_POST['update'])) {
    $nim = trim($_POST['nim'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $major_id = filter_input(INPUT_POST, 'major_id', FILTER_VALIDATE_INT);

    if (!preg_match('/^[0-9]{1,20}$/', $nim)) {
        $error = "NIM must contain 1–20 digits.";
    } elseif ($name === '') {
        $error = "Name is required.";
    } elseif (!$major_id) {
        $error = "Please select a major.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE students SET nim = ?, name = ?, major_id = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssii", $nim, $name, $major_id, $id);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit;
    }

    $data['nim'] = $nim;
    $data['name'] = $name;
    $data['major_id'] = $major_id;
}

$majors = mysqli_query($conn, "SELECT id, name FROM majors ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Edit Student Data</h5>
                </div>

                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim"
                                   maxlength="20" inputmode="numeric"
                                   value="<?= htmlspecialchars($data['nim'], ENT_QUOTES, 'UTF-8') ?>"
                                   required>
                            <div id="nimError" class="text-danger mt-1" style="display: none;">
                                NIM must contain digits only.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8') ?>"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="major_id" class="form-label">Major</label>
                            <select class="form-select" id="major_id" name="major_id" required>
                                <?php while ($major = mysqli_fetch_assoc($majors)): ?>
                                    <option value="<?= (int) $major['id'] ?>"
                                        <?= (int) $major['id'] === (int) $data['major_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($major['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="update" class="btn btn-warning">
                                Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    const input = document.getElementById("nim");
    const error = document.getElementById("nimError");
    const valid = /^[0-9]{1,20}$/.test(input.value);

    error.style.display = valid ? "none" : "block";
    input.classList.toggle("is-invalid", !valid);
    return valid;
}
</script>
</body>
</html>