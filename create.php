<?php
include 'auth.php';
include 'config.php';

$majors = mysqli_query($conn, "SELECT id, name FROM majors ORDER BY name");
$error = "";

if (isset($_POST['submit'])) {
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
            "INSERT INTO students (nim, name, major_id) VALUES (?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "ssi", $nim, $name, $major_id);
        mysqli_stmt_execute($stmt);

        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add New Student</h5>
                </div>

                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form name="studentForm" method="POST" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim"
                                   maxlength="20" inputmode="numeric"
                                   value="<?= htmlspecialchars($_POST['nim'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   required>
                            <div id="nimError" class="text-danger mt-1" style="display: none;">
                                NIM must contain digits only.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   maxlength="100"
                                   value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="major_id" class="form-label">Major</label>
                            <select class="form-select" id="major_id" name="major_id" required>
                                <option value="" disabled <?= empty($_POST['major_id']) ? 'selected' : '' ?>>
                                    Select Major...
                                </option>
                                <?php while ($major = mysqli_fetch_assoc($majors)): ?>
                                    <option value="<?= (int) $major['id'] ?>"
                                        <?= (string) ($major['id']) === ($_POST['major_id'] ?? '') ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($major['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-success">Save Data</button>
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